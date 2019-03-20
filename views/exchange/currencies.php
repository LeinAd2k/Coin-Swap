<div class="container" id="currencies">
    <?php foreach ($currencies as $currency): ?>
        <?php if ($currency['visible'] == 1 && $currency['id'] != 0) : ?>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-md text-center">
                <div class="col-xs-4 col-sm-12 col-md-12">
                    <span><b>USD/<?= $currency['name'] ?> </b></span>
                </div>
                <div class="col-xs-4 col-sm-12 col-md-12">
                    <span id="<?= $currency['id'] ?>-sell"><?= number_format($currency['sell'], 2, '.', '') ?></span>
                </div>
                <div class="col-xs-4 col-sm-12 col-md-12">
                    <span id="<?= $currency['id'] ?>-buy"><?= number_format($currency['buy'], 2, '.', '') ?></span>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>