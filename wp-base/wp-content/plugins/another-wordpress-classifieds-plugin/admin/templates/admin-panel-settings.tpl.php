<?php $page_id = 'awpcp-admin-settings' ?>
<?php $page_title = sprintf(__('AWPCP %s Settings', 'AWPCP'), $group->name) ?>

<?php include(AWPCP_DIR . '/admin/templates/admin-panel-header.tpl.php') ?>

<?php awpcp_print_messages(); ?>

			<ul class="tabs clearfix">
<?php foreach ($groups as $g):
		$href = add_query_arg(array('g' => $g->slug), awpcp_current_url());
		$active = $group->slug == $g->slug ? 'active' : ''; ?>
				<li><a href="<?php echo $href ?>" class="<?php echo $active ?>"><?php echo $g->name ?></a></li>
<?php endforeach ?>
			</ul>

			<?php do_action('awpcp-admin-settings-page--' . $group->slug) ?>

			<form class="settings-form" action="<?php echo admin_url('options.php') ?>" method="post">
				<?php settings_fields($awpcp->settings->option); ?>
				<input type="hidden" name="group" value="<?php echo $group->slug ?>" />

				<!--<p class="submit">
					<input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit">
				</p>-->

				<?php $awpcp->settings->load() ?>
				<?php do_settings_sections($group->slug); ?>

				<p class="submit">
					<input type="submit" value="<?php _e('Save Changes', 'AWPCP') ?>" class="button-primary" id="submit" name="submit">
				</p>
			</form>
		</div><!-- end of .awpcp-main-content -->
	</div><!-- end of .page-content -->
</div><!-- end of #page_id -->