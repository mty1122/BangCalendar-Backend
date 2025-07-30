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

        $aesKey = base64_decode(cache('k' . $data['mail']));
        if (strlen($aesKey) != 16)
            return json([
                'code' => 500,
                'data' => null
            ]);

        $requestCode = Security::aesDecrypt($data['requestCode'], $aesKey);
        if (strcmp($requestCode, $data['mail']) != 0)
            return json([
                'code' => 400,
                'data' => null
            ]);

        try {
            $user = UserPreference::find($data['mail']);
            if ($user == null) return json([
                'code' => 404,
                'data' => null
            ]);
            return json([
                'code' => 200,
                'data' => [
                    'mail' => $user->mail,
                    'name' => $user->name,
                    'theme' => Security::aesEncrypt($user->theme, $aesKey),
                    'band' => Security::aesEncrypt($user->band, $aesKey),
                    'char_pref' => Security::aesEncrypt($user->char_pref, $aesKey),
                ]
            ]);
        } catch (\Throwable $th) {
            return json([
                'code' => 500,
                'data' => null
            ]);
        }
    }

    public function setUser(): int
    {
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);

        $aesKey = base64_decode(cache('k' . $data['mail']));
        if (strlen($aesKey) != 16)
            return 500;
        $requestCode = Security::aesDecrypt($data['requestCode'], $aesKey);
        if (strcmp($requestCode, $data['mail']) != 0)
            return 400;

        $user = new UserPreference;
        $user->mail = $data['mail'];
        $user->name = $data['name'];
        $user->theme = Security::aesDecrypt($data['theme'], $aesKey);
        $user->band = Security::aesDecrypt($data['band'], $aesKey);
        $user->char_pref = Security::aesDecrypt($data['char_pref'], $aesKey);
        $user->replace()->save();
        return 200;
    }
}
