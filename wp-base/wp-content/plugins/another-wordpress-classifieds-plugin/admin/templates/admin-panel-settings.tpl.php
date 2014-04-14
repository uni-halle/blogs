<?php $page_id = 'awpcp-admin-settings' ?>
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

			<!-- <div class="postbox">
				<div class="inside"> -->

			<?php do_action('awpcp-admin-settings-page--' . $group->slug); ?>

			<form class="settings-form" action="<?php echo admin_url('options.php') ?>" method="post">
				<?php settings_fields($awpcp->settings->option); ?>
				<input type="hidden" name="group" value="<?php echo $group->slug ?>" />

				<!--<p class="submit">
					<input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit">
				</p>-->

				<?php $awpcp->settings->load() ?>
				<?php
				ob_start();
				do_settings_sections($group->slug);
				$output = ob_get_contents();
				ob_end_clean();

				echo $output;
				?>

				<?php if ( $output ): ?>
				<p class="submit">
					<input type="submit" value="<?php _e('Save Changes', 'AWPCP') ?>" class="button-primary" id="submit" name="submit">
				</p>
				<?php endif; ?>
			</form>

				<!-- </div>
			</div> -->
		</div><!-- end of .awpcp-main-content -->
	</div><!-- end of .page-content -->
</div><!-- end of #page_id -->
