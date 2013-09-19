<table class="awpcp-table awpcp-transaction-items-table">
    <thead>
        <tr>
            <th class="item"><?php _ex('Item', 'transaction items', 'AWPCP'); ?></th>
            <th class="amount"><?php _ex('Amount', 'transaction items', 'AWPCP'); ?></th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($transaction->get_items() as $item): ?>

        <tr>
            <td class="item">
                <?php echo $item->name; ?><br>
                <?php echo $item->description; ?>
            </td>
            <td class="amount">
            <?php if ($item->payment_type === 'money'): ?>
                <?php echo awpcp_format_money($item->amount); ?>
            <?php else: ?>
                <?php echo number_format($item->amount, 0); ?>
            <?php endif; ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>

    <tfoot>
        <?php $totals = $transaction->get_totals(); ?>

        <?php if ($show_credits): ?>
        <tr>
            <td class="row-header"><?php _ex('Total Amount (credit)', 'transaction items', 'AWPCP'); ?></td>
            <td class="amount"><?php echo number_format($totals['credits'], 0); ?></td>
        </tr>
        <?php endif; ?>

        <tr>
            <td class="row-header"><?php _ex('Total Amount ($)', 'transaction items', 'AWPCP'); ?></td>
            <td class="amount"><?php echo awpcp_format_money($totals['money']); ?></td>
        </tr>
    </tfoot>
</table>
