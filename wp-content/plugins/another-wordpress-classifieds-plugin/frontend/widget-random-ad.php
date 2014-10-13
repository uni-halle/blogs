<?php

require_once(AWPCP_DIR . '/frontend/widget-latest-ads.php');


class AWPCP_RandomAdWidget extends AWPCP_LatestAdsWidget {

    public function __construct($id=null, $name=null, $description=null) {
        parent::__construct('awpcp-random-ads', __('AWPCP Random Ads', 'AWPCP'), __('Displays a list of random Ads', 'AWPCP'));
    }

    protected function defaults() {
        return array(
            'title' => __('Random Ads', 'AWPCP'),
            'show-title' => 1,
            'show-excerpt' => 1,
            'show-images' => 1,
            'show-blank' => 1,
            'limit' => 1,
        );
    }

    protected function query($instance) {
        $query = parent::query($instance);

        $query['args']['order'] = array( 'RAND() DESC' );

        return $query;
    }

    public function form($instance) {
        $instance = array_merge($this->defaults(), $instance);
        include(AWPCP_DIR . '/frontend/templates/widget-latest-ads-form.tpl.php');
    }

    public function update($new_instance, $old_instance) {
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['limit'] = strip_tags($new_instance['limit']);
        $instance['show-title'] = absint($new_instance['show-title']);
        $instance['show-excerpt'] = absint($new_instance['show-excerpt']);
        $instance['show-images'] = absint($new_instance['show-images']);
        $instance['show-blank'] = absint($new_instance['show-blank']);
        return $instance;
    }
}
