<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
get_header(); 
get_sidebar(); ?>

<div id="main">	
    <div id="header">
		<aside id='topbar' style='display: none;'>
			<?php
				get_sidebar( 'top' );
			?>
		</aside>
        <div id="header_images">
			<div id="cimy_div_id_0">Loading images...</div>
			<div class="cimy_div_id_0_caption"></div>
			<style type="text/css">
				#cimy_div_id_0 {
					float: left;
					width: 799px;
					height: 238px;
				}
				div.cimy_div_id_0_caption {
					position: absolute;
					margin-top: 175px;
					margin-left: -75px;
					width: 150px;
					text-align: center;
					left: 50%;
					padding: 5px 10px;
					background: black;
					color: white;
					font-family: sans-serif;
					border-radius: 10px;
					display: none;
					z-index: 2;
				}
			</style>
			<noscript> 
				<div id="cimy_div_id_0_nojs">
					<img id="cimy_img_id" src="http://ausdauerprofi.com/wordpress/wp-content/Cimy_Header_Images/0/bild01.jpg" alt="" />
				</div>
			</noscript>
		</div>
    </div>
    <div id="content">

        <?php if ( have_posts() ) : ?>

                <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'loop', 'category' ); ?>
                <?php endwhile; ?>

        <?php else : ?>
                <article id="post-0" class="post no-results not-found">
                        <header class="entry-header">
                                <h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
                        </header><!-- .entry-header -->

                        <div class="entry-content">
                                <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
                                <?php get_search_form(); ?>
                        </div><!-- .entry-content -->
                </article><!-- #post-0 -->
        <?php endif; ?>

    </div><!-- #content -->
</div><!-- #main -->
		
<?php get_footer(); ?>

<style>
	#global-footer:hover	{
		background: rgba(255,255,255,0.0);
		color: #000;
	}

	#global-footer:hover a {
		color: #000;
		text-decoration: underline;
		text-shadow: #fff 0 0 0;
	}

	#global-footer a:hover {
		color: #000;
		text-decoration: none;
		background-color: #fff;
		text-shadow: #fff 0 0 0;
	}

	#global-footer p	{ 
		margin-bottom: 10px;
		margin-top: 0;
		padding: 0;
		line-height: 12px;
	}

	#global-footer p:last-child	{ 
		margin-bottom: 0;
	}
</style>