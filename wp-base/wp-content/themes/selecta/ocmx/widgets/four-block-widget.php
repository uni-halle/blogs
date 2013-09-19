<?php
class four_block_widget extends WP_Widget {
    /** constructor */
    function four_block_widget() {
        parent::WP_Widget(false, $name = "OCMX Thumbnail Widget", array("description" => "Four block thumbnail widget for the Home Page. Place in the 'Index Header Panel'."));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
		$widget_title =  esc_attr($instance["widget_title"]);
		
		if(!($instance["post_category"])) :
			$use_catId = 0;
            $cat_query = "";
		else :
	        $use_category = $instance["post_category"];
			$use_catId = get_category_by_slug($use_category);
            $cat_query = "&category_name=".$use_catId->cat_name;
		endif;
       //Fetch the category for the widget
		if(!($instance["post_offset"])) :
	        $use_offset = 0;
		else :
    	    $use_offset = $instance["post_offset"];
		endif;
		
       //Fetch the count for the widget
		if(!($instance["post_count"])) :
	        $post_count = 4;
		else :
    	    $post_count = $instance["post_count"];
		endif;
		
		$ocmx_featured = new WP_Query("showposts=$post_count&post_type=post".$cat_query);

		//Set the post Aguments and Query accordingly
		$count = 0;
?>
		<div class="post-slider clearfix">
            <h2><?php echo $widget_title; ?></h2>
            <ul>
				<?php while ($ocmx_featured->have_posts()) : $ocmx_featured->the_post();
						setup_postdata($post);
						$count++;
						if ($count == 5) : 
						$count = 1; ?>
							</ul>
                            	</div>
                                <div class="post-slider clearfix">
							<ul>
				<?php endif;
						// Fetch the PermaLink, Thumbnail and Video Metas
						$this_post = get_post($ocmx_featured->ID);
						$get_post_video = get_post_meta($this_post->ID, "main_video", true);
						$get_thumbnail = get_post_meta($this_post->ID, "other_media", true);
						$get_video_thumbnail = get_post_meta($this_post->ID, "video_thumbnail", true);
						$link = get_permalink($this_post->ID);
						// Set our category
						$category = get_the_category(); 			
						// If we haven't used our custom image input, search for the first image in the post
						$post_image = "";
						if($get_thumbnail == "" && (get_option("ocmx_auto_home_images") && get_option("ocmx_auto_home_images") !== "no")) :
							$post_image =  fetch_post_image($ocmx_featured->ID, "300", "600");
						endif;
				?>
                    <li<?php if($count == 4) : echo " class=\"last\""; endif;?>>
                        <div class="container-header-light-normal"><span></span></div>
                        <div class="container-light">
							<div class="thumbnail">
								<?php if ($get_video_thumbnail !== "") : 
									/* Display Custom Video Image */
								?>
									<img src="<?php bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_video_thumbnail ?>&amp;h=&amp;w=300&amp;zc=1" alt="<?php the_title(); ?>" />
								<?php elseif ($post_image !== "") :
									/* Display Post Image */
									echo $post_image;
								elseif ($get_thumbnail !== "") : 
									/* Display Custom Post Image */
								?>
									<img src="<?php bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_thumbnail ?>&amp;h=&amp;w=300&amp;zc=1" alt="<?php the_title(); ?>" />
								<?php endif; ?>
							</div>
                        </div>
                        <div class="container-footer-light-normal"><span></span></div>
                        <h3><a href="<?php echo $link; ?>"><?php echo $this_post->post_title; ?></a></h3>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
<?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $widget_title = 	esc_attr($instance["widget_title"]);
        $post_category = 	esc_attr($instance["post_category"]);
        $post_count = 		esc_attr($instance["post_count"]);
		$post_offset =  	esc_attr($instance["post_offset"]);
		
        ?>
        	<p>
            	<label for="<?php echo $this->get_field_id('widget_title'); ?>">Title
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" value="<?php echo $widget_title; ?>">
			</p>
            <p>
            	<label for="<?php echo $this->get_field_id('post_count'); ?>">Post Count
                <select size="1" class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>">
                	<?php for($i = 4; $i < 25; $i=$i+4) : ?>
	                    <option <?php if($post_count == $i) : ?>selected="selected"<?php endif; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
			</p>
            <p>
            	<label for="<?php echo $this->get_field_id('post_offset'); ?>">Post Offset
                <select size="1" class="widefat" id="<?php echo $this->get_field_id('post_offset'); ?>" name="<?php echo $this->get_field_name('post_offset'); ?>">
	                    <option <?php if($post_offset == 0) : ?>selected="selected"<?php endif; ?> value="0">Latest Post</option>
                	<?php for($i = 1; $i <= 8; $i++) : ?>
	                    <option <?php if($post_offset == $i) : ?>selected="selected"<?php endif; ?> value="<?php echo $i; ?>"><?php echo $i; ?> Posts Old</option>
                    <?php endfor; ?>
                </select>
			</p>
        
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
add_action('widgets_init', create_function('', 'return register_widget("four_block_widget");'));

?>