<?php
if ((inkthemes_get_option('colorway_slideimage1') || inkthemes_get_option('colorway_slideimage2')) || inkthemes_get_option('colorway_dummy_data') == 'on') {
    ?>
    <div id="slider" class="sl-slider-wrapper">
        <div class="sl-slider">
            <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                <div class="sl-slide-inner">	
                    <?php
                    $sliders = array(
                        'slider1' => array(
                            'img' => inkthemes_get_option('colorway_slideimage1', get_template_directory_uri() . '/images/slider.jpg'),
                            'heading' => inkthemes_get_option('colorway_slideheading1', __('Your Site is faster to build.', 'colorway')),
                            'link' => inkthemes_get_option('colorway_slidelink1', '#'),
                            'desc' => inkthemes_get_option('colorway_slidedescription1', __('Premium WordPress Themes with Single Click Installation, Just a Click and your website is ready for use.', 'colorway')),
                        ),
                        'slider2' => array(
                            'img' => inkthemes_get_option('colorway_slideimage2', get_template_directory_uri() . '/images/slider2.jpg'),
                            'heading' => inkthemes_get_option('colorway_slideheading2'),
                            'link' => inkthemes_get_option('colorway_slidelink2'),
                            'desc' => inkthemes_get_option('colorway_slidedescription2'),
                        ),
                    );
                    $slider1 = $sliders['slider1']['img'];
                    $slider2 = $sliders['slider2']['img'];
                    $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
                    ?>
                    <div class="salesdetails">
                        <h1><a href="<?php echo $sliders['slider1']['link']; ?>"><?php echo $sliders['slider1']['heading']; ?></a></h1>
                        <p><?php echo $sliders['slider1']['desc']; ?></p>
                    </div>
                </div>
                <?php
                //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider						
                $check_img_ofset = 0;
                foreach ($value_img as $get_value) {
                    if (preg_match("/$get_value/", $sliders['slider1']['img'])) {
                        $check_img_ofset = 1;
                    }
                }
                // Note our use of ===.  Simply == would not work as expected
                // because the position of 'a' was the 0th (first) character.
                ?>
                <?php if ($check_img_ofset == 0 && $sliders['slider1']['img'] != '') { ?>
                    <div class="bg-img bg-img-1"><?php echo $sliders['slider1']['img']; ?></div>
                <?php } else { ?>  
                    <div class="bg-img bg-img-1">
                        <a href="<?php echo $sliders['slider1']['link']; ?>" >
                            <img  src="<?php echo $sliders['slider1']['img']; ?>" alt="<?php echo $sliders['slider1']['heading']; ?>"/>
                        </a>
                    </div>
                <?php } ?>
            </div>	
            <?php if ($sliders['slider2']['img'] != '') { ?>
                <div class="sl-slide slide2" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5">
                    <div class="sl-slide-inner">
                        <div class="salesdetails">
                            <h1><a href="<?php echo $sliders['slider2']['link']; ?>"><?php echo $sliders['slider2']['heading']; ?></a></h1>
                            <p><?php echo $sliders['slider2']['desc']; ?></p>
                        </div>
                    </div>
                    <?php
                    //The strpos funtion is comparing the strings to allow uploading of the Videos & Images in the Slider								
                    $check_img_ofset = 0;
                    foreach ($value_img as $get_value) {
                        if (preg_match("/$get_value/", $sliders['slider2']['img'])) {
                            $check_img_ofset = 1;
                        }
                    }
                    // Note our use of ===.  Simply == would not work as expected
                    // because the position of 'a' was the 0th (first) character.
                    ?>
                    <?php if ($check_img_ofset == 0 && $sliders['slider2']['img'] != '') { ?>
                        <div class="bg-img bg-img-2"><?php echo $sliders['slider2']['img']; ?></div>
                    <?php } else { ?>  
                        <div class="bg-img bg-img-2">
                            <a href="<?php echo $sliders['slider2']['link']; ?>" >
                                <img  src="<?php echo $sliders['slider2']['img']; ?>" alt="<?php echo $sliders['slider2']['heading']; ?>"/></a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div><!-- /sl-slider -->	

        <?php if (($slider1 && $slider2) || ($slider1 == '' && $slider2 == '')) { ?>
            <nav id="nav-arrows" class="nav-arrows">
                <span class="nav-arrow-prev"><?php _e('Previous', 'colorway'); ?></span>
                <span class="nav-arrow-next"><?php _e('Next', 'colorway'); ?></span>
            </nav>  
            <nav id="nav-dots" class="nav-dots">
                <span class="nav-dot-current"></span>		
                <span class="slide2"></span>		
            </nav>
        <?php } ?>
    </div>
    <?php
} else {
    ?>
    <div class="slider-container"><br/><?php _e('Please go to Appearance-> Widgets and add atleast one widget to the Home Page Left Feature Widget Area to the "ColorWay Homepage". You can enable dummy data option from the Appearance-> Customize-> General Settings to set up the theme like the demo website.', 'colorway'); ?></div>
    <?php
}
?>
