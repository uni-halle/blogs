<?php
// 
// Based on Tutorial
// by Fouad Matin
// 
// http://wp.tutsplus.com/tutorials/changing-the-fonts-of-your-wordpress-site-part-2-theme-integration/
// 

//add_action( 'admin_menu', 'my_fonts' );
function my_fonts() {
    add_theme_page( 'Fonts', 'Fonts Options', 'edit_theme_options', 'fonts', 'fonts' );
}
function fonts() {
?>
    <div class="wrap">
        <div><br></div>
        <h2>Fonts</h2>

        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-fonts' ); ?>
            <?php settings_fields( 'fonts' ); ?>
            <?php do_settings_sections( 'fonts' ); ?>
            <?php submit_button(); ?>
            </form>
        <img style="float:right; border:0;" src="http://i.imgur.com/1qqJG.png" />
    </div>
<?php
}

//add_action( 'admin_init', 'my_register_admin_settings' );
function my_register_admin_settings() {
    register_setting( 'fonts', 'fonts' );
    add_settings_section( 'font_section', 'Font Options', 'font_description', 'fonts' );
    add_settings_field( 'body-font', 'Body Font', 'body_font_field', 'fonts', 'font_section' );
    add_settings_field( 'h1-font', 'Headers and Menu Links Font', 'h1_font_field', 'fonts', 'font_section' );
	add_settings_field( 'title-font', 'Main Title Font', 'title_font_field', 'fonts', 'font_section' );
}
function font_description() {
    echo 'Use the form below to change fonts of your theme.';
}
function get_fonts() {
    $fonts = array(
        'arial' => array(
            'name' => 'Arial',
            'font' => '',
            'css' => "font-family: Arial, sans-serif;"
        ),
		'alegreya' => array(
            'name' => 'Alegreya',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Alegreya);',
            'css' => "font-family: 'Alegreya', serif;"
        ),		
		'cookie' => array(
            'name' => 'Cookie',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Cookie);',
            'css' => "font-family: 'Cookie', serif;"
        ),						
        'vollkorn' => array(
            'name' => 'Vollkorn',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Vollkorn);',
            'css' => "font-family: 'Vollkorn', serif;"
        ),
        'vollkornitalic' => array(
            'name' => 'Vollkorn Italic',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Vollkorn:400italic);',
            'css' => "font-family: 'Vollkorn', serif;"
        ),
		'sevillana' => array(
            'name' => 'Sevillana',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Sevillana);',
            'css' => "font-family: 'Sevillana', sans-serif;"
        ),
		'share' => array(
            'name' => 'Share',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Share);',
            'css' => "font-family: 'Share', sans-serif;"
        ),
		'pontano' => array(
            'name' => 'Pontano Sans',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Pontano+Sans);',
            'css' => "font-family: 'Pontano Sans', sans-serif;"
        ),
		'karla' => array(
            'name' => 'Karla',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Karla);',
            'css' => "font-family: 'Karla', sans-serif;"
        ),
		'lustria' => array(
            'name' => 'Lustria',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Lustria);',
            'css' => "font-family: 'Lustria', sans-serif;"
        ),
		'noticia' => array(
            'name' => 'Noticia Text',
            'font' => '@import url(http://fonts.googleapis.com/css?family=Noticia+Text);',
            'css' => "font-family: 'Noticia Text', sans-serif;"
        )			
    );



    return apply_filters( 'get_fonts', $fonts );
}
function body_font_field() {
    $options = (array) get_option( 'fonts' );
    $fonts = get_fonts();
    $current = 'vollkorn';

    if ( isset( $options['body-font'] ) )
        $current = $options['body-font'];
    ?>
        <select name="fonts[body-font]">
        <?php foreach( $fonts as $key => $font ): ?>
            <option <?php if($key == $current) echo "SELECTED"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
        <?php endforeach; ?>
        </select>
    <?php
}
function h1_font_field() {
    $options = (array) get_option( 'fonts' );
    $fonts = get_fonts();
    $current = 'pontano';

    if ( isset( $options['h1-font'] ) )
        $current = $options['h1-font'];

    ?>
        <select name="fonts[h1-font]">
        <?php foreach( $fonts as $key => $font ): ?>
            <option <?php if($key == $current) echo "SELECTED"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
        <?php endforeach; ?>
        </select>
    <?php
}
function title_font_field() {
    $options = (array) get_option( 'fonts' );
    $fonts = get_fonts();
    $current = 'cookie';

    if ( isset( $options['title-font'] ) )
        $current = $options['title-font'];
    ?>
        <select name="fonts[title-font]">
        <?php foreach( $fonts as $key => $font ): ?>
            <option <?php if($key == $current) echo "SELECTED"; ?> value="<?php echo $key; ?>"><?php echo $font['name']; ?></option>
        <?php endforeach; ?>
        </select>
    <?php
}

//add_action( 'wp_head', 'font_head' );
function font_head() {
    $options = (array) get_option( 'fonts' );
    $fonts = get_fonts();
    $body_key = 'pontano';

    if ( isset( $options['body-font'] ) )
        $body_key = $options['body-font'];

    if ( isset( $fonts[ $body_key ] ) ) {
        $body_font = $fonts[ $body_key ];

        echo '<style>';
        echo $body_font['font'];
        echo 'body  { ' . $body_font['css'] . ' } ';
        echo '</style>';
    }

    $h1_key = 'pontano';

    if ( isset( $options['h1-font'] ) )
        $h1_key = $options['h1-font'];

    if ( isset( $fonts[ $h1_key ] ) ) {
        $h1_key = $fonts[ $h1_key ];

        echo '<style>';
        echo $h1_key['font'];
        echo 'h1, h2, h3, h4, h5, h6, #menu a  { ' . $h1_key['css'] . ' } ';

        echo '</style>';
    }
	
 	$title_key = 'cookie';

    if ( isset( $options['title-font'] ) )
        $title_key = $options['title-font'];

    if ( isset( $fonts[ $title_key ] ) ) {
        $title_key = $fonts[ $title_key ];

        echo '<style>';
        echo $title_key['font'];
        echo '.main_title  { ' . $title_key['css'] . ' } ';

        echo '</style>';
    }	
}
?>