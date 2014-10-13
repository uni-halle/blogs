<?php

require_once(AWPCP_DIR . '/import.php');

class AWPCP_Admin_CSV_Importer {

	public function scripts() {
		wp_enqueue_style('awpcp-jquery-ui');
  		wp_enqueue_script( 'awpcp-admin-import' );
	}

	public function dispatch() {
		global $awpcp_plugin_path;
		global $start_date;
		global $end_date;
		global $import_date_format;
		global $date_sep;
		global $time_sep;
		global $auto_cat;
		global $assign_user;
		global $assigned_user;
		global $test_import;

		global $import_count;
		global $reject_count;
		global $pic_import_count;

		global $import_errors;

		$import_type = awpcp_post_param('import_type', '');
		$test_import = strcmp($import_type, "Test Import") === 0;

		$start_date = awpcp_post_param("startDate", '');
		$end_date = awpcp_post_param("endDate", '');
		$import_date_format = awpcp_post_param("date_fmt", 'us_date');
		$date_sep = awpcp_post_param("sep_date", '/');
		$time_sep = awpcp_post_param("sep_time", ':');
		$auto_cat = awpcp_post_param("auto_cat", 0);
		$assign_user = awpcp_post_param('assign_user', 0);
		$assigned_user = intval(awpcp_post_param('user', 0));

		// Original implementation used a global var to pass errors.
		// That is still used until I got a change to refactor the
		// existing functions to use an errors array passed by reference.
		// The messages array is only used to report when a new user
		// is created.
		$errors = array();
		$messages = array();
		$form_errors = array();

		$importer = null;

		if (!empty($import_type)) {

			$msg = __('There was an error with your CSV file: %s', 'AWPCP');
			list($csv_error, $message) = awpcp_uploaded_file_error($_FILES['import']);
			if (!in_array($csv_error, array(UPLOAD_ERR_OK))) {
				$form_errors['import'] = sprintf($msg, $message);
			} else {
				$csv_file_name = $_FILES['import']['name'];
				$ext = trim(strtolower(substr(strrchr($csv_file_name, "."), 1)));
				if ($ext != "csv") {
					$form_errors['import'] = sprintf($msg, __('Please upload a valid CSV file.', 'AWPCP'));
				}
			}

			$msg = __('There was an error with your ZIP file: %s', 'AWPCP');
			list($zip_error, $message) = awpcp_uploaded_file_error($_FILES['import_zip']);
			if (!in_array($zip_error, array(UPLOAD_ERR_OK, UPLOAD_ERR_NO_FILE))) {
				$form_errors['import_zip'] = sprintf($msg, $message);
			} else if ($zip_error === UPLOAD_ERR_OK) {
				$zip_file_name = $_FILES['import_zip']['name'];
				$ext = trim(strtolower(substr(strrchr($zip_file_name, "."), 1)));
				if ($ext != "zip") {
					$form_errors['import_zip'] = sprintf($msg, __('Please upload a valid ZIP file.', 'AWPCP'));
				}
			}

			if (!empty($start_date)) {
				$date_arr = explode("/", $start_date);
				if (!is_valid_date($date_arr[0], $date_arr[1], $date_arr[2])) {
					$form_errors['startDate'] = __('Invalid Start Date.', 'AWPCP');
				} else if (strlen($date_arr[2]) != 4) {
					$form_errors['startDate'] = __('Invalid Start Date -- Year Must be of Four Digit.', 'AWPCP');
				}
			}

			if (!empty($end_date)) {
				$date_arr = explode("/", $end_date);
				if (!is_valid_date($date_arr[0], $date_arr[1], $date_arr[2])) {
					$form_errors['endDate'] = __('Invalid End Date.', 'AWPCP');
				} else if (strlen($date_arr[2]) != 4) {
					$form_errors['endDate'] = __('Invalid End Date -- Year Must be of Four Digit.', 'AWPCP');
				}
			}

			if (empty($form_errors)) {
 				if (empty($errors)) {
					$csv = $_FILES['import']['tmp_name'];
					$zip = $_FILES['import_zip']['tmp_name'];

					$importer = new AWPCP_CSV_Importer(array(
						'start-date' => $start_date,
						'end-date' => $end_date,
						'date-format' => $import_date_format,
						'date-separator' => $date_sep,
						'time-separator' => $time_sep,
						'autocreate-categories' => $auto_cat,
						'assign-user' => $assign_user,
						'default-user' => $assigned_user,
						'test-import' => $test_import)
					);

					$importer->import($csv, $zip, $errors, $messages);
 				}
			}
		}

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-csv-importer.tpl.php');
			$html = ob_get_contents();
		ob_end_clean();

		echo $html;
	}
}
