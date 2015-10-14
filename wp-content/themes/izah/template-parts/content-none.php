<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
?>

<section class="no-results not-found">
	<header class="entry-header">
		<h1 class="entry-title"><?php _e( 'Keine Suchergebnisse', 'cleantraditional' ); ?></h1>
	</header><!-- .page-header -->

	<div class="entry-content">

		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Bereit um deinen ersten Beitrag zu erstellen ? <a href="%1$s">starte hier</a>.', 'cleantraditional' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Verzeihen sie, zu ihrer Suche konnten keine Ergebnisse gefunden werden. Bitte versuchen sie eine andere Eingabe.', 'cleantraditional' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php _e( 'Zu ihrer Eingabe konnten wir keinen Inhalt finden. Vielleicht kann ihnen die Suchfunktion helfen.', 'cleantraditional' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>

	</div><!-- .page-content -->
</section><!-- .no-results -->
