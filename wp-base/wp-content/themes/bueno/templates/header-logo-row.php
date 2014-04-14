<?php
/**
 * The template part for displaying logo and site description.
 *
 * @package bueno
 */
?>
<div class="header_middle_row">
	<div class="logo">
	<?php if (( of_get_option('logo_type') == 'image_logo') && ( of_get_option('logo_url') != '')) { ?>
	        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo esc_url( of_get_option('logo_url') ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
	<?php } else { ?>
	    <?php if ( is_front_page() || is_home() || is_404() ) { ?>
	        <h1 class="text-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	    <?php } else { ?>
	        <h2 class="text-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
	    <?php } ?>
	<?php } ?>
	    <p class="site-description"><?php bloginfo( 'description' ); ?></p>
	</div>
	<div class="header_banner">
		<?php
			$bueno_top_banner_img = of_get_option('g_header_banner_img');
			if ( '' != $bueno_top_banner_img ) {
				$bueno_top_banner_url = of_get_option('g_header_banner_url');
				if ( '' != $bueno_top_banner_url ) {
					echo "<a href='" . esc_url( $bueno_top_banner_url ) . "'>";
				}
				echo "<img src='" . esc_url( $bueno_top_banner_img ) . "' alt=''>";
				if ( '' != $bueno_top_banner_url ) {
					echo "</a>";
				}
			}
		?>
	</div>
</div>