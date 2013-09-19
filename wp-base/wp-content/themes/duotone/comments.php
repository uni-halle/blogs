<?php
/**
 * @package Duotone
 */

if ( post_password_required() )
	return;

if ( have_comments() ) : ?>
	<h3 id="comments"><?php
		printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'duotone' ), number_format_i18n( get_comments_number() ), get_the_title() );
	?></h3>

	<ol class="commentlist">
		<?php wp_list_comments( array( 'callback' => 'duotone_comment' ) ); ?>
	</ol>
	<div class="comment-navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>

	<br />

	<?php if ( !comments_open() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'duotone' ); ?></p>
	<?php endif; ?>
<?php endif; ?>


<?php comment_form();
