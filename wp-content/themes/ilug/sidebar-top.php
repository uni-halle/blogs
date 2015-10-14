<?php
/**
 * The Top widget areas.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'first-top-widget-area'  ) )
		return;
	// If we get this far, we have widgets. Let do this.
?>

<?php if ( is_active_sidebar( 'first-top-widget-area' ) ) : ?>
		<script>
			var topmenu = 0;
			function switchTopMenu()
			{
				if(topmenu == 0)
				{
					document.getElementById('topwidgetarea').style.display='block';
					topmenu = 1;
				}
				else
				{
					document.getElementById('topwidgetarea').style.display='none';
					topmenu = 0;
				}
			}
		</script>
		<button id='topmenubutton' onclick='switchTopMenu();' ><center><img style='width: 70%;' src='https://ilug.uni-halle.de/wp-content/themes/ilug/images/menusymbol.png'/>Men&uuml;</center></button>
		<ul id='topwidgetarea' style='display:none;'>
			<?php dynamic_sidebar( 'first-top-widget-area' ); ?>
		</ul>
<?php endif; ?>
