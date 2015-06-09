<?php

function awpcp_meta_tags_generator() {
    return new AWPCP_MetaTagsGenerator( awpcp_tag_renderer() );
}

class AWPCP_MetaTagsGenerator {

    private $tag_renderer;

    public function __construct( $tag_renderer ) {
        $this->tag_renderer = $tag_renderer;
    }

    public function generate_meta_tags( $metadata ) {
        return array_merge(
            $this->generate_basic_meta_tags( $metadata ),
            $this->generate_opengraph_meta_tags( $metadata )
        );
    }

    public function generate_opengraph_meta_tags( $metadata ) {
        $meta_tags['og:type'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'og:type', 'content' => $metadata['http://ogp.me/ns#type'] ) );
        $meta_tags['og:url'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'og:url', 'content' => $metadata['http://ogp.me/ns#url'] ) );
        $meta_tags['og:title'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'og:title', 'content' => $metadata['http://ogp.me/ns#title'] ) );
        $meta_tags['og:description'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'og:description', 'content' => $metadata['http://ogp.me/ns#description'] ) );

        $meta_tags['article:published_time'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'article:published_time', 'content' => $metadata['http://ogp.me/ns/article#published_time'] ) );
        $meta_tags['article:modified_time'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'article:modified_time', 'content' => $metadata['http://ogp.me/ns/article#modified_time'] ) );

        if ( isset( $metadata['http://ogp.me/ns#image'] ) ) {
            $meta_tags['og:image'] = $this->tag_renderer->render_tag( 'meta', array( 'property' => 'og:image', 'content' => $metadata['http://ogp.me/ns#image'] ) );
        }

        if ( isset( $metadata['http://ogp.me/ns#image'] ) ) {
            // this helps Facebook determine which image to put next to the link
            $meta_tags['image_src'] = $this->tag_renderer->render_tag( 'link', array( 'rel' => 'image_src', 'href' => $metadata['http://ogp.me/ns#image'] ) );
        }

        return $meta_tags;
    }

    public function generate_basic_meta_tags( $metadata ) {
        $meta_tags = array();

        $meta_tags['title'] = $this->tag_renderer->render_tag( 'meta', array( 'name' => 'title', 'content' => $metadata['http://ogp.me/ns#title'] ) );
        $meta_tags['description'] = $this->tag_renderer->render_tag( 'meta', array( 'name' => 'description', 'content' => $metadata['http://ogp.me/ns#description'] ) );

        return $meta_tags;
    }
}
