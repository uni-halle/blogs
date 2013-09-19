<div id="sidebar">
<ul>
    <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
    <li>
        <h2><?php _e('Archives'); ?></h2>
        <ul>
            <?php wp_get_archives('type=monthly'); ?>
        </ul>
    </li>
    <li>
        <h2><?php _e('Categories'); ?></h2>
        <ul>
            <?php wp_list_categories('depth=3&title_li='); ?>
        </ul>
    </li>
    <?php endif; ?>
</ul>
</div>