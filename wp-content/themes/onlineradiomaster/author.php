<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

	<div id="content" class="narrowcolumn" role="main">

		<?php if (have_posts()) : ?>
 	  
		<?php $thisauthor = get_userdata(get_query_var('author')); ?>
		
		<h1 class="pagetitle"><?php echo $thisauthor->display_name; ?></h1>
					
		<div id="authorinfo">
		
			<div id="avatar">
					<?php if(function_exists('get_avatar')) {
						echo get_avatar($thisauthor->user_email, 70, "" );
					} ?>
			</div>
			
			<?php if ($thisauthor->description) { ?>
				<div id="author-description"><?php echo $thisauthor->description; ?></div>
			<?php } ?>
			
			<?php if ($thisauthor->user_url) { ?>
				<div><span>Website:</span> <a class="url" href="<?php echo $thisauthor->user_url; ?>" title="Website von <?php echo $thisauthor->display_name; ?>"><?php echo $thisauthor->user_url; ?></a></div>
			<?php } ?>
			
			<?php if ($thisauthor->twitter) { ?>
				<div><span>Twitter:</span> <a class="url" href="https://twitter.com/<?php echo $thisauthor->twitter; ?>" title="<?php echo $thisauthor->display_name; ?> auf Twitter"><?php echo $thisauthor->twitter; ?></a></div>
			<?php } ?>
			
			<?php if ($thisauthor->facebook) { ?>
				<div><span>Facebook:</span> <a class="url" href="<?php echo $thisauthor->facebook; ?>" title="<?php echo $thisauthor->display_name; ?> auf Facebook"><?php echo $thisauthor->display_name; ?>'s Profil</a></div>
			<?php } ?>
			
			<?php if ($thisauthor->studivz) { ?>
				<div><span>StudiVZ:</span> <a class="url" href="<?php echo $thisauthor->studivz; ?>" title="<?php echo $thisauthor->display_name; ?> auf StudiVZ"><?php echo $thisauthor->display_name; ?>'s Profil</a></div>
			<?php } ?>
			
			<?php if ($thisauthor->studip) { ?>
				<div><span>Stud.IP:</span> <a class="url" href="http://studip.uni-halle.de/about.php?username=<?php echo $thisauthor->studip; ?>" title="<?php echo $thisauthor->display_name; ?> auf Stud.IP"><?php echo $thisauthor->display_name; ?>s Homepage</a></div>
				<?php } ?>
			
			<?php if ($thisauthor->jabber) { ?>
				<div><span>Jabber/GTalk:</span> <a href="xmpp:<?php echo $thisauthor->jabber; ?>"><?php echo $thisauthor->jabber; ?></a></div>
			<?php } ?>
			
			<?php if ($thisauthor->aim) { ?>
				<div><span>AIM:</span> <a href="aim:addbuddy?screenname=<?php echo $thisauthor->aim; ?>"><?php echo $thisauthor->aim; ?></a></div>
			<?php } ?>
			
			<?php if ($thisauthor->yim) { ?>
				<div><span>Yahoo IM:</span> <a href="ymsgr:addfriend?<?php echo $thisauthor->yim; ?>"><?php echo $thisauthor->yim; ?></a></div>
			<?php } ?>
			
			<?php if ($thisauthor->icq) { ?>
				<div><span>ICQ:</span> <a href="<?php echo $thisauthor->icq; ?>"><?php echo $thisauthor->icq; ?></a></div>
			<?php }
			
			if ( is_user_logged_in() ) { ?>
				<a id="profile-edit-link" href="http://blog.onlineradiomaster.de/wp-admin/profile.php">F&uuml;lle dein eigenes Profil aus</a>
			<?php } ?>
		</div>
		
		
		<h2 class="pagetitle">Alle Texte von <?php echo $thisauthor->display_name; ?></h2>

		<?php while (have_posts()) : the_post(); ?>
		
		<div <?php post_class(); ?>>
			<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h3>
			<small><?php the_time(__('l, F jS, Y', 'kubrick')) ?></small>
		</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'kubrick')); ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'kubrick')); ?></div>
		</div>
		
	<?php else :

		$userdata = get_userdatabylogin(get_query_var('author_name'));
		printf("<h2 class='center'>".__("Sorry, but there aren't any posts by %s yet.", 'kubrick')."</h2>", $userdata->display_name);
		
	  get_search_form();
	  
	endif;
?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
