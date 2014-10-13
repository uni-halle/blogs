<div class="metabox-holder">
    <div class="postbox">
        <h3 class="hndle"><span><?php echo esc_html( __( 'Credit System Settings', 'AWPCP' ) ); ?></span></h3>
        <div class="inside">
        <form action="<?php echo esc_attr( admin_url( 'options.php' ) ); ?>" method="post">
            <table class="form-table">
            <?php do_settings_fields( 'payment-settings', 'credit-system' ); ?>
            </table>
            <?php settings_fields( $option ); ?>
            <input type="hidden" name="group" value="<?php echo 'payment-settings'; ?>" />

            <p class="submit">
                <input type="submit" value="<?php echo esc_attr( __( 'Save Changes', 'AWPCP' ) ); ?>" class="button-primary" id="submit" name="submit">
            </p>
        </form>
        </div>
    </div>
</div>

<form method="get" action="<?php echo esc_attr( $this->url( array( 'action' => false ) ) ); ?>">
    <?php foreach ($this->params as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
    <?php endforeach ?>

    <?php $url = $this->url( array( 'action' => 'add-credit-plan' ) ); ?>
    <?php $label = __( 'Add Credit Plan', 'AWPCP' ); ?>
    <a class="add button-primary" title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_attr( $url ); ?>" accesskey="s"><?php echo esc_html( $label ); ?></a>

    <?php echo $table->display(); ?>
</form>
