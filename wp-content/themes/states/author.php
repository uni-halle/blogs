<?php get_header(); ?>
		       
        <div id="bd">
				  
			<div id="yui-main">
									
				<div class="yui-b">
										
					<div id="page" class="yui-g">

				        <?php 
						if(isset($_GET['author_name'])) :
						$curauth = get_userdatabylogin($author_name);
						else :
						$curauth = get_userdata(intval($author));
						endif;
						?>
											
				        <?php if (have_posts()) : ?>
						
						<div class="box-container">
				        
							<h1><?php echo $curauth->display_name; ?></h1> 
							
							<div id="about-author">
								
								<p><a href="mailto:<?php the_author_email(); ?>">Email <?php echo $curauth->first_name; ?></a></p>
						
								<p><?php echo $curauth->description; ?></p>					
							
							</div><!-- end #about-author -->						
							
							<?php while (have_posts()) : the_post(); ?>			            
				                        
							<div class="entry archive-post-block">
								
								<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
							
								<?php the_excerpt(); ?>
							
								<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
							
							</div><!-- end .entry -->
							
							<?php endwhile; ?>

							<div class="navigation">
								<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
								<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
							</div>
						
						</div><!-- end .box-container -->		
				
					<?php else : ?>
				
						<h2 class="center">Not Found</h2>
						<p class="center">Sorry, but you are looking for something that isn't here.</p>
						<?php include (TEMPLATEPATH . "/searchform.php"); ?>
				
					<?php endif; ?>
							
					</div><!-- end #page -->
					
				</div><!-- end .yui-b -->
				
			</div><!-- end #yui-main -->
			
			<ul class="yui-b">
			
				<?php get_sidebar(); ?>

			</ul><!-- end .yui-b -->  	

   	 	</div><!-- end #bd -->        
        
<?php get_footer(); ?>