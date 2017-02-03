<?php get_header(); ?>

<?php
$args = array (
	'posts_per_page' => -1,
);
global $query_string;
if (!empty($query_string)){
  $args['s'] = get_query_var('s');
}
$posts = get_posts($args);
$countPosts = count($posts);
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Suchergebnis
        <small><?php echo $countPosts; ?> Projekte</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php bloginfo('url') ?>">Projektdatenbank</a></li>
        <li class="active">Suchergebnis</li>
      </ol>
    </section>

		<!-- Main content -->
		<section class="content">

			<div class="row">
				<div class="col-sm-12">

			<?php
			foreach ($posts as $post):
				setup_postdata ($post);
				$postList[] = $post->ID;
				$fields = get_fields();
			?>

			<?php
			include('box-projekt.php');
			?>

					<?php
						endforeach;
						wp_reset_postdata();
					?>

					<!-- Button -->
					<div>
						<a href="<?php echo '/export?list=' . implode(',', $postList); ?>" target="export" class="btn btn-default<?php if($countPosts == 0) { echo ' disabled'; } ?>" role="button">Projekte exportieren</a>
						<a href="<?php echo '/mail?list=' . implode(',', $postList); ?>" target="export" class="btn btn-default<?php if($countPosts == 0) { echo ' disabled'; } ?>" role="button" style="margin-left: 10px;">Kontakte exportieren</a>
					</div>

				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->

    <?php get_footer(); ?>
