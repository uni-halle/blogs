<?php

function awpcp_render_listing_form_steps( $selected_step, $transaction = null ) {
    return awpcp_listing_form_steps_componponent()->render( $selected_step, $transaction );
}

function awpcp_listing_form_steps_componponent() {
    return new AWPCP_ListingFormStepsComponent(
        awpcp_payments_api(),
        awpcp_listing_upload_limits(),
        awpcp()->settings
    );
}

class AWPCP_ListingFormStepsComponent {

    private $payments;
    private $upload_limits;
    private $settings;

    public function __construct( $payments, $upload_limits, $settings ) {
        $this->payments = $payments;
        $this->upload_limits = $upload_limits;
        $this->settings = $settings;
    }

    public function render( $selected_step, $transaction ) {
        return $this->render_steps( $selected_step, $this->get_steps( $transaction ) );
    }

    private function get_steps( $transaction ) {
        $steps = array();

        $steps['login'] = __( 'Login/Registration', 'AWPCP' );

        if ( $this->payments->payments_enabled() && $this->payments->credit_system_enabled() ) {
            $steps['select-category'] = __( 'Select Category, Payment Term and Credit Plan', 'AWPCP' );
        } else if ( $this->payments->payments_enabled() ) {
            $steps['select-category'] = __( 'Select Category and Payment Term', 'AWPCP' );
        } else {
            $steps['select-category'] = __( 'Select Category', 'AWPCP' );
        }

        if ( $this->payments->payments_enabled() && $this->settings->get_option( 'pay-before-place-ad' ) ) {
            $steps['checkout'] = __( 'Checkout', 'AWPCP' );
            $steps['payment'] = __( 'Payment', 'AWPCP' );
        }

        $steps['listing-details'] = __( 'Enter Listing Details', 'AWPCP' );

        if ( $this->should_show_upload_files_step( $transaction ) ) {
            $steps['upload-files'] = __( 'Upload Files', 'AWPCP' );
        }

        if ( $this->settings->get_option( 'show-ad-preview-before-payment' ) ) {
            $steps['preview'] = __( 'Preview Listing', 'AWPCP' );
        }

        if ( $this->payments->payments_enabled() && ! $this->settings->get_option( 'pay-before-place-ad' ) ) {
            $steps['checkout'] = __( 'Checkout', 'AWPCP' );
            $steps['payment'] = __( 'Payment', 'AWPCP' );
        }

        $steps['finish'] = __( 'Finish', 'AWPCP' );

        return $steps;
    }

    /**
     * TODO: merge with similar method in Place Ad page? or move to UploadLimits class.
     */
    private function should_show_upload_files_step( $transaction ) {
        if ( is_null( $transaction ) ) {
            $payment_term = null;
        } else {
            $payment_term = $this->payments->get_transaction_payment_term( $transaction );
        }

        if ( is_null( $payment_term ) ) {
            $upload_limits = $this->upload_limits->get_upload_limits_for_free_board();
        } else {
            $upload_limits = $this->upload_limits->get_upload_limits_for_payment_term( $payment_term );
        }

        foreach ( $upload_limits as $file_type => $limits ) {
            if ( $limits['allowed_file_count'] > $limits['uploaded_file_count'] ) {
                return true;
            }
        }

        return false;
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
