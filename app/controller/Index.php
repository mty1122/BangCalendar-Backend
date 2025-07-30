<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'Welcome to BangCalendar backend!';
    }

    public function update()
    {
        return json_encode([
            'versionCode' => 23,
            'versionName' => '1.6.3'
        ]);
    }
}
