<?php 

/** Sets up the WordPress Environment. */
require( 'wp-load.php' );
require( 'wp-blog-header.php' );

if ( is_user_logged_in() ) {
    ob_start();
    header("Location: /wp-signup.php");
    ob_flush();
}
else {

	get_header(); ?>
	
	<div id="content" class="widecolumn nutzerkennzeichen">
		
		<div class="post hentry">
		
			<h1>Neuen Blog anlegen</h1>
			
			<ul id="steps">
				<li class="current">1. Schritt: Anmelden</li>
				<li>2. Schritt: Blog anlegen</li>
				<li>3. Schritt: Loslegen</li>
			</ul>
			
			<h3>Anmelden</h3>
			
			<p class="info">Bitte melde dich hier mit deinem <strong>5-stelligen Uni-Nutzerkennzeichen</strong> und dem dazugeh&ouml;rigen <strong>Passwort</strong> an.</p>
			
			<p class="info dimmed">Das Nutzerkennzeichen wurde dir mit deinem Immatrikulationsschreiben mitgeteilt. Bei Fragen zu deinem Nutzerkennzeichen wende dich bitte an <a href="mailto:nutzerverwaltung@urz.uni-halle.de" title="URZ Nutzerverwaltung">nutzerverwaltung@urz.uni-halle.de</a></p>
			
			<?php wp_login_form("label_username=Nutzerkennzeichen&redirect=/wp-signup.php"); ?>

		</div>
		
	</div>

<?php 
	get_footer(); 
}
?>
