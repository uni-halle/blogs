<table class="awpcp-table awpcp-payment-methods-table">
    <tbody>
    <?php if (empty($payment_methods)): ?>
        <tr><td colspan="2"><?php echo __('No payment methods available.', 'AWPCP') ?></td></tr>
    <?php endif ?>

    <?php foreach ($payment_methods as $pm): ?>

        <tr>
            <?php $id = 'payment-method-' . $pm->slug ?>
            <td class="payment-method">
                <?php $checked = $pm->slug == $payment_method ? 'checked="checked"' : '' ?>
                <input class="" id="<?php echo $id ?>" type="radio" value="<?php echo $pm->slug ?>" name="payment_method" <?php echo $checked ?>>
            </td>
            <td class="payment-method-icon">
                <?php if ($pm->icon): ?>
                <label for="<?php echo $id ?>"><img alt=<?php echo esc_attr($pm->name) ?> src="<?php echo $pm->icon ?>"></label>
                <?php else: ?>
                <label for="<?php echo $id ?>"><?php echo $pm->name ?></label>
                <?php endif ?>
            </td>
        </tr>

    <?php endforeach ?>
    </tbody>
</table>
