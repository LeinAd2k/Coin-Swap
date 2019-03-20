<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class Order extends ActiveRecord
{
    public $currency_from;
    public $currency_to;

    public static function create($obj)
    {
        $order = new Order;
        $order->currency_from = $obj->currency_from;
        $order->currency_to = $obj->currency_to;
        $order->currency_id_from = $obj->currency_id_from;
        $order->currency_id_to = $obj->currency_id_to;
        $order->amount = $obj->amount;
        $order->sum = $obj->sum;
        $order->client_wallet = $obj->client_wallet;
        $order->client_card = $obj->client_card;
        $order->email = $obj->email;
        $order->datetime = date('Y-m-d H:i:s');
        $order->key = $obj->key;
        $order->save();

        return $order->id;
    }

    public static function confirm($key)
    {
        $order = Order::findOne(['key' => $key, 'confirmed' => '0']);
        if ($order) {
            $order->confirmed = '1';
            $order->save();
            echo "Success";
        } else {
            echo "Error";
        }

        return $order;
    }

    public static function notifyPendingOrder($order)
    {
        Yii::$app->mailer->compose('pending_order_mail', [
            'id' => $order->id,
            'from' => $order->currency_from->name,
            'to' => $order->currency_to->name,
            'amount' => number_format(round($order->amount, 5), 5, '.', ''),
            'sum' => number_format(round($order->sum, 5), 5, '.', ''),
            'email' => $order->email,
            'client_card' => $order->client_card,
            'client_wallet' => $order->client_wallet,
        ])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([Yii::$app->params['siteEmail'] => 'Exchanger'])
            ->setSubject('New order has been received!')
            ->send();
    }

    public static function notifyConfirmedOrder($order)
    {
        Yii::$app->mailer->compose('confirmed_order_mail', [
            'id' => $order->id,
            'from' => $order->currency_from->name,
            'to' => $order->currency_to->name,
            'amount' => number_format(round($order->amount, 5), 5, '.', ''),
            'sum' => number_format(round($order->sum, 5), 5, '.', ''),
            'email' => $order->email,
            'client_card' => $order->client_card,
            'client_wallet' => $order->client_wallet,
        ])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([Yii::$app->params['siteEmail'] => 'Exchanger'])
            ->setSubject('New order confirmation has been received!')
            ->send();
    }

    public static function clientNotifyPendingOrder($order)
    {
        Yii::$app->mailer->compose('client_pending_order_mail', [
            'from' => $order->currency_from->name,
            'to' => $order->currency_to->name,
            'amount' => number_format(round($order->amount, 5), 5, '.', ''),
            'sum' => number_format(round($order->sum, 5), 5, '.', ''),
            'model' => $order,
        ])
            ->setTo($order->email)
            ->setFrom([Yii::$app->params['siteEmail'] => 'Exchanger'])
            ->setSubject('Your order is awating confirmation!')
            ->send();
    }
}
