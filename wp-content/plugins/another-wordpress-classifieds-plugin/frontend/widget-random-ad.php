<?php

require_once(AWPCP_DIR . '/frontend/widget-latest-ads.php');


class AWPCP_RandomAdWidget extends AWPCP_LatestAdsWidget {

    public function __construct($id=null, $name=null, $description=null) {
        parent::__construct('awpcp-random-ads', __('AWPCP Random Ads', 'AWPCP'), __('Displays a list of random Ads', 'AWPCP'));
    }

    protected function defaults() {
        return wp_parse_args( array(
            'title' => __('Random Ads', 'AWPCP'),
            'limit' => 1,
        ), parent::defaults() );
    }

    protected function query($instance) {
        $query = array_merge( parent::query( $instance ), array(
            'orderby' => 'random',
            'order' => 'DESC',
        ) );

        return $query;
    }

    public function form($instance) {
        $instance = array_merge($this->defaults(), $instance);
        include(AWPCP_DIR . '/frontend/templates/widget-latest-ads-form.tpl.php');
    }
}
