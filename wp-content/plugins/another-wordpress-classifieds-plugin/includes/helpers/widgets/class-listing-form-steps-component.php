<?php

function awpcp_render_listing_form_steps( $selected_step, $transaction = null ) {
    return awpcp_listing_form_steps_componponent()->render( $selected_step, $transaction );
}

function awpcp_listing_form_steps_componponent() {
    return new AWPCP_ListingFormStepsComponent(
        awpcp_payments_api(),
        awpcp_listing_upload_limits(),
        awpcp()->settings,
        awpcp_request()
    );
}

class AWPCP_ListingFormStepsComponent {

    private $payments;
    private $upload_limits;
    private $settings;
    private $request;

    public function __construct( $payments, $upload_limits, $settings, $request ) {
        $this->payments = $payments;
        $this->upload_limits = $upload_limits;
        $this->settings = $settings;
        $this->request = $request;
    }

    public function render( $selected_step, $transaction ) {
        return $this->render_steps( $selected_step, $this->get_steps( $transaction ) );
    }

    private function get_steps( $transaction ) {
        $steps = array();

        if ( $this->should_show_login_step( $transaction ) ) {
            $steps['login'] = __( 'Login/Registration', 'another-wordpress-classifieds-plugin' );
        }

        if ( $this->payments->payments_enabled() && $this->payments->credit_system_enabled() ) {
            $steps['select-category'] = __( 'Select Category, Payment Term and Credit Plan', 'another-wordpress-classifieds-plugin' );
        } else if ( $this->payments->payments_enabled() ) {
            $steps['select-category'] = __( 'Select Category and Payment Term', 'another-wordpress-classifieds-plugin' );
        } else {
            $steps['select-category'] = __( 'Select Category', 'another-wordpress-classifieds-plugin' );
        }

        if ( $this->should_show_payment_steps() && $this->settings->get_option( 'pay-before-place-ad' ) ) {
            $steps['checkout'] = __( 'Checkout', 'another-wordpress-classifieds-plugin' );
            $steps['payment'] = __( 'Payment', 'another-wordpress-classifieds-plugin' );
        }

        $steps['listing-details'] = __( 'Enter Listing Details', 'another-wordpress-classifieds-plugin' );

        if ( $this->should_show_upload_files_step( $transaction ) ) {
            $steps['upload-files'] = __( 'Upload Files', 'another-wordpress-classifieds-plugin' );
        }

        if ( $this->settings->get_option( 'show-ad-preview-before-payment' ) ) {
            $steps['preview'] = __( 'Preview Listing', 'another-wordpress-classifieds-plugin' );
        }

        if ( $this->should_show_payment_steps() && ! $this->settings->get_option( 'pay-before-place-ad' ) ) {
            $steps['checkout'] = __( 'Checkout', 'another-wordpress-classifieds-plugin' );
            $steps['payment'] = __( 'Payment', 'another-wordpress-classifieds-plugin' );
        }

        $steps['finish'] = __( 'Finish', 'another-wordpress-classifieds-plugin' );

        return $steps;
    }

    private function should_show_login_step( $transaction ) {
        if ( ! is_user_logged_in() ) {
            return true;
        } else if ( ! is_null( $transaction ) ) {
            return $transaction->get( 'user-just-logged-in', false );
        } else {
            return $this->request->param( 'loggedin', false );
        }
    }

    /**
     * TODO: merge with similar method in Place Ad page? or move to UploadLimits class.
     */
    private function should_show_upload_files_step( $transaction ) {
        if ( is_null( $transaction ) ) {
            $payment_terms_by_type = $this->payments->get_payment_terms();
            $arrays_of_payment_terms = array_values( $payment_terms_by_type );
            $payment_terms = call_user_func_array( 'array_merge', $arrays_of_payment_terms );
        } else {
            $transaction_payment_term = $this->payments->get_transaction_payment_term( $transaction );
            $payment_terms = $transaction_payment_term ? array( $transaction_payment_term ) : array();
        }

        return $this->all_payment_terms_allow_to_upload_files( $payment_terms );
    }

    private function all_payment_terms_allow_to_upload_files( $payment_terms ) {
        if ( empty( $payment_terms ) ) {
            return false;
        }

        $upload_limits = $this->get_upload_limits_for_payment_terms( $payment_terms );

        foreach ( $upload_limits as $payment_term_limits ) {
            if ( ! $this->payment_term_allows_to_upload_files( $payment_term_limits ) ) {
                return false;
            }
        }

        return true;
    }

    private function get_upload_limits_for_payment_terms( $payment_terms ) {
        $upload_limits = array();

        foreach ( $payment_terms as $payment_term ) {
            $upload_limits[] = $this->upload_limits->get_upload_limits_for_payment_term( $payment_term );
        }

        return $upload_limits;
    }

    private function payment_term_allows_to_upload_files( $upload_limits ) {
        foreach ( $upload_limits as $file_type => $limits ) {
            if ( $limits['allowed_file_count'] > $limits['uploaded_file_count'] ) {
                return true;
            }
        }

        return false;
    }

    private function should_show_payment_steps() {
        if ( awpcp_current_user_is_admin() ) {
            return false;
        } else {
            return $this->payments->payments_enabled();
        }
    }

    private function render_steps( $selected_step, $steps ) {
        $form_steps = $this->prepare_steps( $steps, $selected_step );

        ob_start();
        include( AWPCP_DIR . '/templates/components/listing-form-steps.tpl.php' );
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private function prepare_steps( $steps, $selected_step ) {
        $form_steps = array();

        $previous_steps = array();
        $steps_count = 0;

        foreach ( $steps as $step => $name ) {
            $steps_count = $steps_count + 1;

            if ( $selected_step == $step ) {
                $step_class = 'current';
            } else if ( ! in_array( $selected_step, $previous_steps ) ) {
                $step_class = 'completed';
            } else {
                $step_class = 'pending';
            }

            $form_steps[ $step ] = array( 'number' => $steps_count, 'name' => $name, 'class' => $step_class );

            $previous_steps[] = $step;
        }

        return $form_steps;
    }
}
