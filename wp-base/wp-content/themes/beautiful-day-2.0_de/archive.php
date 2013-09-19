<?php get_header(); ?>

<div class="main">
  <div class="content">
    <?php if (have_posts()) : ?>
    <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
    <?php /* If this is a category archive */ if (is_category()) { ?>
    <h2>Archiv der Kategorie &#8216;<?php echo single_cat_title(); ?>&#8217;</h2>
    <?php /* If this is a tag archive */ } elseif(is_tag() ) { ?>
    <h2 class="pagetitle">Archiv des Tags &#8216;
      <?php single_tag_title(); ?>
      &#8217;</h2>
    <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
    <h2>Tagesarchiv f&uuml;r den
      <?php the_time('j. F Y'); ?>
    </h2>
    <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
    <h2>Monatsarchiv f&uuml;r
      <?php the_time('F Y'); ?>
    </h2>
    <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
    <h2>Jahresarchiv f&uuml;r
      <?php the_time('Y'); ?>
    </h2>
    <?php /* If this is an author archive */ } elseif (is_author()) { ?>
    <h2>Autoren Archiv</h2>
    <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
      <h2>Blog Archiv</h2>
      <?php } ?>
    <div class="left">
      <?php next_posts_link('&laquo; Vorherige Eintr&auml;ge') ?>
    </div>
    <div class="right">
      <?php previous_posts_link('N&auml;chste Eintr&auml;ge &raquo;') ?>
    </div>
    <div class="clearer"></div>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post">
      <h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
        <?php the_title(); ?>
        </a></h1>
      <div class="descr">
        <?php the_tags('Tags: ', ', ', '<br />'); ?>
        <?php the_time('j. F Y') ?>
        in
        <?php the_category(', ') ?>
        |
        <?php edit_post_link('Bearbeiten', '', ' | '); ?>
        <?php comments_popup_link('0 Kommentar &#187;', '1 Kommentar &#187;', '% Kommentare &#187;'); ?>
      </div>
    </div>
    <?php endwhile; ?>
    <div class="left">
      <?php next_posts_link('&laquo; Vorherige Eintr&auml;ge') ?>
    </div>
    <div class="right">
      <?php previous_posts_link('N&auml;chste Eintr&auml;ge &raquo;') ?>
    </div>
    <div class="clearer"></div>
    <?php else : ?>
    <h2 class="center">Nichts gefunden.</h2>
    <?php include (TEMPLATEPATH . '/searchform.php'); ?>
    <?php endif; ?>
  </div>
  <?php get_sidebar(); ?>
  <div class="clearer"></div>
</div>
<?php get_footer(); ?>
