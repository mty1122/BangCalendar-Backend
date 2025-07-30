<?php
namespace app\model;

use think\Model;

class UserPreference extends Model
{
    // 数据表名称
    protected $table = 'user_preference';

    // 主键
    protected $pk = 'mail';
    
    // 数据库连接
    protected $connection = 'mysql';

    // 字段
    protected $schema = [
        'mail' => 'char',
        'name' => 'varchar',
        'theme' => 'varchar',
        'band' => 'varchar',
        'char_pref' => 'varchar'
    ];
}