<?php
class feature_posts_widget extends WP_Widget {
    /** constructor */
    function feature_posts_widget() {
        parent::WP_Widget(false, $name = "OCMX Featured Posts Widget", array("description" => "Features Posts widget for the Home Page. Place only in the 'Header Panel'."));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
		if(!($instance["post_category"])) :
			$use_catId = 0;
		else :
	        $use_category = $instance["post_category"];
			$use_catId = get_category_by_slug($use_category);
		endif;
       //Fetch the category for the widget
		$post_args = array('numberposts' => '5', 'offest' => 0, 'orderby' => 'post_date', 'order' => 'DESC','cat' => $use_catId->term_id);
		//Set the post Aguments and Query accordingly
		$count = 1;
		$ocmx_featured = get_posts($post_args);	
?>      
        <div class="selected-feature">
            <?php foreach($ocmx_featured as $post) : ?>
                <div class="feature-title<?php if($count !== 1): ?> no_display<?php endif; ?>" id="feature-post-header-<?php echo $post->ID; ?>">
                    <h4><?php echo date('d M Y', strtotime($post->post_date)); ?></h4>
                    <h2>
                        <?php $link = get_permalink($post->ID); ?>
                        <a href="<?php echo $link ?>"><?php echo $post->post_title; ?></a>
                    </h2>
                    <?php if($count == 1): ?><div class="no_display" id="first_selected"><?php echo $post->ID;?></div><?php endif; ?>
                </div>
            <?php $count++; endforeach; ?>
            <div class="container-header-light-dark"><span></span></div>
            <div class="container-light" id="feature-media-container">
                <?php $count = 1; foreach($ocmx_featured as $post) :
                    // Fetch the PermaLink, Thumbnail and Video Metas
                    $get_post_video = get_post_meta($post->ID, "main_video", true);
                    $get_thumbnail = get_post_meta($post->ID, "other_media", true);
                    // Set our category
                    $category = get_the_category(); 			
                    // If we haven't used our custom image input, search for the first image in the post
                    $post_image = "";
                    if($get_thumbnail == "" && (get_option("ocmx_auto_home_images") && get_option("ocmx_auto_home_images") !== "no")) :
                        $post_image =  fetch_post_image($post->ID, "640", "2000");
                    endif;
                ?>
                    <div <?php if($count !== 1): ?>class="no_display"<?php endif; ?> id="feature-post-media-<?php echo $post->ID; ?>">
                        <?php if($get_post_video !== "") :
                            $get_post_video = preg_replace("/(width\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 640 $2 wmode=\"transparent\"", $get_post_video);
                            $get_post_video = preg_replace("/(height\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 360 $2", $get_post_video);
                            /* Display Video */
                            echo $get_post_video;
                        elseif ($post_image !== "") :
                            echo $post_image;
                        elseif ($get_thumbnail !== "") : ?>
                            <img src="<?php bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_thumbnail ?>&amp;h=&amp;w=640&amp;zc=1" alt="<?php the_title(); ?>" />
                        <?php endif; ?>           
                    </div>
                <?php $count++; endforeach; ?>
            </div>
            <div class="container-footer-light-dark"><span></span></div>
        </div>
        
        <ul class="feature-list">
            <?php $count = 1; ?>
            <?php foreach($ocmx_featured as $post) :
                setup_postdata($post);
                $link = get_permalink($post->ID); ?>
                <li>
                    <span class="item"><?php echo $count; ?></span>
                    <h4><?php echo date('d M Y', strtotime($post->post_date)); ?></h4>
                    <a href="<?php echo $link; ?>" id="ocmx-featured-href-<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a>
                </li>
            <?php 
                $count++;
                endforeach; ?>
        </ul>

<?php
		rewind_posts();
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $post_category = esc_attr($instance["post_category"]);
?>
            <p><label for="<?php echo $this->get_field_id('post_category'); ?>">Category
               <select size="1" class="widefat" id="<?php echo $this->get_field_id("post_category"); ?>" name="<?php echo $this->get_field_name("post_category"); ?>">
                    <option <?php if($post_count == 0){echo "selected=\"selected\"";} ?> value="0">All</option>
                    <?php
							$category_args = array('hide_empty' => false);
                            $option_loop = get_categories($category_args);
                            foreach($option_loop as $option_label => $value)
                                { 	
                                    // Set the $value and $label for the options
                                    $use_value =  $value->slug;
                                    $label =  $value->cat_name;
                                    //If this option == the value we set above, select it
                                    if($use_value == $post_category)
                                        {$selected = " selected='selected' ";}
                                    else
                                        {$selected = " ";}
                    ?>
                                    <option <?php echo $selected; ?> value="<?php echo $use_value; ?>"><?php echo $label; ?></option>
                    <?php 
                                }
                    ?>
                </select>
			</p>
<?php 
	} // form

}// class

//This sample widget can then be registered in the widgets_init hook:

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("feature_posts_widget");'));

?>