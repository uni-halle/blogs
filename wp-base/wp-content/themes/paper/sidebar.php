<div id="sidebar">
<div id="notecard">
<div class="description"><?php bloginfo('description'); ?></div>


<!-- You can only fit one of these options in the sidebar, flickr, etsy or an image. -->


<!-- Image 
place an image here and remove the comment tags, image should be no larger than 
220px x 180px - make sure and leave class="sidebar" in the img tag-->

<!-- Image  (erase this line to display) 
<a href="flickr.com/ahawkins"></a><img class="sidebar" src="http://farm4.static.flickr.com/3017/3027921535_151cf3a49f_m.jpg" width="220" alt="Lil' Bird" />
(erase this line to display)   END Image -->

<!-- Flickr Badge 
To get your flickr badge go to http://www.flickr.com/badge.gne and follow these steps: 
1. Select HTML Badge
2. Pick the photos you'd like to display
3. Layout
		a. buddy icon? NO
		b. how many items? 1
		c. which ones? you choose!
		d. what size? Thumbnail
		e. which orientation? horizontal
4. Skip the colors, these won't matter

5. Preview & Code - You'll only need the following code from the code box:
<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=1&display=random&size=t&layout=h&source=user&user=67452956%40N00"></script>

Paste the code over the <script></script> code below. Change count=1 to count=2, so it will display 2 thumbnails and fit nicely in the sidebar. 

6. Last step is to remove the comment code that's around the flickr badge and replace the ahawkins in the link with your flickr account name..-->


<!--FLICKR BADGE (erase this line to display) 

<ul id="f_nav" class="clearfloat">
<table cellpadding="0" cellspacing="1" border="0"><tr>

<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=2&display=random&size=t&layout=h&source=user&user=67452956%40N00"></script>

</tr></table></td></tr>
<a class="badge_text" href="http://www.flickr.com/ahawkins" id="flickr_www">view art on www.<strong style="color:#3993ff">flick<span style="color:#ff1c92">r</span></strong>.com/<span style="color:#3993ff">ahawkins</span></a>
</ul>	
(erase this line to display) End of Flickr Badge -->
		
		
		
		
		
		
		
		
<!-- Etsy Badge 
To get your etsy badge, login and go here: http://www.etsy.com/mini_generator.php
1. Items to show, whichever you choose
2. Image size:  Thumbnail 
3. Layout: 2 columns, 1 row
4. Copy the javascript version and paste over <script></script> below. Make sure and leave the span tags!  -->		


<!--ETSY BADGE (erase this line to display) 
<span class="etsy">		
<script type='text/javascript' src='http://www.etsy.com/etsy_mini.js'></script><script type='text/javascript'>new EtsyNameSpace.Mini(57530, 'shop','thumbnail',1,2).renderIframe();</script>
</span>
(erase this line to display) End of Flickr Badge -->
		
		
		
		
		
		
		
		
		
		
		
</div>
<ul id="sidelist">		
			
				
			
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
	

			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h2>Author</h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

			<?php if ( is_404() || is_category() || is_day() || is_month() ||
						is_year() || is_search() || is_paged() ) {
			?> <li>

			<?php /* If this is a 404 page */ if (is_404()) { ?>
			<?php /* If this is a category archive */ } elseif (is_category()) { ?>
			<p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p>

			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for the day <?php the_time('l, F jS, Y'); ?>.</p>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for <?php the_time('F, Y'); ?>.</p>

			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for the year <?php the_time('Y'); ?>.</p>

			<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<p>You have searched the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for <strong>'<?php the_search_query(); ?>'</strong>. If you are unable to find anything in these search results, you can try one of these links.</p>

			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<p>You are currently browsing the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives.</p>

			<?php } ?>

			</li> <?php }?>

			<?php wp_list_pages('title_li=<h2>Pages</h2>' ); ?>

			<li><h2>Archives</h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<?php wp_list_categories('title_li=<h2>Categories</h2>'); ?>

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
				<?php wp_list_bookmarks(); ?>

				<li><h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
				</li>

			
			<?php } ?>

			<?php endif; ?>
		</ul>
	</div>

