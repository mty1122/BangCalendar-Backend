<?php
namespace app\controller;

use app\BaseController;
use app\model\UserPreference;
use app\util\Security;

class UserService extends BaseController
{
    public function getUser()
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);

        $aesKey = base64_decode(cache('k' . $data['phone']));
        if (strlen($aesKey) != 16)
            return -1;

        $requestCode = Security::aesDecrypt($data['requestCode'], $aesKey);
        if (strcmp($requestCode, $data['phone']) != 0)
            return -2;

        try {
            $user = UserPreference::find($data['phone']);
            $user->theme = Security::aesEncrypt($user->theme, $aesKey);
            $user->band = Security::aesEncrypt($user->band, $aesKey);
            $user->char_pref = Security::aesEncrypt($user->char_pref, $aesKey);
            return $user->toJson();
        } catch (\Throwable $th) {
            return -3;
        }
    }

    public function setUser() 
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);

        $aesKey = base64_decode(cache('k' . $data['phone']));
        if (strlen($aesKey) != 16)
            return 'FAIL';
        $requestCode = Security::aesDecrypt($data['requestCode'], $aesKey);
        if (strcmp($requestCode, $data['phone']) != 0)
            return 'FAIL';

        $user = new UserPreference;
        $user->phone = $data['phone'];
        $user->name = $data['name'];
        $user->theme = Security::aesDecrypt($data['theme'], $aesKey);
        $user->band = Security::aesDecrypt($data['band'], $aesKey);
        $user->char_pref = Security::aesDecrypt($data['char_pref'], $aesKey);
        $user->replace()->save();
        return 'OK';
    }
}
