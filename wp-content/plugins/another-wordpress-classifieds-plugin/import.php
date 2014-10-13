<?php

class AWPCP_CSV_Importer {

	private $required = array(
		"title",
		"details",
		"contact_name",
		"contact_email",
		"category_name",
	);

	private $columns = array(
		"title" => "ad_title",
		"details" => "ad_details",
		"contact_name" => "ad_contact_name",
		"contact_email" => "ad_contact_email",
		"category_name" => "ad_category_id",
		"category_parent" => "ad_category_parent_id",
		"contact_phone" => "ad_contact_phone",
		"website_url" => "websiteurl",
		"city" => "ad_city",
		"state" => 'ad_state',
		"country" => "ad_country",
		"county_village" => "ad_county_village",
		"item_price" => "ad_item_price",
		"start_date" => "ad_startdate",
		"end_date" => "ad_enddate",
		'username' => 'user_id'
	);

	private $ignored = array('ad_id', 'id');

	// empty string to indicate integers :\
	private $types = array(
		"title" => "varchar",
		"details" => "varchar",
		"contact_name" => "varchar",
		"contact_email" => "varchar",
		"category_name" => "",
		"category_parent" => "",
		"contact_phone" => "varchar",
		"website_url" => "varchar",
		"city" => "varchar",
		'state' => 'varchar',
		"country" => "varchar",
		"county_village" => "varchar",
		"item_price" => "",
		"start_date" => "date",
		"end_date" => "date",
		'username' => '',
		"images" => "varchar"
	);

	private $auto_columns = array(
		"is_featured_ad" => 0,
		"disabled" => 0,
		"adterm_id" => 0,
		"ad_postdate" => "?",
		"disabled_date" => "",
		"ad_views" => 0,
		"ad_last_updated" => "?",
		"ad_key" => ""
	);

	private $auto_columns_types = array(
		"is_featured_ad" => "",
		"disabled" => "",
		"adterm_id" => "",
		"ad_postdate" => "?",
		"disabled_date" => "date",
		"ad_views" => "",
		"ad_last_updated" => "?",
		"ad_key" => "varchar"
	);

	private $extra_fields = array();

	private $rejected = array();

	private $defaults = array(
		'start-date' => '',
		'end-date' => '',
		'date-format' => '',
		'date-separator' => '',
		'time-separator' => '',
		'autocreate-categories' => false,
		'assign-user' => false,
		'default-user' => null,
		'test-import' => true
	);

	public $options = array();

	public $ads_imported = 0;
	public $images_imported = 0;
	public $ads_rejected = 0;

	public function __construct($options=array()) {
		$this->options = wp_parse_args($options, $this->defaults);

		// load Extra Fields definitions
		if (defined('AWPCPEXTRAFIELDSMOD')) {
			foreach (awpcp_get_extra_fields() as $field) {
				$this->extra_fields[$field->field_name] = $field;
			}
		}
	}

	/**
	 * @param $csv		filename of the CSV file
	 * @param $zip		filename of the ZIP file
	 */
	public function import( $csv, $zip = '', &$errors = array(), &$messages = array() ) {
		$parsed = $this->get_csv_data($csv);

		if (empty($parsed)) {
			$errors[] = __( 'Invalid CSV file.', 'AWPCP' );
			return false;
		}

		if ( empty( $zip ) ) {
			$import_dir = false;
		} else {
			$import_dir = $this->prepare_import_dir();
			$images = $this->unzip( $zip, $import_dir, $errors, $messages );

			if ( false === $images ) {
				return false;
			}
		}

		$header = $parsed[0];

		if (in_array('images', $header) && empty($zip)) {
			$errors[] = __( 'Image file names were found but no ZIP was provided.', 'AWPCP' );
			return false;
		}

		$ncols = count($header);
		$nrows = count($parsed);

		// if we are assigned an user to the Ads, make sure that column
		// is being considered
		if ($this->options['assign-user'] && !in_array('username', $header)) {
			array_push($header, 'username');
		// if not, make that column optional
		} else if (!$this->options['assign-user']) {
			$this->required = array_diff($this->required, array('username'));
		}

		// per row column count can be handled here
		$data = array();
		for ($i = 1; $i < $nrows; $i++) {
			$column = $parsed[$i];
			$cols = count($column);

			if ($cols != $ncols) {
				// error message
				$errors[] = __( "Row number $i: input length mismatch", 'AWPCP' );
				$this->rejected[$i] = true;
				$this->ads_rejected++;
				continue;
			}

			$data[$i-1] = array('row_no' => $i);
			for ($j = 0; $j < $cols; $j++) {
				$key = trim($header[$j], "\n\r");
				$data[$i-1][$key] = $column[$j];
			}
		}

		if (!$this->validate($header, $data, $errors, $messages)) {
			return false;
		}

		$this->import_ads($header, $data, $import_dir, $errors, $messages);
	}

	/**
	 * @param $header	array of columns in the CSV file
	 * @param $csv		two dimensional array of data extracted from CSV file
	 */
	private function validate($header, $csv, &$errors, &$messages) {
		foreach ($this->required as $required) {
			if (!in_array($required, $header)) {
				$msg = __( "The required column %s is missing. Import can't continue.", 'AWPCP' );
				$msg = sprintf($msg, $required);
				$errors[] = $msg;
				return false;
			}
		}

		// accepted columns are standard Ads columns + extra fields columns
		$accepted = array_merge(array_keys($this->types), array_keys($this->extra_fields), $this->ignored);
		$unknown = array_diff($header, $accepted);

		if (!empty($unknown)) {
			$msg = __( "Import can't continue. Unknown column(s) specified(s):", 'AWPCP' );
			$msg.= '<br/>' . join(', ', $unknown);
			$errors[] = $msg;
			return false;
		}

		return true;
	}

	/**
	 * @param $header	array of columns in the CSV file
	 * @param $csv		two dimensional array of data extracted from CSV file
	 * @param $zip		true if a ZIP file with images was included
	 */
	private function import_ads($header, $csv, $import_dir, &$errors, &$messages) {
		global $wpdb;

		$region_columns = array( 'city', 'state', 'country', 'county_village' );

		$test_import = $this->options['test-import'];
		$images_created = array();

		foreach ($csv as $k => $data) {
			$row = $k+1;

			$columns = array();
			$values = array();
			$placeholders = array();
			$region = array();

			$email = awpcp_array_data('contact_email', '', $data);
			$category = awpcp_array_data('category_name', '', $data);
			list($category_id, $category_parent_id) = $this->get_category_id($category);

			// if ($category == 0) {
			// 	$msg = __('Category name not found at row number %d', 'AWPCP');
			// 	$msg = sprintf($msg, $row);
			// 	$this->rejected[$row] = true;
			// 	$errors[] = $msg;
			// 	break;
			// }

			foreach ($this->columns as $key => $column) {
				$value = awpcp_array_data($key, '', $data);

				$_errors = array();
				if ($key == 'username') {
					$value = awpcp_csv_importer_get_user_id($value, $email, $row, $_errors, $messages);
				} else if ($key == 'category_name') {
					$value = $category_id;
				} else if ($key == 'category_parent') {
					$value = $category_parent_id;
				} else {
					$value = $this->parse($value, $key, $row, $_errors);
				}

				if ( $key == 'username' && empty( $value ) && ! empty( $_errors ) ) {
					$this->rejected[$row] = true;
					$errors = array_merge( $errors, $_errors );
					break;
				}

				// if there was an error getting a value for this field,
				// but the field wasn't included in the CSV, skip and mark
				// the row as good
				if ( $value === false && ! in_array( $key, $header ) ) {
					$this->rejected[$row] = false;
					continue;
				}

				array_splice( $errors, count( $errors ), 0, $_errors );

				// missing value, mark row as bad
				if (strlen($value) === 0 && in_array($key, $this->required)) {
					$msg = __( 'Required value <em>%s</em> missing at row number: %d', 'AWPCP' );
					$msg = sprintf($msg, $key, $row);
					$this->rejected[$row] = true;
					$errors[] = $msg;
					break;
				}

				if ( in_array( $key, $region_columns ) ) {
					if ( $key == 'county_village' ) {
						$region['county'] = $value;
					} else {
						$region[ $key ] = $value;
					}
				} else {
					$placeholders[] = empty($this->types[$key]) ? '%d' : '%s';
					$values[] = $value;
					$columns[] = $column;
				}
			}

			foreach ($this->auto_columns as $key => $value) {
				if ($value == '?') {
					$value = $this->parse($value, $key, $row, $errors);
				}

				$columns[] = $key;
				$placeholders[] = empty($this->auto_columns_types[$key]) ? '%d' : '%s';
				$values[] = empty($this->auto_columns_types[$key]) ? 0 : $value;
			}

			foreach ($this->extra_fields as $field) {
				$name = $field->field_name;

				// validate only extra fields present in the CSV file
				if (!isset($data[$name])) {
					continue;
				}

				$validate = $field->field_validation;
				$type = $field->field_input_type;
				$options = $field->field_options;
				$category = $field->field_category;

				$enforce = in_array($category_id, $category);

				$value = awpcp_validate_extra_field($name, $data[$name], $row, $validate, $type, $options, $enforce, $errors);

				// we found an error, let's skip this row
				if ($value === false) {
					$this->rejected[$row] = true;
					break;
				}

				switch ($field->field_mysql_data_type) {
					case 'VARCHAR':
					case 'TEXT':
						$placeholders[] = '%s';
						break;
					case 'INT':
						$placeholders[] = '%d';
						break;
					case 'FLOAT':
						$placeholders[] = '%f';
						break;
				}

				$columns[] = $name;
				$values[] = $value;
			}

			if ( $import_dir ) {
				$image_names = explode( ';', $data['images'] );
				$images = $this->import_images( $image_names, $row, $import_dir, $errors );
				$this->images_imported += count( $images );
				// save created images to be deleted later, if test mode is on
				array_splice( $images_created, 0, 0, $images );
			} else {
				$images = array();
			}

			// if there was an error, skip this row and try to import the next one
			if (awpcp_array_data($row, false, $this->rejected)) {
				$this->ads_rejected++;
				continue;
			}

			$sql = 'INSERT INTO ' . AWPCP_TABLE_ADS . ' ';
			$sql.= '( ' . join(', ', $columns) . ' ) VALUES ( ' . join(', ', $placeholders) . ' ) ';

			$sql = $wpdb->prepare($sql, $values);

			if ($test_import) {
				$inserted_id = 5;
			} else {
				$wpdb->query($sql);
				$inserted_id = $wpdb->insert_id;
			}

			if ( !empty( $region ) ) {
				$this->save_regions( $region, $inserted_id, $row, $errors );
			}

			if ( !empty( $images ) ) {
				$this->save_images( $images, $inserted_id, $row, $errors );
			}

			$this->ads_imported++;
		}

		if ( $import_dir ) {
			$this->remove_images( $import_dir, $images_created );
		}
	}

	private function extract_images($filename, &$errors=array(), &$messages=array()) {
		global $current_user;
		get_currentuserinfo();

		list($images_dir, $thumbs_dir) = awpcp_setup_uploads_dir();
		$import_dir = str_replace('thumbs', 'import', $thumbs_dir);
		$import_dir = $import_dir . $current_user->ID . '-' . time();

		$owner = fileowner($images_dir);

		if (!is_dir($import_dir)) {
			umask(0);
			mkdir($import_dir, awpcp_directory_permissions(), true);
			chown($import_dir, $owner);
		}

		$import_dir = $this->prepare_import_dir();

		require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');

		$archive = new PclZip($filename);
		$archive_files = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING);

		// Is the archive valid?
		if (!is_array($archive_files)) {
			$errors[] = __('Incompatible ZIP Archive', 'AWPCP');
			return false;
		}

		if (0 == count($archive_files)) {
			$errors[] = __('Empty ZIP Archive', 'AWPCP');
			return false;
		}

		// Extract the files from the zip
		foreach ($archive_files as $file) {
			if ($file['folder'])
				continue;

			// Don't extract the OS X-created __MACOSX directory files
			if ('__MACOSX/' === substr($file['filename'], 0, 9))
				continue;

			if ($fh = fopen(trailingslashit($import_dir) . $file['filename'], 'w')) {
				fwrite($fh, $file['content']);
				fclose($fh);
			} else {
				$msg = __('Could not write temporary file %s', 'AWPCP');
				$errors[] = sprintf($msg, $file['filename']);
				return false;
			}
		}

		return $import_dir;
	}

	private function prepare_import_dir() {
		$current_user = wp_get_current_user();

		list( $images_dir, $thumbnails_dir ) = awpcp_setup_uploads_dir();
		$import_dir = str_replace( 'thumbs', 'import', $thumbnails_dir );
		$import_dir = $import_dir . $current_user->ID . '-' . time();

		$owner = fileowner( $images_dir );

		if ( !is_dir( $import_dir ) ) {
			umask( 0 );
			@mkdir( $import_dir, awpcp_directory_permissions(), true );
			@chown( $import_dir, $owner );
		}

		return file_exists( $import_dir ) ? $import_dir : false;
	}

	public function unzip($file, &$errors=array()) {
		if ( !file_exists( $file ) ) {
			$message = __( 'File %s does not exists.', 'AWPCP' );
			$errors[] = sprintf( $message, $file );
			return false;
		}

		$import_dir = $this->prepare_import_dir();

		if ( false === $import_dir ) {
			$message = __( 'Import directory %s does not exists.', 'AWPCP' );
			$errors[] = sprintf( $message, $import_dir );
			return false;
		}

		require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

		$archive = new PclZip( $file );
		$items = $archive->extract( PCLZIP_OPT_EXTRACT_AS_STRING );
		$files = array();

		if ( !is_array( $items ) ) {
			$errors[] = __( 'Incompatible ZIP Archive', 'AWPCP' );
			return false;
		}

		if ( 0 === count( $items ) ) {
			$errors[] = __( 'Empty ZIP Archive', 'AWPCP' );
			return false;
		}

		foreach ( $items as $item ) {
			// ignore folder and don't extract the OS X-created __MACOSX directory files
			if ( $item['folder'] || '__MACOSX/' === substr( $item['filename'], 0, 9 ) ) {
				continue;
			}

			// don't extract files with a filename starting with . (like .DS_Store)
			if ( '.' === substr( basename( $item['filename'] ), 0, 1 ) ) {
				continue;
			}

			$path = trailingslashit( $import_dir ) . $item['filename'];

			// if file is inside a directory, create it first
			if ( dirname( $item['filename'] ) !== '.' ) {
				@mkdir( $import_dir . '/' . dirname( $item['filename'] ), awpcp_directory_permissions(), true );
			}

			// extract file
			if ( $h = @fopen( $path, 'w' ) ) {
				fwrite( $h, $item['content'] );
				fclose( $h );
			} else {
				$message = __( 'Could not write temporary file %s', 'AWPCP' );
				$errors[] = sprintf( $message, $path );
			}

			if ( file_exists( $path ) ) {
				$files[] = array(
					'path' => $path,
					'filename' => $item['filename'],
				);
			}
		}

		return $files;
	}

	/**
	 * TODO: handle test imports
	 */
	private function import_images($images, $row, $import_dir, &$errors) {
		$test_import = $this->options['test-import'];

		list( $images_dir, $thumbnails_dir ) = awpcp_setup_uploads_dir();
		list( $min_width, $min_height, $min_size, $max_size ) = awpcp_get_image_constraints();

		$import_dir = trailingslashit($import_dir);

		$entries = array();
		foreach (array_filter($images) as $filename) {
			$tmpname = $import_dir . $filename;

			$uploaded = awpcp_upload_image_file($images_dir, basename($filename), $tmpname, $min_size, $max_size, $min_width, $min_height, false);

			if (is_array($uploaded) && isset($uploaded['filename'])) {
				$entries[] = $uploaded;
			} else {
				$errors[] = sprintf(__('Row %d. %s', 'AWPCP'), $row, $uploaded);
				$this->rejected[$row] = true;
			}
		}

		return $entries;
	}

	private function save_regions($region, $ad_id, $row, &$errors) {
		if ( ! $this->options['test-import'] ) {
			$ad = AWPCP_Ad::find_by_id( $ad_id );
            awpcp_basic_regions_api()->update_ad_regions( $ad, array( $region ), 1 );
		}
	}

	private function save_images($entries, $adid, $row, &$errors) {
		global $wpdb;

		$test_import = $this->options['test-import'];
		$media_api = awpcp_media_api();

		foreach ($entries as $entry) {
            $extension = awpcp_get_file_extension( $entry['filename'] );
            $mime_type = sprintf( 'image/%s', $extension );

			$data = array(
				'ad_id' => $adid,
				'name' => $entry['filename'],
				'path' => $entry['filename'],
				'mime_type' => $mime_type,
				'enabled' => true,
				'is_primary' => false,
			);

			$result = $test_import || $media_api->create( $data );

			if ($result === false) {
				$msg = __("Could not save the information to the database for %s in row %d", 'AWPCP');
				$errors[] = sprintf($msg, $entry['original'], $row);
			}
		}
	}

	private function remove_images($import_dir, $images=array()) {
		list($images_dir, $thumbs_dir) = awpcp_setup_uploads_dir();

		$test_import = $this->options['test-import'];

		if ($test_import) {
			foreach ($images as $image) {
				$filename = $image['filename'];
				if (file_exists($images_dir . $filename))
					unlink($images_dir . $filename);
				if (file_exists($thumbs_dir . $filename))
					unlink($thumbs_dir . $filename);
			}
		}

		awpcp_rmdir( $import_dir );
	}

	private function get_csv_data($fileName, $maxLimit=1000) {
		$ini = ini_get('auto_detect_line_endings');
		ini_set('auto_detect_line_endings', true);

		$data = array();
		$csv = fopen($fileName, 'r');

		while ($row = fgetcsv($csv)) {
			$data[] = $row;
		}

		ini_set('auto_detect_line_endings', $ini);

		return $data;

		// $csvData = file_get_contents($fileName);
		// $order   = array("\r\n", "\n", "\r");
		// $csvData = str_replace($order, "\n", $csvData);
		// $rows = split ("[\n]", $csvData);
		// $i=0;

		// for($i=0; $i<count($rows) && $i<$maxLimit; $i++) {
		// 	$row = $rows[$i] . "\n";
		// 	$len = strlen($row);
		// 	$cell = '';
		// 	$col=0;
		// 	$escaped = false;

		// 	for ($j=0; $j<$len; $j++) {
		// 		if ($row[$j] == '"') {
		// 			if ($j+1<$len && $row[$j+1] == '"') {
		// 				$cell .= $row[$j];
		// 				$j++;
		// 				continue;
		// 			}
		// 			$escaped = !$escaped;
		// 			continue;
		// 		}

		// 		if(!$escaped) {
		// 			// if($row[$j] == ',' || $row[$j] == ';' || $row[$j] == '\r' || $row[$j] == '\n' || $j==$len-1) {
		// 			if($row[$j] == ',' || $row[$j] == '\r' || $row[$j] == '\n' || $j==$len-1) {
		// 				if($j==$len-1) {
		// 					$cell .= $row[$j];
		// 				}
		// 				$data[$i][$col] = trim($cell, "\r\n");
		// 				$cell = '';
		// 				$col++;
		// 				$escaped = false;
		// 				continue;
		// 			}
		// 		}

		// 		$cell .= $row[$j];
		// 	}

		// 	// $cols = split ("[,|;]", $rows[$i]);
		// 	// for($j=0; $j<count($cols); $j++) {
		// 	//   $data[$i][$j] = $cols[$j];
		// 	// }
		// }

		// return $data;
	}

	private function get_category_id($name) {
		global $wpdb;

		$auto = $this->options['autocreate-categories'];
		$test = $this->options['test-import'];

		$sql = 'SELECT category_id, category_parent_id FROM ' . AWPCP_TABLE_CATEGORIES . ' ';
		$sql.= 'WHERE category_name = %s';
		$sql = $wpdb->prepare($sql, $name);

		$category = $wpdb->get_row($sql, ARRAY_N);

		if (is_null($category) && $auto && !$test) {
			$sql = 'INSERT INTO ' . AWPCP_TABLE_CATEGORIES . ' ';
			$sql.= '(category_parent_id, category_name, category_order) VALUES (0, %s, 0)';
			$sql = $wpdb->prepare($sql, $name);

			$wpdb->query($sql);

			return array($wpdb->insert_id, 0);
		} else if (!is_null($category)) {
			return $category;
		} else if ($auto && $test) {
			return array(5, 0);
		}

		return false;
	}

	private function parse($val, $key, $row_num, &$errors) {
		$start_date = $this->options['start-date'];
		$end_date = $this->options['end-date'];
		$import_date_format = $this->options['date-format'];
		$date_sep = $this->options['date-separator'];
		$time_sep = $this->options['time-separator'];

		if ($key == "item_price") {
			// numeric validation
			if (is_numeric($val)) {
				// AWPCP stores Ad prices using an INT column (WTF!) so we need to
				// store 99.95 as 9995 and 99 as 9900.
				return $val * 100;
			} else {
				$errors[] = sprintf( __( "Item price non numeric at row number %s", 'AWPCP' ), $row_num );
				$this->rejected[$row_num] = true;
			}
		} else if ($key == "start_date") {
			// TODO: validation
			if (!empty($val)) {
				$val = $this->parse_date($val, $import_date_format, $date_sep, $time_sep);
				if (empty($val) || $val == null) {
					$errors[] = "Invalid Start date at row number: $row_num";
					$this->rejected[$row_num] = true;
				}
				return $val;
			}
			if (empty($start_date)) {
				// $date = new DateTime();
				// $val = $date->format( 'Y-m-d' );
				$errors[] = sprintf( __("Start date missing (alternately you can specify the default start date) at row number %s", 'AWPCP' ), $row_num );
				$this->rejected[$row_num] = true;
			} else {
				// TODO: validation
				$val = $this->parse_date($start_date, 'us_date', $date_sep, $time_sep); // $start_date;
			}
			return $val;
		} else if ($key == "end_date") {
			// TODO: validation
			if (!empty($val)) {
				$val = $this->parse_date($val, $import_date_format, $date_sep, $time_sep);
				if (empty($val) || $val == null) {
					$errors[] = sprintf( __( "Invalid End date at row number: %s", 'AWPCP' ), $row_num );
					$this->rejected[$row_num] = true;
				}
				return $val;
			}
			if (empty($end_date)) {
				// $date = new DateTime();
				// $val = $date->format( 'Y-m-d' );
				$errors[] = sprintf( __( "End date missing (alternately you can specify the default end date) at row number %s", 'AWPCP' ), $row_num );
				$this->rejected[$row_num] = true;
			} else {
				// TODO: validation
				$val = $this->parse_date($end_date, 'us_date', $date_sep, $time_sep); // $end_date;
			}
			return $val;
		} else if ($key == "ad_postdate") {
			if (empty($start_date)) {
				$date = new DateTime();
				$val = $date->format('Y-m-d');
			} else {
				// TODO: validation
				$val = $this->parse_date($start_date, 'us_date', $date_sep, $time_sep, 'Y-m-d'); // $start_date;
			}
			return $val;
		} else if ($key == "ad_last_updated") {
			$date = new DateTime();
			// $date->setTimezone( $timezone );
			$val = $date->format( 'Y-m-d' );
			return $val;
		} else if (!empty($val)) {
			return $val;
		}
		return false;
	}

	public function parse_date($val, $date_time_format, $date_separator, $time_separator, $format = "Y-m-d H:i:s") {
		$date_formats = array(
			'us_date' => array(
				array('%m', '%d', '%y'), // support both two and four digits years
				array('%m', '%d', '%Y'),
			),
			'uk_date' => array(
				array('%d', '%m', '%y'),
				array('%d', '%m', '%Y'),
			)
		);

		$date_formats['us_date_time'] = $date_formats['us_date'];
		$date_formats['uk_date_time'] = $date_formats['uk_date'];

		if (in_array($date_time_format, array('us_date_time', 'uk_date_time')))
			$suffix = join($time_separator, array('%H', '%M', '%S'));
		else
			$suffix = '';

		$date = null;
		foreach ($date_formats[$date_time_format] as $_format) {
			$_format = trim(sprintf("%s %s", join($date_separator, $_format), $suffix));
			$parsed = awpcp_strptime( $val, $_format );
			if ($parsed && empty($parsed['unparsed'])) {
				$date = $parsed;
				break;
			}
		}

		if (is_null($date))
			return null;

		$datetime = new DateTime();

		try {
			$datetime->setDate($parsed['tm_year'] + 1900, $parsed['tm_mon'] + 1, $parsed['tm_mday']);
			$datetime->setTime($parsed['tm_hour'], $parsed['tm_min'], $parsed['tm_sec']);
		} catch (Exception $ex) {
			echo "Exception: " . $ex->getMessage();
		}

	    return $datetime->format($format);
	}
}


function is_valid_date($month, $day, $year) {
	if (strlen($year) != 4)
		return false;
	return checkdate($month, $day, $year);
}


/**
 * Validate extra field values and return value.
 *
 * @param name        field name
 * @param value       field value in CSV file
 * @param row         row number in CSV file
 * @param validate    type of validation
 * @param type        type of input field (Input Box, Textarea Input, Checkbox,
 *                                         SelectMultiple, Select, Radio Button)
 * @param options     list of options for fields that accept multiple values
 * @param enforce     true if the Ad that's being imported belongs to the same category
 *                    that the extra field was assigned to, or if the extra field was
 *                    not assigned to any category.
 *                    required fields may be empty if enforce is false.
 */
function awpcp_validate_extra_field($name, $value, $row, $validate, $type, $options, $enforce, &$errors) {
	$validation_errors = array();
	$serialize = false;

	$list = null;

	switch ($type) {
		case 'Input Box':
		case 'Textarea Input':
			// nothing special here, proceed with validation
			break;

		case 'Checkbox':
		case 'Select Multiple':
			// value can be any combination of items from options list
			$msg = sprintf( __("Extra Field %s's value is not allowed in row %d. Allowed values are: %%s", 'AWPCP'), $name, $row );
			$list = explode( ';', $value );
			$serialize = true;

		case 'Select':
		case 'Radio Button':
			$list = is_array($list) ? $list : array($value);

			if (!isset($msg)) {
				$msg = sprintf( __("Extra Field %s's value is not allowed in row %d. Allowed value is one of: %%s", 'AWPCP'), $name, $row );
			}

			// only attempt to validate if the field is required (has validation)
			foreach ($list as $item) {
				if (empty($item)) {
					continue;
				}
				if (!in_array($item, $options)) {
					$msg = sprintf($msg, join(', ', $options));
					$validation_errors[] = $msg;
				}
			}

			// extra fields multiple values are stored serialized
			if ( $serialize ) {
				$value = maybe_serialize( $list );
			}

			break;

		default:
			break;
	}

	if (!empty($validation_errors)) {
		array_splice( $errors, count( $errors ), 0, $validation_errors );
		return false;
	}

	$list = is_array($list) ? $list : array($value);

	foreach ($list as $k => $item) {
		if (!$enforce && empty($item)) {
			continue;
		}

		switch ($validate) {
			case 'missing':
				if (empty($value)) {
					$validation_errors[] = "Extra Field $name is required in row $row.";
				}
				break;

			case 'url':
				if (!isValidURL($item)) {
					$validation_errors[] = "Extra Field $name must be a valid URL in row $row.";
				}
				break;

			case 'email':
				$regex = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
				if (!eregi($regex, $item)) {
					$validation_errors[] = "Extra Field $name must be a valid email address in row $row.";
				}
				break;

			case 'numericdeci':
				if (!is_numeric($item)) {
					$validation_errors[] = "Extra Field $name must be a number in row $row.";
				}
				break;

			case 'numericnodeci':
				if (!ctype_digit($item)) {
					$validation_errors[$name] = "Extra Field $name must be an integer number in row $row.";
				}
				break;

			default:
				break;
		}
	}

	if (!empty($validation_errors)) {
		array_splice( $errors, count( $errors ), 0, $validation_errors );
		return false;
	}

	return $value;
}


/**
 * Attempts to find a user by its username or email. If a user can't be
 * found one will be created.
 *
 * @param $username string 	User's username
 * @param $email 	string 	User's email address
 * @param $row 		int 	The index of the row being processed
 * @param $errors 	array 	Used to pass errors back to the caller.
 * @param $messages array 	Used to pass messages back to the caller
 *
 * @return User ID or false on error
 */
function awpcp_csv_importer_get_user_id($username, $email, $row, &$errors=array(), &$messages=array()) {
	global $test_import;
	global $assign_user;
	global $assigned_user;

	static $users = array();

	if (!$assign_user) {
		return '';
	}

	if (isset($users[$username])) {
		return $users[$username];
	}

	$user = empty($username) ? false : get_user_by('login', $username);
	if ($user === false) {
		$user = empty($email) ? false : get_user_by('email', $email);
	} else {
		$users[$user->user_login] = $user->ID;
		return $user->ID;
	}
	if (is_object($user)) {
		$users[$user->user_login] = $user->ID;
		return $user->ID;
	}

	// a default user was selected, do not attempt to create a new one
	if ($assigned_user > 0) {
		return $assigned_user;
	}

	if (empty($username)) {
		$errors[] = sprintf(__("Username is required in row %s. Please include a username or selected a default user.", 'AWPCP'), $row);
		return false;
	} else if (empty($email)) {
		$errors[] = sprintf(__("Contact email is required in row %s.", 'AWPCP'), $row);
		return false;
	}

	$password = wp_generate_password(8, false, false);

	if ($test_import) {
		$result = 1; // fake it!
	} else {
		$result = wp_create_user($username, $password, $email);
	}

	if (is_wp_error($result)) {
		$errors[] = $result->get_error_message();
		return false;
	}
	$users[$username] = $result;

	$message = __("A new user '%s' with email address '%s' and password '%s' was created for row %d.", 'AWPCP');
	$messages[] = sprintf($message, $username, $email, $password, $row);

	return $result;
}
