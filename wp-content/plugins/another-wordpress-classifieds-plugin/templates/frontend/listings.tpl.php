<div id="classiwrapper">
    <?php echo $before_content; ?>

    <?php if ( $options['show_intro_message'] ): ?>
    <div class="uiwelcome"><?php echo stripslashes_deep( get_awpcp_option( 'uiwelcome' ) ); ?></div>
    <?php endif; ?>

    <?php if ( $options['show_menu_items'] ): ?>
    <?php echo awpcp_menu_items(); ?>
    <?php endif; ?>

    <?php echo implode( '', $before_pagination ); ?>
    <?php echo $pagination; ?>
    <?php echo $before_list; ?>

    <div class="awpcp-listings awpcp-clearboth">
        <?php if ( count( $items ) ): ?>
            <?php echo implode( '', $items ); ?>
        <?php else: ?>
            <p><?php echo esc_html( __( 'There were no listings found.', 'AWPCP' ) ); ?></p>
        <?php endif;?>
    </div>

    <?php echo $pagination; ?>
    <?php echo implode( '', $after_pagination ); ?>
    <?php echo $after_content; ?>
</div>
