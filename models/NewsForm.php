<?php

namespace app\models;

use yii\base\Model;

class NewsForm extends Model
{
    public $user;
    public $userId;
    public $sourceLink;
    public $ticker;
    public $currency;
    public $sourceDate;
    public $eventDate;
    public $twitter = true;
    public $medium = true;
    public $cmc = true;
    public $rate;
    public $comment;


    public function rules()
    {
        return [
            [['userId', 'ticker', 'currency', 'sourceDate', 'eventDate', 'sourceLink', 'rate', 'comment'], 'required'],
            [['medium', 'cmc', 'twitter'], 'boolean'],
            [['ticker'], 'string', 'max' => 15],
            [['currency', 'sourceDate', 'eventDate', 'publicDate'], 'string', 'max' => 32],
            [['sourceLink', 'chartLink'], 'string', 'max' => 150],
            [['comment'], 'string', 'max' => 1000],
            [['publicRate', 'rate'], 'integer', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }
}
