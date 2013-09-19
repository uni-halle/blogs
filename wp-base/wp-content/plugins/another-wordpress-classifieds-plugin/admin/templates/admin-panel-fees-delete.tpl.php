<?php $message = __('Change Ads associated with Fee plan %s to one of the following fees.', 'AWPCP') ?>
<p><?php echo sprintf($message, '<strong>' . $fee->name . '</strong>') ?></p>

<form method="post" action="<?php echo $this->url() ?>">
    <select name="payment_term">
    <?php foreach ($fees as $term):
            if ($term->id == $fee->id) continue; ?>
        <option value="<?php echo $term->id ?>"><?php echo $term->name ?></option>
    <?php endforeach ?>
    </select>

    <input class="button" type="submit" value="<?php _e('Cancel', 'AWPCP') ?>" id="submit" name="cancel">
    <input class="button button-primary" type="submit" value="<?php _e('Switch', 'AWPCP') ?>" id="submit" name="transfer">
    <input type="hidden" value="<?php echo $fee->id ?>" name="id">
    <input type="hidden" value="transfer" name="action">
</form>
