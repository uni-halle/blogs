<?php if ( $show_payment_terms ): ?>
    <h3><?php _e( 'Please select a payment term for your Ad', 'AWPCP' ); ?></h3>

    <?php echo awpcp_form_error( 'payment-term', $form_errors ); ?>
    <?php echo $table->render(); ?>

    <?php echo $this->render_credit_plans_table( $transaction ); ?>
<?php else: ?>
    <?php $items = $table->get_items(); ?>
    <?php $value = $table->item_id( $items[0], array_shift( $accepted_payment_types ) ); ?>
    <?php $attrs = array( 'type' => 'hidden', 'name' => 'payment_term', 'value' => $value ); ?>
    <?php echo sprintf( '<input %s>', awpcp_render_attributes( $attrs ) ); ?>
<?php endif; ?>
