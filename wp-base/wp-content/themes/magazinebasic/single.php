<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h1><?php the_title(); ?></h1>
			<div class="meta">
				Von <?php the_author() ?>
			</div>
			<div class="entry">
				<?php the_content(); ?>

				<?php the_tags( '<p class="tags">Tags: ', ', ', '</p>'); ?>

				<p class="postmetadata alt">
					<small>
						Dieser Artikel wurde am 
						<?php the_time('d F Y') ?> um <?php the_time() ?> verfasst
						und unter <?php the_category(', ') ?> gespeichert.
						Du kannst diesen Beitrag per <?php post_comments_feed_link('RSS Feed'); ?> verfolgen.

						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							Du kannst <a href="#respond">ein Kommentar hinterlassen</a> oder einen <a href="<?php trackback_url(); ?>" rel="trackback">Trackback</a> von deiner Seite hinzuf&uuml;gen.

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							Kommentare sind zur Zeit nicht zugelassen, aber du kannst einen <a href="<?php trackback_url(); ?> " rel="trackback">Trackback</a> von deiner Seite hinzuf&uuml;gen.

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							Du kannst zum Ende springen und ein Kommentar hinterlassen. Pings und Trackbacks sind nicht zugelassen.

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							Weder Kommentare noch Pings bzw. Trackbacks erlaubt.

						<?php }; ?>

					</small>
				</p>

			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Entschuldige, aber zu den Kriterien gab es keine &Uuml;bereinstimmung.</p>

<?php endif; ?>

<?php get_footer(); ?>
