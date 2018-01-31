<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

opinionstage_enqueue_css_asset('menu-page');
opinionstage_enqueue_css_asset('icon-font');
opinionstage_enqueue_js_asset('menu-page');

?>

<div id="opinionstage-content">
	<div class="opinionstage-header-wrapper">
		<div class="opinionstage-logo-wrapper">
			<div class="opinionstage-logo"></div>
		</div>
		<div class="opinionstage-status-content">
			<?php if ( !$os_client_logged_in ) {?>
			<div class='opinionstage-status-title'>Connect WordPress with Opinion Stage to get started</div>
			<form action="<?php echo OPINIONSTAGE_LOGIN_PATH ?>" method="get" class="opinionstage-connect-form">
				<i class="os-icon icon-os-poll-client"></i>
				<input type="hidden" name="o" value="<?php echo OPINIONSTAGE_WIDGET_API_KEY ?>">
				<input type="hidden" name="callback" value="<?php echo opinionstage_callback_url()?>">
				<input id="os-email" type="email" name="email" placeholder="Enter Your Email" data-os-email-input>
				<button class="opinionstage-connect-btn opinionstage-blue-btn" type="submit" id="os-start-login" data-os-login>CONNECT</button>
			</form>
			<?php } else { ?>
			<div class='opinionstage-status-title'><b>You are connected</b> to Opinion Stage with the following email</div>
			<i class="os-icon icon-os-form-success"></i>
			<label class="checked" for="user-email"></label>
			<input id="os-email" type="email" disabled value="<?php echo($os_options["email"]) ?>">
			<form method="POST" action="<?php echo get_admin_url(null, 'admin.php?page=opinionstage-disconnect-page')?>" class="opinionstage-connect-form">
				<button class="opinionstage-connect-btn opinionstage-blue-btn" type="submit" id="os-disconnect">DISCONNECT</button>
			</form>
			<?php } ?>
		</div>
	</div>
	<div class="opinionstage-dashboard">
		<div class="opinionstage-dashboard-left">
			<div id="opinionstage-section-create" class="opinionstage-dashboard-section">
				<div class="opinionstage-section-header">
					<div class="opinionstage-section-title">Content</div>
					<?php if ( $os_client_logged_in ) {?>
					<a href="<?php echo OPINIONSTAGE_SERVER_BASE.'/dashboard/content'; ?>" target="_blank" class="opinionstage-section-action opinionstage-blue-bordered-btn">VIEW MY CONTENT</a>
					<?php } ?>
				</div>
				<div class="opinionstage-section-content">
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-reports-polls"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Poll</div>
							<div class="example">e.g. What's your favorite color?</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_poll_link('opinionstage-blue-btn opinionstage-create-btn'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-reports-set"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Survey</div>
							<div class="example">e.g. Help us improve our site</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_widget_link('survey', 'opinionstage-blue-btn opinionstage-create-btn'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-reports-trivia"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Trivia Quiz</div>
							<div class="example">e.g. How well do you know dogs?</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_widget_link('quiz', 'opinionstage-blue-btn opinionstage-create-btn'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-reports-personality"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Outcome Quiz</div>
							<div class="example">e.g. Where should you travel next?</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_widget_link('outcome', 'opinionstage-blue-btn opinionstage-create-btn'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-widget-slideshow"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Slideshow</div>
							<div class="example">e.g. Browse the most watched TV series</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_slideshow_link( 'opinionstage-blue-btn opinionstage-create-btn' ); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-widget-form"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">Form</div>
							<div class="example">e.g. Collect email addresses</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_widget_link('contact_form', 'opinionstage-blue-btn opinionstage-create-btn'); ?>
						</div>
					</div>
					<div class="opinionstage-section-raw">
						<div class="opinionstage-section-cell opinionstage-icon-cell">
							<div class="os-icon icon-os-reports-list"></div>
						</div>
						<div class="opinionstage-section-cell opinionstage-description-cell">
							<div class="title">List</div>
							<div class="example">e.g. Top 10 movies of all times</div>
						</div>
						<div class="opinionstage-section-cell opinionstage-btn-cell">
							<?php echo opinionstage_create_widget_link('list', 'opinionstage-blue-btn opinionstage-create-btn'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="opinionstage-dashboard-right">
			<div id="opinionstage-section-placements" class="opinionstage-dashboard-section <?php echo( $os_client_logged_in ? '' : 'opinionstage-disabled-section' ) ?>">
				<div class="opinionstage-section-header">
					<div class="opinionstage-section-title">Placements</div>
				</div>
				<div class="opinionstage-section-content-wrapper">
					<div class="opinionstage-section-content">
						<div class="opinionstage-section-raw">
							<div class="opinionstage-section-cell opinionstage-toggle-cell">
								<div class="opinionstage-onoffswitch <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>">
									<input type="checkbox" name="fly-out-switch" class="opinionstage-onoffswitch-checkbox"
												<?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>
												id="fly-out-switch"
												<?php echo($os_client_logged_in && $os_options['fly_out_active'] == 'true' ? 'checked' : '') ?>
									>
									<label class="opinionstage-onoffswitch-label" for="fly-out-switch">
										<div class="opinionstage-onoffswitch-inner"></div>
										<div class="opinionstage-onoffswitch-switch"></div>
									</label>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-description-cell">
								<div class="title">Popup</div>
								<div class="example">Add a content popup to your site</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-btns-cell">
								<a href="<?php echo opinionstage_flyout_edit_url('content'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-content <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">EDIT CONTENT</a>
								<a href="<?php echo opinionstage_flyout_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">
									<div class="os-icon icon-os-common-settings"></div>
								</a>
							</div>
						</div>
						<div class="opinionstage-section-raw">
							<div class="opinionstage-section-cell opinionstage-toggle-cell">
								<div class="opinionstage-onoffswitch <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>">
									<input type="checkbox" name="article-placement-switch" class="opinionstage-onoffswitch-checkbox"
												<?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>
												id="article-placement-switch"
												<?php echo( $os_client_logged_in && $os_options['article_placement_active'] == 'true' ? 'checked' : '') ?>
									>
									<label class="opinionstage-onoffswitch-label" for="article-placement-switch">
										<div class="opinionstage-onoffswitch-inner"></div>
										<div class="opinionstage-onoffswitch-switch"></div>
									</label>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-description-cell">
								<div class="title">Article</div>
								<div class="example">Add a content section to all posts</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-btns-cell">
								<a href="<?php echo opinionstage_article_placement_edit_url('content'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-content <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">EDIT CONTENT</a>
								<a href="<?php echo opinionstage_article_placement_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">
									<div class="os-icon icon-os-common-settings"></div>
								</a>
							</div>
						</div>
						<div class="opinionstage-section-raw">
							<div class="opinionstage-section-cell opinionstage-toggle-cell">
								<div class="opinionstage-onoffswitch <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>">
									<input type="checkbox" name="sidebar-placement-switch" class="opinionstage-onoffswitch-checkbox"
												<?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>
												id="sidebar-placement-switch"
												<?php echo($os_client_logged_in && $os_options['sidebar_placement_active'] == 'true' ? 'checked' : '') ?>
									>
									<label class="opinionstage-onoffswitch-label" for="sidebar-placement-switch">
										<div class="opinionstage-onoffswitch-inner"></div>
										<div class="opinionstage-onoffswitch-switch"></div>
									</label>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-description-cell">
								<div class="title">Sidebar Widget</div>
								<div class="example">
									<?php if ( !$os_client_logged_in ) {?>
									Add content to your sidebar
									<?php } else { ?>
									<div class="os-long-text">
										<a href="<?php echo $url = get_admin_url('', '', 'admin') . 'widgets.php' ?>">Add widget to your sidebar</a>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="opinionstage-section-cell opinionstage-btns-cell">
								<a href="<?php echo opinionstage_sidebar_placement_edit_url('content'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-content <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">EDIT CONTENT</a>
								<a href="<?php echo opinionstage_sidebar_placement_edit_url('settings'); ?>" class='opinionstage-blue-bordered-btn opinionstage-edit-settings <?php echo( $os_client_logged_in ? '' : 'disabled' ) ?>' target="_blank">
									<div class="os-icon icon-os-common-settings"></div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="opinionstage-dashboard-left">
		<div id="opinionstage-section-help" class="opinionstage-dashboard-section">
				<div class="opinionstage-section-header">
						<div class="opinionstage-section-title">Getting Started Video</div>
				</div>
				<div class="opinionstage-section-content">
					<div class="opinionstage-video-container">
						<iframe width="560" height="315" class="opinionstage-video-itm" src="https://www.youtube.com/embed/zwcRWGsOxxQ?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
		<div class="opinionstage-dashboard-right">
			<div id="opinionstage-section-help" class="opinionstage-dashboard-section">
				<div class="opinionstage-section-header">
					<div class="opinionstage-section-title">Need Help?</div>
				</div>
				<div class="opinionstage-section-content">
					<div class="opinionstage-help-row">
						<a href="http://blog.opinionstage.com/how-to-add-interactive-content-on-wordpress/?o=wp35e8" class="opinionstage-help-link" target="_blank">How to use this plugin</a>
					</div>
					<div class="opinionstage-help-row">
						<?php echo opinionstage_create_link('Live content examples', 'discover', '', 'opinionstage-help-link'); ?>
					</div>
					<div class="opinionstage-help-row">
						<a href="http://blog.opinionstage.com/video-tutorials" class="opinionstage-help-link" target="_blank">View all video tutorials</a>
					</div>
					<div class="opinionstage-help-row">
						<a href="https://www.opinionstage.com/dashboard/content?o=wp35e8" class="opinionstage-help-link" target="_blank">To contact us, click here and then on the chat window located on bottom/right of screen</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
