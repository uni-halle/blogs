<?php get_header(); ?>

		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="catheader">Archiv zu: <?php single_cat_title(); ?></h1>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1 class="catheader">Tags: <?php single_tag_title(); ?></h1>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="catheader">Tagesarchiv vom <?php the_time('d. F Y'); ?></h1>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="catheader">Monatsarchiv vom <?php the_time('F Y'); ?></h1>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="catheader">Jahresarchiv aus <?php the_time('Y'); ?></h1>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="catheader">Archiv vom Autor</h1>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="catheader">Blogarchiv</h1>
 	  <?php } ?>

		<?php while (have_posts()) : the_post(); ?>
		<div class="posts">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanenter Link zu <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time('d. F Y') ?></small>

				<div class="entry">
					<?php theme_excerpt('60') ?>
				</div>

				<p class="meta"><?php the_tags('Tags: ', ', ', '<br />'); ?> Eingetragen unter <?php the_category(', ') ?> | <?php edit_post_link('[Bearbeiten]', '', ' | '); ?>  <?php comments_popup_link('Keine Antworten &#187;', 'Eine Antwort &#187;', '% Antworten &#187;'); ?></p>

			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; &Auml;ltere Eintr&auml;ge') ?></div>
			<div class="alignright"><?php previous_posts_link('Neuere Eintr&auml;ge &raquo;') ?></div>
		</div>
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Entschuldige, aber unter %s gibt es bis jetzt noch keine Beitr&auml;ge...</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Entschuldige, aber unter diesem Datum gibt es keine Eintr&auml;ge...</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Entschuldige, aber %s hat bis jetzt noch keinen Eintrag geschrieben...</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>Keine Eintr&auml;ge gefunden...</h2>");
		}

	endif;
?>

<?php get_footer(); ?>
