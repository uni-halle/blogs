<?php
/*
Template Name: Person List By Category
*/
$profile_picture = get_field('profile_picture');

$odd = array(
	'small-12 medium-4 medium-offset-2 columns text-right',
	'small-12 medium-4 columns end',
);
$even = array(
	'small-12 medium-4 medium-push-6 columns text-left',
	'small-12 medium-4 medium-pull-6 medium-offset-2 columns',
);

//global $counter;
$classes = $query->current_post % 2 == 0 ? $odd : $even;

?>


<section class="row">
	<div class="<?php echo $classes[0]; ?>">
		<h3><?php the_title(); ?></h3>
		<p><?php the_field( 'job_title' ); ?></p>
		<?php if( !empty(get_field('email')) ) : ?>
			<p>Mail: <a href="mailto:<?php the_field( 'email' ); ?>"><?php the_field( 'email' ); ?></a></p>
		<?php endif; ?>
		<a href="#" data-reveal-id="Modal-<?php the_ID(); ?>" class="button"><?php _e( 'Read biography' ); ?></a>
		<div id="Modal-<?php the_ID(); ?>" data-reveal class="reveal-modal">
			<h2><?php the_title(); ?></h2>
			<p class="lead"><?php the_field( 'job_title' ); ?></p>
			<p><?php the_field( 'biography' ); ?></p><a class="close-reveal-modal">&times;</a>
		</div>
	</div>
	<div class="<?php echo $classes[1]; ?>">
		<img src="<?php echo $profile_picture['url']; ?>" alt="<?php echo $profile_picture['alt']; ?>" />
	</div>
</section>
