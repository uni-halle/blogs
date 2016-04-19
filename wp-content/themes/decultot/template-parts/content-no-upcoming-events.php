<?php
/**
 * The template part for displaying a message that there are momentarily no future events
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<?php if (is_front_page()) : ?>

	<p class="no-upcoming"><?php _e('There are currently no upcoming events.', 'dct'); ?></p>

<?php else : ?>

	<section class="no-results not-found">
		<header class="page-header">
			<h1 class="page-title"><?php _e('There are currently no upcoming events.', 'dct'); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content">
			<p><?php _e('Please come back later.', 'dct'); ?></p>
		</div><!-- .page-content -->
	</section><!-- .no-results -->

<?php endif;