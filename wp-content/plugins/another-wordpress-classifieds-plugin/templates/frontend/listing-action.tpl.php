<?php $css_class = esc_attr( "awpcp-listing-action-" . $this->get_slug() ); ?>
<form class="awpcp-listing-action-form <?php echo $css_class; ?>-form" action="<?php echo esc_attr( $this->get_endpoint( $listing, $config ) ); ?>" method="post">
    <?php foreach ( $this->filter_params( $config['hidden-params'] ) as $name => $value ): ?>
    <input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
    <?php endforeach; ?>

    <!--<span class="<?php echo $css_class; ?>-description"><?php echo esc_html( $this->get_description() ); ?></span>-->

    <input class="<?php echo $css_class; ?>-submit-button button-primary" type="submit" value="<?php echo esc_attr( $this->get_submit_button_label() ); ?>" />
</form>
