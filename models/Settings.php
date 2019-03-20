<?php

namespace app\models;

use Yii;

class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    public static function set($obj)
    {

        $user = User::findOne(Yii::$app->user->identity->id);
        $user->email = $obj->email;
        $user->newsrate = $obj->newsrate;
        if ($obj->password) $user->setPassword($obj->password);
        $user->save();

        return true;
    }
}