<table border="1" cellspacing="2" cellpadding="2">
    <tr>
        <td align="center" colspan="2">iCryptoCgroup - icrypto.cash</td>
    </tr>
    <tr>
        <td align="center" colspan="2">Your order № <b><?= $model->id ?></b> is awaiting payment and confirmation:</td>
    </tr>
    <tr>
        <td>From:</td>
        <td><?= $model->currency_from->name ?></td>
    </tr>
    <tr>
        <td>To:</td>
        <td><?= $model->currency_to->name ?></td>
    </tr>
    <tr>
        <td>Amount:</td>
        <td><?= $model->amount ?></td>
    </tr>
    <tr>
        <td>Sum:</td>
        <td><?= $model->sum ?></td>
    </tr>
    <?php if ($model->client_card) : ?>
        <tr>
            <td>Your electronic card:</td>
            <td><?= $model->client_card ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Your wallet address:</td>
        <td><?= $model->client_wallet ?></td>
    </tr>
    <tr>
        <td>Date / time:</td>
        <td><?= date('Y-m-d H:i:s'); ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <p>
                You need to send the amount of <b><?= $model->amount ?> <?= $model->currency_from->name ?></b>
                <?php if ($model->currency_id_from !== '0') : ?>
                    from your wallet address <b><?= $model->client_wallet ?></b><br> to our
                    <b><?= $model->currency_from->name ?></b> wallet address
                    <b><?= $model->currency_from->payment_address ?></b>
                <?php else : ?>
                    to our electronic card number <b><?= $model->currency_from->payment_address ?></b><br>
                <?php endif ?>
                untill <b><?php $date = date('Y-m-d H:i:s');
                    $time = strtotime($date . ' + ' . $model->currency_from->valid_time . ' minutes');
                    echo date('Y-m-d H:i:s T', $time); ?></b>
            </p>
            <p>
                <?php if ($model->currency_from->min_confirmations) : ?>
                    Blockchain confirmations required: <b><?= $model->currency_from->min_confirmations ?></b>
                <?php endif ?>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p>
                <?php if ($model->currency_to->commission) : ?>
                    <?= $model->currency_to->name ?> NET FEE: <?php echo $model->currency_to->commission ?> <?= $model->currency_to->name ?>
                    <br>
                    <b>TOTAL
                        PAYOUT <?php echo $model->sum - $model->currency_to->commission ?> <?= $model->currency_to->name ?></b>
                <?php else : ?>
                    <b>TOTAL PAYOUT <?= $model->sum ?> <?= $model->currency_to->name ?></b>
                <?php endif; ?>
            </p>
            <p>
                This order will be processed in 30 minutes after receiving your payment and payment confirmation by
                clicking the
                link:
                <a href="http://icrypto.cash/order/confirm-order?key=<?= $model->key; ?>">Confirm your payment</a>
            </p>
        </td>
    </tr>
</table>