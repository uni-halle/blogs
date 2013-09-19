<?php get_header(); ?>
	       
    <div id="bd">

		<div id="yui-main">
			
			<div class="yui-b">
				
				<div id="content">
					
					<div class="post">
							
						<h1>Oops!</h1>
							
						<div class="entry">
			            
			            	<p>Looks like the page you're looking for isn't here anymore.</p>

							<p><strong>Try searching for what you're looking for...</strong></p>
							
							<div id="search-form">  
							
								<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
								
									<input type="text" name="s" id="s" class="search_input" value="<?php the_search_query(); ?>" />							
									<input type="submit" id="searchsubmit" class="submit_input" value="Search" />
									
								</form>
							
							</div><!-- end #search_form -->
						
						</div><!-- end .entry -->
											
					</div><!-- end .box-container -->
						
				</div><!-- end #page -->
					
			</div><!-- end .yui-b -->
								
		</div><!-- end #yui-main -->
		
		<?php get_sidebar(); ?>

	 	</div><!-- end #bd -->        
        
<?php get_footer(); ?>    