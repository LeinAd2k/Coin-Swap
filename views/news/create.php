<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;

$this->title = 'Create news';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="col-xs-12 col-md-offset-3 col-md-6">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(['id' => 'news-form']); ?>
            <?= $form->field($model, 'sourceLink')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'ticker') ?>
            <?= $form->field($model, 'currency') ?>
            <?= $form->field($model, 'sourceDate')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Enter source date'],
                'convertFormat' => true,
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'format' => 'yyyy-MM-dd HH:i:ss',
                    'autoclose' => true],
            ]); ?>
            <?= $form->field($model, 'eventDate')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Enter event date'],
                'convertFormat' => true,
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'format' => 'yyyy-MM-dd HH:i:ss',
                    'autoclose' => true]
            ]); ?>
            <?= $form->field($model, 'twitter')->checkbox(['id' => 'twitter_id', 'checked' => true]) ?>
            <?= $form->field($model, 'medium')->checkbox() ?>
            <?= $form->field($model, 'cmc')->checkbox() ?>
            <?= $form->field($model, 'rate') ?>
            <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
            <div class="form-group">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <div></div>
        </div>
    </div>
</div>
