							<?php
								global $options;

								foreach ($options as $value) {
									if (get_settings( $value['id'] ) === FALSE) { 
										$$value['id'] = stripslashes( $value['std'] ); 
									} else { 
										$$value['id'] = stripslashes( get_settings( $value['id'] ) ); 
									} 
								}
							?>
							<div id="logo">
								<h1><?php bloginfo('name'); ?></h1>
							</div>
							<ul id="right-content">
							<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
								<?php if($about_message != "") : ?>
									<li><h5>About</h5>
										<?php echo $about_message; ?>
									</li>
								<?php endif; ?>
								
								<?php if($side_ad != "") : ?>
									<li class="ads">
										<?php echo $side_ad; ?>
									</li>
								<?php endif; ?>
								
								<?php if($show_flickr == "Yes") : ?>
								<li id="flickr" class="clearfix"><h5><?php echo $flickr_title; ?></h5>
									<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $flickr_count; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $flickr_id; ?>"></script>
								</li>
								<?php endif; ?>

								<li><h5>Categories</h5>
									<ul>
										<?php wp_list_categories('title_li='); ?>
									</ul>
								</li>								
								
								<?php if(get_bloginfo('version') == "2.6.2") : ?>
								<li>
									<ul class="tabs clearfix">
										<li><a href="#" title="content_1" class="tab active">Popular</a></li>
										<li><a href="#" title="content_2" class="tab">Comments</a></li>
										<li><a href="#" title="content_3" class="tab">Featured</a></li>
									</ul>
									<div id="tabs">
										<div id="content_1" class="content">
											<ul id="popular">
												<?php get_popular($popular_limit); ?>
											</ul>
										</div>
										<div id="content_2" class="content">
											<ul id="comments">
												<?php get_comments($comments_limit); ?>
											</ul>
										</div>
										<div id="content_3" class="content">
											<ul id="featureded">
												<?php get_featured($featured_cat); ?>
											</ul>
										</div>
									</div>
								</li>
								<?php endif; ?>
								<li><h5>Recent Articles</h5>
									<ul>
										<?php get_recent($recent_limit); ?>
									</ul>
								</li>
								<?php endif; ?>
				

								<li><h5>Archives</h5>
									<ul>
										<?php wp_get_archives('type=monthly'); ?>
									</ul>
								</li>
								
								<li><h5>Search</h5>
									
									<ul>
				
<div class="box">

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="text" size="40" /><br />
	<br />
<input type="submit" id="searchsubmit" value="Search" class="btn submit btn-green" />
</div>

</form>

					
						</div>
										
    
									</ul>
								
							</ul>
<br /><a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/rss.gif" hspace="22" border="0" /></a>
<br /><br /><br />