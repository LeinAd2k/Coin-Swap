<?php

/* @var $this yii\web\View */

$this->title = 'webdevs.top CryptoMonitor';
$this->registerJsFile('/js/main.js', ['depends' => 'yii\web\JqueryAsset']);

?>
<video class="bg_video" autoplay muted loop>
    <source src="https://webdevs.top/video/icrypto_bg.webm" type="video/webm">
</video>
<div class="site-index">
    <div class="container-main">
        <?php foreach ($currencies as $currency): ?>
            <?php if ($currency['visible'] == 1 && $currency['id'] != 0) : ?>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="text-xl"><span>USD/<?= $currency['name'] ?></span></div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-4">
                        <a href="/exchange?from=0&to=<?= $currency['id'] ?>"
                           class="btn btn-default btn-outline-buy btn-block text-xl">
                            <div id="<?= $currency['id'] ?>-sell"><?= number_format($currency['sell'], 2, '.', '') ?></div>
                            <div class="text-sm text-danger">B U Y</div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-4">
                        <a href="/exchange?from=<?= $currency['id'] ?>&to=0"
                           class="btn btn-default btn-outline-sell btn-block text-xl">
                            <div id="<?= $currency['id'] ?>-buy"><?= number_format($currency['buy'], 2, '.', '') ?></div>
                            <div class="text-sm text-success">S E L L</div>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
