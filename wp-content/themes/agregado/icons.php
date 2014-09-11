<?php if(get_option('lifestream_ico1') || get_option('lifestream_ico2') || get_option('lifestream_ico3') || get_option('lifestream_ico4')) { ?>

<style type="text/css">

<?php if(get_option('lifestream_ico1')) : ?>
li.lifestream_feed1 {
	background:url(<?php bloginfo('template_url'); ?>/images/<?php echo get_option(lifestream_ico1); ?>) no-repeat !important;
	}
<?php endif; ?>

<?php if(get_option('lifestream_ico2')) : ?>
li.lifestream_feed2 {
	background:url(<?php bloginfo('template_url'); ?>/images/<?php echo get_option(lifestream_ico2); ?>) no-repeat !important;
	}
<?php endif; ?>

<?php if(get_option('lifestream_ico3')) : ?>
li.lifestream_feed3 {
	background:url(<?php bloginfo('template_url'); ?>/images/<?php echo get_option(lifestream_ico3); ?>) no-repeat !important;
	}
<?php endif; ?>

<?php if(get_option('lifestream_ico4')) : ?>
li.lifestream_feed4 {
	background:url(<?php bloginfo('template_url'); ?>/images/<?php echo get_option(lifestream_ico4); ?>) no-repeat !important;
	}
<?php endif; ?>

</style>

<?php } ?>
