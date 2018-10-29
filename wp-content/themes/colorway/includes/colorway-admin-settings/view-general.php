<?php
/**
 * View General
 *
 * @package     ColorWay
 * @author      ColorWay
 * @copyright   Copyright (c) 2018, ColorWay
 * @link        http://inkthemes.com/
 * @since       ColorWay 1.0
 */

?>

<div class="cwy-container cwy-welcome">
		<div id="poststuff">
			<div id="post-body" class="columns-2">
<!--				<div id="post-body-content">-->
					<!-- All WordPress Notices below header -->
                                        					<div id="side-sortables">
						<?php do_action( 'colorway_welcome_page_right_sidebar_before' ); ?>

						<?php do_action( 'colorway_welcome_page_right_sidebar_content' ); ?>

						<?php do_action( 'colorway_welcome_page_right_sidebar_after' ); ?>
					</div>
					<h1 class="screen-reader-text"> <?php esc_html_e( 'ColorWay', 'colorway' ); ?> </h1>
						<?php do_action( 'colorway_welcome_page_content_before' ); ?>

						<?php do_action( 'colorway_welcome_page_content' ); ?>

						<?php do_action( 'colorway_welcome_page_content_after' ); ?>
<!--				</div>-->
<!--				<div class="postbox-container cwy-sidebar" id="postbox-container-1">-->

<!--				</div>-->
			</div>
			<!-- /post-body -->
			<br class="clear">
		</div>


</div>
