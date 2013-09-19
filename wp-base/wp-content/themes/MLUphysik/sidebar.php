<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 */
 $options = get_option( 'MLUphysik_theme_settings' );
?>
<!--
<div id="sidebar" class="clearfix">

	<div id="logo">
		<?php
            if($options['upload_mainlogo'] !='') { ?>
                <a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>"><img src="<?php echo $options['upload_mainlogo']; ?>" alt="<?php bloginfo( 'name' ) ?>" /></a>
            <?php } else { ?>
            <a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>"><?php bloginfo( 'name' ) ?></a>
        <?php } ?>    
	</div>
	<!-- END logo -->
<!--
	<div id="navigation">
            <?php
            //define main navigation
            wp_nav_menu( array(
                'theme_location' => 'menu',
                'sort_column' => 'menu_order',
                'menu_class' => 'sf-menu',
                'fallback_cb' => 'default_menu'
            )); ?>
	</div>
	<!-- END navigation -->   
        <!--
	<?php dynamic_sidebar('sidebar'); ?>
</div>
-->
<!-- END sidebar -->