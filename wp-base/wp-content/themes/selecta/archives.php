<?php
	/* Template Name: Selecta Archives */
	global $wpdb, $is_archive;
	$is_archive = "1";
	if(file_exists("../../../wp-blog-header.php")) :
		require_once("../../../wp-blog-header.php");
	endif;
	if($_GET["month"]) :
		$use_date = StrToDate("15/".$_GET["month"]."/".$_GET["year"]);
		echo $use_date;
	endif;
	//DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts  
	$fetch_archive = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_status='publish' AND post_type = 'post' GROUP BY $wpdb->posts.ID ORDER BY post_date DESC");
	$last_month = date("m Y", strtotime($fetch_archive[0]->post_date));
	get_header(); 
?>
<div id="content-container">
    <div id="archive-detail-1">
		<h2 class="post-section-title"><?php echo date("M Y", strtotime($fetch_archive[0]->post_date)); ?></h2>
        <div class="post-slider clearfix">
            <ul>
                <?php
                    $count = 0;
                    $month_count = 1;
                    $last_month = date("m Y", strtotime($fetch_archive[0]->post_date));
                    foreach($fetch_archive as $archive_data) :                    	
                        $get_post_video = get_post_meta($post->ID, "main_video", true);
                        $get_thumbnail = get_post_meta($archive_data->ID, "other_media", true);
                        $get_video_thumbnail = get_post_meta($archive_data->ID, "video_thumbnail", true);
                        $post_image = "";
                        if($get_thumbnail == "") :
                            $post_image =  fetch_post_image($archive_data->ID, "300", "2000");
                        endif;
                        $category_id = get_the_category($archive_data->ID);
                        $this_category = get_category($category_id[0]->term_id);
                        $this_category_link = get_category_link($category_id[0]->term_id);
                        $link = get_permalink($archive_data->ID);
                        if(date("m Y", strtotime($archive_data->post_date)) !== $last_month) :
                            $count = 1;
							$month_count++;
                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div id="archive-detail-<?php echo $month_count; ?>" style="display: none;">
                            	<h2 class="post-section-title"><?php echo date("M Y", strtotime($archive_data->post_date)); ?></h2>
                                <div class="post-slider clearfix">
                                    <ul>
                        <?php elseif($count == 4) :
                            $count = 1;
                        ?>
                                </ul>
                            </div>
                            <div class="post-slider clearfix">
                                <ul>
                        <?php
                        else :	
                            $count++;
                        endif;
                        ?>
                        <li class="clearfix"<?php if($count == 4) : echo " class=\"last\""; endif; ?>>
                            <div class="container-header-light-normal"><span></span></div>
							<div class="container-light">
								<div class="thumbnail">
									<?php if ($get_video_thumbnail !== "") : ?>
										<img src="<?php bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_video_thumbnail ?>&amp;h=&amp;w=300&amp;zc=1" alt="<?php echo $archive_data->post_title; ?>" />
									<?php elseif($get_thumbnail !== "") : ?>
										<img src="<?php echo bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_thumbnail ?>&amp;w=300&amp;h=&amp;zc=1" alt="<?php echo $archive_data->post_title; ?>" />
									<?php  elseif ($post_image !== "") :
										echo $post_image; 
									elseif($get_post_video !== "") :
										$get_post_video = preg_replace("/(width\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 190 $2", $get_post_video);
										$get_post_video = preg_replace("/(height\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 137 $2", $get_post_video);
											echo $get_post_video;
									else : ?>
										<img src="<?php bloginfo('template_directory'); ?>/images/slider-test-vid.png" alt="<?php echo $archive_data->post_title; ?>" />
									<?php endif; ?>
								</div>
							</div>
                            <div class="container-footer-light-normal"><span></span></div>
                            <h3><a href="<?php echo get_permalink($archive_data->ID); ?>"><?php echo substr($archive_data->post_title, 0, 45); ?></a></h3>
                        </li>
                    <?php
                        $last_month = date("m Y", strtotime($archive_data->post_date));
                    endforeach;
                ?>
            </ul>
		</div>
	</div>
</div>
<?php get_footer(); ?>

