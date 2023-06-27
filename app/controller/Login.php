<?php
namespace app\controller;

require_once 'C://BangCalendar/sendsms.php'; 

use app\BaseController;
use app\util\Security;

class Login extends BaseController
{
    public function index()
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        $code = strval(cache('s' . $data['phone']));
        if (strcmp($code, $data['smsCode']) != 0)
            return 'FAIL';
        $aesKey = Security::rsaDecrypt($data['key']);
        if (strlen($aesKey) != 16)
            return 'FAIL';
        cache('k' . $data['phone'], base64_encode($aesKey), 0);
        return 'OK';    
    }

    public function sendSMS() 
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        if (!Security::checkRequestCode($data['requestCode'], $data['tag'], $data['iv']))
            return 'FAIL';
        $code = send($data['phone']);
        if ($code == null)
            return 'FAIL';
        cache('s' . $data['phone'], $code, 180);
        return 'OK';
    }

}
