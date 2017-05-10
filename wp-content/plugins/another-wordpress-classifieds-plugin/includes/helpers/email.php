<?php

/**
 * @since 2.1.4
 */
class AWPCP_Email {

    public function __construct() {
        $this->headers = array();

        $this->subject = '';
        $this->from = null;
        $this->to = array();
        $this->cc = array();

        $this->body = '';
        $this->plain = '';
        $this->html = '';
    }

    public function prepare($template, $params=array()) {
        extract($params);

        ob_start();
            include($template);
            $this->body = ob_get_contents();
        ob_end_clean();
    }

    private function get_headers($format='plain') {
        if (!$this->from) {
            $this->from = awpcp_admin_email_from();
        }

        switch ($format) {
            case 'plain':
                $content_type = 'text/plain; charset="' . get_bloginfo( 'charset' ) . '"';
                break;

            case 'html':
                $content_type = 'text/html; charset="' . get_bloginfo( 'charset' ) . '"';
                break;
        }

        $headers = array_merge(array(
            'Content-Type' => $content_type,
            'From' => $this->from,
            'Reply-To' => awpcp_admin_email_to(),
        ), $this->headers);

        $sanitized_headers = array();

        foreach ($headers as $k => $v) {
            $sanitized_headers[] = sprintf( "%s: %s", $k, str_replace( "\n", '', $v ) );
        }

        return $sanitized_headers;
    }

    /**
     * Sends the email.
     * @param string $format allowed values are 'html', 'plain' or 'both'
     * @return boolean true on success, false otherwise
     */
    public function send($format='plain') {
        $headers = $this->get_headers($format);
        $sent_date = awpcp_format_email_sent_datetime();
        $body = sprintf( "%s\n\n%s", $this->body, $sent_date );

        return $this->send_email( $this->to, $this->subject, $body, $headers );
    }

    protected function send_email( $to, $subject, $body, $headers = array(), $attachments = array() ) {
        return wp_mail( $to, $subject, $body, $headers, $attachments );
    }
}
