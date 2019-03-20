<?php

namespace app\models;

use yii\base\Model;

class SettingsForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $newsrate;

    public function rules()
    {
        return [
            [['password', 'password_repeat', 'username'], 'string', 'max' => 50],
            [['newsrate'], 'integer', 'max' => 1000],
            [['email'], 'required'],
            [['email'], 'email'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => "Passwords doesn't match"]
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }
}
