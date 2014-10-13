<?php

class AWPCP_AkismetWrapper extends AWPCP_AkismetWrapperBase {

    private $request;

    public function __construct( $request ) {
        $this->request = $request;
    }

    public function get_user_data() {
        return array(
            'user_ip'      => Akismet::get_ip_address(),
            'user_agent'   => isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null,
            'referrer'     => isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : null,
            'blog'         => get_option('home'),
            'blog_lang'    => get_locale(),
            'blog_charset' => get_option('blog_charset'),
        );
    }

    public function get_reporter_data() {
        $reporter_data = array( 'site_domain' => $this->request->domain() );

        $current_user = $this->request->get_current_user();

        if ( is_object( $current_user ) ) {
            $reporter_data['reporter'] = $current_user->user_login;
            $reporter_data['user_role'] = empty( $current_user->roles ) ? '' : end( $current_user->roles );
        }

        return $reporter_data;
    }

    public function http_post( $request_data, $path, $ip=null ) {
        return Akismet::http_post( $request_data, $path, $ip );
    }
}
