<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'User Settings';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="col-xs-12 col-md-offset-3 col-md-6">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(['id' => 'settings-form']); ?>
            <?= $form->field($model, 'username')->textInput(['value' => $user->identity->username, 'disabled' => 'true']) ?>
            <?= $form->field($model, 'email')->textInput(['value' => $user->identity->email]) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Your new password']) ?>
            <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Confirm your new password']) ?>
            <?= $form->field($model, 'newsrate')->textInput(['value' => $user->identity->newsrate]) ?>
            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <div></div>
        </div>
    </div>
</div>
