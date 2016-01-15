<?php if (!$table_only): ?>
<p><?php _ex('You can additionally purchase a Credit Plan to add credit to your account. If you select to pay using credits, the price of the selected payment term will be deducted from your account balance after you have completed payment.', 'credit plans table', 'another-wordpress-classifieds-plugin') ?></p>

<fieldset>
    <h3><?php _ex('Credit Plans', 'credit plans table', 'another-wordpress-classifieds-plugin') ?></h3>
<?php endif ?>

    <table class="awpcp-credit-plans-table awpcp-table">
        <thead>
            <tr>
                <th><?php echo esc_html( $column_names['plan'] ); ?></th>
                <th><?php echo esc_html( $column_names['description'] ); ?></th>
                <th><?php echo esc_html( $column_names['credits'] ); ?></th>
                <th><?php echo esc_html( $column_names['price'] ); ?></th>
            </tr>
        </thead>
        <tbody>

        <?php if (empty($credit_plans)): ?>
            <tr><td colspan="4"><?php echo __('No credit plans available.', 'another-wordpress-classifieds-plugin') ?></td></tr>
        <?php endif ?>

        <?php $type = '' ?>
        <?php foreach ($credit_plans as $plan): ?>

            <tr data-price="<?php echo esc_attr($plan->price) ?>" data-credits="<?php echo esc_attr($plan->credits) ?>">
                <td data-title="<?php echo esc_attr( $column_names['plan'] ); ?>">
                    <input id="credit-plan-<?php echo esc_attr( $plan->id ); ?>" type="radio" name="credit_plan" value="<?php echo esc_attr( $plan->id ); ?>" <?php echo $plan->id == $selected ? 'checked="checked"' : '' ?> />
                    <label for="credit-plan-<?php echo esc_attr( $plan->id ); ?>"><?php echo esc_html( $plan->name ); ?></label>
                </td>
                <td data-title="<?php echo esc_attr( $column_names['description'] ); ?>"><?php echo esc_html( $plan->description ); ?>&nbsp;</td>
                <td data-title="<?php echo esc_attr( $column_names['credits'] ); ?>"><?php echo esc_html( awpcp_format_integer( $plan->credits ) ); ?></td>
                <td data-title="<?php echo esc_attr( $column_names['price'] ); ?>"><?php echo esc_html( awpcp_format_money( $plan->price ) ); ?></td>
            </tr>

        <?php endforeach ?>
        </tbody>

        <?php if (!empty($credit_plans)): ?>
        <tfoot>
            <tr class="clear-selection" data-price="0" data-credits="0">
                <td colspan="4">
                    <input id="credit-plan-0" type="radio" name="credit_plan" value="0" <?php echo 0 == $selected ? 'checked="checked"' : '' ?> />
                    <label for="credit-plan-0"><?php _ex('clear selection', 'credit plans table', 'another-wordpress-classifieds-plugin') ?></label></td>
                </td>
            </tr>
        </tfoot>
        <?php endif ?>
    </table>

<?php if (!$table_only): ?>
</fieldset>
<?php endif ?>
