<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use app\assets\FontAsset;

FontAsset::register($this);
$this->title = 'webdevs.top Exchanger';
$this->registerJsFile('/js/exchange.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<video class="bg_video" autoplay muted loop>
    <source src="https://webdevs.top/video/icrypto_bg.webm" type="video/webm">
</video>
<div class="site-index">
    <?php include('currencies.php'); ?>
    <div class="container exchange-form">
        <?php
        (isset($params['from']) && isset($params['to'])) ? $params : $params = false;
        ($params['from'] != $params['to']) ? $params : $params = false;
        ($params['from'] == '0' || $params['to'] == 0) ? $params : $params = false;
        $form = ActiveForm::begin(['id' => 'exchange-form', 'enableClientValidation' => true]);
        ?>
        <div class="container">
            <div class="col-xs-6 col-md-offset-3 col-md-3 table-condensed">
                <?= $form->field($model, 'amount', ['enableAjaxValidation' => true,
                    'template' => '{label}<div class="input-group-lg">{input}</div><div>{error}</div>'
                ])->textInput(['placeholder' => '0', 'maxlength' => 7]); ?>
            </div>
            <div class="col-xs-6 col-md-3 table-condensed">
                <?= $form->field($model, 'currency_id_from', [
                    'template' => '{label}<div class="input-group-lg">{input}</div><div>{error}</div>'
                ])->dropDownList(ArrayHelper::map($currencies, 'id', 'name'), [
                    'options' =>
                        [$params['from'] => ['selected' => true]],
                    'class' => 'form-control',
                    'prompt' => 'Select'
                ]) ?>
            </div>
        </div>
        <div class="container">
            <div class="col-xs-6 col-md-offset-3 col-md-3 table-condensed">
                <?= $form->field($model, 'sum', ['enableAjaxValidation' => true,
                    'template' => '{label}<div class="input-group-lg">{input}</div><div>{error}</div>'
                ])->textInput(['placeholder' => '0', 'maxlength' => 7]); ?>
            </div>
            <div class="col-xs-6 col-md-3 table-condensed">
                <?= $form->field($model, 'currency_id_to', [
                    'template' => '{label}<div class="input-group-lg">{input}</div><div>{error}</div>'
                ])->dropDownList(ArrayHelper::map($currencies, 'id', 'name'), [
                    'options' =>
                        [$params['to'] => ['selected' => true]],
                    'prompt' => 'Select'
                ]) ?>
            </div>
        </div>
        <div class="container exchange-button">
            <div class="col-xs-12 text-center">
                <?= Html::Button('<i class="glyphicon-resize-vertical glyphicon"></i>Swap pair', ['class' => 'btn btn-primary', 'id' => 'revert']) ?>
                <?= Html::Button('Exchange', ['class' => 'btn btn-success', 'id' => 'exchange-button']) ?>
            </div>
        </div>
        <div class="container confirmation collapse">
            <div class="col-xs-12 col-md-offset-3 col-md-6 collapse table-condensed" id="client_wallet">
                <?= $form->field($model, 'client_wallet', [
                    'template' => '{label}<div class="input-group input-group-lg">{input}<i class="input-group-addon fa fa-lg fa-money"></i></div><div>{error}</div>'
                ])->textInput(['placeholder' => 'Enter you wallet address', 'maxlength' => 50]) ?>
            </div>
        </div>
        <div class="container confirmation collapse">
            <div class="col-xs-12 col-md-offset-3 col-md-6 collapse table-condensed" id="client_card">
                <?= $form->field($model, 'client_card')->widget(\app\widgets\MaskedInput::className(), ['mask' => '[9999 9999 9999 9999]',
                    'options' => [
                        'class' => 'form-control input-lg'],
                    'clientOptions' => [
                        'placeholder' => '*',
                        'removeMaskOnSubmit' => false,
                    ]]);
                ?>
            </div>
        </div>
        <div class="container confirmation collapse">
            <div class="col-xs-12 col-md-offset-3 col-md-6 collapse table-condensed" id="client_email">
                <?= $form->field($model, 'email', [
                    'template' => '{label}<div class="input-group input-group-lg">{input}<i class="input-group-addon fa fa-lg fa-envelope"></i></div><div>{error}</div>'
                ])->textInput(['placeholder' => 'Enter you e-mail address', 'maxlength' => 32]); ?>
            </div>
        </div>
        <div class="container confirmation collapse">
            <div class="col-xs-12 col-md-offset-3 col-md-6 table-condensed">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-xs-3 col-sm-3 col-md-3">{image}</div><div class="col-xs-9 col-sm-9 col-md-9 input-group-lg">{input}</div></div>',
                    'imageOptions' => [
                        'id' => 'my-captcha-image'
                    ]
                ]) ?>
            </div>
        </div>
        <div class="container confirmation collapse">
            <div class="col-xs-12 text-center table-condensed">
                <?= Html::Button('<i class="glyphicon-refresh glyphicon"></i> Reload captcha', ['class' => 'btn btn-primary', 'id' => 'refresh-captcha']) ?>
                <?= Html::Button('<i class="glyphicon-resize-vertical glyphicon"></i>Swap pair', ['class' => 'btn btn-primary', 'id' => 'swap']) ?>
                <?= Html::submitButton('Confirm order', ['class' => 'btn btn-success', 'name' => 'confirm-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
