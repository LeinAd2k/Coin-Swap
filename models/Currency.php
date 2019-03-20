<?php

namespace app\models;

use yii\db\ActiveRecord;

class Currency extends ActiveRecord
{
    public static function reload()
    {
        $currencies = self::find()
            ->where(['update' => 1])
            ->all();

        foreach ($currencies as $obj) {
            $obj->sell_rate !== 0 ? $rate_sell = self::findOne(['id' => $obj->sell_rate])->sell : $rate_sell = 1;
            $obj->buy_rate !== 0 ? $rate_buy = self::findOne(['id' => $obj->buy_rate])->sell : $rate_buy = 1;
            $sell = json_decode(file_get_contents($obj->api_sell_url), true)[$obj->api_sell_column][$obj->api_sell_idx] * $obj->coefficient_sell;
            $buy = json_decode(file_get_contents($obj->api_buy_url), true)[$obj->api_buy_column][$obj->api_buy_idx] * $obj->coefficient_buy;
            ($obj->sell_rate_action == '/') ? round($obj->sell = $sell / $rate_sell, 2) : round($obj->sell = $sell * $rate_sell, 2);
            ($obj->buy_rate_action == '/') ? round($obj->buy = $buy / $rate_buy, 2) : round($obj->buy = $buy * $rate_buy, 2);
            $obj->save();
        }

        return true;
    }

    public static function get()
    {
        $result = self::find()
            ->select('id, name, sell, buy, visible, valid_time')
            ->where(['visible' => 1])
            ->asArray()
            ->all();

        return $result;
    }
}
