<?php
/**
 * The template part for displaying post meta info.
 *
 * @package bueno
 */
?> 
<?php $format = get_post_format(); ?>
<span class="post_type_label <?php echo esc_attr( $format ); ?>"><span class="post_type_label_inner"></span></span>
	<!-- Post Meta -->
	<?php  
		if ('aside' == $format) {
	?>
	<div class="post_meta aside">
		<div class="post_meta_row_1">
			<?php bueno_show_post_author() ?>
			<?php bueno_show_post_date() ?>
			<?php bueno_show_post_comments() ?>
		</div>
		<div class="post_meta_row_2">
			<?php bueno_show_post_categories() ?>
		</div>
	</div>
	<?php
		} elseif ('chat' == $format) {
	?>
	<div class="post_meta chat">
		<div class="post_meta_row_1">
			<?php bueno_show_post_author() ?>
			<?php bueno_show_post_date() ?>
			<?php bueno_show_post_comments() ?>
		</div>
		<div class="post_meta_row_2">
			<?php bueno_show_post_categories() ?>
		</div>
	</div>
	<?php
		} elseif ('link' == $format) {
	?>
	<div class="post_meta link">
		<div class="post_meta_row_1">
			<?php bueno_show_post_author() ?>
			<?php bueno_show_post_date() ?>
		</div>
		<div class="post_meta_row_2">
			<?php bueno_show_post_categories() ?>
			<?php if ( !is_singular() ) { ?>
			<span class="post_permalink"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Permalink to:', 'bueno');?> <?php the_title(); ?>"><i class="icon-link"></i><?php _e('Permalink', 'bueno') ?></a></span>
			<?php } ?>
		</div>
	</div>
	<?php
		} elseif ('quote' == $format) {
	?>
	<div class="post_meta quote">
		<div class="post_meta_row_1">
			<?php bueno_show_post_author() ?>
			<?php bueno_show_post_date() ?>
			<?php bueno_show_post_comments() ?>
		</div>
		<div class="post_meta_row_2">
			<?php bueno_show_post_categories() ?>
			<?php if ( !is_singular() ) { ?>
			<span class="post_permalink"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Permalink to:', 'bueno');?> <?php the_title(); ?>"><i class="icon-link"></i><?php _e('Permalink', 'bueno') ?></a></span>
			<?php } ?>
		</div>
	</div>
	<?php
		} elseif ('status' == $format) {
	?>
	<div class="post_meta status">
		<div class="post_meta_row_1">
			<?php bueno_show_post_author() ?>
			<?php bueno_show_post_date() ?>
			<?php bueno_show_post_comments() ?>
		</div>
		<div class="post_meta_row_2">
			<?php bueno_show_post_categories() ?>
			<?php if ( !is_singular() ) { ?>
			<span class="post_permalink"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Permalink to:', 'bueno');?> <?php the_title(); ?>"><i class="icon-link"></i><?php _e('Permalink', 'bueno') ?></a></span>
			<?php } ?>
		</div>
	</div>
	<?php 	
		} else {
	?>
	<div class="post_meta default">
		<div class="post_meta_row_1">
			<?php bueno_show_post_author() ?>
			<?php bueno_show_post_date() ?>
			<?php bueno_show_post_comments() ?>
		</div>
		<div class="post_meta_row_2">
			<?php bueno_show_post_categories() ?>
		</div>
	</div>
	<?php } ?>
	<!--// Post Meta -->