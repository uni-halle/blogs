<?php $page_id = 'awpcp-admin-settings' ?>
<?php $page_class = "$page_id awpcp-$group->slug"; ?>
<?php $page_title = sprintf(__('AWPCP %s Settings', 'AWPCP'), $group->name) ?>

<?php include(AWPCP_DIR . '/admin/templates/admin-panel-header.tpl.php') ?>

			<?php awpcp_print_messages(); ?>

			<h2 class="nav-tab-wrapper">
			<?php foreach ($groups as $g): ?>
				<?php $href = add_query_arg(array('g' => $g->slug), awpcp_current_url()); ?>
				<?php $active = $group->slug == $g->slug ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>
				<a href="<?php echo $href ?>" class="<?php echo $active ?>"><?php echo $g->name ?></a>
			<?php endforeach ?>
			</h2>

			<?php do_action('awpcp-admin-settings-page--' . $group->slug); ?>

			<form class="settings-form" action="<?php echo admin_url('options.php') ?>" method="post">
				<?php settings_fields( $awpcp->settings->setting_name ); ?>
				<input type="hidden" name="group" value="<?php echo $group->slug ?>" />

				<?php $awpcp->settings->load() ?>
				<?php
				ob_start();
				do_settings_sections($group->slug);
				$output = ob_get_contents();
				ob_end_clean();
				?>

				<?php if ( $output ): ?>
				<p class="submit hidden">
					<input type="submit" value="<?php _e( 'Save Changes', 'AWPCP' ); ?>" class="button-primary" id="submit-top" name="submit">
				</p>
				<?php endif; ?>

				<?php
					// A hidden submit button is necessary so that whenever the user hits enter on an input field,
					// that one is the button that is triggered, avoiding other submit buttons in the form to trigger
					// unwanted behaviours.
				?>

				<?php echo $output; ?>

				<?php if ( $output ): ?>
				<p class="submit">
					<input type="submit" value="<?php _e('Save Changes', 'AWPCP') ?>" class="button-primary" id="submit-bottom" name="submit">
				</p>
				<?php endif; ?>
			</form>

				<!-- </div>
			</div> -->
		</div><!-- end of .awpcp-main-content -->
	</div><!-- end of .page-content -->
</div><!-- end of #page_id -->
