<?php

class AWPCP_YoastWordPressSEOPluginIntegration {

    private $meta;
    private $image_already_processed;

    public function should_generate_opengraph_tags( $should, AWPCP_Meta $meta ) {
        $this->meta = $meta;

        if ( defined( 'WPSEO_VERSION' ) && class_exists( 'WPSEO_OpenGraph' ) ) {
            add_filter( 'wpseo_opengraph_type', array( $this, 'og_type' ) );
            add_filter( 'wpseo_opengraph_title', array( $this, 'og_title' ) );
            add_filter( 'wpseo_opengraph_desc', array( $this, 'og_description' ) );
            add_filter( 'wpseo_opengraph_url', array( $this, 'og_url' ) );
            add_filter( 'wpseo_og_article_published_time', array( $this, 'og_published_time' ) );
            add_filter( 'wpseo_og_article_modified_time', array( $this, 'og_modified_time' ) );
            add_filter( 'wpseo_opengraph_image', array( $this, 'og_image' ) );

            add_action( 'wpseo_opengraph', array( $this, 'maybe_render_og_image_tag' ), 90 );

            return false;
        }

        return $should;
    }

    public function og_type() {
        $tags = $this->meta->get_meta_tags();
        return $tags['http://ogp.me/ns#type'];
    }

    public function og_title() {
        $tags = $this->meta->get_meta_tags();
        return $tags['http://ogp.me/ns#title'];
    }

    public function og_description() {
        $tags = $this->meta->get_meta_tags();
        return $tags['http://ogp.me/ns#description'];
    }

    public function og_url() {
        $tags = $this->meta->get_meta_tags();
        return $tags['http://ogp.me/ns#url'];
    }

    public function og_published_time() {
        $tags = $this->meta->get_meta_tags();
        return $tags['http://ogp.me/ns/article#published_time'];
    }

    public function og_modified_time() {
        $tags = $this->meta->get_meta_tags();
        return $tags['http://ogp.me/ns/article#modified_time'];
    }

    public function og_image( $image ) {
        $this->image_already_processed = true;
        return $this->get_image_src( $image );
    }

    private function get_image_src( $default = '' ) {
        $tags = $this->meta->get_meta_tags();
        if ( isset( $tags['http://ogp.me/ns#image'] ) ) {
            return $tags['http://ogp.me/ns#image'];
        } else {
            return $default;
        }
    }

    public function maybe_render_og_image_tag() {
        $image_src = $this->get_image_src();

        if ( empty( $image_src ) ) {
            return;
        }

        if ( $this->image_already_processed ) {
            echo $this->render_image_src_link( $image_src );
        } else {
            echo $this->render_og_image_tag( $image_src );
            echo $this->render_image_src_link( $image_src );
        }
    }

    private function render_image_src_link( $image_src ) {
        return $this->meta->render_tag( 'link', array( 'rel' => 'image_src', 'href' => $image_src ) );
    }

    private function render_og_image_tag( $image_src ) {
        return $this->meta->render_tag( 'meta', array( 'property' => 'og:image', 'content' => $image_src ) );
    }

    public function should_generate_rel_canonical( $should ) {
        if ( defined( 'WPSEO_VERSION' ) ) {
            add_filter( 'wpseo_canonical', array( $this, 'canonical_url' ) );
            return false;
        }

        return $should;
    }

    /**
     * TODO: move to a parent class for all SEO plugin integrations.
     */
    public function canonical_url( $url ) {
        $awpcp_canonical_url = awpcp_rel_canonical_url();

        if ( $awpcp_canonical_url ) {
            return $awpcp_canonical_url;
        }

        return $url;
    }

    public function should_generate_title( $should, $meta ) {
        $this->meta = $meta;

        if ( defined( 'WPSEO_VERSION' ) ) {
            add_filter( 'wpseo_title', array( $this, 'build_title' ) );
            return false;
        }

        return $should;
    }

    public function build_title( $title ) {
        global $sep;
        return $this->meta->title_builder->build_title( $title, $sep, '' );
    }
}
