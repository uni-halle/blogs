<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to tempera_comment which is
 * located in the includes/theme-comments.php file.
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */

$temperas = tempera_get_theme_options();
$tempera_comclass = '';
if ( (!comments_open()) && (get_comments_number()<1) && 
	( ($temperas['tempera_comclosed']=="Hide everywhere") || 
	  (is_page() && $temperas['tempera_comclosed']=="Hide in pages") || 
	  (is_single() && $temperas['tempera_comclosed']=="Hide in posts") 
	) ) {
	$tempera_comclass="hideme";
};
?> <div id="comments" class="<?php echo $tempera_comclass ?>"> <?php
if (get_comments_number()<1):

else:
	if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'tempera' ); ?></p>
		</div><!-- #comments --> <?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;

	// You can start editing here -- including this comment!

	if ( have_comments() ) : cryout_before_comments_hook(); ?>
	<ol class="commentlist">
		<?php cryout_comments_hook(); ?>
	</ol>
	<?php cryout_after_comments_hook(); 
	else : // or, if we don't have comments:
		cryout_nocomments_hook();
	endif; // end have_comments() ?>


<?php
endif; 
?>
<?php if ( comments_open() ): comment_form(); 
	  else : ?> <p class="nocomments<?php if (is_page()) echo "2"; ?>"><?php _e("Comments are closed","tempera");?></p> <?php
	  endif; ?>
</div><!-- #comments -->
