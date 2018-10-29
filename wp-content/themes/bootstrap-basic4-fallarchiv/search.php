<?php
/**
 * The search template.
 * 
 * @package bootstrap-basic4
 */
$max_num_pages = $wp_query->max_num_pages;
$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
// begins template. -------------------------------------------------------------------------
get_header();
get_sidebar();
?> 
<main id="main" class="<?php echo $Bsb4Design->mainContainerClasses(); ?> mr-md-auto" role="main">
  <?php
  if (have_posts()) {
    ?> 
    <header class="page-header">
      <h1 class="page-title"><?php printf(__('Search Results for: %s', 'bootstrap-basic4'), '<span>' . get_search_query() . '</span>'); ?></h1>
      <?php
      if ($max_num_pages > 1) {
        $Bsb4Design->pagination();
      }
      $Bsb4Design->numberOfPosts();
      ?>
    </header><!-- .page-header -->
    <!-- .page-content -->
    <?php
    while (have_posts()) {
      the_post();
      get_template_part('template-parts/content', get_post_format());
    }// endwhile;

    $Bsb4Design->pagination();
  } else {
    get_template_part('template-parts/section', 'no-results');
  }// endif;
  ?> 
</main>
<?php
unset($Bsb4Design);
get_sidebar('right');
get_footer();
