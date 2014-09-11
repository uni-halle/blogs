<div id="smoothmenu1" class="ddsmoothmenu">
    <ul>
        <?php if($options['menu_items'] == 'pages') { ?>
        <li <?php if(!is_page() ) {?> class="current_page_item"<?php }?>><a href="<?php bloginfo('home'); ?>"><?php _e('Home'); ?></a></li>
        <?php echo remove_title_attribute(wp_list_pages('depth=3&title_li=&echo=0'.$excludelist)); ?>
        <?php } else if($options['menu_items'] == 'categories') { ?>
        <li <?php if(!is_archive() ) {?> class="current-cat"<?php }?>><a href="<?php bloginfo('home'); ?>"><?php _e('Home'); ?></a></li>
        <?php echo remove_title_attribute(wp_list_categories('depth=3&title_li=&echo=0')); ?>
        <?php } ?>
    </ul>
    <div class="clear"></div>
</div>