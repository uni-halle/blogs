<?php
/**
 * The search template.
 * 
 * @package bootstrap-basic4
 */
global $searchandfilter, $wp_query;
$id_suchformular = 2103;
$page_title = '';
$sfid = \BootstrapBasic4\Bsb4SearchAndFilter::get_sfid();
$post_count_all = \BootstrapBasic4\Bsb4Utilities::faelle_count(); // wp_count_posts();
$max_num_pages = $wp_query->max_num_pages;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
//var_dump($post_count_all);
if ($sfid) {
  $sf_form = $searchandfilter->get($sfid);
  $current_query = $sf_form->current_query();
  $page_title = get_the_title($sfid);
}
// begins template. -------------------------------------------------------------------------
get_header();
get_sidebar();
?> 
<main id="main" class="col-md-6 site-main" role="main">
  <?php
  if (have_posts()) {
    ?> 
    <header class="page-header">
      <?php if ($sfid == $id_suchformular) { ?>
        <h1 class="page-title">Suchergebnisse für '<?php echo $current_query->get_search_term(); ?>'</h1>
      <?php } else { ?>
        <h1 class="page-title"><?php echo $page_title; ?></h1>
      <?php } ?>
      <?php
      if ($max_num_pages > 1) {
        $Bsb4Design->pagination();
      }
      $Bsb4Design->numberOfPosts();
      ?>
      <?php
      if (\BootstrapBasic4\Bsb4SearchAndFilter::is_filtered()) {
        \BootstrapBasic4\Bsb4SearchAndFilter::render_active_filters();
      }
      ?>

    </header><!-- .page-header -->
    <?php
    while (have_posts()) {
      the_post();
      get_template_part('template-parts/content', get_post_format());
    }// endwhile;

    $Bsb4Design->pagination();
    unset($Bsb4Design);
  } else {
    get_template_part('template-parts/section', 'no-results');
  }// endif;
  ?> 
</main>
<?php
get_sidebar('right');
get_footer();
