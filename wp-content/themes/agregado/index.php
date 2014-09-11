<?php get_header(); ?>

<div class="clearfloat">
<div id="content">

<?php
	$postcount = 0;
	$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
	query_posts( 'paged=$page&showposts=9' );
	if (have_posts()) { while (have_posts()) { the_post(); 
		if( $postcount == 0 ) { 
		//GETS LATEST POST
?>
			
		<div id="latest" class="entry">
			
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					
			<p class="postmetadata">Posted <em>by</em> <?php the_author(); ?> <em>on</em> <?php the_time('M j, Y') ?> &bull; <span class="commentcount">(<a href="<?php the_permalink(); ?>#commentarea"><?php comments_number('0', '1', '%'); ?></a>)</span></p>
				
			<?php the_excerpt(); ?>

                </div><!--END LATEST-->

<ul id="headlines">		
<?php
		} elseif( $postcount > 0 && $postcount <= 8 ) { 
		//GETS MORE EXCERPTS
?>
		<li id="headline-<?php echo $postcount; ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> <span class="commentcount">(<?php comments_popup_link('0', '1', '%'); ?>)</span></li>
<?php
		}
		$postcount ++;
		// close the loop
		} 
	}
?>
</ul>

<a href="<?php bloginfo('url'); ?>/archives" class="button">&laquo;Older Posts</a>
</div><!--END CONTENT-->




<div id="sidebar">

<h3>Find <em>me</em> Elsewhere</h3>

<?php lifestream(); ?>





<h3>My Profile</h3>
<div class="module">
<?php query_posts('showposts=1'); ?>
<?php while (have_posts()) : the_post(); ?>
<?php echo get_avatar( get_the_author_email(), '56' ); ?>

<p><?php the_author_description(); ?></p>
<?php endwhile; ?>

</div>
</div><!--END SIDEBAR-->

</div><!--END FLOATS-->
</div><!--END TOP-->
</div><!--END WRAPPER-->

<div id="middle">
<div class="wrapper clearfloat">

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-home') ) : ?>

<?php include (TEMPLATEPATH . "/tag-cloud.php"); ?>

<?php include (TEMPLATEPATH . "/recent-comments.php"); ?>


<?php endif; ?>


<?php 
//Contact script created by Tim McDaniels and Darren Hoyt for the Mimbo Pro and Agregado themes
//May be re-used with credits intact

if($_REQUEST['submit']): ?>

<?php
		$admin_email = get_settings( "contact_email" );
		$admin_subject = get_settings( "contact_subject" );
		$admin_success = get_settings( "contact_success" );
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $admin_email . "\r\n";
		$body = "<blockquote>
	Name: " . $_REQUEST['Name'] . "<br/>
	Email: " . $_REQUEST['Email'] . "<br/>
	Message:<br/>" . $_REQUEST['Message'] . "<br/>
	<hr/>
	Remote Address: " . $_SERVER['REMOTE_ADDR'] . "<br/>
	Browser: " . $_SERVER['HTTP_USER_AGENT'] . "
	<hr/>
	</blockquote>";
                mail ($admin_email, $admin_subject, $body, $headers);
?>

<?php endif; ?>
	
	<form onsubmit="return(submitContactForm(this));" id="contactform" action="">
    
		

		<h3>Get <em>in</em> Touch</h3>
		<fieldset>
			<legend>Contact</legend>
			<label for="user-name">Your Name</label>
			<input type="text" id="user-name" name="Name" class="field" title="Enter name here" />
			<label for="user-email">Your Email</label>
			<input type="text" id="user-email" name="Email" class="field" title="Enter email address here" />
			<label for="user-comment">Your Message</label>
			<textarea id="user-comment" name="Message" class="field" cols="" rows="" title="Enter comments here"></textarea>
			<input type="hidden" name="submit" value="1" />
			<input type="submit" value="Submit&raquo;" id="submit" class="button" />
		</fieldset>

	</form>

</div><!--END WRAPPER-->
</div><!--END MIDDLE-->

<?php get_footer(); ?>
