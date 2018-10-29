<?php
/*
  Template Name: Blog Page
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
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
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

            <div class="content-wrap">
                
                <div class="blog" id="blogmain">
                    <?php
                    $limit = get_option('posts_per_page');
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    query_posts('showposts=' . $limit . '&paged=' . $paged);
                    $wp_query->is_archive = true;
                    $wp_query->is_home = false;

                    if (have_posts()) : while (have_posts()) : the_post();

                            get_template_part('templates/content/content', 'loop');

                        endwhile;
                    else:
                        ?>
                        <div>
                            <p> <?php get_template_part('templates/content/content', 'none'); ?> </p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php inkthemes_pagination(); ?>
            </div>
        </div>

        <?php if ($sidebar == 'right') { ?>
            <div class="col-md-4 col-sm-4">
                <div class="sidebar <?php echo esc_attr($sidebar); ?>">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="clear"></div>
    <!--End Content Grid-->
</div>
</div>

<!--End Container Div-->
<?php get_footer(); ?>
