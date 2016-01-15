<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="maincontainer">

						<main id="main" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title" itemprop="headline">
                                    <?php the_title(); ?>
                                    </h1>  

								</header> <?php // end article header ?>

								<section class="entry-content cf" itemprop="articleBody">
                                    <div class="page-content">
                                    
                                    <!-- If the page has the category 'uebersicht' and therefore is the first of a new topic: display the child pages of this topic -->
                                <?php 
if ( in_category( 'uebersicht' )) {
    echo '<h2>Inhaltsübersicht</h2>';
    echo '<ol class="themenuebersicht">';
    echo wp_list_pages('title_li=&child_of='.$post->ID.'&depth=1&sort_column=menu_order') . '</ol>';
} ;

?>
                                  
  
									<?php
										// the content (pretty self explanatory huh)
										the_content();

										/*
										 * Link Pages is used in case you have posts that are set to break into
										 * multiple pages. You can remove this if you don't plan on doing that.
										 *
										 * Also, breaking content up into multiple pages is a horrible experience,
										 * so don't do it. While there are SOME edge cases where this is useful, it's
										 * mostly used for people to get more ad views. It's up to you but if you want
										 * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
										 *
										 * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
										 *
										*/
										wp_link_pages( array(
											'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
											'after'       => '</div>',
											'link_before' => '<span>',
											'link_after'  => '</span>',
										) );
									?>
                                    
                                    
                                  </div>  
                                    
                                <?php
                                    if (!is_front_page() ) {
                                    get_sidebar( gkuh ); 
                                    }
                                ?>
                                    
								</section> 

								<footer class="article-footer cf">

								</footer>

								<?php comments_template(); ?>
                                

							</article>

							<?php endwhile; endif; ?>
                            
                            

						</main>
                    
                    

				</div>

			</div>


<?php get_footer(); ?>