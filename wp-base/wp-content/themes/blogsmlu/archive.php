<?php get_header(); ?>

	<div id="content">

		<?php if (have_posts()) : ?>

		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>				
			<h4 class="pagetitle"><?php echo single_cat_title(); ?></h4>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
			<h4 class="pagetitle"><?php _e('All posts tagged','blogsmlu'); ?> &#8216;<?php echo single_tag_title(); ?>&#8216;</h4>
		
 	  	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h4 class="pagetitle"><?php _e('Day Archive for ','blogsmlu'); ?> <?php the_time('j. F Y'); ?></h4>
		
	 	<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h4 class="pagetitle"><?php _e('Month Archive for ','blogsmlu'); ?> <?php the_time('F Y'); ?></h4>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h4 class="pagetitle"><?php _e('Year Archive for ','blogsmlu'); ?> <?php the_time('Y'); ?></h4>
				
	  	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			
			<!-- Get and display some user metadata the complicated way cause we are not within the loop -->
			<?php $thisauthor = get_userdata(intval($author)); ?>
			
			<h4 class="pagetitle"><?php echo $thisauthor->display_name; ?></h4>
			
			<ul id="authorinfo" class="grey-box">
				<li class="avatar">
					<?php if(function_exists('get_avatar')) {
						echo get_avatar($thisauthor->user_email, 130, "" );
					} ?>
				</li>
				
				<?php if ($thisauthor->unirole1) { ?>
						<li class="unirole1"><?php echo $thisauthor->unirole1; ?></li>
					<?php } ?>
					
					<?php if ($thisauthor->unirole2) { ?>
						<li class="unirole2"><?php echo $thisauthor->unirole2 ?></li>
					<?php } ?>
					
					<?php if ($thisauthor->description) { ?>
						<li class="description"><?php echo $thisauthor->description; ?></li>
					<?php } ?>
					
					<?php if ($thisauthor->user_url) { ?>
						<li><span>Website:</span> <a class="url" href="<?php echo $thisauthor->user_url; ?>" title="Website von <?php echo $thisauthor->display_name; ?>"><?php echo $thisauthor->user_url; ?></a></li>
					<?php } ?>
					
					<?php if ($thisauthor->twitter) { ?>
						<li><span>Twitter:</span> <a class="url" href="https://twitter.com/<?php echo $thisauthor->twitter; ?>" title="<?php echo $thisauthor->display_name; ?> auf Twitter"><?php echo $thisauthor->twitter; ?></a></li>
					<?php } ?>
					
					<?php if ($thisauthor->facebook) { ?>
						<li><span>Facebook:</span> <a class="url" href="<?php echo $thisauthor->facebook; ?>" title="<?php echo $thisauthor->display_name; ?> auf Facebook"><?php echo $thisauthor->display_name; ?>'s Profil</a></li>
					<?php } ?>
					
					<?php if ($thisauthor->studivz) { ?>
						<li><span>StudiVZ:</span> <a class="url" href="<?php echo $thisauthor->studivz; ?>" title="<?php echo $thisauthor->display_name; ?> auf StudiVZ"><?php echo $thisauthor->display_name; ?>'s Profil</a></li>
					<?php } ?>
					
					<?php if ($thisauthor->studip) { ?>
						<li><span>Stud.IP:</span> <a class="url" href="http://studip.uni-halle.de/about.php?username=<?php echo $thisauthor->studip; ?>" title="<?php echo $thisauthor->display_name; ?> auf Stud.IP"><?php echo $thisauthor->display_name; ?>'s Homepage</a></li>
					<?php } ?>
					
					<?php if ($thisauthor->xing) { ?>
						<li><span>XING:</span> <a class="url" rel="author" href="<?php echo $thisauthor->xing; ?>"><?php echo $thisauthor->xing; ?>'s Profil</a></li>
					<?php } ?>
					
					<?php if ($thisauthor->linkedin) { ?>
						<li><span>LinkedIn:</span> <a class="url" rel="author" href="<?php echo $thisauthor->linkedin; ?>"><?php echo $thisauthor->linkedin; ?>'s Profil</a></li>
					<?php } ?>
					
					<?php if ($thisauthor->flickr)  { ?>
						<li><span>Flickr:</span> <a class="url" rel="author" href="<?php echo $thisauthor->flickr; ?>"><?php echo $thisauthor->display_name; ?>'s Photos</a></li>
					<?php } ?>
					
					<?php if ($thisauthor->jabber) { ?>
						<li><span>Jabber/GTalk:</span> <a href="xmpp:<?php echo $thisauthor->jabber; ?>"><?php echo $thisauthor->jabber; ?></a></li>
					<?php } ?>
					
					<?php if ($thisauthor->aim) { ?>
						<li><span>AIM:</span> <a href="aim:<?php echo $thisauthor->aim; ?>"><?php echo $thisauthor->aim; ?></a></li>
					<?php } ?>
					
					<?php if ($thisauthor->yim) { ?>
						<li><span>Yahoo IM:</span> <a href="ymsgr:<?php echo $thisauthor->yim; ?>"><?php echo $thisauthor->yim; ?></a></li>
					<?php } ?>
					
					<?php if ($thisauthor->icq) { ?>
						<li><span>ICQ:</span> <a href="<?php echo $thisauthor->icq; ?>"><?php echo $thisauthor->icq; ?></a></li>
					<?php } ?>
				</ul>
			
			<h4 class="pagetitle"><?php _e('All posts by ','blogsmlu'); ?><?php echo $thisauthor->display_name; ?></h4>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>

		<?php } ?>		

		<?php while (have_posts()) : the_post(); ?>

		<div <?php post_class() ?>>
		
			<p class="date"><?php the_time('j.'); ?>
			<span><?php the_time('M'); ?></span>
			<?php the_time('Y'); ?></p>
			<h2 id="post-<?php the_ID(); ?>" class="posttitle"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<p class="author"><?php _e('Written by ','blogsmlu'); ?> <?php the_author_posts_link(); ?></p>
			
			<div class="entry">
				
				<?php if( is_category() ){ ?>
					<?php the_content(__('[ Read On ... ]', 'blogsmlu')); ?>
				<?php } else {
					the_excerpt(); 
				} ?>
		
			</div>
	
			<p class="postmetadata"><?php the_category(', ') ?> | <a href="<?php comments_link(); ?>" title=""><?php comments_number(__( 'No Comments', 'blogsmlu'), __('1 Comment', 'blogsmlu'), __('% Comments', 'blogsmlu'));?></a> <?php edit_post_link(__('Edit ', 'blogsmlu'), ' | ', ''); ?></p>
	
	</div>
	
	<?php endwhile; ?>
	
		<div class="pagenavigation2">
			<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries','blogsmlu')); ?></div>
			<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;','blogsmlu')); ?></div>
		</div>
	
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>No posts found.</h2>");
		}
		include ('searchform.php');

	endif; ?>

</div>

<?php get_sidebar('Sidebar'); ?>

<?php get_footer(); ?>
