<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header();
$sidebar = '';
$center = '';
$a = get_option('blog-layout');
switch ($a) {
    case 'content-sidebar':
        $sidebar = 'right';
        break;
    case 'sidebar-content':
        $sidebar = 'left';
        break;
    case 'content':
        $center = 'col-md-12 col-sm-12';
        break;
    default:
        $sidebar = 'right';
}
?>
    <div class="row content">
        <?php if ($sidebar == 'left') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_html($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>

        <div class="<?php
        if ($center != '') {
            echo esc_attr($center);
        } else {
            echo 'col-md-8';
        }
        ?>">
            <!--Start Content Grid-->
            <div class="content-wrap">
                
                <div class="blog" id="blogmain">
                    <?php
                    /* Queue the first post, that way we know
                     * what date we're dealing with (if that is the case).
                     *
                     * We reset this later so we can run the loop
                     * properly with a call to rewind_posts().
                     */
                    if (have_posts()) :
                        while (have_posts()) :
                            the_post();
                            the_archive_title('<h1 class="page-title">', '</h1>');
                            the_archive_description('<div class="taxonomy-description">', '</div>');


                            /* Run the loop for the archives page to output the posts.
                             * If you want to overload this in a child theme then include a file
                             * called loop-archives.php and that will be used instead.
                             */
                            get_template_part('templates/content/content', 'loop');
                        endwhile;
                        ?>
                    </div>
                    <?php
                    inkthemes_pagination();
                else :
               
                    ?>    <div>
                        <p> <?php get_template_part('templates/content/content', 'none'); ?> </p>
                    </div>
                <?php endif;
                ?>
            </div>
        </div>
        <?php if ($sidebar == 'right') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>

        <div class="clear"></div>
        <!--End Content Grid-->
    </div>
</div>
</div>

<!--End Container Div-->
<?php get_footer(); ?>
