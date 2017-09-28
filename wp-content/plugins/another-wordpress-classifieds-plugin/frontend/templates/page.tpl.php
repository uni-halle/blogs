<?php awpcp_print_messages() ?>

<div class="<?php echo esc_attr( $this->page ); ?> awpcp-page" id="classiwrapper">

    <?php if ( $this->show_menu_items ): ?>
        <?php echo awpcp_render_classifieds_bar( $this->classifieds_bar_components ); ?>
    <?php endif; ?>

	<?php echo $content ?>
</div>
