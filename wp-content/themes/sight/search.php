<?php get_header(); ?>

<?php if ( have_posts() ) : ?>

    <div class="content-title">
        Search Result <span>/</span> <?php the_search_query(); ?>
        <a href="javascript: void(0);" id="mode"<?php if ($_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a>
    </div>

    <?php get_template_part('loop'); ?>

<?php else : ?>

    <div class="content-title">
        Your search <strong><?php the_search_query(); ?></strong> did not match any documents
    </div>

    <div class="entry">
        <div class="single clear">
            <div class="post-content">
                <div class="search">
                    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
                        <fieldset>
                            <input name="s" type="text" onfocus="if(this.value=='Search with some different keywords') this.value='';" onblur="if(this.value=='') this.value='Search with some different keywords';" value="Search with some different keywords" />
                            <button type="submit"></button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php get_template_part('pagination'); ?>

<?php get_footer(); ?>