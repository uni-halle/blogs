<?php

class AWPCP_CSV_Importer_Delegate {

    private $import_session;
    private $db;

    private $accepted_columns = array(
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
        'username' => 'user_id',
    );

    private $region_columns = array( 'city', 'state', 'country', 'county_village' );

    private $required_columns = array(
        'title',
        'details',
        'contact_name',
        'contact_email',
        'category_name',
    );

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

    private $extra_fields = null;

    public function __construct( $import_session, $db ) {
        $this->import_session = $import_session;
        $this->db = $db;
    }

    public function import_row( $row_data ) {
        $columns = array();
        $values = array();
        $placeholders = array();
        $region = array();

        $contact_email = awpcp_array_data( 'contact_email', '', $row_data );
        $category_name = awpcp_array_data( 'category_name', '', $row_data );
        list( $category_id, $category_parent_id ) = $this->get_category_id( $category_name );

        foreach ( $this->accepted_columns as $column_name => $database_column ) {
            // DO NOT USE awpcp_array_data BECAUSE IT WILL TREAT '0' AS
            // AN EMPTY VALUE
            $value = isset( $row_data[ $column_name ] ) ? $row_data[ $column_name ] : '';

            $_errors = array();
            if ( $column_name == 'username' ) {
                $value = $this->get_user_id( $value, $contact_email );

                if ( empty( $value ) && ! empty( $_errors ) ) {
                    $exception = new AWPCP_CSV_Importer_Exception();
                    $exception->setErrors( $_errors );

                    throw $exception;
                }
            } else if ( $column_name == 'category_name' ) {
                $value = $category_id;
            } else if ( $column_name == 'category_parent' ) {
                $value = $category_parent_id;
            } else {
                $value = $this->parse( $value, $column_name );
            }

            // missing value, mark row as bad
            if ( strlen( $value ) === 0 && in_array( $column_name, $this->required_columns ) ) {
                $message = __( 'Missing value for required column <em>%s</em>.', 'another-wordpress-classifieds-plugin' );
                $message = sprintf( $message, $column_name );

                throw new AWPCP_CSV_Importer_Exception( $message );
            }

            // if there was an error getting a value for this field,
            // but the field wasn't included in the CSV, skip and mark
            // the row as good
            if ( $value === false && ! isset( $row_data[ $column_name ] ) ) {
                continue;
            }

            if ( in_array( $column_name, $this->region_columns ) ) {
                if ( $column_name == 'county_village' ) {
                    $region['county'] = $value;
                } else {
                    $region[ $column_name ] = $value;
                }
            } else {
                $placeholders[] = empty( $this->types[ $column_name ] ) ? '%d' : '%s';
                $values[] = $value;
                $columns[] = $database_column;

                if ( $column_name == 'contact_phone' ) {
                    $placeholders[] = '%s';
                    $values[] = awpcp_get_digits_from_string( $value );
                    $columns[] = 'phone_number_digits';
                }
            }
        }

        foreach ( $this->auto_columns as $column_name => $value ) {
            if ( $value == '?' ) {
                $value = $this->parse( $value, $column_name );
            }

            $columns[] = $column_name;
            $placeholders[] = empty( $this->auto_columns_types[ $column_name ] ) ? '%d' : '%s';
            $values[] = empty( $this->auto_columns_types[ $column_name ]) ? 0 : $value;
        }

        foreach ( $this->get_extra_fields() as $field ) {
            $name = $field->field_name;

            // validate only extra fields present in the CSV file
            if ( ! isset( $row_data[ $name ] ) ) {
                continue;
            }

            $validate = $field->field_validation;
            $type = $field->field_input_type;
            $options = $field->field_options;
            $category = $field->field_category;
            $errors = array();
            $enforce = in_array( $category_id, $category ) && $field->required;

            $value = awpcp_validate_extra_field(
                $name,
                $row_data[ $name ],
                $validate,
                $type,
                $options,
                $enforce,
                $errors
            );

            if ( ! empty( $errors ) ) {
                throw new AWPCP_CSV_Importer_Exception( array_shift( $errors ) );
            }

            switch ( $field->field_mysql_data_type ) {
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

        $image_names = array_filter( explode( ';', $row_data['images'] ) );

        if ( $image_names ) {
            $images = $this->import_images( $image_names );
        } else {
            $images = array();
        }

        // $this->images_imported += count( $images );
        // // save created images to be deleted later, if test mode is on
        // array_splice( $images_created, 0, 0, $images );

        $sql = 'INSERT INTO ' . AWPCP_TABLE_ADS . ' ';
        $sql.= '( ' . implode( ', ', $columns ) . ' ) VALUES ( ' . implode( ', ', $placeholders ) . ' ) ';

        $sql = $this->db->prepare( $sql, $values);

        if ( $this->import_session->is_test_mode_enabled() ) {
            $inserted_id = 5;
        } else {
            $this->db->query( $sql );
            $inserted_id = $this->db->insert_id;
        }

        if ( !empty( $region ) ) {
            $this->save_regions( $region, $inserted_id );
        }

        if ( $images && $this->import_session->is_test_mode_enabled() ) {
            $this->delete_imported_images( $images );
        } else if ( $images ) {
            $this->save_images( $images, $inserted_id );
        }

        if ( ! $this->import_session->is_test_mode_enabled() ) {
            do_action( 'awpcp-listing-imported', $inserted_id, $row_data );
        }
    }

    private function get_category_id( $category_name ) {
        $auto = $this->import_session->get_param( 'create_missing_categories', false );
        $test = $this->import_session->is_test_mode_enabled();

        $sql = 'SELECT category_id, category_parent_id FROM ' . AWPCP_TABLE_CATEGORIES . ' ';
        $sql.= 'WHERE category_name = %s';
        $sql = $this->db->prepare( $sql, $category_name );

        $category = $this->db->get_row( $sql, ARRAY_N );

        if ( is_null( $category ) && $auto && ! $test ) {
            $sql = 'INSERT INTO ' . AWPCP_TABLE_CATEGORIES . ' ';
            $sql.= '(category_parent_id, category_name, category_order) VALUES (0, %s, 0)';
            $sql = $this->db->prepare( $sql, $category_name );

            $this->db->query( $sql );

            return array( $this->db->insert_id, 0 );
        } else if ( ! is_null( $category ) ) {
            return $category;
        } else if ( $auto && $test ) {
            return array( 5, 0 );
        }

        return false;
    }

    /**
     * Attempts to find a user by its username or email. If a user can't be
     * found one will be created.
     *
     * @param $username string  User's username
     * @param $email    string  User's email address
     * @param $row      int     The index of the row being processed
     * @param $errors   array   Used to pass errors back to the caller.
     * @param $messages array   Used to pass messages back to the caller
     *
     * @return User ID or false on error
     */
    public function get_user_id( $username, $email ) {
        static $users = array();

        if ( ! $this->import_session->get_param( 'assign_listings_to_user', false ) ) {
            return '';
        }

        if ( isset( $users[ $username ] ) ) {
            return $users[ $username ];
        }

        $user = empty( $username ) ? false : get_user_by( 'login', $username );

        if ( $user === false ) {
            $user = empty( $email ) ? false : get_user_by( 'email', $email );
        } else {
            $users[ $user->user_login ] = $user->ID;
            return $user->ID;
        }

        if ( is_object( $user ) ) {
            $users[ $user->user_login ] = $user->ID;
            return $user->ID;
        }

        $default_user = $this->import_session->get_param( 'default_user' );

        // a default user was selected, do not attempt to create a new one
        if ( $default_user > 0) {
            return $default_user;
        }

        if ( empty( $username ) ) {
            $message = __( "No user could be assigned to this listing. A new user couldn't be created because the username column has an empty value. Please include a username or select a default user.", 'another-wordpress-classifieds-plugin' );
            throw new AWPCP_CSV_Importer_Exception( $message );
        } else if ( empty( $email ) ) {
            $message = __( "No user could be assigned to this listing. A new user couldn't be created because the contact_email column has an empty value. Please include a contact_email or select a default user.", 'another-wordpress-classifieds-plugin' );
            throw new AWPCP_CSV_Importer_Exception( $message );
        }

        $password = wp_generate_password( 8, false, false );

        if ( $this->import_session->is_test_mode_enabled() ) {
            $result = 1; // fake it!
        } else {
            $result = wp_create_user( $username, $password, $email );
        }

        if ( is_wp_error( $result ) ) {
            $message = __( 'No user could be assigned to this listing. Our attempt to create a new user failed with the following error: <error-message>.', 'another-wordpress-classifieds-plugin' );
            $message = str_replace( '<error-message>', $result->get_error_message(), $message );

            throw new AWPCP_CSV_Importer_Exception( $message );
        }

        $users[ $username ] = $result;

        $message = __( "A new user '%s' with email address '%s' and password '%s' was created.", 'another-wordpress-classifieds-plugin' );
        $messages[] = sprintf( $message, $username, $email, $password );

        return $result;
    }

    private function parse( $val, $key ) {
        $start_date = $this->import_session->get_param( 'default_start_date' );
        $end_date = $this->import_session->get_param( 'default_end_date' );
        $import_date_format = $this->import_session->get_param( 'date_format' );
        $date_sep = $this->import_session->get_param( 'date_separator' );
        $time_sep = $this->import_session->get_param( 'time_separator' );

        if ( $key == "item_price" ) {
            if ( empty( $val ) ) {
                return 0;
            }

            // numeric validation
            if ( is_numeric( $val ) ) {
                // AWPCP stores Ad prices using an INT column (WTF!) so we need to
                // store 99.95 as 9995 and 99 as 9900.
                return $val * 100;
            }

            $message = __( 'Item price is not a numeric value.', 'another-wordpress-classifieds-plugin' );
            throw new AWPCP_CSV_Importer_Exception( $message );
        } else if ($key == "start_date") {
            // TODO: validation
            if ( ! empty( $val ) ) {
                $val = $this->parse_date( $val, $import_date_format, $date_sep, $time_sep );

                if ( empty( $val ) || $val == null ) {
                    $message = __( 'Invalid Start date.', 'another-wordpress-classifieds-plugin' );
                    throw new AWPCP_CSV_Importer_Exception( $message );
                }

                return $val;
            }

            if ( empty( $start_date ) ) {
                $message = __( 'Start date missing. You can define a default value for this column changing the import configuration.', 'another-wordpress-classifieds-plugin' );
                throw new AWPCP_CSV_Importer_Exception( $message );
            } else {
                // TODO: validation
                $val = $this->parse_date( $start_date, 'us_date', $date_sep, $time_sep ); // $start_date;
            }

            return $val;
        } else if ($key == "end_date") {
            // TODO: validation
            if ( ! empty( $val ) ) {
                $val = $this->parse_date( $val, $import_date_format, $date_sep, $time_sep );

                if ( empty( $val ) || $val == null ) {
                    $message = __( 'Invalid End date.', 'another-wordpress-classifieds-plugin' );
                    throw new AWPCP_CSV_Importer_Exception( $message );
                }

                return $val;
            }

            if ( empty( $end_date ) ) {
                $message = __( 'End date missing. You can define a default value for this column changing the import configuration.', 'another-wordpress-classifieds-plugin' );
                throw new AWPCP_CSV_Importer_Exception( $message );
            } else {
                // TODO: validation
                $val = $this->parse_date( $end_date, 'us_date', $date_sep, $time_sep ); // $end_date;
            }
            return $val;
        } else if ( $key == "ad_postdate" ) {
            if ( empty( $start_date ) ) {
                $date = new DateTime();
                $val = $date->format( 'Y-m-d' );
            } else {
                // TODO: validation
                $val = $this->parse_date( $start_date, 'us_date', $date_sep, $time_sep, 'Y-m-d' ); // $start_date;
            }

            return $val;
        } else if ( $key == "ad_last_updated" ) {
            $date = new DateTime();
            $val = $date->format( 'Y-m-d' );
            return $val;
        } else if ( ! empty( $val ) ) {
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
            $suffix = implode($time_separator, array('%H', '%M', '%S'));
        else
            $suffix = '';

        $date = null;
        foreach ($date_formats[$date_time_format] as $_format) {
            $_format = trim(sprintf("%s %s", implode($date_separator, $_format), $suffix));
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

    private function import_images( $filenames ) {
        $images_directory = $this->import_session->get_images_directory();

        if ( empty( $images_directory ) ) {
            throw new AWPCP_CSV_Importer_Exception( __( 'No images directory was configured. Are you sure you uploaded a ZIP file or defined a local directory?', 'another-wordpress-classifieds-plugin' ) );
        }

        list( $images_dir, $_ ) = awpcp_setup_uploads_dir();
        list( $min_width, $min_height, $min_size, $max_size ) = awpcp_get_image_constraints();

        $entries = array();

        foreach ( array_filter( $filenames ) as $filename ) {
            $uploaded = awpcp_upload_image_file(
                $images_dir,
                awpcp_unique_filename( $images_directory . DIRECTORY_SEPARATOR . $filename, basename( $filename ), array( $images_dir, $images_dir . 'images/', $images_dir . 'thumbs/' ) ),
                $images_directory . DIRECTORY_SEPARATOR . $filename,
                $min_size,
                $max_size,
                $min_width,
                $min_height,
                false
            );

            if ( is_array( $uploaded ) && isset( $uploaded['filename'] ) ) {
                $entries[] = $uploaded;
            } else {
                $message = __( 'An image could not be succesfully imported. The operation failed with the following error: <error-message>', 'another-wordpress-classifieds-plugin' );
                $message = str_replace( '<error-message>', $uploaded, $message );

                throw new AWPCP_CSV_Importer_Exception( $message );
            }
        }

        return $entries;
    }

    private function save_regions( $region, $ad_id ) {
        if ( ! $this->import_session->is_test_mode_enabled() ) {
            $ad = AWPCP_Ad::find_by_id( $ad_id );
            awpcp_basic_regions_api()->update_ad_regions( $ad, array( $region ), 1 );
        }
    }

    private function delete_imported_images( $images ) {
        list( $images_dir, $thumbs_dir ) = awpcp_setup_uploads_dir();

        foreach ( $images as $image ) {
            $basename = $image['filename'];

            $filename = awpcp_utf8_pathinfo( $basename, PATHINFO_FILENAME );
            $extension = awpcp_utf8_pathinfo( $basename, PATHINFO_EXTENSION );

            if ( file_exists( $images_dir . $basename ) ) {
                unlink( $images_dir . $basename );
            }

            if ( file_exists( $thumbs_dir . $basename ) ) {
                unlink( $thumbs_dir . $basename );
            }

            if ( file_exists( $images_dir . $filename . '-large.' . $extension ) ) {
                unlink( $images_dir . $filename . '-large.' . $extension );
            }

            if ( file_exists( $thumbs_dir . $filename . '-primary.' . $extension ) ) {
                unlink( $thumbs_dir . $filename . '-primary.' . $extension );
            }
        }
    }

    private function save_images( $entries, $adid ) {
        $media_api = awpcp_media_api();

        foreach ( $entries as $entry ) {
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

            if ( $media_api->create( $data ) === false ) {
                $message =__( 'Could not save the information for image <image> into the database.', 'another-wordpress-classifieds-plugin' );
                $message = str_replace( '<image>', $entry['original'], $message );

                throw new AWPCP_CSV_Importer_Exception( $message );
            }
        }
    }

    private function get_extra_fields() {
        if ( is_array( $this->extra_fields ) ) {
            return $this->extra_fields;
        }

        $this->extra_fields = array();

        if ( function_exists( 'awpcp_get_extra_fields' ) ) {
            foreach ( awpcp_get_extra_fields() as $field ) {
                $this->extra_fields[ $field->field_name ] = $field;
            }
        }

        return $this->extra_fields;
    }
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
function awpcp_validate_extra_field( $name, $value, $validate, $type, $options, $enforce, &$errors ) {
    $validation_errors = array();
    $serialize = false;

    $list = null;

    switch ( $type ) {
        case 'Input Box':
        case 'Textarea Input':
            // nothing special here, proceed with validation
            break;

        case 'Checkbox':
        case 'Select Multiple':
            // value can be any combination of items from options list
            $msg = sprintf( __( "The value for Extra Field %s's is not allowed. Allowed values are: %%s", 'another-wordpress-classifieds-plugin' ), $name );
            $list = explode( ';', $value );
            $serialize = true;

        case 'Select':
        case 'Radio Button':
            $list = is_array( $list ) ? $list : array( $value );

            if ( ! isset( $msg ) ) {
                $msg = sprintf( __( "The value for Extra Field %s's is not allowed. Allowed value is one of: %%s", 'another-wordpress-classifieds-plugin' ), $name, $row );
            }

            // only attempt to validate if the field is required (has validation)
            foreach ( $list as $item ) {
                if ( empty( $item ) ) {
                    continue;
                }
                if ( ! in_array( $item, $options ) ) {
                    $msg = sprintf( $msg, implode( ', ', $options ) );
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

    if ( ! empty( $validation_errors ) ) {
        array_splice( $errors, count( $errors ), 0, $validation_errors );
        return false;
    }

    $list = is_array( $list ) ? $list : array( $value );

    foreach ( $list as $k => $item ) {
        if ( ! $enforce && empty( $item ) ) {
            continue;
        }

        switch ( $validate ) {
            case 'missing':
                if ( empty( $value ) ) {
                    $validation_errors[] = "A value for Extra Field $name is required.";
                }
                break;

            case 'url':
                if ( ! isValidURL( $item ) ) {
                    $validation_errors[] = "The value for Extra Field $name must be a valid URL.";
                }
                break;

            case 'email':
                if ( ! awpcp_is_valid_email_address( $item ) ) {
                    $validation_errors[] = "The value for Extra Field $name must be a valid email address.";
                }
                break;

            case 'numericdeci':
                if ( ! is_numeric( $item ) ) {
                    $validation_errors[] = "The value for Extra Field $name must be a number.";
                }
                break;

            case 'numericnodeci':
                if ( ! ctype_digit( $item ) ) {
                    $validation_errors[ $name ] = "The value for Extra Field $name must be an integer number.";
                }
                break;

            default:
                break;
        }
    }

    if ( ! empty( $validation_errors ) ) {
        array_splice( $errors, count( $errors ), 0, $validation_errors );
        return false;
    }

    return $value;
}
