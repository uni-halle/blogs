<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */
$temperas = tempera_get_theme_options();
?>
		<div id="primary" class="widget-area sidey" role="complementary">
		<?php cryout_before_primary_widgets_hook(); ?>

			<ul class="xoxo">
				<?php if ( $temperas['tempera_socialsdisplay1'] ) { ?>
					<li id="socials-left" class="widget-container">
					<?php tempera_smenul_socials(); ?>
					</li>
				<?php } ?>
				<?php if (is_active_sidebar('left-widget-area')): dynamic_sidebar( 'left-widget-area' );
                                                            else: ?>
				<li class="widget-container widget-placeholder">
					<h3 class="widget-title"><?php _e('Left Sidebar','tempera'); ?></h3>
					<p><?php
					if ( current_user_can( 'edit_theme_options' ) ) {
						printf( __('You currently have no widgets set in the left sidebar. You can add widgets via the <a href="%s">Dasboard</a>.','tempera'), esc_url( admin_url()."widgets.php") ); echo "<br/>";
						printf( __('To hide this sidebar, switch to a different Layout via the <a href="%s">Theme Settings</a>.','tempera'), esc_url( admin_url()."themes.php?page=tempera-page") );
					}
					?></p>
				</li>
				<?php endif; ?>
			</ul>

			<?php cryout_after_primary_widgets_hook(); ?>

		</div>

