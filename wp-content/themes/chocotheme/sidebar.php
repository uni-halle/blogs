<?php
/**
 * The template for displaying the sidebar.
 *
 * Contains the closing of the id=content div
 *
 * @package WordPress
 * @subpackage Choco
 */
?>
					</div><!-- /.content-inner -->
				</div><!-- END Content -->
				<div id="sidebar">
					<?php do_action( 'before_sidebar' ); ?>

					<a href="#" class="close-sidebar"></a>

					<div class="sidebar-nav">
						<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary', 'menu_class' => 'menu clear-fix') ); ?>
					</div><!-- /.sidebar-nav -->

					<ul class="xoxo">
						<?php if ( !dynamic_sidebar() ) : ?>
							<li class="widget widget_search">
								<?php get_search_form(); ?>
							</li>

							<li class="widget widget_pages">
								<h4><?php _e( 'Pages', 'choco' ); ?></h4>
								<ul>
								<?php wp_list_pages( 'title_li=' ); ?>
								</ul>
							</li>

							<li class="widget widget_archive">
								<h4><?php _e( 'Archives', 'choco' ); ?></h4>
								<ul>
								<?php wp_get_archives( 'type=monthly' ); ?>
								</ul>
							</li>

							<li class="widget widget_categories">
								<h4><?php _e( 'Categories', 'choco' ); ?></h4>
								<ul>
								<?php wp_list_categories( 'show_count=1&title_li=' ); ?>
								</ul>
							</li>

							<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
							<li class="widget widget widget_links">
								<h4><?php _e( 'Bookmarks', 'choco' ); ?></h4>
								<ul>
									<?php wp_list_bookmarks( 'categorize=0&title_li=' ); ?>
								</ul>

							</li>

							<li class="widget widget widget_meta">
								<h4><?php _e( 'Meta', 'choco' ); ?></h4>
								<ul>
									<?php wp_register(); ?>
									<li><?php wp_loginout(); ?></li>
									<?php wp_meta(); ?>
								</ul>
							</li>
							<?php } ?>
						<?php endif; ?>
					</ul>
				</div><!-- END Sidebar -->
				<div class="cl">&nbsp;</div>