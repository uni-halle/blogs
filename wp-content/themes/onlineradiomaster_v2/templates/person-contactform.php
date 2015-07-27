<?php
/*
Template Name: Person Contact Form
*/
$profile_picture = get_field('profile_picture');
?>

<div class="row">
	<div class="medium-6 columns">
		<?php if( !empty($profile_picture) ): ?>
			<img src="<?php echo $profile_picture['url']; ?>" alt="<?php echo $profile_picture['alt']; ?>" />
		<?php endif; ?>
	</div>

	<div class="medium-6 columns">
		<h5><?php the_title(); ?></h5>
		<h6><?php the_field('job_title'); ?></h6>
		<?php
			$phone = get_field('phone');
			if ( !empty($phone) ) : ?>
				<a href="tel:+49<?php the_field('phone'); ?>">+49<?php the_field('phone'); ?></a>
		<?php endif; ?>
	</div>
</div>
<div class="row">
	<div class="medium-12 columns">
		<?php the_field('contact'); ?>
	</div>
</div>
