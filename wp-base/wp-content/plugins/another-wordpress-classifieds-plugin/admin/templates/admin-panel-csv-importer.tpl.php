<?php $page_id = 'awpcp-admin-csv-importer' ?>
<?php $page_title = __('AWPCP Classifieds Management System: Import Ad', 'AWPCP') ?>

<?php include(AWPCP_DIR . '/admin/templates/admin-panel-header.tpl.php') ?>

<?php if (!is_null($importer) && ($importer->ads_imported > 0 || $importer->ads_rejected > 0)): ?>
	<?php if ($test_import): ?>
			<h3><?php _e('Import Testing Results', 'AWPCP'); ?></h3>
	<?php else: ?>
			<h3><?php _e('Final Import Results', 'AWPCP'); ?></h3>
	<?php endif ?>

			<ul>
				<li><b><?php _e('Imported rows:', 'AWPCP') ?></b> <?php echo $importer->ads_imported ?></li>
				<li><b><?php _e('Imported Picture count:', 'AWPCP') ?></b> <?php echo $importer->images_imported ?></li>
				<li><b><?php _e('Rejected rows:', 'AWPCP') ?></b> <?php echo $importer->ads_rejected ?></li>
			</ul>
<?php endif ?>

			<?php if (!empty($messages)): ?>
				<h3><?php _ex('Messages', 'csv importer', 'AWPCP'); ?></h3>
				<ul>
				<?php foreach ($messages as $message): ?>
					<li><?php echo "$message" ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif ?>

			<?php if (!empty($errors)): ?>
				<h3><?php _ex('Errors', 'csv importer', 'AWPCP'); ?></h3>
				<ul>
				<?php foreach ($errors as $error): ?>
					<li><?php echo "$error" ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif ?>

			<script type="text/javascript">
				(function($){
					$(function() {
						$('#awpcp-importer-start-date, #awpcp-importer-end-date').datepicker({
							changeMonth: true,
							changeYear: true
						});
						$('#awpcp-importer-auto-assign-user').change(function(event) {
							if (!$(this).attr('checked') || !$(this).prop('checked')) {
								$('#awpcp-importer-user').attr('disabled', 'disabled');
							} else {
								$('#awpcp-importer-user').removeAttr('disabled');
							}
						}).change();
					});
				})(jQuery);
			</script>

			<form enctype="multipart/form-data" method="post">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label for="awpcp-importer-csv-file"><?php _e('CSV file', 'AWPCP') ?></label>
							</th>
							<td>
								<input id="awpcp-importer-csv-file" type="file" name="import" id="import" />
								<br/><?php echo awpcp_form_error('import', $form_errors) ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="awpcp-importer-zip-file"><?php _e('Zip file containing images', 'AWPCP') ?></label>
							</th>
							<td>
								<input id="awpcp-importer-zip-file" type="file" name="import_zip" id="import_zip" />
								<br/><?php echo awpcp_form_error('import_zip', $form_errors) ?>
							</td>
						</tr>

						<tr><th scope="row" colspan="2"><b><?php _e('You can configure default dates for imported Ads', 'AWPCP') ?></b></th></tr>
						<tr>
							<th scope="row">
								<label for="awpcp-importer-start-date"><?php _e('Start Date (mm/dd/yyyy)', 'AWPCP') ?></label>
							</th>
							<td>
								<input id="awpcp-importer-start-date" type="text" name="startDate" value="<?php echo esc_attr($start_date) ?>" />
								<br/><br/><?php echo awpcp_form_error('startDate', $form_errors) ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="awpcp-importer-end-date"><?php _e('End Date (mm/dd/yyyy)', 'AWPCP') ?></label>
							</th>
							<td>
								<input id="awpcp-importer-end-date" type="text" name="endDate" value="<?php echo esc_attr($end_date) ?>" />
								<br/><br/><?php echo awpcp_form_error('endDate', $form_errors) ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e('Date Format', 'AWPCP') ?>
							</th>
							<td>
								<br/><br/><?php echo awpcp_form_error('date_fmt', $form_errors) ?>
								<input id="awpcp-importer-format-us-date" type="radio" name="date_fmt" value="us_date" <?php if ($import_date_format == "us_date") echo "checked"; ?> />
								<label for="awpcp-importer-format-us-date">
									<?php _e('US Date Only (mm/dd/year)', 'AWPCP') ?></label>
								<br/>

								<input id="awpcp-importer-format-uk-date" type="radio" name="date_fmt" value="uk_date" <?php if ($import_date_format == "uk_date") echo "checked"; ?> />
								<label for="awpcp-importer-format-uk-date"><?php _e('UK Date Only (dd/mm/year)', 'AWPCP') ?></label>
								<br/>

								<input id="awpcp-importer-format-us-date-time" type="radio" name="date_fmt" value="us_date_time" <?php if ($import_date_format == "us_date_time") echo "checked"; ?> />
								<label for="awpcp-importer-format-us-date-time">
									<?php _e('US Date and Time (mm/dd/year hh:mm:ss)', 'AWPCP') ?></label>
								<br/>

								<input id="awpcp-importer-format-uk-date-time" type="radio" name="date_fmt" value="uk_date_time" <?php if ($import_date_format == "uk_date_time") echo "checked"; ?> />
								<label for="awpcp-importer-format-uk-date-time"><?php _e('UK Date and Time (dd/mm/year hh:mm:ss)', 'AWPCP') ?></label>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e('Separators Used in CSV', 'AWPCP') ?>
							</th>
							<td>
								<label for="awpcp-importer-date-separator"><?php _e('Date Separator', 'AWPCP'); ?></label>
								<input id="awpcp-importer-date-separator" type="text" maxlength="1" size="1" name="sep_date" value="<?php echo esc_attr($date_sep); ?>" />
								<br/><br/><?php echo awpcp_form_error('sep_date', $form_errors) ?>

								<label for="awpcp-importer-time-separator"><?php _e('Time Separator', 'AWPCP'); ?></label>
								<input id="awpcp-importer-time-separator" type="text" maxlength="1" size="1" name="sep_time" value="<?php echo esc_attr($time_sep); ?>" />
								<br/><br/><?php echo awpcp_form_error('sep_time', $form_errors) ?>

								<label for="awpcp-importer-image-separator"><?php _e('Image Separator', 'AWPCP'); ?></label>
								<input id="awpcp-importer-image-separator" type="text" maxlength="1" size="1" name="sep_image" value=";" disabled="disabled" /> <?php _e('(semi-colon)', 'AWPCP'); ?>
								<br/><br/><?php echo awpcp_form_error('sep_image', $form_errors) ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e('Category Handling on Import', 'AWPCP') ?>
							</th>
							<td>
								<select name="auto_cat" id="auto_cat">
									<option value=1 <?php if ($auto_cat == "1") echo 'selected="selected"'; ?>><?php _e('Auto create Categories', 'AWPCP') ?></option>
									<option value=0 <?php if ($auto_cat == "0") echo 'selected="selected"'; ?>><?php _e('Generate errors if Category not found', 'AWPCP') ?></option>
								</select><br/>
								<?php echo awpcp_form_error('auto_cat', $form_errors) ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e('Assign Ads to an user?', 'AWPCP') ?>
							</th>
							<td>
								<input type="checkbox" name="assign_user" id="awpcp-importer-auto-assign-user" value="1" <?php echo $assign_user == 1 ? 'checked="checked"' : ''; ?> />
								<label for="awpcp-importer-auto-assign-user"><?php _e('Assign Ads to an user?', 'AWPCP') ?></label><br/>
								<span class="description"><?php _e("If unchecked, Ads won't be associated to an user.", 'AWPCP') ?></span>
								<br/><br/><?php echo awpcp_form_error('assign_user', $form_errors) ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="awpcp-importer-user"><?php _e('Default user', 'AWPCP') ?></label>
							</th>
							<td>
								<select id="awpcp-importer-user" name="user">
									<option value=""><?php _e('use spreadsheet information', 'AWPCP') ?></option>
								<?php foreach (awpcp_get_users() as $user): ?>
									<option value="<?php echo esc_attr($user->ID) ?>" <?php echo $assigned_user == $user->ID ? 'selected="selected"' : ''; ?>>
										<?php echo $user->user_login ?>
									</option>
								<?php endforeach ?>
								</select><br/>
								<span class="description"><?php _e("Ads will be associated to this user if the username column is not present in the CSV file, there is no user with that username and we couldn't find an user with the contact_email address specified in the CSV file.", 'AWPCP') ?></span>
								<br/><br/><?php echo awpcp_form_error('user', $form_errors) ?>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<input type="submit" class="button" name="import_type" value="<?php _e('Test Import', 'AWPCP') ?>"></input>
					<input type="submit" class="button-primary button" name="import_type" value="<?php _e('Import', 'AWPCP') ?>"></input>
				</p>
			</form>
		</div><!-- end of .awpcp-main-content -->
	</div><!-- end of .page-content -->
</div><!-- end of #page_id -->
