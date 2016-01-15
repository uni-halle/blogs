<form class='userform' method='post' action='/wp-admin/admin-post.php'>
    <div class='formrow'>
        <label class='required'><?php echo __("Name", "quintlab"); ?></label>
        <input class='text' name='name' type='text'>
    </div>
    <div class='formrow'>
        <label class='required'><?php echo __("Email address", "quintlab"); ?></label>
        <input class='text' name='email' type='text'>
    </div>
    <div class='formrow'>
        <label><?php echo __("Subject", "quintlab"); ?></label>
        <input class='text' name='subject' type='text'>
    </div>
    <div class='formrow'>
        <label class='required'><?php echo __("Message", "quintlab"); ?></label>
        <textarea class='text' name='message' rows='7'></textarea>
    </div>
    <div class='formrow'>
        <?php $captcha = ql_captcha(); ?>
        <label class='required'><?php echo __("Spam protection:", "quintlab") . " " . $captcha["label"]; ?></label>
        <input name='captcha_id' type='hidden' value='<?php echo $captcha["id"]; ?>'>
        <input class='text' name='captcha_result' type='text'>
    </div>
    <div class='formrow'>
        <?php wp_nonce_field('contactform', '_wpnonce', true); ?>
        <input type="hidden" name="action" value="ql_contactform">
        <button class='main' type='submit'><?php echo __("Send", "quintlab"); ?></button>
        <button type='reset'><?php echo __("Cancel", "quintlab"); ?></button>
    </div>
</form>
