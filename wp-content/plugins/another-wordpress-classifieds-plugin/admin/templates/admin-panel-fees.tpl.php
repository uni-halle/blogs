<form method="get" action="<?php echo esc_attr($this->url(array('action' => false))) ?>">
    <?php foreach ($this->params as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach ?>

    <?php $url = $this->url( array( 'action' => 'add-fee' ) ); ?>
    <?php $label = __( 'Add Fee Plan', 'AWPCP' ); ?>
    <a class="add button-primary" title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_attr( $url ); ?>" accesskey="s"><?php echo $label; ?></a>
    <span><?php
        $fee_settings_url = awpcp_get_admin_settings_url( 'payment-settings' );
        $fee_settings_link = sprintf( '<a href="%s">%s</a>', $fee_settings_url, __( 'Fee Plan Settings', 'AWPCP' ) );

        $message = __( 'If you wish to change the sorting of your fee plans, you can change the <fee-settings-link>.', 'AWPCP' );
        $message = str_replace( '<fee-settings-link>', $fee_settings_link, $message );

        echo $message; ?></span>

    <?php //echo $table->views() ?>
    <?php //echo $table->search_box(__('Search Credit Plans', 'AWPCP'), 'credit-plans') ?>
    <?php //echo $table->get_search_by_box() ?>

    <?php echo $table->display() ?>
</form>
