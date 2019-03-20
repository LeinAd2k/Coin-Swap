<table border="1" cellspacing="2">
    <tr>
        <td colspan="2">New exchange order № <b><?= $id ?></b> is pending payment confirmation:</td>
    </tr>
    <tr>
        <td>From:</td>
        <td><?= $from ?></td>
    </tr>
    <tr>
        <td>To:</td>
        <td><?= $to ?></td>
    </tr>
    <tr>
        <td>Amount:</td>
        <td><?= $amount ?></td>
    </tr>
    <tr>
        <td>Sum:</td>
        <td><?= $sum ?></td>
    </tr>
    <?php if ($client_card) : ?>
        <tr>
            <td>Card:</td>
            <td><?= $client_card ?></td>
        </tr>
    <?php endif; ?>
    <tr>
        <td>Wallet:</td>
        <td><?= $client_wallet ?></td>
    </tr>
    <tr>
        <td>E-mail:</td>
        <td><?= $email ?></td>
    </tr>
    <tr>
        <td>Date / time:</td>
        <td><?= date('Y-m-d H:i:s'); ?></td>
    </tr>
</table>