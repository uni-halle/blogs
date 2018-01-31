<?php if ( $options['page'] ): ?>
<div class="<?php echo $options['page']; ?> awpcp-page" id="classiwrapper">
<?php else: ?>
<div id="classiwrapper">
<?php endif; ?>
    <?php echo $before_content; ?>

    <?php if ( $options['show_intro_message'] ): ?>
    <div class="uiwelcome"><?php echo stripslashes_deep( get_awpcp_option( 'uiwelcome' ) ); ?></div>
    <?php endif; ?>

    <?php if ( $options['show_menu_items'] ): ?>
    <?php echo awpcp_render_classifieds_bar( $options['classifieds_bar_components'] ); ?>
    <?php endif; ?>

    <?php echo implode( '', $before_pagination ); ?>
    <?php echo $pagination; ?>
    <?php echo $before_list; ?>

    <div class="awpcp-listings awpcp-clearboth">
        <?php if ( count( $items ) ): ?>
            <?php echo implode( '', $items ); ?>
        <?php else: ?>
            <p><?php echo esc_html( __( 'There were no listings found.', 'another-wordpress-classifieds-plugin' ) ); ?></p>
        <?php endif;?>
    </div>

    <?php echo $pagination; ?>
    <?php echo implode( '', $after_pagination ); ?>
    <?php echo $after_content; ?>
</div>
