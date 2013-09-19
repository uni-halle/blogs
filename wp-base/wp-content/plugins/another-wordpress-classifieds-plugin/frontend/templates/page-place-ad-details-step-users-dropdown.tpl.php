<p class="awpcp-form-spacer">
    <label for="ad-user-id"><?php _e('User', 'AWPCP') ?></label>
    <select id="ad-user-id" name="user" class="required">
        <option value=""><?php _e('Select an User owner for this Ad', 'AWPCP') ?></option>

        <?php foreach ($users as $k => $user): ?>
        <option value="<?php echo esc_attr($user->ID) ?>" data-payment-terms="<?php echo esc_attr($user->payment_terms) ?>" <?php echo $selected == $user->ID ? 'selected="selected"' : '' ?>>
            <?php echo $user->display_name ?>
        </option>
        <?php endforeach ?>
    </select>
    <?php echo awpcp_form_error('user', $errors) ?>
</p>
