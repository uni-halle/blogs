<?php
/*
 Template Name: No Footer
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

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
                                    
                                        <?php
										// the content (pretty self explanatory huh)
										the_content();

										/*
										 * Link Pages is used in case you have posts that are set to break into
										 * multiple pages. You can remove this if you don't plan on doing that.
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

								<?php 
                                if( is_user_logged_in() ) { 
                                    comments_template();
                                }
                                ?>
                                

							</article>

							<?php endwhile; endif; ?>

						</main>
                    
				</div>

			</div>

<?php get_footer( nofooter ); ?>
