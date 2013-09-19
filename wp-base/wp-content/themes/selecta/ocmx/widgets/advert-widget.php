<?php
class ocmx_small_ad_widget extends WP_Widget {
    /** constructor */
    function ocmx_small_ad_widget() {
        parent::WP_Widget(false, $name = 'OCMX 125 x 125 Adverts');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
		if(get_option("ocmx_small_buysell_ads") == "on") :
			echo stripslashes(get_option("ocmx_small_buysell_id"));
		endif;
		if(get_option("ocmx_small_ads") !== "0") :
			//To alternate the classes we set the right-handside class first
			$use_class == "advert right";
			for ($i = 1; $i <= get_option("ocmx_small_ads"); $i++)
				{
					$ad_title_id = "ocmx_small_ad_title_".$i;
					$ad_link_id = "ocmx_small_ad_link_".$i;
					$ad_img_id ="ocmx_small_ad_img_".$i;
					if(get_option($ad_img_id) !== "") :
						if($use_class == "advert") :
							$use_class = "advert right";
						else :
							$use_class = "advert";
						endif;
		?>
						<div class="<?php echo $use_class; ?>">
							<a href="<?php echo get_option($ad_link_id); ?>" class="sponsor-item" title="<?php echo get_option($ad_title_id); ?>" rel="nofollow">
								<img src="<?php echo get_option($ad_img_id); ?>" alt="<?php echo get_option($ad_title_id); ?>" />
							</a>
						</div>
		<?php
					endif;
				}				
		endif;
		
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
		
        ?>
            <p><em>You can modify your sidebar ad's in the <a href="admin.php?page=ocmx-adverts">OCMX Options</a> panel</em></p>
        <?php 
    }

} // class FooWidget

//This sample widget can then be registered in the widgets_init hook:

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("ocmx_small_ad_widget");'));

?>