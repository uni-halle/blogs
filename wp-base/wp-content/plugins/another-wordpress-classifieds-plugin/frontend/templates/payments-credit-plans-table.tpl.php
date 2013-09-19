<?php if (!$table_only): ?>
<p><?php _ex('You can additionally purchase a Credit Plan to add credit to your account. If you select to pay using credits, the price of the selected payment term will be deducted from your account balance after you have completed payment.', 'credit plans table', 'AWPCP') ?></p>

<fieldset>
    <h3><?php _ex('Credit Plans', 'credit plans table', 'AWPCP') ?></h3>
<?php endif ?>

    <table class="awpcp-table awpcp-credit-plans-table">
        <thead>
            <tr>
                <th><?php _ex('Plan', 'credit plans table', 'AWPCP') ?></th>
                <th><?php _ex('Description', 'credit plans table', 'AWPCP') ?></th>
                <th><?php _ex('Credits', 'credit plans table', 'AWPCP') ?></th>
                <th><?php _ex('Price', 'credit plans table', 'AWPCP') ?></th>
            </tr>
        </thead>
        <tbody>

        <?php if (empty($credit_plans)): ?>
            <tr><td colspan="4"><?php echo __('No credit plans available.', 'AWPCP') ?></td></tr>
        <?php endif ?>

        <?php $type = '' ?>
        <?php foreach ($credit_plans as $plan): ?>

            <tr data-price="<?php echo esc_attr($plan->price) ?>" data-credits="<?php echo esc_attr($plan->credits) ?>">
                <td>
                    <input id="credit-plan-<?php echo $plan->id ?>" type="radio" name="credit_plan" value="<?php echo $plan->id ?>" <?php echo $plan->id == $selected ? 'checked="checked"' : '' ?> />
                    <label for="credit-plan-<?php echo $plan->id ?>"><?php echo $plan->name ?></label></td>
                <td><?php echo $plan->description ?></td>
                <td><?php echo number_format($plan->credits, 0) ?></td>
                <td><?php echo number_format($plan->price, 2) ?></td>
            </tr>

        <?php endforeach ?>
        </tbody>

        <?php if (!empty($credit_plans)): ?>
        <tfoot>
            <tr class="clear-selection" data-price="0" data-credits="0">
                <td colspan="4">
                    <input id="credit-plan-0" type="radio" name="credit_plan" value="0" <?php echo 0 == $selected ? 'checked="checked"' : '' ?> />
                    <label for="credit-plan-0"><?php _ex('clear selection', 'credit plans table', 'AWPCP') ?></label></td>
                </td>
            </tr>
        </tfoot>
        <?php endif ?>
    </table>

<?php if (!$table_only): ?>
</fieldset>
<?php endif ?>
