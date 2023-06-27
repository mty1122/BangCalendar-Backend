<?php

use think\facade\Route;

Route::get('update', 'index/update');

Route::post('login', 'login/index');

Route::post('sms', 'login/sendsms');

Route::post('get', 'userservice/getuser');

Route::post('set', 'userservice/setuser');