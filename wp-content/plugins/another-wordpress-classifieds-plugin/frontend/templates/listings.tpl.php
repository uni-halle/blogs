<div id="classiwrapper">
    <?php if ($config['show_intro']): ?>
    <div class="uiwelcome"><?php echo stripslashes_deep(get_awpcp_option('uiwelcome')); ?></div>
    <?php endif; ?>

    <?php if ($config['show_menu']): ?>
    <?php echo awpcp_menu_items(); ?>
    <?php endif; ?>

    <?php echo join('', $before_content); ?>

    <?php echo $pagination_block; ?>
    <div class="awpcp-listings awpcp-clearboth"><?php echo join( '', $items ); ?></div>
    <?php echo $pagination_block; ?>

    <?php echo join('', $after_content); ?>
</div>
