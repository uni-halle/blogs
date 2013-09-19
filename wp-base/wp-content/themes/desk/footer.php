<?php 
/* @package WordPress
 * @subpackage Desk
 */
 if (!isset($dk_settings)){
$dk_settings = get_option( 'sa_options' ); //gets the current value of all the settings as stored in the db
}
?>
<div class="clear" style="height: 35px">
</div>
</div>
<!-- /Content --></div>
<!-- /Wrapper -->
<div id="footer">
	<div id="gradfoot"></div>
		
	<div id="centfoot">	
	<div id="aligner">
		<ul id="footbar-left" class="footbar"><li></li>
		<?php dynamic_sidebar(2); ?>	
		</ul>
				<ul id="footbar-center" class="footbar"><li></li>
		<?php dynamic_sidebar(3); ?>	
		</ul>
	</div>
		<ul id="footbar-right" class="footbar"><li></li>
		<?php dynamic_sidebar(4); ?>	
		</ul>

		
		<p class="permfooter">
			Desk theme by <a title="Nearfrog Designs"href="http://nearfrog.com/themes/desk">Nearfrog</a>
			 | Valid <a href="http://validator.w3.org/check?uri=<?php echo home_url(); ?>" title="W3C Page Validator"> xhtml</a>
			 | Powered by <a href="http://wordpress.org" title="Wordpress.org">Wordpress</a></p>
	</div>	
<?php if ($dk_settings['desk_analytics_code'] != '') { ?>
<?php echo(stripslashes ($dk_settings['desk_analytics_code']));?>
<?php } ?>
<?php wp_footer(); ?>	
</div>
</div>
<!-- /Container -->


</body>

</html>
