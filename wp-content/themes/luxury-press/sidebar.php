<div class="sideBar">
<!-- Sidebar List ************************************************************* -->
    				<!--<ul><li><div class="sideBarTitle"><h2><?php _e('Menu'); ?></h2></div></li></ul>-->
                    <ul>
						<?php 	/* Widgetized sidebar, if you have the plugin installed. */
                        if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) : ?>
                        
                         <!-- ************************************************************* -->
                                <li>
                                            <div class="sideBarTitle"><h2><?php _e('Pages'); ?></h2></div>
                                        	<ul><?php wp_list_pages('title_li'); ?></ul>
                                </li>
                         <!-- ************************************************************* -->
                                <li>
                                            <div class="sideBarTitle"><h2><?php _e('Categories'); ?></h2></div>
                                            <ul><?php wp_list_categories("title_li=");?></ul>
                                </li>
                         <!-- ************************************************************* -->
                                <li>
                                            <div class="sideBarTitle"><h2><?php _e('Archives'); ?></h2></div>
                                        	<ul><?php wp_get_archives('type=monthly'); ?></ul>
                                </li>
                         <!-- ************************************************************* -->
                        <?php endif; ?>
                    </ul>
<!-- Close Sidebar List ************************************************************* -->
</div><!-- close sidebar -->