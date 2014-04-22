<?php
/**
 * The front page file.
 *
 * @package BizFlare
 */

get_header(); ?>

	<?php 
		
		if( !of_get_option('homepage_layout') || of_get_option('homepage_layout') == 'bone' ) {
			get_template_part( 'index', 'bone' );			 
		}elseif( of_get_option('homepage_layout') == 'bsix' ) {
			get_template_part( 'index', 'bsix' ); 
		}else{	
			if( 'page' == get_option( 'show_on_front' ) ){	
				get_template_part('index', 'page');
			}else {
				get_template_part('index', 'standard');
			}
		}
		
	?>

<?php get_footer(); ?>