<div class="metabox-holder">
    <div class="apostboxes">
        <h3 class="hndle"><span><?php _e( 'Credit System Settings', 'AWPCP' ); ?></span></h3>
        <div class="inside">
        <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
            <table class="form-table">
            <?php do_settings_fields( 'payment-settings', 'credit-system' ); ?>
            </table>
            <?php settings_fields( $option ); ?>
            <input type="hidden" name="group" value="<?php echo 'payment-settings' ?>" />

            <p class="submit">
                <input type="submit" value="<?php _e( 'Save Changes', 'AWPCP' ); ?>" class="button-primary" id="submit" name="submit">
            </p>
        </form>
        </div>
    </div>
</div>

<form method="get" action="<?php echo esc_attr($this->url(array('action' => false))) ?>">
    <?php foreach ($this->params as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name) ?>" value="<?php echo esc_attr($value) ?>" />
    <?php endforeach ?>

    <?php $url = $this->url( array( 'action' => 'add-credit-plan' ) ); ?>
    <?php $label = __( 'Add Credit Plan', 'AWPCP' ); ?>
    <a class="add button-primary" title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_attr( $url ); ?>" accesskey="s"><?php echo $label; ?></a>

    <?php //echo $table->views() ?>
    <?php //echo $table->search_box(__('Search Credit Plans', 'AWPCP'), 'credit-plans') ?>
    <?php //echo $table->get_search_by_box() ?>

    <?php echo $table->display() ?>
</form>
