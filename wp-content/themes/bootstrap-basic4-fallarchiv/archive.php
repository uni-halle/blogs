<?php
/**
 * The archive template.
 * 
 * Use for display author archive, category, custom post archive, custom taxonomy archive, tag, date archive.<br>
 * These archive can override by each archive file name such as category will be override by category.php.<br>
 * To learn more, please read on this link. https://developer.wordpress.org/themes/basics/template-hierarchy/
 * 
 * @package bootstrap-basic4
 */
global $wp_query;
$max_num_pages = $wp_query->max_num_pages;
$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
// begins template. -------------------------------------------------------------------------
get_header();
get_sidebar();
?> 
<main id="main" class="<?php echo $Bsb4Design->mainContainerClasses();?> ml-md-0 mr-md-auto" role="main">
  <?php if (have_posts()) { ?> 
    <header class="page-header">
      <?php
      the_archive_title('<h1 class="page-title">', '</h1>');
      the_archive_description('<div class="taxonomy-description">', '</div>');
      ?>
      <?php
      if ($max_num_pages > 1) {
        $Bsb4Design->pagination();
      }
      $Bsb4Design->numberOfPosts();
      ?>
    </header><!-- .page-header -->

    <?php
    // Start the Loop
    while (have_posts()) {
      the_post();
      get_template_part('template-parts/content', get_post_type());
    } //endwhile; 

    $Bsb4Design->pagination();
  } else {
    get_template_part('template-parts/section', 'no-results');
  } //endif; 
  ?> 
</main>
<?php
unset($Bsb4Design);
get_sidebar('right');
get_footer();
