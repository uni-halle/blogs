<?php


function be_verlauf_shortcode( $atts, $content = null ) {
    return '
<div class="be-gradient-2 spacer-v-4">
    <div class="grid-container">
        <div class="grid-x grid-margin-x">
' .do_shortcode($content). '
        </div>
    </div>
</div>
';
}
add_shortcode( 'be_verlauf', 'be_verlauf_shortcode' );

function be_verlauf_invers_shortcode( $atts, $content = null ) {
    return '
<div class="be-gradient-2-invers spacer-v-4">
    <div class="grid-container">
        <div class="grid-x grid-margin-x">
' .do_shortcode($content). '
        </div>
    </div>
</div>
';
}
add_shortcode( 'be_verlauf_invers', 'be_verlauf_invers_shortcode' );

function be_container_shortcode ( $atts, $content = null ) {
    return '
<div class="grid-container">
    <div class="grid-x grid-margin-x">
' .do_shortcode($content). '
    </div>
</div>
';
}
add_shortcode( 'be_container', 'be_container_shortcode' );

function be_grid_shortcode ( $atts, $content = null ) {
    return '
    <div class="grid-x grid-margin-x">
' .do_shortcode($content). '
    </div>
';
}
add_shortcode( 'be_grid', 'be_grid_shortcode' );

function be_spalte_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'desktop' => '6',
        'tablet' => '6',
        'mobile' => '12',
    ), $atts );
    return '
<div class="small-' . esc_attr($a['mobile']) . ' medium-' . esc_attr($a['tablet']) . ' large-' . esc_attr($a['desktop']) . ' cell">
' .do_shortcode($content). '
</div>
';
}
add_shortcode( 'be_spalte', 'be_spalte_shortcode' );

function be_link_shortcode( $atts, $content = null ) {
    return '
<div class="be-link">
' .do_shortcode($content). '
</div>
';
}
add_shortcode( 'be_link', 'be_link_shortcode' );


error_reporting(E_ALL);

function be_link_ext_shortcode( $atts = [], $content = null ) {

    $a = shortcode_atts( array(
        'url'  => 'text',
        'link' => 'text',
    ), $atts );
    //$theurl = 'http://be-up.doom/wp-content/uploads/2018/01/kbd.pdf';
    //$theurl = esc_attr($a['link']);
    $filename = $content;
    $theurl   =  '/internal_download.php?file=' .$filename;

    return '<a href="' . $theurl . '">' . $filename . '</a>';

    if(!function_exists('extcheck')) {

        function extcheck($extension) {

            preg_match('|<a.+?href\="(.+?)".*?>(.+?)</a>|i', $extension, $match);
            $url = $match[1];
            $text = $match[2];
            //$path_parts = pathinfo($extension);
            $path_parts = pathinfo($url);
            $theext = $path_parts["extension"];

            $mime_types = array(

                'txt' => 'file-text-o',
                'htm' => 'file-code',
                'html' => 'file-code',
                'php' => 'file-code',
                'css' => 'file-code',
                'js' => 'file-code',
                'json' => 'file-code',
                'xml' => 'file-code',
                'swf' => 'file-video',
                'flv' => 'file-video',

                // images
                'png' => 'file-image-o',
                'jpe' => 'file-image-o',
                'jpeg' => 'file-image-o',
                'jpg' => 'file-image-o',
                'gif' => 'file-image-o',
                'bmp' => 'file-image-o',
                'ico' => 'file-image-o',
                'tiff' => 'file-image-o',
                'tif' => 'file-image-o',
                'svg' => 'file-image-o',
                'svgz' => 'file-image-o',

                // archives
                'zip' => 'file-archive-o',
                'rar' => 'file-archive-o',
                'exe' => 'file-archive-o',
                'msi' => 'file-archive-o',
                'cab' => 'file-archive-o',

                // audio/video
                'mp3' => 'file-audio-o',
                'qt' => 'file-audio-o',
                'mov' => 'file-audio-o',

                // adobe
                'pdf' => 'file-pdf-o',
                'psd' => 'file-image-o',
                'ai' => 'file-image-o',
                'eps' => 'file-image-o',
                'ps' => 'file-image-o',

                // ms office
                'doc' => 'file-word-o',
                'docx' => 'file-word-o',
                'rtf' => 'file-word-o',
                'xls' => 'file-word-o',
                'xlsx' => 'file-word-o',
                'ppt' => 'file-word-o',

                // open office
                'odt' => 'file-word-o',
                'ods' => 'file-word-o',
            );

            if (array_key_exists($theext, $mime_types)) {
                return '<i class="fa fa-' . $mime_types[$theext] . '" aria-hidden="true"></i>';
            }
            else {
                return '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
            }
        }
    };
//     return '
// <div class="be-link">
// ' .do_shortcode($content).'
// ' .extcheck($theurl).'
// </div>
// ';
return '
<div class="be-link">
' .$theurl.'
</div>
';
}
add_shortcode( 'be_link_ext', 'be_link_ext_shortcode' );


// function be_link_ext_shortcode( $atts, $content = null) {

//     return '<a href="test">test</a>';
// }
// add_shortcode( 'be_link_ext', 'be_link_ext_shortcode' );


function be_maplink_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'ort' => '',
    ), $atts );
    return '
<div class="be-link" id="' . esc_attr($a['ort']) . '" data-open="reveal_' . esc_attr($a['ort']) . '">
' .do_shortcode($content). '
</div>
';
}
add_shortcode( 'be_maplink', 'be_maplink_shortcode' );

function be_link_info_shortcode( $atts, $content = null ) {
    return '
<div class="be-link-info">
' .do_shortcode($content). '
</div>
';
}
add_shortcode( 'be_link_info', 'be_link_info_shortcode' );

function be_image_shortcode( $atts, $content = null ) {
    return '
<div class="be-gradient-2 be-border-upper be-image-wide">
    <div class="">
' .do_shortcode($content). '
    </div>
</div>
';
}
add_shortcode( 'be_image', 'be_image_shortcode' );

function be_slider_top_shortcode( $atts, $content = null ) {
    ob_start();
        //get_template_part('my_form_template');
        echo '<div class="slick-rail be-border-upper"><div class="slick slick-top">';
        echo do_shortcode($content);
        echo '</div></div>';
        get_template_part( 'template-parts/slider-top', 'page' );
    return ob_get_clean();
}
add_shortcode( 'be_slider_top', 'be_slider_top_shortcode' );

function be_slider_container_shortcode( $atts, $content = null ) {
    return '
<div class="grid-container">
    <div class="slick slick-top">
    ' .do_shortcode($content). '
    </div>
</div>
';
}
add_shortcode( 'be_slider_container', 'be_slider_container_shortcode' );

function be_slide_shortcode( $atts, $content = null ) {
    return '
<div class="slick-slide">
    ' .do_shortcode($content). '
</div>
';
}
add_shortcode( 'be_slide', 'be_slide_shortcode' );

function be_heading_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'hash' => '',
    ), $atts );
    return '
<div class="be-h2-special">
    <div class="topspace">
    </div>
    <div class="be-border-upper">
        <div class="grid-container clearfix">
            <div class="radius-bg">
                <h2 id="' . esc_attr($a['hash']) . '">
' .do_shortcode($content). '
                </h2>
            </div>
        </div>
    </div>
</div>
';
}
add_shortcode( 'be_heading', 'be_heading_shortcode' );

function be_heading_link_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'link' => '#',
    ), $atts );
    return '
    <div class="be-h2-special be-h2-link">
    <div class="topspace">
    </div>
    <div class="be-border-upper">
        <a href="' . esc_attr($a['link']) . '">
            <div class="grid-container">
                <div class="radius-bg h2-heading-link">
' .do_shortcode($content). '
                </div>
            </div>
        </a>
    </div>
</div>
';
}
add_shortcode( 'be_heading_link', 'be_heading_link_shortcode' );

function be_heading_link_shortcode_gradient( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'link' => '#',
    ), $atts );
    return '
    <div class="be-h2-special be-h2-link">
    <div class="topspace be-gradient-2">
    </div>
    <div class="be-border-upper">
        <a href="' . esc_attr($a['link']) . '">
            <div class="grid-container">
                <div class="radius-bg h2-heading-link">
' .do_shortcode($content). '
                </div>
            </div>
        </a>
    </div>
</div>
';
}
add_shortcode( 'be_heading_link_gradient', 'be_heading_link_shortcode_gradient' );

function be_akkordeon_container_shortcode( $atts, $content = null ) {
    return '
<div class="grid-container">
    <div class="grid-x grid-margin-x">
        <div class="small-12 cell">
            <ul class="accordion" id="" data-accordion data-allow-all-closed="true">
' .do_shortcode($content). '
            </ul>
        </div>
    </div>
</div>
';
}
add_shortcode( 'be_akkordeon_container', 'be_akkordeon_container_shortcode' );

function be_akkordeon_eintrag_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'title' => 'Ausklappen',
        'hash' => '',
    ), $atts );
    $deeplink = '';
    if (!empty ($a['hash'])) {
        $deeplink = '#acc'.$a['hash'];
        $deepid = 'acc'.$a['hash'];
    };
    return '
<li class="accordion-item" data-accordion-item>
    <!--<a href="#" class="accordion-title">' . esc_attr($a['title']) . '</a>-->
    <a href="' . $deeplink . '" class="accordion-title"><h3>' . $a['title'] . '</h3></a>
    <div class="accordion-content" data-tab-content id="' . $deepid . '">
        <div class="grid-x">
' .do_shortcode($content). '
        </div>
    <div class="topspace be-gradient-2">
    </div>
    </div>
</li>
';
}
add_shortcode( 'be_akkordeon_eintrag', 'be_akkordeon_eintrag_shortcode' );

function be_akkordeon_container_shortcode_nested( $atts, $content = null ) {
    return '

            <ul class="accordion accordion-nested" id="" data-accordion data-allow-all-closed="true">
' .do_shortcode($content). '
            </ul>

';
}
add_shortcode( 'be_akkordeon_container_nested', 'be_akkordeon_container_shortcode_nested' );

function be_akkordeon_eintrag_shortcode_nested( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'title' => 'Ausklappen',
        'hash' => '',
    ), $atts );
    $deeplink = '';
    if (!empty ($a['hash'])) {
        $deeplink = '#acc'.$a['hash'];
        $deepid = 'acc'.$a['hash'];
    };
    return '
<li class="accordion-item" data-accordion-item>
    <!--<a href="#" class="accordion-title">' . esc_attr($a['title']) . '</a>-->
    <a href="' . $deeplink . '" class="accordion-title"><h3>' . $a['title'] . '</h3></a>
    <div class="accordion-content" data-tab-content id="' . $deepid . '">

' .do_shortcode($content). '

    </div>
</li>
';
}
add_shortcode( 'be_akkordeon_eintrag_nested', 'be_akkordeon_eintrag_shortcode_nested' );

function be_reveal_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'id' => 'stolberg',
        'title' => 'Ausklappen',
    ), $atts );
    return '
<div class="reveal" id="reveal_' . esc_attr($a['id']) . '" aria-labelledby="reveal_Header_' . esc_attr($a['id']) . '" data-reveal>
    <h1 id="reveal_Header_' . esc_attr($a['id']) . '">' . esc_attr($a['title']) . '</h1>
    <p class="lead">
    ' .do_shortcode($content). '
    </p>
    <button class="close-button" data-close aria-label="Close Accessible Modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
';
}
add_shortcode( 'be_reveal', 'be_reveal_shortcode' );


function be_piwik_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'src' => 'https://uni-halle.de',
        'width' => '100%',
        'height' => '100%',
    ), $atts );
    return '
        <iframe frameborder="no" width="' . esc_attr($a['width']) . '" height="' . esc_attr($a['height']) . '" src="' . esc_attr($a['src']) . '"></iframe>
        ' .do_shortcode($content). '
    ';
}
add_shortcode( 'be_piwik', 'be_piwik_shortcode' );


function be_map_shortcode( $atts, $content = null ) {
    // return
    // do_shortcode($content)
    // ;
    ob_start();
        //get_template_part('my_form_template');
        get_template_part( 'template-parts/imagemap', 'page' );
        do_shortcode($content);
    return ob_get_clean();
}
add_shortcode( 'be_map', 'be_map_shortcode' );


function be_get_slider_shortcode( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'page' => 'slider_top',
    ), $atts );
    ob_start();
                // do_shortcode($content);
        $page = get_posts(
            array(
                'name'      => esc_attr($a['page']),
                'post_type' => 'page'
            )
        );
        if ( $page ) {
            echo '<div class="slick-rail be-border-upper"><div class="slick slick-top">';
            echo do_shortcode($page[0]->post_content);
            echo '</div></div>';
            get_template_part( 'template-parts/slider-top', 'page' );
        };
    return ob_get_clean();
    }
add_shortcode( 'be_get_slider', 'be_get_slider_shortcode' );


// function be_link_ext_shortcode( $atts, $content = null ) {
//     require_once('secret_download.php');

//     if ($content !== null) {
//         $relative_filename = $content;
//         return sd_echo_file($relative_filename);
//     }
// }
// add_shortcode( 'be_link_ext', 'be_link_ext_shortcode' );
