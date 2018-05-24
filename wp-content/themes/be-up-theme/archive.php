<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>
				<div class="be-gradient-2-invers spacer-v-4">
					<div class="grid-container">
						<div class="grid-x grid-margin-x">
						</div>
					</div>
				</div>

	<main class="main-content-full-width">
		<div class="be-gradient-2 spacer-v-4">
			<div class="grid-container">
				<div class="grid-x grid-margin-x align-left passwordlogin">
					<div class="small-12 cell">
						<h1>Interner Bereich.</h1>
					</div>
					<?php
					if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) ) {
						//echo '<div class="be-link" id="' . esc_attr($a['ort']) . '" data-open="reveal_logout">Logout</div>';
						//echo 'Der interne Bereich steht den Hebammen, Ärztinnen und Ärzten der teilnehmenden Kliniken zur Verfügung. Wenn Sie an der Studie mitarbeiten und die Zugangsdaten erhalten möchten, kontaktieren Sie Frau Dipl. Med. Päd. Sabine Striebich oder Frau Elke Mattern, M.Sc. über kontakt@be-up-studie.de.';
						//echo '<a href="'.get_stylesheet_directory_uri().'/logout.php">Logout</a><br /><br />';
					}
					else {
						echo '<div class="small-12 medium-6 cell destroyme">';
						echo 'Der interne Bereich steht den Hebammen, Ärztinnen und Ärzten der teilnehmenden Kliniken zur Verfügung. Wenn Sie an der Studie mitarbeiten und die Zugangsdaten erhalten möchten, kontaktieren Sie Frau Dipl. Med. Päd. Sabine Striebich oder Frau Elke Mattern, M.Sc. über kontakt@be-up-studie.de.';
						echo '</div>';
					}
					?>					
					<?php
						if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) ) {
							//echo '<div class="be-link" id="' . esc_attr($a['ort']) . '" data-open="reveal_logout">Logout</div>';
							echo '<div class="small-12 cell destroyme">';
							echo '<button type="button" id="logout" class="alert button" data-open="reveal_logout">Logout</button>';
							//echo '<a href="'.get_stylesheet_directory_uri().'/logout.php">Logout</a><br /><br />';
							echo '</div>';
						}
					?>
				</div>	
			</div>	
		</div>
		<?php
			if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) ) {
				//echo '<div class="be-link" id="' . esc_attr($a['ort']) . '" data-open="reveal_logout">Logout</div>';
				echo '
				<div class="be-h2-special">
					<div class="topspace">
					</div>
					<div class="be-border-upper">
						<div class="grid-container clearfix">
							<div class="radius-bg">
								<h2 id="' . esc_attr($a['hash']) . '">
									Blog
								</h2>
							</div>
						</div>
					</div>
				</div>				
				';
				//echo '<a href="'.get_stylesheet_directory_uri().'/logout.php">Logout</a><br /><br />';
			}
		?>			
				
<div class="grid-container">
	<div class="grid-x grid-margin-x mmain-grid">
		<main class="small-12 medium-8 cell main-content">
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php //get_template_part( 'template-parts/content', get_post_format() ); ?>

<?php
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					// echo '<div class="catpost-title" style="margin-top: 1rem;">'.esc_html( $categories[0]->name ).'</div>';
					echo '<div class="catpost-title" style="margin-top: 1rem;">
						<p style="font-size: 22px; color: #FFF; line-height: 20px; margin-bottom: 0px; padding-top: 8px;">'.esc_html( $categories[0]->name ).'</p>
						<p style="font-size: 18px; color: #FFEFDE; margin-bottom: 0px;">'.esc_html( get_the_date('d.m.Y') ).' | '.esc_html( get_the_author()).'</p>
					</div>';
				}
				echo '<div class="entry-content be-gradient-2-invers spacer-v-4" style="padding:1rem; margin-bottom:1rem;">';
					echo '<div class="catpost" id="post-'.get_the_ID().'">';
						echo '<a href="'.get_the_permalink().'" rel="bookmark" title="'.get_the_title().'">';
							echo '<h3>';
								
									echo get_the_title();
								
							echo '</h3>';
						echo '</a>';							
							
							if (has_post_thumbnail()) {
							echo '<div class="threadimg">';
								the_post_thumbnail('medium');
							echo '</div>';
							};
							echo get_the_excerpt();
						echo '</div>';
					echo '</div>';
?>



			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; // End have_posts() check. ?>

			<?php /* Display navigation to next/previous pages when applicable */ ?>
			<?php
			if ( function_exists( 'foundationpress_pagination' ) ) :
				foundationpress_pagination();
			elseif ( is_paged() ) :
			?>
				<nav id="post-nav">
					<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'foundationpress' ) ); ?></div>
					<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'foundationpress' ) ); ?></div>
				</nav>
			<?php endif; ?>

		</main>

		<div class="small-12 medium-4 cell secret">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>


		<script>
			// $("button#logout").click(function() {
			// 	function explode(){
			// 		window.location.href = "<?php echo get_stylesheet_directory_uri()?>/logout.php";
			// 		}
			// 	setTimeout(explode, 1000);
			// });
			jQuery(document).ready(function($){
				$('button#logout').click(function(){
					var data = {
						action : "pplb_logout"
					}
					
					$.post( pplb_ajax.ajaxurl, data, function( response ) {
						if ( response.log == 1 && typeof console == "object" && typeof console.log != 'undefined' ) {
							console.log( response );
						}
						if( response.status == 0 ){
							// nothing hanppens
						}
						else{
							alert( response.message );
						}
						document.location.href = document.location.href;
					}, "JSON" );
				});
			});
		</script>

<?php get_footer();
