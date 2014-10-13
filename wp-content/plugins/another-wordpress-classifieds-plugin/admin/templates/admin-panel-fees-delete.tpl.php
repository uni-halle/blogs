<?php $message = __( 'Associate Ads currently associated with Fee plan %s to one of the following fees:', 'AWPCP' ); ?>
<p><?php echo sprintf($message, '<strong>' . esc_html( $fee->name ) . '</strong>') ?></p>

<form method="post" action="<?php echo esc_attr( $this->url() ); ?>">
    <select name="payment_term">
    <?php foreach ($fees as $term):
            if ($term->id == $fee->id) continue; ?>
        <option value="<?php echo esc_attr( $term->id ); ?>"><?php echo esc_html( $term->name ); ?></option>
    <?php endforeach ?>
    </select>

    <input class="button" type="submit" value="<?php echo esc_attr( __( 'Cancel', 'AWPCP' ) ); ?>" id="submit" name="cancel">
    <input class="button button-primary" type="submit" value="<?php echo esc_attr( __( 'Switch', 'AWPCP' ) ); ?>" id="submit" name="transfer">
    <input type="hidden" value="<?php echo esc_attr( $fee->id ); ?>" name="id">
    <input type="hidden" value="transfer" name="action">
</form>
