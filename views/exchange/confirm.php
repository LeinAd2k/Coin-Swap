<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\assets\FontAsset;

FontAsset::register($this);
$this->title = 'webdevs.top';
$this->registerJsFile('/js/jquery.countdown.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/confirmation.js', ['depends' => 'yii\web\JqueryAsset']);
?>
<video class="bg_video" autoplay muted loop>
    <source src="https://webdevs.top/video/icrypto_bg.webm" type="video/webm">
</video>
<div class="site-index">
    <?php include('currencies.php'); ?>
    <div class="container">
        <div class="container">
            <div class="col-xs-6 col-md-offset-3 col-md-3 table-condensed input-group-lg form-group">
                <label>Send</label><input class="form-control" value="<?= $model->amount; ?>" disabled>
            </div>
            <div class="col-xs-6 col-md-3 table-condensed input-group-lg form-group">
                <label>Currency</label><input class="form-control" value="<?= $model->currency_from->name; ?>" disabled>
            </div>
        </div>
        <div class="container">
            <div class="col-xs-6 col-md-offset-3 col-md-3 table-condensed input-group-lg">
                <label>Receive</label><input class="form-control" value="<?= $model->sum ?>" disabled>
            </div>
            <div class="col-xs-6 col-md-3 table-condensed input-group-lg form-group">
                <label>Currency</label><input class="form-control" value="<?= $model->currency_to->name; ?>" disabled>
            </div>
        </div>
        <div class="container">
            <div class="col-xs-12 col-md-offset-3 col-md-6 table-condensed input-group-lg form-group"
                 id="client_wallet">
                <label>Your wallet address</label><input class="form-control" value="<?= $model->client_wallet ?>"
                                                         disabled>
            </div>
        </div>
        <?php if ($model->currency_id_from !== '0'): ?>
            <div class="container">
                <div class="col-xs-12 col-md-offset-3 col-md-6 table-condensed input-group-lg form-group"
                     id="client_card">
                    <label>Your electronic card number</label><input class="form-control"
                                                                     value="<?= $model->client_card ?>" disabled>
                </div>
            </div>
        <?php endif; ?>
        <div class="container">
            <div class="col-xs-12 col-md-offset-3 col-md-6 table-condensed input-group-lg form-group" id="client_email">
                <label>Your e-mail</label><input class="form-control" value="<?= $model->email ?>" disabled>
            </div>
        </div>
        <div class="container">
            <div class="col-xs-12 col-md-offset-3 col-md-6 alert alert-success text-center">
                <p>
                    Order № <b><?= $model->id ?></b> has been accepted on <b><?= date('Y-m-d H:i:s T'); ?></b>
                </p>
                <p>
                    The e-mail containing all the required payment information has been sent to
                    <b><?= $model->email ?></b>
                </p>
                <p>
                    You need to send the amount of <b><?= $model->amount ?> <?= $model->currency_from->name ?></b>
                    <?php if ($model->currency_id_from !== '0') : ?>
                    from your wallet address <b><?= $model->client_wallet ?></b> to our
                    <b><?= $model->currency_from->name ?></b> wallet address
                <div class="input-group">
                    <span id="icrypto_wallet" class="form-control"><?= $model->currency_from->payment_address ?></span>
                    <span class="input-group-addon">
                                <button class="btn-default fa fa-clipboard"
                                        onclick="copyToClipboard('#icrypto_wallet')"></button>
                            </span>
                </div>
                <?php else : ?>
                    to our electronic card number
                    <div class="input-group">
                        <span id="icrypto_card"
                              class="form-control"><?= $model->currency_from->payment_address ?></span>
                        <span class="input-group-addon">
                                <button class="btn-default fa fa-clipboard"
                                        onclick="copyToClipboard('#icrypto_card')"></button>
                            </span>
                    </div>
                <?php endif ?>
                <p id="timer" class="text-md">
                    within <span id="clock">30</span> minutes<br>
                    or untill <b><?php $date = date('Y-m-d H:i:s');
                        $time = strtotime($date . ' + ' . $model->currency_from->valid_time . ' minutes');
                        echo date('Y-m-d H:i:s T', $time); ?></b>
                </p>
                <p>
                    <?php if ($model->currency_from->min_confirmations) : ?>
                        Blockchain confirmations required: <b><?= $model->currency_from->min_confirmations ?></b>
                    <?php endif ?>
                </p>
                <p>
                    <?php if ($model->currency_to->commission) : ?>
                        <span class="text-info"><?= $model->currency_to->name ?>
                            NET FEE: <?php echo $model->currency_to->commission ?> <?= $model->currency_to->name ?></span>
                        <br>
                        <span class="text-danger text-md">TOTAL PAYOUT: <?php echo $model->sum - $model->currency_to->commission ?> <?= $model->currency_to->name ?></span>
                    <?php else : ?>
                        <span class="text-danger text-md">TOTAL PAYOUT: <?= $model->sum ?> <?= $model->currency_to->name ?></span>
                    <?php endif; ?>
                </p>
                <p>
                    This order will be processed in 30 minutes after receiving your payment and payment confirmation by
                    clicking the
                    confirmation link from the e-mail which we have sent to you or the confirmation button below.
                </p>
                <p class="collapse confirmed">
                    Thank you for your trust, your order is already being processed!
                </p>
                <?= Html::Button('Confirm payment', ['class' => 'btn btn-success', 'id' => 'confirm-button']) ?>
            </div>
        </div>
        <div class="container">
            <div class="col-xs-12">
                <input type="hidden" id="key" value="<?= $model->key ?>"></input>
                <input type="hidden" id="valid_time" value="<?= $model->currency_from->valid_time ?>"></input>
            </div>
        </div>
    </div>
</div>