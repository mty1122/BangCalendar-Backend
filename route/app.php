<?php

use think\facade\Route;

Route::get('update', 'index/update');

Route::post('login', 'login/index');

Route::post('mail', 'login/sendmail');

Route::post('get', 'user_service/getuser');

Route::post('set', 'user_service/setuser');