<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */

/* This  retrieves  admin options. */
$temperas = tempera_get_theme_options();
foreach ($temperas as $key => $value) { ${"$key"} = $value; }
?>
		<div id="secondary" class="widget-area sidey" role="complementary">
		<?php cryout_before_primary_widgets_hook(); ?>

			<ul class="xoxo">
				<?php if($tempera_socialsdisplay2) { ?>
					<li id="socials-left" class="widget-container">
					<?php tempera_smenur_socials(); ?>
					</li>
				<?php } ?>
				<?php if (is_active_sidebar('right-widget-area')): dynamic_sidebar( 'right-widget-area' );
                                                           else: ?>
				<li class="widget-container widget-placeholder">
					<h3 class="widget-title"><?php _e('Right Sidebar','tempera'); ?></h3>
					<p><?php
					printf( __('You currently have no widgets set in the right sidebar. You can add widgets via the <a href="%s">Dasboard</a>.','tempera'),esc_url( admin_url()."widgets.php") ); echo "<br/>";
					printf( __('To hide this sidebar, switch to a different Layout via the <a href="%s">Theme Settings</a>.','tempera'), esc_url( admin_url()."themes.php?page=tempera-page") );
					?></p>
				</li>
				<?php endif; ?>
			</ul>

			<?php cryout_after_primary_widgets_hook(); ?>

		</div>
