<?php if(!isset($all_configs['ajax'])):?> 
<svg xmlns="http://www.w3.org/2000/svg" style="display: none">
  <symbol id="ptl-icon">
    <polygon class="st0" points="1,129.2 220.7,2.3 440.4,129.2 440.4,382.8 220.7,509.7 1,382.8 " style="fill:<?php echo $color_nav ?>;stroke:<?php echo $color_nav ?>;"/>
  </symbol>
</svg>
<span class="line" style="background:<?php echo $color_nav ?>;"></span>
<div class="ptl-posts-cont">
<?php endif; 

$all_configs['offset'] = 0;

$tag         = null;
$tag_change  = false;
$is_start    = true;
$color       = 0;
$row_index   = isset($prev_index)?$prev_index:0;



//$all_configs['ptl-anim-type'] = 'fadeInLeft';

/*Animations*/
$anim_post      = $all_configs['ptl-anim-type'];
$anim_delay     = "100ms";
$anim_dur       = "1s";

$anim_tag       = 'fadeIn';
$anim_tag_delay = '700ms';
$anim_tag_dur   = '1s';

$anim_post_odd  = $anim_post;


//Validate Animation
if($anim_post) {

    if($anim_post_odd == 'fadeInLeft') {

        $anim_post_odd = 'fadeInRight';
    }

    $anim_post_odd  .= ' panim';
    $anim_post      .= ' panim';

    $anim_tag       .= ' panim';
}



$index_count = $row_index;
    
foreach($child_posts as $c_post):
    
    
    /*for left and right*/
    $post_pos = ($index_count % 2 == 0)?' ptl-p-left':' ptl-p-right';
        
    $c_image = ptl_get_image($c_post,$ptl_admin);

    $color_index = 0;

    $p_content = $c_post->post_content;

    if (strlen($p_content) > $all_configs["ptl-post-length"]) {

        // truncate string
        $stringCut = substr($p_content, 0, $all_configs["ptl-post-length"]);

        // make sure it ends in a word so assassinate doesn't become ass...
        $p_content = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
    }


    $color_hex = $post_bg;
    
    //Add the Custom Post Color
    if(isset($c_post->custom['ptl-post-color'][0]))
        $color_hex = $c_post->custom['ptl-post-color'][0];

    //Generate new color
    $color_2  = ptl_newcolor($color_hex, 0.5);

    
    if($c_post->date_comp[0] !== $tag) {

        $is_start = ($tag === null)?true:false;

        $tag = $c_post->date_comp[0];

        $tag_change = true;
    }
    else
        $tag_change = false;
    

    //Animation
    $anim_type = ($post_pos == ' ptl-p-left')?$anim_post:$anim_post_odd;

    $url_link = (isset($c_post->custom['ptl-post-link'][0]))?$c_post->custom['ptl-post-link'][0]:($ptl_URL.$c_post->post_name);

    $img_top   = (isset($c_post->custom['ptl-img-txt-pos'][0]) && $c_post->custom['ptl-img-txt-pos'][0] == '1')?'ptl-img-top':'';
    //$img_top   = 'ptl-img-top';
        

    if($is_start || $tag_change):

        //Put the previous tag ending  div
        if(!$is_start) {
            echo '</div><!-- close timeline-section -->';
        }

        $is_start  = false;
        $row_index = 0;

        /*for left and right*/
        //Animation
        $post_pos = ($row_index % 2 == 0)?' ptl-p-left':' ptl-p-right';
        $anim_type = ($post_pos == ' ptl-p-left')?$anim_post:$anim_post_odd;

        //TAG TITLE
        $tag_title = $tag_list[$tag];
    ?>

    <!-- tag  -->
    <div class="row">
        <span id="ptl-tag-<?php echo $tag ?>" class="ptl-ach-tag"></span>
        <div class="col-md-5"></div>
        <div class="col-md-2">
            <div class="row">
                <div class="tag">
                    <span><?php echo $tag_title; ?></span>
                    <!-- style="enable-background:new 0 0 441.3 512;" -->
                    <svg  x="0px" y="0px" viewBox="0 0 441.3 512">
                        <use xlink:href="#ptl-icon"></use>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-5"></div>
    </div>
    <div class="blank-space-90"></div>
    <!-- tag  End -->

    <div class="row timeline-section">
    <?php endif; //tag change ?> 
            <!-- post html -->
            <div data-tag="<?php echo $tag ?>" style="background:<?php echo $color_2  ?>;" class="timeline-box <?php echo $post_pos ?>  <?php echo $anim_type ?>" data-panim-delay="<?php echo $anim_delay?>"  data-panim-duration="<?php echo $anim_dur ?>">
                <div data-tag="<?php echo $tag ?>" class="ptl-callb panim"></div>
                <h1 style="background:<?php echo $color_hex  ?>;color:<?php echo $head_color ?>">
                    <span><?php echo $c_post->post_title; ?> </span>
                    <div class="month-box <?php echo $anim_tag ?>" data-panim-delay="<?php echo $anim_tag_delay ?>"  data-panim-duration="<?php echo $anim_tag_dur ?>" style="background:<?php echo $color_hex ?>;">
                        <div style="border-color:transparent <?php echo $color_hex ?> transparent <?php echo $color_hex ?>" class="ptl-tr"></div><i style="color:<?php echo $head_color ?>" class="fa <?php echo $c_post->icon ?>"></i><div class="ptl-line" style="background:<?php echo $color_hex ?>;"></div>
                    </div>
                </h1>
                <div class="ptl-cont-box <?php echo $img_top ?>">
                    <?php if($c_image): ?>
                    <img alt="image" src="<?php echo $c_image; ?>" class="ptl-p-img" />
                    <?php endif; ?>
                    <p class="ptl-desc ptl-p-desc" style="color:<?php echo $desc_color ?>"><?php echo $p_content;  ?></p>
                </div>
                <div class="">
                    <div class="ptl-m-date" style="color:<?php echo $head_color ?>" href="<?php echo $url_link ?>"><?php echo $c_post->date_str ?></div>
                    <?php if($all_configs['ptl-rm-status']): ?>
                    <a class="at-read-more pull-right" style="color:<?php echo $head_color ?>;background:<?php echo $color_hex  ?>" href="<?php echo $url_link ?>"><?php echo __( 'Read more', 'post-timeline') ?></a>
                    <?php endif; ?>
                    <span class="at-read-more ptl-left-side pull-left" style="color:<?php echo $head_color ?>;background:<?php echo $color_hex  ?>" ><?php echo $c_post->date_str; ?></span>
                </div>
                <?php if($all_configs['class']  == 'one-side-left'): ?>
                <div class="ptl-p-icon"><i style="color:<?php echo $color_hex ?>"  class="<?php echo $c_post->icon ?>"></i></div>
                <?php endif; ?>
            </div>
            <!-- post html end -->
<?php        
    $row_index++;
    $color++;
    $index_count++;

endforeach;
?>

<!-- the end of timeline div and the Ending tag -->
<?php if(count($child_posts) > 0):
$tag_title = $tag_list[$tag];
?>
</div><!-- END OF OPEN -->
<!-- tag  -->
<div class="row ptl-last-tag">
    <div class="col-md-5"></div>
    <div class="col-md-2">
        <div class="row">
            <div class="tag">
                <span data-tag="<?php echo $tag ?>"><?php echo $tag_title; ?></span>
                <svg  x="0px" y="0px" viewBox="0 0 441.3 512">
                    <use xlink:href="#ptl-icon"></use>
                </svg>
            </div>
        </div>
    </div>
    <div class="col-md-5"></div>
</div>

<!-- Last tag End -->
<?php endif; ?>

<?php if(!isset($all_configs['ajax'])):?> 
</div>

<?php if($all_configs['class']  == 'one-side-left') {

    $all_configs['grid'] = array('lg' => 12,'md' => 12,'sm' => 12,'xs' => 12);
}

endif; ?>