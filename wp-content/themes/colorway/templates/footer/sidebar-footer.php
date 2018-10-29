<?php
/*
 * Setting default values
 */
$default_heading = array(
    '<h6>ColorWay Theme</h6>',
    '<h6>Recent Post</h6>',
    '<h6>Fully Responsive</h6>',
    '<h6>Design Your Home</h6>'
);
$default_content = array(
    '<p>Footer is widgetized. To setup the footer, drag the required Widgets in Appearance -> Widgets Tab in the First, Second, Third and Fourth Footer Widget Areas.</p>',
    '<p> Worth A Thousand Words<br/> Category Hierarchy <br/> Elements <br/>',
    '<p>ColorWay is a unique responsive WordPress theme. The theme design is fabulous enough giving your visitors the absolute reason to stay on your site.</p>',
    '<p>Express your creativity, find inspiration and make smarter home design choices, faster.</p>'
);
$class = '';

/*
 * Initializing the footer widget numbers
 */
$m = inkthemes_get_option('footer_col_area_select',4);
switch ($m) {
    case '1':
        $class = 'col-md-12 col-sm-12';
        break;
    case '2':
        $class = 'col-md-6 col-sm-6';
        break;
    case '3':
        $class = 'col-md-4 col-sm-6';
        break;
    case '4':
        $class = 'col-md-3 col-sm-3';
        break;
}
for ($i = 1; $i <= $m; $i++) {
    ?>
    <div class="<?php echo esc_attr($class); ?>">
        <div class="widget_inner common animated" style="-webkit-animation-delay: 0.4s; -moz-animation-delay: 0.4s; -o-animation-delay: 0.4s; -ms-animation-delay: 0.4s;"><?php
            if (is_active_sidebar('footer-widget-area' . $i)) :
                dynamic_sidebar('footer-widget-area' . $i);
            else :
                ?>
                <div class="footer_widget_wrapper">
                    <div class="footer_widget_title"><?php echo wp_kses_post($default_heading[$i - 1]); ?></div>
                    <div class="footer_widget_desc"><?php echo wp_kses_post($default_content[$i - 1]); ?></div>
                </div>
            <?php endif; ?> 
        </div>
    </div>
    <!--</div>-->
<?php } ?>
<div class="clear"></div>