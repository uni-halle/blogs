<?php
/*
Template Name: be-up Intern
*/
get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>
<div class="main-container">
	<div class="main-grid">
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
					if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) && wp_check_password( $post->post_password, $_COOKIE['wp-postpass_' . COOKIEHASH])) {
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
					if (!empty($post->post_password)) { // if there's a password
						
						if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) && wp_check_password( $post->post_password, $_COOKIE['wp-postpass_' . COOKIEHASH])) {
							//echo '<div class="be-link" id="' . esc_attr($a['ort']) . '" data-open="reveal_logout">Logout</div>';
							echo '<div class="small-12 cell destroyme">';
							echo '<button type="button" id="logout" class="alert button" data-open="reveal_logout">Logout</button>';
							//echo '<a href="'.get_stylesheet_directory_uri().'/logout.php">Logout</a><br /><br />';
							echo '</div>';
							echo '<div class="small-12 cell">';
							echo '<h3>Downloads</h3>';
							$cookiehash = 'wp-postpass_' . COOKIEHASH;
							echo $cookiehash;
							echo '<a href="https://be-up-studie.de/wp-content/themes/be-up-theme/secret_download.php?file=Ramsayer_Titel_final.pdf&hash='.$cookiehash.'">Ramsayer</a>';
							echo '</div>';
						}
					}
					?>
				</div>	
			</div>	
		</div>
		<?php
		if (!empty($post->post_password)) { // if there's a password
			
			if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) && wp_check_password( $post->post_password, $_COOKIE['wp-postpass_' . COOKIEHASH])) {
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
		}
		?>			
		<div class="grid-container">
			<div class="grid-x grid-margin-x align-left">							
				<div class="small-12 medium-8 cell">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content', 'page' ); ?>
						<?php //get_template_part( 'template-parts/demo-content-standard', 'page' ); ?>
						<?php //comments_template(); ?>
					<?php endwhile; ?>
				</div>
				<div class="small-12 medium-4 cell secret">
					<?php 
					if (!empty($post->post_password)) { // if there's a password
						if (isset($_COOKIE['wp-postpass_' . COOKIEHASH]) && wp_check_password( $post->post_password, $_COOKIE['wp-postpass_' . COOKIEHASH])) {
							get_sidebar();
						}
					}
					else {
					};
					?>
				</div>
			</div>
		</div>

		<div class="reveal" id="reveal_logout" aria-labelledby="reveal_Header_logout" data-reveal>
			<h1 id="reveal_Header_logout">Logout</h1>
			<p class="lead">
				Sie werden ausgeloggt...
				Bitte warten Sie auf den Abschluss des Vorgangs!
			</p>
			<button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
				<span aria-hidden="true">&times;</span>
			</button>
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
				
	</main>
</div>
	
<?php get_footer();
