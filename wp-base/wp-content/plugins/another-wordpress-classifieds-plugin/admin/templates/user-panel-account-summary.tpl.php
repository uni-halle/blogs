<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<p><?php echo $payments->render_account_balance() ?></p>

<p><?php _e('The credit in your account can be used to pay for posting your Ads. You can add credit when posting a new Ad or using the "Add Credit" button below.', 'AWPCP') ?></p>

<form method="post" action="<?php echo $action ?>">
    <p class="form-submit">
        <input class="button" type="submit" value="<?php _e('Add Credit', 'AWPCP') ?>" id="submit" name="submit">
        <input type="hidden" value="order" name="step">
    </p>
</form>
