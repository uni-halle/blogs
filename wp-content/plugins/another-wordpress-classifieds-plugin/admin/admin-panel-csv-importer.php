<?php

function awpcp_admin_csv_importer() {
    return new AWPCP_Admin_CSV_Importer(
        awpcp_csv_import_sessions_manager(),
        awpcp_csv_importer_factory(),
        awpcp()->js,
        awpcp()->settings,
        awpcp_request()
    );
}

class AWPCP_Admin_CSV_Importer {

    private $import_session = false;

    private $import_sessions_manager;
    private $csv_importer_factory;
    private $javascript;
    private $settings;
    private $request;

    public function __construct( $import_sessions_manager, $csv_importer_factory, $javascript, $settings, $request ) {
        $this->import_sessions_manager = $import_sessions_manager;
        $this->csv_importer_factory = $csv_importer_factory;
        $this->javascript = $javascript;
        $this->settings = $settings;
        $this->request = $request;
    }

	public function scripts() {
		wp_enqueue_style('awpcp-jquery-ui');
  		wp_enqueue_script( 'awpcp-admin-import' );
	}

    public function dispatch() {
        echo $this->handle_request();
    }

    private function handle_request() {
        if ( $this->request->post( 'cancel' ) || $this->request->post( 'finish' ) ) {
            return $this->delete_current_import_session();
        }

        $import_session = $this->get_import_session();

        if ( ! is_null( $import_session ) && ( $import_session->is_ready() || $import_session->is_in_progress() ) ) {
            $step = 'execute';
        } else if ( ! is_null( $import_session ) ) {
            $step = 'configure';
        } else {
            $step = $this->request->get( 'step', 'upload-files' );
        }

        if ( $step == 'upload-files' ) {
            return $this->do_upload_files_step();
        } else if ( $step == 'configure' ) {
            return $this->do_configuration_step();
        } else if ( $step == 'execute' ) {
            return $this->do_execute_step();
        }
    }

    private function delete_current_import_session() {
        awpcp_rmdir( $this->get_import_session()->get_working_directory() );

        $this->import_sessions_manager->delete_current_import_session();

        return $this->show_upload_files_form();
    }

    private function get_import_session() {
        if ( $this->import_session === false ) {
            $this->import_session = $this->import_sessions_manager->get_current_import_session();
        }

        return $this->import_session;
    }

    private function do_upload_files_step() {
        if ( $this->request->post( 'upload_files' ) ) {
            return $this->upload_files();
        } else {
            return $this->show_upload_files_form();
        }
    }

    private function upload_files() {
        $import_session = $this->import_session = $this->import_sessions_manager->create_import_session();

        $working_directory = $this->get_working_directory( $import_session->get_id() );
        $images_directory = null;

        $form_data = array(
            'images_source' => $this->request->post( 'images_source' ),
            'local_path' => $this->request->post( 'local_path' ),
        );

        if ( $_FILES['csv_file']['error'] != UPLOAD_ERR_OK ) {
            list( $_, $error_message ) = awpcp_uploaded_file_error( $_FILES['csv_file'] );
            $form_errors['csv_file'] = $error_message;
        } else if ( substr( $_FILES['csv_file']['name'], -4 ) !== '.csv' ) {
            $form_errors['csv_file'] = __( "The uploaded file doesn't look like a CSV file. Please upload a valid CSV file.", 'another-wordpress-classifieds-plugin' );
        } else if ( ! @move_uploaded_file( $_FILES['csv_file']['tmp_name'], "$working_directory/source.csv" ) ) {
            $form_errors['csv_file'] = __( 'There was an error moving the uploaded CSV file to a proper location.', 'another-wordpress-classifieds-plugin' );
        }

        $uploads_dir = $this->settings->get_runtime_option( 'awpcp-uploads-dir' );

        if ( $form_data['images_source'] == 'zip' ) {
            if ( ! in_array( $_FILES['zip_file']['error'], array( UPLOAD_ERR_OK, UPLOAD_ERR_NO_FILE ) ) ) {
                list( $_, $error_message ) = awpcp_uploaded_file_error( $_FILES['zip_file'] );
                $form_errors['zip_file'] = $error_message;
            } else if ( $_FILES['zip_file']['error'] == UPLOAD_ERR_NO_FILE ) {
                // all good...
            } else if ( substr( $_FILES['zip_file']['name'], -4 ) !== '.zip' ) {
                $form_errors['zip_file'] = __( "The uploaded file doesn't look like a ZIP file. Please upload a valid ZIP file.", 'another-wordpress-classifieds-plugin' );
            } else if ( ! @move_uploaded_file( $_FILES['zip_file']['tmp_name'], "$working_directory/images.zip" ) ) {
                $form_errors['zip_file'] = __( 'There was an error moving the uploaded ZIP file to a proper location.', 'another-wordpress-classifieds-plugin' );
            }

            if ( ! isset( $form_errors['zip_file'] ) ) {
                require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

                $images_directory = $this->get_images_directory( $working_directory );

                $zip = new PclZip( "$working_directory/images.zip" );
                $zip_contents = $zip->extract( PCLZIP_OPT_EXTRACT_AS_STRING );

                if ( ! is_array( $zip_contents ) ) {
                    $form_errors['zip_file'] = __( 'Incompatible ZIP Archive', 'another-wordpress-classifieds-plugin' );
                } else if ( 0 === count( $zip_contents ) ) {
                    $form_errors['zip_file'] = __( 'Empty ZIP Archive', 'another-wordpress-classifieds-plugin' );
                }

                foreach ( $zip_contents as $item ) {
                    // ignore folder and don't extract the OS X-created __MACOSX directory files
                    if ( $item['folder'] || '__MACOSX/' === substr( $item['filename'], 0, 9 ) ) {
                        continue;
                    }

                    // don't extract files with a filename starting with . (like .DS_Store)
                    if ( '.' === substr( basename( $item['filename'] ), 0, 1 ) ) {
                        continue;
                    }

                    $path = $images_directory . DIRECTORY_SEPARATOR . $item['filename'];

                    // if file is inside a directory, create it first
                    if ( dirname( $item['filename'] ) !== '.' ) {
                        @mkdir( dirname( $path ), awpcp_directory_permissions(), true );
                    }

                    // extract file
                    if ( $file_handler = @fopen( $path, 'w' ) ) {
                        fwrite( $file_handler, $item['content'] );
                        fclose( $file_handler );
                    } else {
                        $message = __( 'Could not write temporary file %s', 'another-wordpress-classifieds-plugin' );
                        $form_errors['unzip'][] = sprintf( $message, $path );
                    }
                }
            }
        } else if ( $form_data['images_source'] == 'local' ) {
            $local_directory = realpath( $uploads_dir . DIRECTORY_SEPARATOR . str_replace( '..', '', $form_data['local_path'] ) );

            if ( strpos( $local_directory, $uploads_dir ) !== 0 || strpos( $local_directory, $uploads_dir ) === false ) {
                $form_errors['local_path'] = __( 'The specified directory is not a valid path.', 'another-wordpress-classifieds-plugin' );
            } else if ( ! is_dir( $local_directory ) ) {
                $form_errors['local_path'] = __( 'The specified directory does not exists.', 'another-wordpress-classifieds-plugin' );
            } else {
                $images_directory = $local_directory;
            }
        }

        if ( empty( $form_errors ) ) {
            $import_session->set_working_directory( $working_directory );
            $import_session->set_images_directory( $images_directory );

            $csv_importer = $this->csv_importer_factory->create_importer( $import_session );

            $import_session->set_data( 'number_of_rows', $csv_importer->count_rows() );
            $import_session->set_status( 'configuration' );

            $this->import_sessions_manager->update_current_import_session( $import_session );

            return $this->show_configuration_form();
        } else {
            return $this->show_upload_files_form( $form_data, $form_errors );
        }
    }

    private function get_working_directory( $session_id ) {
        list( $images_dir, $thumbnails_dir ) = awpcp_setup_uploads_dir();

        $import_dir = str_replace( 'thumbs', 'import', $thumbnails_dir );
        $working_directory = $import_dir . $session_id;

        if ( $this->create_directory( $working_directory ) ) {
            return $working_directory;
        } else {
            return false;
        }
    }

    private function create_directory( $directory ) {
        list( $images_dir, $_ ) = awpcp_setup_uploads_dir();

        if ( ! is_dir( $directory ) ) {
            umask( 0 );
            @mkdir( $directory, awpcp_directory_permissions(), true );
            @chown( $directory, fileowner( $images_dir ) );
        }

        return file_exists( $directory );
    }

    private function get_images_directory( $working_directory ) {
        $images_directory = $working_directory . DIRECTORY_SEPARATOR . 'images';

        if ( $this->create_directory( $images_directory ) ) {
            return $images_directory;
        } else {
            return false;
        }
    }

    private function show_upload_files_form( $form_data = array(), $form_errors = array() ) {
        $params = array(
            'form_data' => wp_parse_args( $form_data, array(
                'images_source' => 'none',
                'local_path' => '',
            ) ),
            'form_errors' => $form_errors,
        );

        $template = AWPCP_DIR . '/templates/admin/import-listings-admin-page-upload-files-form.tpl.php';

        return awpcp_render_template( $template, $params );
    }

    private function do_configuration_step() {
        if ( $this->request->post( 'configure' ) ) {
            return $this->save_configuration();
        } else {
            return $this->show_configuration_form();
        }
    }

    private function save_configuration() {
        $import_session = $this->get_import_session();

        if ( is_null( $import_session ) ) {
            return $this->show_upload_files_form();
        }

        if ( $this->request->post( 'test_import' ) ) {
            $import_session->set_mode( 'test' );
        } else {
            $import_session->set_mode( 'live' );
        }

        if ( $this->request->post( 'define_default_user' ) ) {
            $default_user = $this->request->post( 'default_user' );
        } else {
            $default_user = null;
        }

        $import_session->set_params( array(
            'date_format' => $this->request->post( 'date_format' ),
            'date_separator' => $this->request->post( 'date_separator' ),
            'time_separator' => $this->request->post( 'time_separator' ),
            'images_separator' => $this->request->post( 'images_separator' ),
            'create_missing_categories' => $this->request->post( 'create_missing_categories' ),
            'assign_listings_to_user' => $this->request->post( 'assign_listings_to_user' ),
            'default_user' => $default_user,
            'default_start_date' => $this->request->post( 'default_start_date' ),
            'default_end_date' => $this->request->post( 'default_end_date' ),
        ) );

        $import_session->set_status( 'ready' );

        $this->import_sessions_manager->update_current_import_session( $import_session );

        return $this->show_import_form();
    }

    private function show_configuration_form( $form_data = array(), $form_errors = array() ) {
        $import_session = $this->get_import_session();

        $form_data = array_merge( $form_data, $import_session->get_params() );

        $define_default_dates = ! empty( $form_data['default_start_date'] ) || ! empty( $form_data['default_end_date'] );
        $define_default_user = ! empty( $form_data['default_user'] );

        $params = array(
            'form_data' => wp_parse_args( $form_data, array(
                'define_default_dates' => $define_default_dates,
                'default_start_date' => '',
                'default_end_date' => '',
                'date_format' => 'us_date',
                'time_separator' => ':',
                'date_separator' => '/',
                'images_separator' => ';',
                'create_missing_categories' => false,
                'assign_listings_to_user' => true,
                'define_default_user' => $define_default_user,
                'default_user' => null,
            ) ),
            'form_errors' => $form_errors,
        );

        $template = AWPCP_DIR . '/templates/admin/import-listings-admin-page-configuration-form.tpl.php';

        return awpcp_render_template( $template, $params );
    }

    private function do_execute_step() {
        $import_session = $this->get_import_session();

        if ( $this->request->post( 'change_configuration' ) ) {
            $import_session->set_status( 'configuration' );
            $import_session->set_data( 'number_of_rows_imported', 0 );
            $import_session->set_data( 'number_of_rows_rejected', 0 );
            $import_session->set_data( 'last_row_processed', 0 );
            $import_session->clear_errors();

            $this->import_sessions_manager->update_current_import_session( $import_session );

            return $this->show_configuration_form();
        }

        return $this->show_import_form();
    }

    private function show_import_form() {
        $import_session = $this->get_import_session();

        $this->javascript->set( 'csv-import-session', array(
            'numberOfRows' => $import_session->get_number_of_rows(),
            'numberOfRowsImported' => $import_session->get_number_of_rows_imported(),
            'numberOfRowsRejected' => $import_session->get_number_of_rows_rejected(),
        ) );

        $this->javascript->localize( 'csv-import-session', array(
            'progress-report' => __( '(<percentage>) <number-of-rows-processed> of <number-of-rows> rows processed. <number-of-rows-imported> rows imported and <number-of-rows-rejected> rows rejected.', 'another-wordpress-classifieds-plugin' ),
            'message-description' => _x( '<message-type> in line <message-line>', 'description for messages used to show feedback for the Import Listings operation', 'another-wordpress-classifieds-plugin' )
        ) );

        $params = array( 'test_mode_enabled' => $import_session->is_test_mode_enabled() );

        if ( $import_session->is_test_mode_enabled() ) {
            $params['action_name'] = _x( 'Test Import', 'text for page subtitle and submit button', 'another-wordpress-classifieds-plugin' );
        } else {
            $params['action_name'] = _x( 'Import', 'text for page subtitle and submit button', 'another-wordpress-classifieds-plugin' );
        }

        $template = AWPCP_DIR . '/templates/admin/import-listings-admin-page-import-form.tpl.php';

        return awpcp_render_template( $template, $params );
    }
}
