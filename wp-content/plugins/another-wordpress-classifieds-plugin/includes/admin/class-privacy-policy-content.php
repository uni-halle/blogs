<?php
/**
 * @package AWPCP\Admin
 */

/**
 * @since 3.8.6
 */
function awpcp_privacy_policy_content() {
    return new AWPCP_PrivacyPolicyContent();
}

/**
 * Suggests content for the website's Privacy Policy page.
 */
class AWPCP_PrivacyPolicyContent {

    /**
     * @var string
     */
    private $template = '/privacy-policy.tpl.php';

    /**
     * @since 3.8.6
     */
    public function add_privacy_policy_content() {
        if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
            return;
        }

        wp_add_privacy_policy_content( 'Another WordPress Classifieds Plugin', $this->get_privacy_policy_content() );
    }

    /**
     * @since 3.8.6
     */
    private function get_privacy_policy_content() {
        $content = awpcp_render_template( $this->template, array() );

        return wp_kses_post( $content, false );
    }
}
