<?php

namespace app\controllers;

use app\models\Currency;
use app\models\ExchangeForm;
use Yii;
use yii\web\Controller;


class CurrencyController extends Controller
{
    public function actionGet()
    {
        if (\Yii::$app->request->isAjax) {

            return json_encode(Currency::get(), true);
        } else {
            echo 'Error';
        }
    }

    public function actionUpdate()
    {
        if (Yii::$app->request->get('secure_key') === Yii::$app->params['secure_key']) {
            Currency::reload();
        } else {
            echo 'Error';
        }
    }

    public function actionCalculate()
    {
        if (Yii::$app->request->isAjax && (Yii::$app->request->get('from') != null) && (Yii::$app->request->get('to') != null) && (Yii::$app->request->get('amount') != null)) {

            return json_encode(ExchangeForm::calculateSum(Yii::$app->request->get('from'), Yii::$app->request->get('to'), Yii::$app->request->get('amount')));
        }

        if (Yii::$app->request->isAjax && (Yii::$app->request->get('from') != null) && (Yii::$app->request->get('to') != null) && (Yii::$app->request->get('sum') != null)) {

            return json_encode(ExchangeForm::calculateAmount(Yii::$app->request->get('from'), Yii::$app->request->get('to'), Yii::$app->request->get('sum')));
        }

        echo 'Error';
    }
}

