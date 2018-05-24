<?php
/**
 * The Sidebar for display in the content page. 
 *
 * @package Graphene
 * @since graphene 1.0.8
 */
global $graphene_settings;

if ( is_front_page() && $graphene_settings['alt_home_footerwidget'] ) $columns = $graphene_settings['alt_footerwidget_column'];
else $columns = $graphene_settings['footerwidget_column'];

if (((!$graphene_settings['alt_home_footerwidget'] || !is_front_page()) && is_active_sidebar('footer-widget-area')) 
	|| ($graphene_settings['alt_home_footerwidget'] && is_active_sidebar('home-footer-widget-area') && is_front_page())) : ?>
    
    <?php do_action('graphene_before_bottomsidebar'); ?>
    
    <div id="sidebar_bottom" class="sidebar widget-area row footer-widget-col-<?php echo $columns; ?>">
        <?php graphene_container_wrapper( 'start' ); ?>
            <?php 
                do_action( 'graphene_bottomsidebar' );

                if ( is_front_page() && $graphene_settings['alt_home_footerwidget'] ) dynamic_sidebar( 'home-footer-widget-area' );
                else dynamic_sidebar( 'footer-widget-area' );
            ?>		
        <?php graphene_container_wrapper( 'end' ); ?>
    </div>

	<?php do_action('graphene_after_bottomsidebar'); ?>
<?php endif; ?>