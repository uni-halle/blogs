<?php
/*
Template Name: Person Testimonial Sidebar
*/

$profile_picture = get_field( 'profile_picture' );
?>

<div class="row">
	<?php if( !empty($profile_picture) ): ?>
		<img src="<?php echo $profile_picture['url']; ?>" alt="<?php echo $profile_picture['alt']; ?>" class="medium-6 columns" />
	<?php endif; ?>
	<div class="medium-6 columns">
		<h5><?php the_title(); ?></h5>
		<h6><?php the_field( 'status' ); ?></h6>
	</div>
	<blockquote class="medium-12 columns"><?php the_field( 'testimonial' ); ?></blockquote>
</div>
