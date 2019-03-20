<?php

namespace app\models;

use Yii;

class Operlog extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'oper_log';
    }

    public function rules()
    {
        return [
            [['record_time', 'update_time'], 'safe'],
            [['user_type', 'op_ip', 'op_type'], 'integer'],
            [['user_name'], 'string', 'max' => 16],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_time' => '创建时间',
            'update_time' => '更新时间',
            'user_name' => '用户名称',
            'user_type' => '用户类型',//0：总管理员，1：管理员
            'desc' => '描述',
            'op_ip' => '操作者id',
            'op_type' => '操作者类型',//操作类型：0新增类，1，修改类，2删除类，3查询类，9 其他
        ];
    }
}
