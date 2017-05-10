<?php get_header(); ?>

<?php
$user = wp_get_current_user();
$filterQuery[] = array (
		'key'		=> 'entleiher',
		'value'		=> $user->user_login,
		'compare'	=> '=',
	);
$args = array (
	'posts_per_page' => -1,
	'meta_query' => $filterQuery
	);
$posts = query_posts($args);
$countPosts = count($posts);
?>

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Von mir ausgeliehen
      <small><?php echo $countPosts; ?> Artikel</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
    	<div class="col-sm-12">

		<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			$postList[] = $post->ID;
    	$fields = get_fields();
		?>

		<?php
		include('box-artikel.php');
		?>

		<?php endwhile; ?>
		<?php else : ?>
		<?php endif; ?>
		<?php wp_reset_query(); ?>

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
		<p class="text-muted">Du kannst einen von dir ausgeliehenen Artikel nicht selbst zurückgeben. Ein anderer Benutzer muss deinen Artikel zurückzunehmen.</p>
  </section>
  <!-- /.content -->

<?php get_footer(); ?>

<script>
	$(function () {
		/* Navigationsmenü */
    	$("ul.sidebar-menu li#meine").addClass("active");
	});
</script>
