<?php

namespace app\controllers;

use app\models\Order;
use app\models\Currency;
use Yii;
use yii\web\Controller;

class OrderController extends Controller
{
    public function actionConfirmOrder()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->post('key') || Yii::$app->request->get() && Yii::$app->request->get('key')) {
            if (Yii::$app->request->get('key')) {
                $order = Order::confirm(Yii::$app->request->get('key'));
            } elseif (Yii::$app->request->post('key')) {
                $order = Order::confirm(Yii::$app->request->post('key'));
            }
            if ($order) {
                $order->currency_from = Currency::find()
                    ->select('name')
                    ->where(['id' => $order->currency_id_from])
                    ->one();
                $order->currency_to = Currency::find()
                    ->select('name')
                    ->where(['id' => $order->currency_id_to])
                    ->one();
                Order::notifyConfirmedOrder($order);
            }
        }
    }
}

