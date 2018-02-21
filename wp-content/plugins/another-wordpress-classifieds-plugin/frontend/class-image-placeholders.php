<?php

function awpcp_image_placeholders() {
    return new AWPCP_Image_Placeholders( awpcp_media_api() );
}

class AWPCP_Image_Placeholders {

    private $media_api;

    public function __construct( $media_api ) {
        $this->media_api = $media_api;
    }

    public function do_image_placeholders( $ad, $placeholder ) {
        global $awpcp_imagesurl;

        static $replacements = array();

        if (isset($replacements[$ad->ad_id])) {
            return $replacements[$ad->ad_id][$placeholder];
        }

        $placeholders = array(
            'featureimg' => '',
            'awpcpshowadotherimages' => '',
            'images' => '',
            'awpcp_image_name_srccode' => '',
        );

        $url = awpcp_listing_renderer()->get_view_listing_url( $ad );
        $thumbnail_width = get_awpcp_option('displayadthumbwidth');

        if ( awpcp_are_images_allowed() ) {
            $images_uploaded = $ad->count_image_files();
            $primary_image = $this->media_api->get_ad_primary_image( $ad );
            $gallery_name = 'awpcp-gallery-' . $ad->ad_id;

            if ($primary_image) {
                $large_image = $primary_image->get_large_image_url( 'large' );
                $thumbnail = $primary_image->get_primary_thumbnail_url( 'primary' );

                if (get_awpcp_option('show-click-to-enlarge-link', 1)) {
                    $link = '<a class="thickbox enlarge" href="%s">%s</a>';
                    $link = sprintf($link, $large_image, __('Click to enlarge image.', 'another-wordpress-classifieds-plugin'));
                } else {
                    $link = '';
                }

                $image_dimensions = $this->media_api->get_metadata( $primary_image, 'image-dimensions', array() );
                $image_dimensions = awpcp_array_data( 'primary', array(), $image_dimensions );

                $link_attributes = array(
                    'class' => 'awpcp-listing-primary-image-thickbox-link thickbox thumbnail',
                    'href' => esc_url( $large_image ),
                    'rel' => esc_attr( $gallery_name ),
                );

                // single ad
                $image_attributes = array(
                    'attributes' => array(
                        'class' => 'thumbshow',
                        'src' => esc_attr( $thumbnail ),
                        'alt' => __( "Thumbnail for the listing's main image", 'another-wordpress-classifieds-plugin' ),
                        'width' => awpcp_array_data( 'width', null, $image_dimensions ),
                        'height' => awpcp_array_data( 'height', null, $image_dimensions ),
                    )
                );

                $content = '<div class="awpcp-ad-primary-image">';
                $content.= '<a ' . awpcp_html_attributes( $link_attributes ) . '>';
                $content.= awpcp_html_image( $image_attributes );
                $content.= '</a>' . $link;
                $content.= '</div>';

                $placeholders['featureimg'] = $content;

                // listings
                $image_attributes = array(
                    'attributes' => array(
                        'class' => 'awpcp-listing-primary-image-thumbnail',
                        'alt' => awpcp_esc_attr( $ad->ad_title ),
                        'src' => esc_attr( $thumbnail ),
                        'width' => $thumbnail_width,
                    )
                );

                $content = '<a class="awpcp-listing-primary-image-listing-link" href="%s">%s</a>';
                $content = sprintf( $content, $url, awpcp_html_image( $image_attributes ) );

                $placeholders['awpcp_image_name_srccode'] = $content;
            }

            if ($images_uploaded >= 1) {
                $results = $this->media_api->find_public_images_by_ad_id( $ad->ad_id );

                $columns = get_awpcp_option('display-thumbnails-in-columns', 0);
                $rows = $columns > 0 ? ceil(count($results) / $columns) : 0;
                $shown = 0;

                $images = array();
                foreach ($results as $image) {
                    if ( $primary_image->id == $image->id ) {
                        continue;
                    }

                    $large_image = $image->get_url( 'large' );
                    $thumbnail = $image->get_url( 'thumbnail' );

                    $image_dimensions = awpcp_media_api()->get_metadata( $image, 'image-dimensions', array() );
                    $image_dimensions = awpcp_array_data( 'thumbnail', array(), $image_dimensions );

                    if ($columns > 0) {
                        $li_attributes['class'] = join(' ', awpcp_get_grid_item_css_class(array(), $shown, $columns, $rows));
                    } else {
                        $li_attributes['class'] = '';
                    }

                    $link_attributes = array(
                        'class' => 'thickbox',
                        'href' => esc_url( $large_image ),
                        'rel' => esc_attr( $gallery_name )
                    );

                    $image_attributes = array(
                        'attributes' => array(
                            'class' => 'thumbshow',
                            'src' => esc_attr( $thumbnail ),
                            'width' => awpcp_array_data( 'width', null, $image_dimensions ),
                            'height' => awpcp_array_data( 'height', null, $image_dimensions ),
                        )
                    );

                    $content = '<li ' . awpcp_html_attributes( $li_attributes ) . '>';
                    $content.= '<a ' . awpcp_html_attributes( $link_attributes ) . '>';
                    $content.= awpcp_html_image( $image_attributes );
                    $content.= '</a>';
                    $content.= '</li>';

                    $images[] = $content;

                    $shown = $shown + 1;
                }

                $placeholders['awpcpshowadotherimages'] = join('', $images);

                $content = '<ul class="awpcp-single-ad-images">%s</ul>';
                $placeholders['images'] = sprintf($content, $placeholders['awpcpshowadotherimages']);
            }
        }

        // fallback thumbnail
		if ( awpcp_are_images_allowed() && empty( $placeholders['awpcp_image_name_srccode'] ) && ! get_awpcp_option( 'hide-noimage-placeholder', 1 ) ) {

			// check if user has enabled override for no image placeholder
			if ( get_awpcp_option( 'override-noimage-placeholder', 1 ) ) {
				// get saved no image placeholer url
				$thumbnail = get_awpcp_option( 'noimage-placeholder-url' );

			}else {
				$thumbnail = sprintf( '%s/adhasnoimage.png', $awpcp_imagesurl );
			}

			$image_attributes = array(
				'attributes' => array(
                    'class' => 'awpcp-listing-primary-image-thumbnail',
					'alt' => awpcp_esc_attr( $ad->ad_title ),
					'src' => esc_attr( $thumbnail ),
					'width' => esc_attr( $thumbnail_width ),
				)
			);

			$content = '<a class="awpcp-listing-primary-image-listing-link" href="%s">%s</a>';
			$content = sprintf( $content, $url, awpcp_html_image( $image_attributes ) );

			$placeholders['awpcp_image_name_srccode'] = $content;
		}

        $placeholders['featureimg'] = apply_filters( 'awpcp-featured-image-placeholder', $placeholders['featureimg'], 'single', $ad );
        $placeholders['awpcp_image_name_srccode'] = apply_filters( 'awpcp-featured-image-placeholder', $placeholders['awpcp_image_name_srccode'], 'listings', $ad );

        $placeholders['featured_image'] = $placeholders['featureimg'];
        $placeholders['imgblockwidth'] = "{$thumbnail_width}px";
        $placeholders['thumbnail_width'] = "{$thumbnail_width}px";

        $replacements[ $ad->ad_id ] = apply_filters( 'awpcp-image-placeholders', $placeholders, $ad );

        return $replacements[$ad->ad_id][$placeholder];
    }
}
