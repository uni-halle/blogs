<div class="sidebar">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) :
        $widget_args = array(
            'after_widget' => '</div></div>',
            'before_title' => '<h3>',
            'after_title' => '</h3><div class="widget-body clear">'
        );
    ?>

    <?php the_widget( 'GetConnected', 'title=Get Connected', $widget_args); ?>

    <?php the_widget( 'Recentposts_thumbnail', 'title=Recent posts', $widget_args); ?>

    <div class="widget sponsors">
        <h3>Our sponsors</h3>
        <div class="widget-body">
            <a href="#"><img src="<?php bloginfo('template_url'); ?>/example/sponsor01.jpg" width="200" height="125" alt=""/></a>
            <a href="#"><img src="<?php bloginfo('template_url'); ?>/example/sponsor02.jpg" width="200" height="125" alt=""/></a>
            <a href="#"><img src="<?php bloginfo('template_url'); ?>/example/sponsor03.jpg" width="200" height="125" alt=""/></a>
        </div>
    </div>
            
    <?php endif; ?>
</div>