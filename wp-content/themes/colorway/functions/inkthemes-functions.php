<?php
ob_start();

function inkthemes_setup() {
    add_theme_support('custom-background', array(
        'default-color' => '#cecece',
            //'default-image' => get_template_directory_uri() . '/assets/images/body-bg.png'
    ));

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('customize-selective-refresh-widgets');
    add_editor_style();
    add_image_size('post_thumbnail', 393, 200, true);
    add_image_size('colorway_custom_size', 260, 350, true);

    add_theme_support('automatic-feed-links');
    register_nav_menu('custom_menu', 'Main Menu');

    load_theme_textdomain('colorway', get_template_directory() . '/languages');

    add_theme_support('custom-header', array(
        //'default-image' => get_template_directory_uri() . '/assets/images/bg-img.jpg',
        'random-default' => false,
        'width' => '',
        'height' => '',
        'flex-height' => true,
        'flex-width' => true,
        'default-text-color' => '',
        'header-text' => true,
        'uploads' => true,
        'wp-head-callback' => '',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
    ));
}

add_action('after_setup_theme', 'inkthemes_setup');
/* ----------------------------------------------------------------------------------- */
/* Custom Menus Function
  /*----------------------------------------------------------------------------------- */

// Add CLASS attributes to the first <ul> occurence in wp_page_menu
function inkthemes_add_menuclass($ulclass) {
    return preg_replace('/<ul>/', '<ul class="ddsmoothmenu">', $ulclass, 1);
}

add_filter('wp_page_menu', 'inkthemes_add_menuclass');

function inkthemes_nav() {
    if (function_exists('wp_nav_menu'))
        wp_nav_menu(array('theme_location' => 'custom_menu', 'items_wrap' => inkthemes_menu_button(), 'container_id' => 'menu', 'menu_class' => 'sf-menu', 'fallback_cb' => 'inkthemes_nav_fallback'));
    else
        inkthemes_nav_fallback();
}

function inkthemes_nav_fallback() {
    ?>
    <div id="menu">
        <ul class="sf-menu">
            <?php
            wp_list_pages('title_li=&show_home=1&sort_column=menu_order');
            ?>

        </ul>
    </div>
    <?php
}

function sticky_header() {
    wp_register_script('inkthemes_stickyheader_js', get_template_directory_uri() .'/assets/js/stickyheader.js',  array( 'jquery' ), '' , true );
        wp_enqueue_script('inkthemes_stickyheader_js');
        wp_enqueue_style('inkthemes_stickyheader_css', get_template_directory_uri() .'/assets/css/stickyheader.css');
    }
    
if(inkthemes_get_option('colorway_sticky_header',false) == true){
add_action( 'wp_enqueue_scripts', 'sticky_header' );
}

function inkthemes_menu_button() {
    // default value of 'items_wrap' is <ul id="%1$s" class="%2$s">%3$s</ul>'  
    // open the <ul>, set 'menu_class' and 'menu_id' values
    $wrap = '<ul id="%1$s" class="%2$s">';
    // get nav items as configured in /wp-admin/
    $wrap .= '%3$s';
    // the static link 
    if (inkthemes_get_option('colorway_button_html') != '' && inkthemes_get_option('btn_on_off') != "off") {
        $wrap .= "<li class='colorway_button_html'> " . html_entity_decode(inkthemes_get_option('colorway_button_html')) . "</li>";
    }
    // close the <ul>
    $wrap .= '</ul>';
    // return the result
    return $wrap;
}

function inkthemes_new_nav_menu_items($items) {
    if (is_home()) {
        $homelink = '<li class="current_page_item">' . '<a href="' . home_url('/') . '">' . __('Home', 'colorway') . '</a></li>';
    } else {
        $homelink = '<li>' . '<a href="' . home_url('/') . '">' . 'Home' . '</a></li>';
    }
    if (inkthemes_get_option('colorway_button_html') != '' && inkthemes_get_option('btn_on_off') != "off") {
        $items .= "<li class='colorway_button_html'> " . html_entity_decode(inkthemes_get_option('colorway_button_html')) . "</li>";
    }
    $items = $homelink . $items;
    return $items;
}

add_filter('wp_list_pages', 'inkthemes_new_nav_menu_items');

//function add_last_nav_item($items) {
//  echo $items .= inkthemes_menu_button();
//}
//add_filter('wp_nav_menu_menu_items','add_last_nav_item');



if (!function_exists('inkthemes_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own inkthemes_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    function inkthemes_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case '' :
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <div id="comment-<?php comment_ID(); ?>">
                        <div class="comment-author vcard"> <?php echo get_avatar($comment, 40); ?> <?php printf('%s <span class="says">says:</span>' . sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?> </div>
                        <!-- .comment-author .vcard -->
                        <?php if ($comment->comment_approved == '0') : ?>
                            <em> <?php echo ('Your comment is awaiting moderation.'); ?> </em> <br />
                        <?php endif; ?>
                        <div class="comment-meta commentmetadata"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                <?php
                                /* translators: 1: date, 2: time */
                                printf('%1$s at %2$s' . get_comment_date(), get_comment_time());
                                ?>
                            </a>
                            <?php edit_comment_link('(Edit)', ' ');
                            ?>
                        </div>
                        <!-- .comment-meta .commentmetadata -->
                        <div class="comment-body">
                            <?php comment_text(); ?>
                        </div>
                        <div class="reply">
                            <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div>
                        <!-- .reply -->
                    </div>
                    <!-- #comment-##  -->
                    <?php
                    break;
                case 'pingback' :
                case 'trackback' :
                    ?>
                <li class="post pingback">
                    <p> <?php echo ('Pingback:'); ?>
                        <?php comment_author_link(); ?>
                        <?php edit_comment_link('(Edit)', ' '); ?>
                    </p>
                    <?php
                    break;
            endswitch;
        }

    endif;

    /**
     * Set the content width based on the theme's design and stylesheet.
     *
     * Used to set the width of images and content. Should be equal to the width the theme
     * is designed for, generally via the style.css stylesheet.
     */
    if (!isset($content_width))
        $content_width = 590;

    /**
     * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
     *
     * To override inkthemes_widgets_init() in a child theme, remove the action hook and add your own
     * function tied to the init hook.
     * @uses register_sidebar
     */
    function inkthemes_widgets_init() {
        // Area 1, located at the top of the sidebar.
        register_sidebar(array(
            'name' => __('Primary Widget Area', 'colorway'),
            'id' => 'primary-widget-area',
            'description' => __('The primary widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
        // Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
        register_sidebar(array(
            'name' => __('Secondary Widget Area', 'colorway'),
            'id' => 'secondary-widget-area',
            'description' => __('The secondary widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
        // Area 3, located in the footer. Empty by default.
        register_sidebar(array(
            'name' => __('First Footer Widget Area', 'colorway'),
            'id' => 'footer-widget-area1',
            'description' => __('The first footer widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h6>',
            'after_title' => '</h6>',
        ));
        // Area 4, located in the footer. Empty by default.
        register_sidebar(array(
            'name' => __('Second Footer Widget Area', 'colorway'),
            'id' => 'footer-widget-area2',
            'description' => __('The second footer widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h6>',
            'after_title' => '</h6>',
        ));
        // Area 5, located in the footer. Empty by default.
        register_sidebar(array(
            'name' => __('Third Footer Widget Area', 'colorway'),
            'id' => 'footer-widget-area3',
            'description' => __('The third footer widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h6>',
            'after_title' => '</h6>',
        ));
        // Area 6, located in the footer. Empty by default.
        register_sidebar(array(
            'name' => __('Fourth Footer Widget Area', 'colorway'),
            'id' => 'footer-widget-area4',
            'description' => __('The fourth footer widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h6>',
            'after_title' => '</h6>',
        ));
        // Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
        register_sidebar(array(
            'name' => __('Home Page Left Feature Widget Area', 'colorway'),
            'id' => 'home-page-right-feature-widget-area',
            'description' => __('The Home Page Left Feature widget area', 'colorway'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4>',
            'after_title' => '</h4>',
        ));
        //default header
        register_default_headers(array(
            'default-image' => array(
                'url' => get_template_directory_uri() . '/assets/images/3.jpg',
                'thumbnail_url' => get_template_directory_uri() . '/assets/images/3.jpg',
                'description' => __('Default Header Image', 'colorway')
            ),
        ));
    }

    /** Register sidebars by running inkthemes_widgets_init() on the widgets_init hook. */
    add_action('widgets_init', 'inkthemes_widgets_init');

    /**
     * Display navigation to next/previous pages when applicable
     */
    function inkthemes_content_nav($nav_id) {
        global $wp_query;
        if ($wp_query->max_num_pages > 1) :
            ?>
            <nav id="<?php echo esc_attr($nav_id); ?>">
                <h3 class="assistive-text"><?php echo esc_html__('Post navigation', 'colorway'); ?></h3>
                <div class="nav-next">
                    <?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'colorway')); ?>
                </div>
                <div class="nav-previous">
                    <?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'colorway')); ?>
                </div>  
            </nav>
            <!-- #nav-above -->
            <?php
        endif;
    }

    /**
     * Pagination
     *
     */
    function inkthemes_pagination($pages = '', $range = 2) {
        $showitems = ($range * 2) + 1;
        global $paged;
        if (empty($paged))
            $paged = 1;
        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }
        if (1 != $pages) {
            echo "<ul class='paging'>";
            if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
                echo "<li><a href='" . esc_url(get_pagenum_link(1)) . "'>&laquo;</a></li>";
            if ($paged > 1 && $showitems < $pages)
                echo "<li><a href='" . esc_url(get_pagenum_link($paged - 1)) . "'>&lsaquo;</a></li>";
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                    echo ($paged == $i) ? "<li><a href='" . esc_url(get_pagenum_link($i)) . "' class='current' >" . esc_html($i) . "</a></li>" : "<li><a href='" . esc_url(get_pagenum_link($i)) . "' class='inactive' >" . esc_html($i) . "</a></li>";
                }
            }
            if ($paged < $pages && $showitems < $pages)
                echo "<li><a href='" . esc_url(get_pagenum_link($paged + 1)) . "'>&rsaquo;</a></li>";
            if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
                echo "<li><a href='" . esc_url(get_pagenum_link($pages)) . "'>&raquo;</a></li>";
            echo "</ul>\n";
        }
    }

    /* ----------------------------------------------------------------------------------- */
    /* Show analytics code in footer */
    /* ----------------------------------------------------------------------------------- */

    function inkthemes_analytics() {
        $output = inkthemes_get_option('colorway_analytics');
        if ($output <> "")
            echo html_entity_decode($output);
    }

    add_action('wp_head', 'inkthemes_analytics');

//Green color style
    function inkthemes_green_css() {
        ?>
        <?php
        //wp_enqueue_style('inkthemes-green-css', get_template_directory_uri() . '/assets/css/green.css', '', '', 'all');
    }

    add_action('wp_head', 'inkthemes_green_css');

//Trm post title
    function the_titlesmall($before = '', $after = '', $echo = true, $length = false) {
        $title = get_the_title();
        if ($length && is_numeric($length)) {
            $title = substr($title, 0, $length);
        }
        if (strlen($title) > 0) {
            $title = apply_filters('the_titlesmall', $before . $title . $after, $before, $after);
            if ($echo)
                echo esc_attr($title);
            else
                return esc_attr($title);
        }
    }

    ob_clean();

    /*
     * * Enqueue Google Fonts
     */

    function colorway_gfonts_scripts() {
        wp_enqueue_style('colorway-google-fonts', colorway_google_fonts());
        add_action('wp_head', 'colorway_typography');
    }

    add_action('wp_enqueue_scripts', 'colorway_gfonts_scripts');

    $fonts = array('ABeeZee', 'Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akronim', 'Aladin', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya SC', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra Display', 'Almendra SC', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Anaheim', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo Black', 'Archivo Narrow', 'Arial Black', 'Arial Narrow', 'Arimo', 'Arizonia', 'Armata', 'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar', 'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Bell MT', 'Bell MT Alt', 'Belleza', 'BenchNine', 'Bentham', 'Berkshire Swash', 'Bevan', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bodoni', 'Bokor', 'Bonbon', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Bubbler One', 'Buenard', 'Butcherman', 'Butcherman Caps', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calibri', 'Calligraffitti', 'Cambo', 'Cambria', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Caudex', 'Cedarville Cursive', 'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chivo', 'Cinzel', 'Cinzel Decorative', 'Clara', 'Clicker Script', 'Coda', 'Codystar', 'Combo', 'Comfortaa', 'Coming Soon', 'Concert One', 'Condiment', 'Consolas', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Corsiva', 'Courgette', 'Courier New', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Creepster Caps', 'Crete Round', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhyana', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Arabic Kufi', 'Droid Arabic Naskh', 'Droid Sans', 'Droid Sans Mono', 'Droid Sans TV', 'Droid Serif', 'Duru Sans', 'Dynalight', 'EB Garamond', 'Eagle Lake', 'Eater', 'Eater Caps', 'Economica', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Engagement', 'Englebert', 'Enriqueta', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Finger Paint', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'GFS Didot', 'GFS Neohellenic', 'Gabriela', 'Gafata', 'Galdeano', 'Galindo', 'Garamond', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One', 'Gilda Display', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas', 'Goudy Bookletter 1911', 'Graduate', 'Grand Hotel', 'Gravitas One', 'Great Vibes', 'Griffy', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Headland One', 'Helvetica Neue', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Irish Grover', 'Irish Growler', 'Istok Web', 'Italiana', 'Italianno', 'Jacques Francois', 'Jacques Francois Shadow', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans', 'Josefin Sans Std Light', 'Josefin Slab', 'Joti One', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron', 'Karla', 'Kaushan Script', 'Kavoon', 'Keania One', 'Kelly Slab', 'Kenia', 'Khmer', 'Kite One', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemon One', 'Libre Baskerville', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Lohit Bengali', 'Lohit Devanagari', 'Lohit Tamil', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Miss Saint Delafield', 'Modern Antiqua', 'Molengo', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedford', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'New Rocker', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Nosifer Caps', 'Nothing You Could Do', 'Noticia Text', 'Noto Sans', 'Noto Sans UI', 'Noto Serif', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Offside', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orienta', 'Original Surfer', 'Oswald', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'Oxygen Mono', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Patua One', 'Paytone One', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab', 'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Proxima Nova', 'Proxima Nova Tabular Figures', 'Puritan', 'Poppins', 'Purple Purse', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Raleway', 'Raleway Dots', 'Rambla', 'Rammetto One', 'Ranchers', 'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Slab', 'Rochester', 'Rock Salt', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sail', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita One', 'Sans Serif', 'Sarina', 'Satisfy', 'Scada', 'Scheherazade', 'Schoolbell', 'Seaweed Script', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shojumaru', 'Short Stack', 'Siamreap', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slackey', 'Smokum', 'Smythe', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Special Elite', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stalemate', 'Stalin One', 'Stalinist One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'Stoke', 'Strait', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tahoma', 'Tangerine', 'Taprom', 'Tauri', 'Telex', 'Tenor Sans', 'Terminal Dosis', 'Terminal Dosis Light', 'Text Me One', 'Thabit', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Titillium Web', 'Trade Winds', 'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Vampiro One', 'Varela', 'Varela Round', 'Vast Shadow', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada', 'jsMath cmbx10', 'jsMath cmex10', 'jsMath cmmi10', 'jsMath cmr10', 'jsMath cmsy10', 'jsMath cmti10', 'Maracellus');
    $fontwt = array('Default', 'normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');
    $fontsz = array('8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40');

    /* String of Google fonts */
function colorway_google_fonts() {
    
       global $fonts;
       global $fontwt;
       global $fontsz;
       $fonts_collection = add_query_arg(array(
           "family" => implode("|", $fonts),
           "font-weight" => implode("|", $fontwt),
           "font-size" => implode("|", $fontsz),
           "subset" => "latin"
               ), '//fonts.googleapis.com/css');
       return $fonts_collection;
   }
    /* typography style CSS code function */

    function colorway_typography() {
        global $fonts;
        global $fontwt;
        global $fontsz;

        $index = inkthemes_get_option('typography_logo_family', '525');
        $index0 = inkthemes_get_option('typography_menu_family', '525');
        $index1 = inkthemes_get_option('typography_nav_family', '345');
        $index2 = inkthemes_get_option('typography_heading1', '525');
        $index3 = inkthemes_get_option('typography_heading2', '525');
        $index4 = inkthemes_get_option('typography_heading3', '525');
        $index5 = inkthemes_get_option('typography_heading4', '525');
        $index6 = inkthemes_get_option('typography_heading5', '525');
        $index7 = inkthemes_get_option('typography_heading6', '525');
        $index8 = inkthemes_get_option('typography_para');


        $fontweight = inkthemes_get_option('typography_title_fontweight', '10');
        $fontweight0 = inkthemes_get_option('typography_tagline_fontweight', '8');
        $fontweight_menu = inkthemes_get_option('typography_fontweight_navmenu', '8');
        $fontweight1 = inkthemes_get_option('typography_fontweight_heading1', '10');
        $fontweight2 = inkthemes_get_option('typography_fontweight_heading2', '10');
        $fontweight3 = inkthemes_get_option('typography_fontweight_heading3', '10');
        $fontweight4 = inkthemes_get_option('typography_fontweight_heading4', '8');
        $fontweight5 = inkthemes_get_option('typography_fontweight_heading5', '10');
        $fontweight6 = inkthemes_get_option('typography_fontweight_heading6', '8');
        $fontweight7 = inkthemes_get_option('typography_fontweight_para');
        
        $font_menu_size = inkthemes_get_option('typography_menu_fontsize', '8');
        $font_btn_size = inkthemes_get_option('typography_btn_fontsize', '9');
        $font_para = inkthemes_get_option('typography_fontsize_para');
        $fontsize1 = inkthemes_get_option('typography_fontsize_heading1','27');
        $fontsize2 = inkthemes_get_option('typography_fontsize_heading2', '24');
        $fontsize3 = inkthemes_get_option('typography_fontsize_heading3', '21');
        $fontsize4 = inkthemes_get_option('typography_fontsize_heading4', '16');
        $fontsize5 = inkthemes_get_option('typography_fontsize_heading5', '16');
        $fontsize6 = inkthemes_get_option('typography_fontsize_heading6', '14');
        $fontsize7 = inkthemes_get_option('footer_fontsize_para');
        $fontsize8 = inkthemes_get_option('footer_fontsize_link', '8');



        if ($index != '') {
            echo "<style type='text/css'> h1.site-title, p.site-description{ font-family: '" . esc_attr($fonts[$index]) . "', Sans-Serif; }</style>";
        }
        if ($index0 != '') {
            echo "<style type='text/css'> #menu .sf-menu li a, ul.sf-menu button{ font-family: '" . esc_attr($fonts[$index0]) . "', Sans-Serif;}</style>";
        }
        if ($index1 != '') {
            echo "<style type='text/css'> body{ font-family: '" . esc_attr($fonts[$index1]) . "', Sans-Serif;}</style>";
        }
        if ($index2 != '') {
            echo "<style type='text/css'> h1{ font-family: '" . esc_attr($fonts[$index2]) . "', Sans-Serif;}</style>";
        }
        if ($index3 != '') {
            echo "<style type='text/css'> h2{ font-family: '" . esc_attr($fonts[$index3]) . "', Sans-Serif;}</style>";
        }
        if ($index4 != '') {
            echo "<style type='text/css'> h3{ font-family: '" . esc_attr($fonts[$index4]) . "', Sans-Serif;}</style>";
        }
        if ($index5 != '') {
            echo "<style type='text/css'> h4{ font-family: '" . esc_attr($fonts[$index5]) . "', Sans-Serif;}</style>";
        }
        if ($index6 != '') {
            echo "<style type='text/css'> h5{ font-family: '" . esc_attr($fonts[$index6]) . "', Sans-Serif;}</style>";
        }
        if ($index7 != '') {
            echo "<style type='text/css'> h6{ font-family: '" . esc_attr($fonts[$index7]) . "', Sans-Serif;}</style>";
        }
        if ($index8 != '') {
            echo "<style type='text/css'> p{ font-family: '" . esc_attr($fonts[$index8]) . "', Sans-Serif;}</style>";
        }

        //Font Weight Typography
        if ($fontweight != '') {
            echo "<style type='text/css'> h1.site-title{ font-weight: " . esc_attr($fontwt[$fontweight]) . "; }</style>";
        }
        if ($fontweight0 != '') {
            echo "<style type='text/css'> p.site-description{ font-weight: " . esc_attr($fontwt[$fontweight0]) . "; }</style>";
        }
        if ($fontweight_menu != '') {
            echo "<style type='text/css'> #menu .sf-menu li a{ font-weight: " . esc_attr($fontwt[$fontweight_menu]) . "; }</style>";
        }
        if ($fontweight1 != '') {
            echo "<style type='text/css'> h1{ font-weight: " . esc_attr($fontwt[$fontweight1]) . "; }</style>";
        }
        if ($fontweight2 != '') {
            echo "<style type='text/css'> h2{ font-weight: " . esc_attr($fontwt[$fontweight2]) . "; }</style>";
        }
        if ($fontweight3 != '') {
            echo "<style type='text/css'> h3{ font-weight: " . esc_attr($fontwt[$fontweight3]) . "; }</style>";
        }
        if ($fontweight4 != '') {
            echo "<style type='text/css'> h4{ font-weight: " . esc_attr($fontwt[$fontweight4]) . "; }</style>";
        }
        if ($fontweight5 != '') {
            echo "<style type='text/css'> h5{ font-weight: " . esc_attr($fontwt[$fontweight5]) . "; }</style>";
        }
        if ($fontweight6 != '') {
            echo "<style type='text/css'> h6{ font-weight: " . esc_attr($fontwt[$fontweight6]) . "; }</style>";
        }
        if ($fontweight7 != '') {
            echo "<style type='text/css'> p{ font-weight: " . esc_attr($fontwt[$fontweight7]) . "; }</style>";
        }
        if ($font_menu_size != '') {
            echo "<style type='text/css'> #menu .sf-menu li a{ font-size: " . esc_attr($fontsz[$font_menu_size]) . "px; }</style>";
        }
        if ($font_btn_size != '') {
            echo "<style type='text/css'> li.colorway_button_html button{ font-size: " . esc_attr($fontsz[$font_btn_size]) . "px; }</style>";
        }
        if ($font_para != '') {
            echo "<style type='text/css'> p{ font-size: " . esc_attr($fontsz[$font_para]) . "px; }</style>";
        }
        if ($fontsize1 != '') {
            echo "<style type='text/css'> h1{ font-size: " . esc_attr($fontsz[$fontsize1]) . "px; }</style>";
        }
        if ($fontsize2 != '') {
            echo "<style type='text/css'> h2{ font-size: " . esc_attr($fontsz[$fontsize2]) . "px; }</style>";
        }
        if ($fontsize3 != '') {
            echo "<style type='text/css'> h3{ font-size: " . esc_attr($fontsz[$fontsize3]) . "px; }</style>";
        }
        if ($fontsize4 != '') {
            echo "<style type='text/css'> h4{ font-size: " . esc_attr($fontsz[$fontsize4]) . "px; }</style>";
        }
        if ($fontsize5 != '') {
            echo "<style type='text/css'> h5{ font-size: " . esc_attr($fontsz[$fontsize5]) . "px; }</style>";
        }
        if ($fontsize6 != '') {
            echo "<style type='text/css'> .footer-container h6, .footer .widget_inner h4{ font-size: " . esc_attr($fontsz[$fontsize6]) . "px; }</style>";
        }
        if ($fontsize7 != '') {
            echo "<style type='text/css'> .footer p, .footer a{ font-size: " . esc_attr($fontsz[$fontsize7]) . "px; }</style>";
        }
        if ($fontsize8 != '') {
            echo "<style type='text/css'> .footer-navi a{ font-size: " . esc_attr($fontsz[$fontsize8]) . "px; }</style>";
        }
    }

    function menu_link_color_styles() {
        $header_bg = inkthemes_get_option('header_bg_color');
        $site_title = inkthemes_get_option('site_title_color', '#3868bb');
        $site_tagline = inkthemes_get_option('site_tagline_color');
        $menu_color = inkthemes_get_option('menu_link_color');
        $menu_hover_color = inkthemes_get_option('menu_hover_color');
        $menu_bg_color = inkthemes_get_option('menu_background_color');
        $menu_bg_hover_color = inkthemes_get_option('menu_background_hover_color');
        $menu_dd_hover_color = inkthemes_get_option('menu_dropdown_hover_color');

        $button_color = inkthemes_get_option('button_link_color');
        $button_hover_color = inkthemes_get_option('button_link_hover_color');
        $button_bg_color = inkthemes_get_option('button_bg_color');
        $button_bg_hover = inkthemes_get_option('button_bg_hover_color');

        $theme_link = inkthemes_get_option('theme_link_color');
        $theme_link_hover = inkthemes_get_option('theme_link_hover_color');
        $theme_h1 = inkthemes_get_option('theme_h1_color');
        $theme_h2 = inkthemes_get_option('theme_h2_color');
        $theme_h3 = inkthemes_get_option('theme_h3_color');
        $theme_h4 = inkthemes_get_option('theme_h4_color');
        $theme_h5 = inkthemes_get_option('theme_h5_color');
        $theme_h6 = inkthemes_get_option('theme_h6_color');
        $theme_para = inkthemes_get_option('theme_para_color');

        $footer_link = inkthemes_get_option('footer_link_color', '#949494');
        $footer_link_hover = inkthemes_get_option('footer_link_hover_color');
        $footer_text = inkthemes_get_option('footer_text_color','#949494');
        $footer_head_col = inkthemes_get_option('footer_header_color','#cccccc');
        $footer_col = inkthemes_get_option('footer_col_bg_color', '#343434');
        $footer_bottom = inkthemes_get_option('bottom_footer_bg_color', '#292929');

        $btn_rad = get_option('btn_rad');
        $btn_h_pad = get_option('btn_h_pad');
        $btn_v_pad = get_option('btn_v_pad');

//       $header_height = get_option('header_height');

        $header_v_pad = get_option('header_v_pad',45);
        $header_h_pad = get_option('header_h_pad', 85);
        $content_h_pad = get_option('content_h_pad',100);
        $content_v_pad = get_option('content_v_pad');
        $bottom_footer_css = get_option('container-layout');

//         if ($bottom_footer_css == 'container') {
//            echo "<style type='text/css'>.cw-content {
//					margin-bottom: 60px;}</style>";
//				}

        if ($btn_rad != '') {
            echo '<style type="text/css">ul.sf-menu li.colorway_button_html button{ border-radius:' . esc_attr($btn_rad) . 'px;}'
            . 'ul.sf-menu li.colorway_button_html span button { border-radius: 50%;}</style>';
        }

        if ($btn_h_pad != '') {
            echo '<style type="text/css">ul.sf-menu li.colorway_button_html button{ padding:' . esc_attr($btn_v_pad) . 'px ' . esc_attr($btn_h_pad) . 'px;}'
            . 'ul.sf-menu li.colorway_button_html span button { padding: inherit;}</style>';
        }

        if ($header_bg != '#000') {
            ?>
            <style type="text/css">
                .container-h {
                    background-color: <?php echo esc_attr($header_bg); ?>;
                }
            </style>
        <?php
    }
    if ($site_title != '#000') {
        ?>
            <style type="text/css">
                h1.site-title {
                    color: <?php echo esc_attr($site_title); ?>;
                }
            </style>
        <?php
    }
    if ($site_tagline != '#000') {
        ?>
            <style type="text/css">
                p.site-description {
                    color: <?php echo esc_attr($site_tagline); ?>;
                }
            </style>
        <?php
    }
//        if ($header_height != '') {
//            echo '<style type="text/css">.header{ height:' . $header_height . 'px;}</style>';
//        }
    if ($header_v_pad != '') {
        echo '<style type="text/css">.header{ padding:' . esc_attr($header_v_pad) . 'px 0;}</style>';
    }
    if ($header_h_pad != '') {
        echo '<style type="text/css">@media only screen and ( min-width: 968px ){ .container{ width:' . esc_attr($header_h_pad) . '%;}}</style>';
    }
        if ($content_h_pad != '') {
        echo '<style type="text/css"> .cyw-container{ width:' . esc_attr($content_h_pad) . '%;}</style>';
        }
        if ($content_v_pad != '') {
        echo '<style type="text/css"> .cw-content.container-fluid{ padding-top:' . esc_attr($content_v_pad) . 'px;}</style>';
        }
    if ($menu_color != '') {
        ?>
            <style type="text/css">
                #menu .sf-menu li.menu-item a, #menu .sf-menu li.page_item a, #menu .sf-menu li.page_item li a, #menu .sf-menu li.menu-item li a:link, #menu .sf-menu li.current_page_item a {
                    color: <?php echo esc_attr($menu_color); ?>;
                }
            </style>
        <?php
    }
    if ($menu_hover_color != '') {
        ?>
            <style type="text/css">
                #menu .sf-menu li.menu-item a:hover, #menu .sf-menu li.page_item a:hover, #menu .sf-menu li.page_item li a:hover, #menu .sf-menu li.menu-item li a:link:hover{
                    color: <?php echo esc_attr($menu_hover_color); ?>;
                }
            </style>
        <?php
    }
    if ($menu_bg_color != '#2B4908') {
        ?>
            <style type="text/css">
                #menu li.current_page_item a, #menu li.current_page_parent a, #menu .sf-menu li li {
                    background-color: <?php echo esc_attr($menu_bg_color); ?>;
                }
            </style>
        <?php
    }
    if ($menu_bg_hover_color != '#2B4908') {
        ?>
            <style type="text/css">
                #menu li.current_page_item a:hover, #menu .sf-menu li.menu-item a:hover, #menu .sf-menu li.page_item a:hover {
                    background-color: <?php echo esc_attr($menu_bg_hover_color); ?>;
                }
            </style>
        <?php
    }
    if ($menu_dd_hover_color != '#2B4908') {
        ?>
            <style type="text/css">
                #menu .sf-menu li li a:hover{
                    background-color: <?php echo esc_attr($menu_dd_hover_color); ?>;
                }
            </style>
        <?php
    }
    if ($button_color != '#2B4908') {
        ?>
            <style type="text/css">
                ul.sf-menu button ,ul.sf-menu button a{
                    color: <?php echo esc_attr($button_color); ?>;
                }
            </style>
        <?php
    }
    if ($button_hover_color != '#2B4908') {
        ?>
            <style type="text/css">
                ul.sf-menu button:hover, ul.sf-menu button a:hover {
                    color: <?php echo esc_attr($button_hover_color); ?>;
                }
            </style>
        <?php
    }
    if ($button_bg_color != '#2B4908') {
        ?>
            <style type="text/css">
                ul.sf-menu button {
                    background-color: <?php echo esc_attr($button_bg_color); ?>;
                }
            </style>
        <?php
    }
    if ($button_bg_hover != '#2B4908') {
        ?>
            <style type="text/css">
                #menu .sf-menu li button:hover {
                    background-color: <?php echo esc_attr($button_bg_hover); ?>;
                }
            </style>
        <?php
    }
    if ($theme_link != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content a {
                    color: <?php echo esc_attr($theme_link); ?>;
                }
            </style>
        <?php
    }
    if ($theme_link_hover != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content a:hover {
                    color: <?php echo esc_attr($theme_link_hover); ?>;
                }
            </style>
        <?php
    }
    if ($theme_h1 != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content h1{
                    color: <?php echo esc_attr($theme_h1); ?>;
                }
            </style>
        <?php
    }
    if ($theme_h2 != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content h2 {
                    color: <?php echo esc_attr($theme_h2); ?>;
                }
            </style>
        <?php
    }
    if ($theme_h3 != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content h3 {
                    color: <?php echo esc_attr($theme_h3); ?>;
                }
            </style>
        <?php
    }
    if ($theme_h4 != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content h4 {
                    color: <?php echo esc_attr($theme_h4); ?>;
                }
            </style>
        <?php
    }
    if ($theme_h5 != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content h5 {
                    color: <?php echo esc_attr($theme_h5); ?>;
                }
            </style>
        <?php
    }
    if ($theme_h6 != '#2B4908') {
        ?>
            <style type="text/css">
                .cw-content h6 {
                    color: <?php echo esc_attr($theme_h6); ?>;
                }
            </style>
        <?php
    }
    if ($theme_para != '') {
        ?>
            <style type="text/css">
                .cw-content p {
                   color: <?php echo esc_attr($theme_para); ?>;
                }
            </style>
        <?php
    }
    if ($footer_link != '#fff') {
        ?>
            <style type="text/css">
                .footer-navi a {
                    color: <?php echo esc_attr($footer_link); ?>;
                }
            </style>
        <?php
    }

    if ($footer_link != '#fff') {
        ?>
            <style type="text/css">
                .footer a {
                    color: <?php echo esc_attr($footer_link); ?>;
                }
            </style>
        <?php
    }

    if ($footer_link_hover != '#fff') {
        ?>
            <style type="text/css">
                .footer-navi a:hover {
                    color: <?php echo esc_attr($footer_link_hover); ?>;
                }
            </style>
        <?php
    }

    if ($footer_link_hover != '#fff') {
        ?>
            <style type="text/css">
                .footer a:hover {
                    color: <?php echo esc_attr($footer_link_hover); ?>;
                }
            </style>
        <?php
    }
    
    if ($footer_text != '') {
        ?>
            <style type="text/css">
                .footer h1, .footer h2, .footer h3, .footer h4, .footer h5, .footer p {
                    color: <?php echo esc_attr($footer_text); ?>;
                }
            </style>
        <?php
    }
    
    if ($footer_head_col != '') {
        ?>
            <style type="text/css">
                .footer h6,.footer .widget_inner h6 {
                    color: <?php echo esc_attr($footer_head_col); ?>;
                }
            </style>
        <?php
    }
    
    if ($footer_col != '#4F7327') {
        ?>
            <style type="text/css">
                .footer-container {
                    background-color: <?php echo esc_attr($footer_col); ?>;
                }
            </style>
        <?php
    }
    if ($footer_bottom != '#2b4908') {
        ?>
            <style type="text/css">
                .footer-navi {
                    background-color: <?php echo esc_attr($footer_bottom); ?>;
                }
            </style>
        <?php
    }
}

add_action('wp_head', 'menu_link_color_styles');

//        /*
//         * Home paget control function
//         */
//
//        $homepage = get_page_by_title('Home', OBJECT, 'page');
//        if ($homepage) {           
//            //fetch value from customizer, if set to Classic Home Page following function will run else 
//            $get_home = inkthemes_get_option('classic_homepage'); 
//                update_post_meta($homepage->ID, '_wp_page_template', $get_home);
//                 update_option('page_on_front', $homepage->ID);
//            update_option('show_on_front', 'page');           
//            
//        }
