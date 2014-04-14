<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>
<head>
<?php wp_head(); // Hook required for scripts, styles, and other <head> items. ?>
</head>

<body <?php body_class(); ?> <?php omega_attr( 'body' ); ?>>

<?php do_action( 'omega_before' ); ?>

<div class="<?php echo omega_apply_atomic( 'site_container_class', 'site-container' );?>">

	<?php 
	do_action( 'omega_before_header' );
	do_action( 'omega_header' );
	do_action( 'omega_after_header' ); 
	?>

	<div class="site-inner">

		<?php do_action( 'omega_before_main' ); ?>