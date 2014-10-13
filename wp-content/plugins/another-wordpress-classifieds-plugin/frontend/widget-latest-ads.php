<?php

class AWPCP_LatestAdsWidget extends WP_Widget {

    public function __construct($id=null, $name=null, $description=null) {
        $id = is_null($id) ? 'awpcp-latest-ads': $id;
        $name = is_null($name) ? __('AWPCP Latest Ads', 'AWPCP') : $name;
        $description = is_null($description) ? __('Displays a list of latest Ads', 'AWPCP') : $description;
        parent::__construct($id, $name, array('description' => $description));
    }

    protected function defaults() {
        $translations = array(
            'hlimit' => 'limit',
            'showimages' => 'show-images',
            'showblank' => 'show-blank',
        );

        $defaults = array(
            'title' => __('Latest Ads', 'AWPCP'),
            'show-title' => 1,
            'show-excerpt' => 1,
            'show-images' => 1,
            'show-blank' => 1,
            'limit' => 10,
        );

        // TODO: get rid of the widget_awpcplatestads option in 3.1 or 3.0.1
        $options = get_option('widget_awpcplatestads');
        $options = is_array($options) ? $options : array();

        foreach ($translations as $old => $new) {
            if (isset($options[$old])) {
                $options[$new] = $options[$old];
            }
        }

        return array_intersect_key(array_merge($defaults, $options), $defaults);
    }

    /**
     * [render description]
     * @param  [type] $items      [description]
     * @param  [type] $instance   [description]
     * @param  string $html_class CSS class for each LI element.
     * @since  3.0-beta
     * @return string             HTML
     */
    protected function render($items, $instance, $html_class='') {
        global $awpcp_imagesurl;

        if ( empty( $items ) ) {
            return sprintf( '<li class="awpcp-empty-widget %s">%s</li>', $html_class, __( 'There are currently no Ads to show.', 'AWPCP' ) );
        }

        foreach ($items as $item) {
            $images_are_allowed = get_awpcp_option( 'imagesallowdisallow' ) == 1;
            $url = url_showad($item->ad_id);
            $title = sprintf('<a href="%s">%s</a>', $url, stripslashes($item->ad_title));

            if (!$instance['show-images']) {
                $html[] = sprintf('<li class="%s">%s</li>', $html_class, $title);
            } else {
                $image = awpcp_media_api()->get_ad_primary_image( $item );

                if ( ! is_null( $image ) && $images_are_allowed ) {
                    $image_url = $image->get_url();
                } else if ( $instance['show-blank'] && $images_are_allowed ) {
                    $image_url = "$awpcp_imagesurl/adhasnoimage.png";
                } else {
                    $image_url = '';
                }

                if ( empty( $image_url ) ) {
                    $html_image = '';
                } else {
                    $html_image = sprintf( '<a class="self" href="%1$s"><img src="%2$s" alt="%3$s" /></a>',
                                           $url,
                                           $image_url,
                                           esc_attr( $item->ad_title ) );
                }

                if ($instance['show-title']) {
                    $html_title = sprintf('<div class="awpcp-listing-title">%s</div>', $title);
                } else {
                    $html_title = '';
                }

                if ($instance['show-excerpt']) {
                    $excerpt = stripslashes( awpcp_utf8_substr( $item->ad_details, 0, 50 ) ) . "...";
                    $read_more = sprintf('<a class="awpcp-widget-read-more" href="%s">[%s]</a>', $url, __("Read more", "AWPCP"));
                    $html_excerpt = sprintf( '<div class="awpcp-listings-widget-item-excerpt">%s%s</div>', $excerpt, $read_more );
                }

                if ( $images_are_allowed ) {
                    $template = '<li class="awpcp-listings-widget-item %1$s"><div class="awpcplatestbox"><div class="awpcplatestthumb clearfix">%2$s</div>%3$s %4$s</div></li>';
                } else {
                    $template = '<li class="awpcp-listings-widget-item %1$s"><div class="awpcplatestbox">%3$s %4$s</div></li>';
                }

                $html[] = sprintf( $template, $html_class, $html_image, $html_title, $html_excerpt );
            }
        }

        return join("\n", $html);
    }

    protected function query($instance) {
        return array(
            'conditions' => array( "ad_title <> ''" ),
            'args' => array(
                'order' => array( 'ad_postdate DESC', 'ad_id DESC' ),
                'limit' => $instance['limit']
            )
        );
    }

    public function widget($args, $instance) {
        extract($args);

        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $before_widget;

        // do not show empty titles
        echo !empty( $title ) ? $before_title . $title . $after_title : '';

        echo '<ul class="awpcp-listings-widget-items-list">';
        $query = $this->query( $instance );
        $items = AWPCP_Ad::get_enabled_ads( $query['args'], $query['conditions'] );
        echo $this->render( $items, $instance );
        echo '</ul>';

        echo $after_widget;
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
