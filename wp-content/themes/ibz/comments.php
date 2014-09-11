<?php
/**
 * The template for displaying Comments.
 */
?>

<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!'); 
?>

<?php if ( post_password_required() ) : ?>
	
    <p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'maja' ); ?></p>		

<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php $altcomment = 'alt'; // alternating comments have a class of 'alt' ?>

<?php if ( have_comments() ) : ?>

	<h2><?php _e('Comments', 'maja'); ?></h2>
    
    <ul class="commentlist">
    	<?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
    </ul>
        
    <nav>
    	<ul class="pages">
            <li class="left">
                <?php previous_comments_link(); ?>
            </li>
            
            <li class="right">
                <?php next_comments_link(); ?>
            </li>
        </ul>
    </nav>  
        
    <?php if  (pings_open()) : ?> 
    
    	<div class="space"></div> 
                
        <h2><?php _e('Trackbacks and Pingbacks', 'maja'); ?></h2>
        
        <ul class="commentlist">
            <?php wp_list_comments('type=pings&callback=mytheme_comment'); ?>
        </ul>
    
    <?php endif; ?>
    
<?php else : ?>
    
    <?php if ($post->comment_status == 'open') : ?>
    
        <?php _e('There are no comments yet, add one below.', 'maja'); ?>
        <div class="space"></div>
        
    <?php else : ?>
    
        <p><?php _e('Comments are closed.', 'maja'); ?></p>
        
<?php endif; endif; ?>

<?php if ($post->comment_status == 'open') : ?>
        
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
    
		<p>
			<?php _e('You must be', 'maja'); ?>
            <a href="<?php echo home_url(); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">
				<?php _e('logged in', 'maja'); ?>
            </a> 
			<?php _e('to post a comment.', 'maja'); ?>
        </p>
        
	<?php else : ?>
                                       
	<?php comment_form(array('comment_notes_before' => '<div class="clear"></div>', 'comment_notes_after' => '')); ?> 
                          
<?php endif; endif; ?>