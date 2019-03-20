<?php

namespace app\controllers;

use app\models\Currency;
use app\models\ExchangeForm;
use app\models\Order;
use Yii;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;


class ExchangeController extends Controller
{
    public function actionIndex()
    {
        $exchangeForm = new ExchangeForm();
        $params = false;

        if (Yii::$app->request->isAjax && $exchangeForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';

            return ActiveForm::validate($exchangeForm);
        }

        if (Yii::$app->request->get('from') || Yii::$app->request->get('to')) {
            $params = Yii::$app->request->get();
        }

        if ($exchangeForm->load(Yii::$app->request->post())) {

            $exchangeForm->sum = $exchangeForm->calculateSum($exchangeForm->currency_id_from, $exchangeForm->currency_id_to, $exchangeForm->amount);
            $exchangeForm->currency_from = Currency::find()
                ->select('name, valid_time, min_confirmations, payment_address')
                ->where(['id' => $exchangeForm->currency_id_from])
                ->one();
            $exchangeForm->currency_to = Currency::find()
                ->select('name, min_payout, commission')
                ->where(['id' => $exchangeForm->currency_id_to])
                ->one();
            $exchangeForm->key = Yii::$app->security->generateRandomString(32);
            $exchangeForm->admin_email = Yii::$app->params['adminEmail'];

            $exchangeForm->id = Order::create($exchangeForm);
            Order::notifyPendingOrder($exchangeForm);
            Order::clientNotifyPendingOrder($exchangeForm);

            return $this->render('confirm', [
                'currencies' => Currency::get(),
                'model' => $exchangeForm,
            ]);
        }

        return $this->render('index', [
            'currencies' => Currency::get(),
            'model' => $exchangeForm,
            'params' => $params
        ]);
    }
}

