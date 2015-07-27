<?php

/*
 * Restrict direct access
 */
if ( ! defined( 'ABSPATH' ) ) wp_die( __( 'Sorry hackers! This is not your place!', 'sn' ) );


/*
 * Filter for font awesome icon list
 */
$font_awesome = apply_filters( 'font-awesome-icons', array('fa-adjust'=>'adjust','fa-adn'=>'adn','fa-align-center'=>'align-center','fa-align-justify'=>'align-justify','fa-align-left'=>'align-left','fa-align-right'=>'align-right','fa-ambulance'=>'ambulance','fa-anchor'=>'anchor','fa-android'=>'android','fa-angle-double-down'=>'angle-double-down','fa-angle-double-left'=>'angle-double-left','fa-angle-double-right'=>'angle-double-right','fa-angle-double-up'=>'angle-double-up','fa-angle-down'=>'angle-down','fa-angle-left'=>'angle-left','fa-angle-right'=>'angle-right','fa-angle-up'=>'angle-up','fa-apple'=>'apple','fa-archive'=>'archive','fa-arrow-circle-down'=>'arrow-circle-down','fa-arrow-circle-left'=>'arrow-circle-left','fa-arrow-circle-o-down'=>'arrow-circle-o-down','fa-arrow-circle-o-left'=>'arrow-circle-o-left','fa-arrow-circle-o-right'=>'arrow-circle-o-right','fa-arrow-circle-o-up'=>'arrow-circle-o-up','fa-arrow-circle-right'=>'arrow-circle-right','fa-arrow-circle-up'=>'arrow-circle-up','fa-arrow-down'=>'arrow-down','fa-arrow-left'=>'arrow-left','fa-arrow-right'=>'arrow-right','fa-arrow-up'=>'arrow-up','fa-arrows'=>'arrows','fa-arrows-alt'=>'arrows-alt','fa-arrows-h'=>'arrows-h','fa-arrows-v'=>'arrows-v','fa-asterisk'=>'asterisk','fa-automobile'=>'automobile','fa-backward'=>'backward','fa-ban'=>'ban','fa-bank'=>'bank','fa-bar-chart-o'=>'bar-chart-o','fa-barcode'=>'barcode','fa-bars'=>'bars','fa-beer'=>'beer','fa-behance'=>'behance','fa-behance-square'=>'behance-square','fa-bell'=>'bell','fa-bell-o'=>'bell-o','fa-bitbucket'=>'bitbucket','fa-bitbucket-square'=>'bitbucket-square','fa-bitcoin'=>'bitcoin','fa-bold'=>'bold','fa-bolt'=>'bolt','fa-bomb'=>'bomb','fa-book'=>'book','fa-bookmark'=>'bookmark','fa-bookmark-o'=>'bookmark-o','fa-briefcase'=>'briefcase','fa-btc'=>'btc','fa-bug'=>'bug','fa-building'=>'building','fa-building-o'=>'building-o','fa-bullhorn'=>'bullhorn','fa-bullseye'=>'bullseye','fa-cab'=>'cab','fa-calendar'=>'calendar','fa-calendar-o'=>'calendar-o','fa-camera'=>'camera','fa-camera-retro'=>'camera-retro','fa-car'=>'car','fa-caret-down'=>'caret-down','fa-caret-left'=>'caret-left','fa-caret-right'=>'caret-right','fa-caret-square-o-down'=>'caret-square-o-down','fa-caret-square-o-left'=>'caret-square-o-left','fa-caret-square-o-right'=>'caret-square-o-right','fa-caret-square-o-up'=>'caret-square-o-up','fa-caret-up'=>'caret-up','fa-certificate'=>'certificate','fa-chain'=>'chain','fa-chain-broken'=>'chain-broken','fa-check'=>'check','fa-check-circle'=>'check-circle','fa-check-circle-o'=>'check-circle-o','fa-check-square'=>'check-square','fa-check-square-o'=>'check-square-o','fa-chevron-circle-down'=>'chevron-circle-down','fa-chevron-circle-left'=>'chevron-circle-left','fa-chevron-circle-right'=>'chevron-circle-right','fa-chevron-circle-up'=>'chevron-circle-up','fa-chevron-down'=>'chevron-down','fa-chevron-left'=>'chevron-left','fa-chevron-right'=>'chevron-right','fa-chevron-up'=>'chevron-up','fa-child'=>'child','fa-circle'=>'circle','fa-circle-o'=>'circle-o','fa-circle-o-notch'=>'circle-o-notch','fa-circle-thin'=>'circle-thin','fa-clipboard'=>'clipboard','fa-clock-o'=>'clock-o','fa-cloud'=>'cloud','fa-cloud-download'=>'cloud-download','fa-cloud-upload'=>'cloud-upload','fa-cny'=>'cny','fa-code'=>'code','fa-code-fork'=>'code-fork','fa-codepen'=>'codepen','fa-coffee'=>'coffee','fa-cog'=>'cog','fa-cogs'=>'cogs','fa-columns'=>'columns','fa-comment'=>'comment','fa-comment-o'=>'comment-o','fa-comments'=>'comments','fa-comments-o'=>'comments-o','fa-compass'=>'compass','fa-compress'=>'compress','fa-copy'=>'copy','fa-credit-card'=>'credit-card','fa-crop'=>'crop','fa-crosshairs'=>'crosshairs','fa-css3'=>'css3','fa-cube'=>'cube','fa-cubes'=>'cubes','fa-cut'=>'cut','fa-cutlery'=>'cutlery','fa-dashboard'=>'dashboard','fa-database'=>'database','fa-dedent'=>'dedent','fa-delicious'=>'delicious','fa-desktop'=>'desktop','fa-deviantart'=>'deviantart','fa-digg'=>'digg','fa-dollar'=>'dollar','fa-dot-circle-o'=>'dot-circle-o','fa-download'=>'download','fa-dribbble'=>'dribbble','fa-dropbox'=>'dropbox','fa-drupal'=>'drupal','fa-edit'=>'edit','fa-eject'=>'eject','fa-ellipsis-h'=>'ellipsis-h','fa-ellipsis-v'=>'ellipsis-v','fa-empire'=>'empire','fa-envelope'=>'envelope','fa-envelope-o'=>'envelope-o','fa-envelope-square'=>'envelope-square','fa-eraser'=>'eraser','fa-eur'=>'eur','fa-euro'=>'euro','fa-exchange'=>'exchange','fa-exclamation'=>'exclamation','fa-exclamation-circle'=>'exclamation-circle','fa-exclamation-triangle'=>'exclamation-triangle','fa-expand'=>'expand','fa-external-link'=>'external-link','fa-external-link-square'=>'external-link-square','fa-eye'=>'eye','fa-eye-slash'=>'eye-slash','fa-facebook'=>'facebook','fa-facebook-square'=>'facebook-square','fa-fast-backward'=>'fast-backward','fa-fast-forward'=>'fast-forward','fa-fax'=>'fax','fa-female'=>'female','fa-fighter-jet'=>'fighter-jet','fa-file'=>'file','fa-file-archive-o'=>'file-archive-o','fa-file-audio-o'=>'file-audio-o','fa-file-code-o'=>'file-code-o','fa-file-excel-o'=>'file-excel-o','fa-file-image-o'=>'file-image-o','fa-file-movie-o'=>'file-movie-o','fa-file-o'=>'file-o','fa-file-pdf-o'=>'file-pdf-o','fa-file-photo-o'=>'file-photo-o','fa-file-picture-o'=>'file-picture-o','fa-file-powerpoint-o'=>'file-powerpoint-o','fa-file-sound-o'=>'file-sound-o','fa-file-text'=>'file-text','fa-file-text-o'=>'file-text-o','fa-file-video-o'=>'file-video-o','fa-file-word-o'=>'file-word-o','fa-file-zip-o'=>'file-zip-o','fa-files-o'=>'files-o','fa-film'=>'film','fa-filter'=>'filter','fa-fire'=>'fire','fa-fire-extinguisher'=>'fire-extinguisher','fa-flag'=>'flag','fa-flag-checkered'=>'flag-checkered','fa-flag-o'=>'flag-o','fa-flash'=>'flash','fa-flask'=>'flask','fa-flickr'=>'flickr','fa-floppy-o'=>'floppy-o','fa-folder'=>'folder','fa-folder-o'=>'folder-o','fa-folder-open'=>'folder-open','fa-folder-open-o'=>'folder-open-o','fa-font'=>'font','fa-forward'=>'forward','fa-foursquare'=>'foursquare','fa-frown-o'=>'frown-o','fa-gamepad'=>'gamepad','fa-gavel'=>'gavel','fa-gbp'=>'gbp','fa-ge'=>'ge','fa-gear'=>'gear','fa-gears'=>'gears','fa-gift'=>'gift','fa-git'=>'git','fa-git-square'=>'git-square','fa-github'=>'github','fa-github-alt'=>'github-alt','fa-github-square'=>'github-square','fa-gittip'=>'gittip','fa-glass'=>'glass','fa-globe'=>'globe','fa-google'=>'google','fa-google-plus'=>'google-plus','fa-google-plus-square'=>'google-plus-square','fa-graduation-cap'=>'graduation-cap','fa-group'=>'group','fa-h-square'=>'h-square','fa-hacker-news'=>'hacker-news','fa-hand-o-down'=>'hand-o-down','fa-hand-o-left'=>'hand-o-left','fa-hand-o-right'=>'hand-o-right','fa-hand-o-up'=>'hand-o-up','fa-hdd-o'=>'hdd-o','fa-header'=>'header','fa-headphones'=>'headphones','fa-heart'=>'heart','fa-heart-o'=>'heart-o','fa-history'=>'history','fa-home'=>'home','fa-hospital-o'=>'hospital-o','fa-html5'=>'html5','fa-image'=>'image','fa-inbox'=>'inbox','fa-indent'=>'indent','fa-info'=>'info','fa-info-circle'=>'info-circle','fa-inr'=>'inr','fa-instagram'=>'instagram','fa-institution'=>'institution','fa-italic'=>'italic','fa-joomla'=>'joomla','fa-jpy'=>'jpy','fa-jsfiddle'=>'jsfiddle','fa-key'=>'key','fa-keyboard-o'=>'keyboard-o','fa-krw'=>'krw','fa-language'=>'language','fa-laptop'=>'laptop','fa-leaf'=>'leaf','fa-legal'=>'legal','fa-lemon-o'=>'lemon-o','fa-level-down'=>'level-down','fa-level-up'=>'level-up','fa-life-bouy'=>'life-bouy','fa-life-ring'=>'life-ring','fa-life-saver'=>'life-saver','fa-lightbulb-o'=>'lightbulb-o','fa-link'=>'link','fa-linkedin'=>'linkedin','fa-linkedin-square'=>'linkedin-square','fa-linux'=>'linux','fa-list'=>'list','fa-list-alt'=>'list-alt','fa-list-ol'=>'list-ol','fa-list-ul'=>'list-ul','fa-location-arrow'=>'location-arrow','fa-lock'=>'lock','fa-long-arrow-down'=>'long-arrow-down','fa-long-arrow-left'=>'long-arrow-left','fa-long-arrow-right'=>'long-arrow-right','fa-long-arrow-up'=>'long-arrow-up','fa-magic'=>'magic','fa-magnet'=>'magnet','fa-mail-forward'=>'mail-forward','fa-mail-reply'=>'mail-reply','fa-mail-reply-all'=>'mail-reply-all','fa-male'=>'male','fa-map-marker'=>'map-marker','fa-maxcdn'=>'maxcdn','fa-medkit'=>'medkit','fa-meh-o'=>'meh-o','fa-microphone'=>'microphone','fa-microphone-slash'=>'microphone-slash','fa-minus'=>'minus','fa-minus-circle'=>'minus-circle','fa-minus-square'=>'minus-square','fa-minus-square-o'=>'minus-square-o','fa-mobile'=>'mobile','fa-mobile-phone'=>'mobile-phone','fa-money'=>'money','fa-moon-o'=>'moon-o','fa-mortar-board'=>'mortar-board','fa-music'=>'music','fa-navicon'=>'navicon','fa-openid'=>'openid','fa-outdent'=>'outdent','fa-pagelines'=>'pagelines','fa-paper-plane'=>'paper-plane','fa-paper-plane-o'=>'paper-plane-o','fa-paperclip'=>'paperclip','fa-paragraph'=>'paragraph','fa-paste'=>'paste','fa-pause'=>'pause','fa-paw'=>'paw','fa-pencil'=>'pencil','fa-pencil-square'=>'pencil-square','fa-pencil-square-o'=>'pencil-square-o','fa-phone'=>'phone','fa-phone-square'=>'phone-square','fa-photo'=>'photo','fa-picture-o'=>'picture-o','fa-pied-piper'=>'pied-piper','fa-pied-piper-alt'=>'pied-piper-alt','fa-pied-piper-square'=>'pied-piper-square','fa-pinterest'=>'pinterest','fa-pinterest-square'=>'pinterest-square','fa-plane'=>'plane','fa-play'=>'play','fa-play-circle'=>'play-circle','fa-play-circle-o'=>'play-circle-o','fa-plus'=>'plus','fa-plus-circle'=>'plus-circle','fa-plus-square'=>'plus-square','fa-plus-square-o'=>'plus-square-o','fa-power-off'=>'power-off','fa-print'=>'print','fa-puzzle-piece'=>'puzzle-piece','fa-qq'=>'qq','fa-qrcode'=>'qrcode','fa-question'=>'question','fa-question-circle'=>'question-circle','fa-quote-left'=>'quote-left','fa-quote-right'=>'quote-right','fa-ra'=>'ra','fa-random'=>'random','fa-rebel'=>'rebel','fa-recycle'=>'recycle','fa-reddit'=>'reddit','fa-reddit-square'=>'reddit-square','fa-refresh'=>'refresh','fa-renren'=>'renren','fa-reorder'=>'reorder','fa-repeat'=>'repeat','fa-reply'=>'reply','fa-reply-all'=>'reply-all','fa-retweet'=>'retweet','fa-rmb'=>'rmb','fa-road'=>'road','fa-rocket'=>'rocket','fa-rotate-left'=>'rotate-left','fa-rotate-right'=>'rotate-right','fa-rouble'=>'rouble','fa-rss'=>'rss','fa-rss-square'=>'rss-square','fa-rub'=>'rub','fa-ruble'=>'ruble','fa-rupee'=>'rupee','fa-save'=>'save','fa-scissors'=>'scissors','fa-search'=>'search','fa-search-minus'=>'search-minus','fa-search-plus'=>'search-plus','fa-send'=>'send','fa-send-o'=>'send-o','fa-share'=>'share','fa-share-alt'=>'share-alt','fa-share-alt-square'=>'share-alt-square','fa-share-square'=>'share-square','fa-share-square-o'=>'share-square-o','fa-shield'=>'shield','fa-shopping-cart'=>'shopping-cart','fa-sign-in'=>'sign-in','fa-sign-out'=>'sign-out','fa-signal'=>'signal','fa-sitemap'=>'sitemap','fa-skype'=>'skype','fa-slack'=>'slack','fa-sliders'=>'sliders','fa-smile-o'=>'smile-o','fa-sort'=>'sort','fa-sort-alpha-asc'=>'sort-alpha-asc','fa-sort-alpha-desc'=>'sort-alpha-desc','fa-sort-amount-asc'=>'sort-amount-asc','fa-sort-amount-desc'=>'sort-amount-desc','fa-sort-asc'=>'sort-asc','fa-sort-desc'=>'sort-desc','fa-sort-down'=>'sort-down','fa-sort-numeric-asc'=>'sort-numeric-asc','fa-sort-numeric-desc'=>'sort-numeric-desc','fa-sort-up'=>'sort-up','fa-soundcloud'=>'soundcloud','fa-space-shuttle'=>'space-shuttle','fa-spinner'=>'spinner','fa-spoon'=>'spoon','fa-spotify'=>'spotify','fa-square'=>'square','fa-square-o'=>'square-o','fa-stack-exchange'=>'stack-exchange','fa-stack-overflow'=>'stack-overflow','fa-star'=>'star','fa-star-half'=>'star-half','fa-star-half-empty'=>'star-half-empty','fa-star-half-full'=>'star-half-full','fa-star-half-o'=>'star-half-o','fa-star-o'=>'star-o','fa-steam'=>'steam','fa-steam-square'=>'steam-square','fa-step-backward'=>'step-backward','fa-step-forward'=>'step-forward','fa-stethoscope'=>'stethoscope','fa-stop'=>'stop','fa-strikethrough'=>'strikethrough','fa-stumbleupon'=>'stumbleupon','fa-stumbleupon-circle'=>'stumbleupon-circle','fa-subscript'=>'subscript','fa-suitcase'=>'suitcase','fa-sun-o'=>'sun-o','fa-superscript'=>'superscript','fa-support'=>'support','fa-table'=>'table','fa-tablet'=>'tablet','fa-tachometer'=>'tachometer','fa-tag'=>'tag','fa-tags'=>'tags','fa-tasks'=>'tasks','fa-taxi'=>'taxi','fa-tencent-weibo'=>'tencent-weibo','fa-terminal'=>'terminal','fa-text-height'=>'text-height','fa-text-width'=>'text-width','fa-th'=>'th','fa-th-large'=>'th-large','fa-th-list'=>'th-list','fa-thumb-tack'=>'thumb-tack','fa-thumbs-down'=>'thumbs-down','fa-thumbs-o-down'=>'thumbs-o-down','fa-thumbs-o-up'=>'thumbs-o-up','fa-thumbs-up'=>'thumbs-up','fa-ticket'=>'ticket','fa-times'=>'times','fa-times-circle'=>'times-circle','fa-times-circle-o'=>'times-circle-o','fa-tint'=>'tint','fa-toggle-down'=>'toggle-down','fa-toggle-left'=>'toggle-left','fa-toggle-right'=>'toggle-right','fa-toggle-up'=>'toggle-up','fa-trash-o'=>'trash-o','fa-tree'=>'tree','fa-trello'=>'trello','fa-trophy'=>'trophy','fa-truck'=>'truck','fa-try'=>'try','fa-tumblr'=>'tumblr','fa-tumblr-square'=>'tumblr-square','fa-turkish-lira'=>'turkish-lira','fa-twitter'=>'twitter','fa-twitter-square'=>'twitter-square','fa-umbrella'=>'umbrella','fa-underline'=>'underline','fa-undo'=>'undo','fa-university'=>'university','fa-unlink'=>'unlink','fa-unlock'=>'unlock','fa-unlock-alt'=>'unlock-alt','fa-unsorted'=>'unsorted','fa-upload'=>'upload','fa-usd'=>'usd','fa-user'=>'user','fa-user-md'=>'user-md','fa-users'=>'users','fa-video-camera'=>'video-camera','fa-vimeo-square'=>'vimeo-square','fa-vine'=>'vine','fa-vk'=>'vk','fa-volume-down'=>'volume-down','fa-volume-off'=>'volume-off','fa-volume-up'=>'volume-up','fa-warning'=>'warning','fa-wechat'=>'wechat','fa-weibo'=>'weibo','fa-weixin'=>'weixin','fa-wheelchair'=>'wheelchair','fa-windows'=>'windows','fa-won'=>'won','fa-wordpress'=>'wordpress','fa-wrench'=>'wrench','fa-xing'=>'xing','fa-xing-square'=>'xing-square','fa-yahoo'=>'yahoo','fa-yen'=>'yen','fa-youtube'=>'youtube','fa-youtube-play'=>'youtube-play','fa-youtube-square'=>'youtube-square',) );



/************************************* CORE FUNCTIONS *******************************************/

/**
 * Dump a variable or array and show as formatted way
 *
 * @since 1.0
 *
 * @param bool          $var the variable you want to dump
 * @print string        The dumped data
 */
function dump( $var ){
    echo '<pre>';
    var_dump( $var );
    echo '</pre>';
}

function get_user_by_meta( $key, $val ) {
    return get_users( array( 'meta_key' => $key, 'meta_value' => $val ) );
}

/**
 * Get current page URL
 *
 * @param bool          $queryPram if you want to get url with query string, false otherwise
 * @return string       the url of the current page
 */
function curPageURL( $queryPram = true ) {
    $url = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
        $url .= "s";
    $url .= "://";
    if ($_SERVER["SERVER_PORT"] != "80")
        $url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    else
        $url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    if( $queryPram ) return $url;
    else{
        $url = explode( '?', $url );
        return $url[0];
    }
}

/**
 * Get the ID and display name of all users
 *
 * @return object       List of all users
 */
function get_user_list() {
    $users = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );
    return $users;
}


function avalable_addons(){
    //echo DKB_ADDONS_DIR;
    $addons = array();
    if ($handle = opendir(DKB_ADDONS_DIR)) {

        while (false !== ($entry = readdir($handle))) {

            if ($entry != "." && $entry != "..") {
                require_once DKB_ADDONS_DIR . '/' . $entry;
            }
        }

        closedir($handle);
    }
}
//avalable_addons();

/**
 * @param $hex
 * @return string
 */
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    return implode(",", $rgb); // returns the rgb values separated by commas
    //return $rgb; // returns an array with the rgb values
}

function get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}





/************************************* TEMPLATE FUNCTIONS *******************************************/

if( ! function_exists( 'get_date_time_value' ) ) {
    /**
     * Customized date and time based on wordpress settings
     *
     * @param bool|string               $str false if you don't pass any timestamp, otherwise the timestamp value
     * @param bool          $date       true if want to return date based in wordpress settings
     * @param bool          $time       true if want to return time based in wordpress settings
     * @param bool          $zone       true if want to return gmt value based in wordpress settings
     * @return string
     */
    function get_date_time_value( $str = false, $date = true, $time = false, $zone = false )
    {
        $res = '';
        if ( $str == false ) {
            $str = current_time( 'timestamp' );
        }
        if ( $date ) {
            $res .= date_i18n( get_option( 'date_format' ), $str );
        }
        if ( $time ) {
            $res .= ' ' . date_i18n( get_option( 'time_format' ), $str );
        }
        if ( $zone ) {
            $res .= ' UTC ' . ( ( get_option( 'gmt_offset' ) > 0 ) ? '+ ' : '' ) . get_option( 'gmt_offset' );
        }
        return $res;
    }
}