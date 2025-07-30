<?php
namespace app\controller;

use app\BaseController;
use app\util\Mail;
use app\util\Security;

class Login extends BaseController
{
    public function index(): int
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        $code = strval(cache('v' . $data['mail']));
        $aesKey = Security::rsaDecrypt($data['key']);
        if (strlen($aesKey) != 16)
            return 400;
        $post_code = Security::aesDecrypt($data['mailCode'], $aesKey);
        if (strcmp($code, $post_code) != 0)
            return 400;
        if (!Security::checkIpLimit(request()->ip()))
            return 429;
        cache('v' . $data['mail'], null);
        cache('k' . $data['mail'], base64_encode($aesKey), 0);
        return 200;
    }

    public function sendMail(): int
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        if (!Security::checkRequestCode($data['requestCode'], $data['tag'], $data['iv']))
            return 400;
        if (!Security::checkIpLimit(request()->ip()))
            return 429;
        $mail = $data['mail'];
        $code = rand(100000, 999999);
        $code = Mail::sendVerificationCode($code, $mail);
        if ($code == null)
            return 500;
        cache('v' . $data['mail'], $code, 180);
        return 200;
    }

}
