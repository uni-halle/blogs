<?php
/**
 *
 * This file is used to markup the public-facing pattern template.
 *
 * @since      0.0.1
 *
 * @package    Post_TIMELINE
 * @subpackage Post_TIMELINE/public/partials
 */

$color_nav    = $all_configs['ptl-nav-color'];
$bg_color     = $all_configs['ptl-bg-color'];
$nav_class    = $all_configs['ptl-nav-type'];
$head_color   = $all_configs['ptl-post-head-color'];
$desc_color   = $all_configs['ptl-post-desc-color'];
$post_bg      = $all_configs['ptl-post-bg-color'];


if($parent_post) {

    if(isset($parent_post->custom['_thumbnail_id'][0])) {

        $main_image = wp_get_attachment_image_src($parent_post->custom['_thumbnail_id'][0] , 'large' );
        $main_image = isset( $main_image['0'] ) ? $main_image['0'] : null;
    }
    else
        $main_image = POST_TIMELINE_URL_PATH.'public/img/dummy.jpg';
}


$ptl_id = 'ptl-'.$uniqid;


if(isset($all_configs['fonts'])): ?>
<style type="text/css">
.p-tl-cont *:not(.fa):not(.glyphicon){font-family:'<?php echo $all_configs['fonts']; ?>' !important}
</style>
<?php endif; ?>

<!-- Start of timeline -->
<div data-id="<?php echo $uniqid ?>" class="p-tl-cont timeline_section ptl-vert-tml <?php echo $tmpl_class ?> <?php echo $ptl_id ?> <?php echo $all_configs['ptl-post-size'] ?> <?php echo $all_configs['class']; ?> " id="p-tl-cont" style="background:<?php echo $bg_color ?>">
    <div class="ptl-preloder">
        <div class="cssload-loader">
            <div class="inner-loader">
                <div class="cssload-container">
                    <div class="cssload-l">L</div>
                    <div class="cssload-circle"></div>
                    <div class="cssload-square"></div>
                    <div class="cssload-triangle"></div>
                    <div class="cssload-i">I</div>
                    <div class="cssload-n">N</div>
                    <div class="cssload-g">G</div>
                </div>
            </div>
        </div>
    </div>    
    <?php if($parent_post): 

        $p_content = $parent_post->post_content;

        if (strlen($p_content) > $all_configs["ptl-post-length"]) {

            // truncate string
            $p_content = substr($p_content, 0, $all_configs["ptl-post-length"]);

            // make sure it ends in a word so assassinate doesn't become ass...
            $p_content = substr($p_content, 0, strrpos($p_content, ' ')).'...'; 
        }
    ?>
        <!-- head -->
        <div class="container timeline_details" style="display:block !important">
            <?php if($main_image): ?>
            <div class="col-md-6">
                <?php if(isset($parent_post->custom['_thumbnail_id'][0])): ?> 
                    <div class="u-img"><img src="<?php echo $main_image; ?>"></div>
                <?php endif; ?> 
            </div>
            <?php endif; ?>
            <div class="col-md-6 details-sec">
                <h1 style="color: <?php echo $color_nav ?>"><?php echo $parent_post->post_title ?></h1>
                <?php if(isset($parent_post->custom['ptl-tag-line'][0])): ?>
                <h3><?php echo $parent_post->custom['ptl-tag-line'][0] ?></h3>
                <?php endif; ?>
                <p><?php echo $p_content; ?></p>
                <p class="time-p"><span><?php echo $parent_post->time_range ?></span></p>
            </div>
        </div>
    <?php endif; ?>

    <!--Navigation-->
    <?php if($all_configs['ptl-nav-status']): ?>
    <div class="yr_list <?php echo $nav_class ?>">
        <div class="btn-top ptl-btn temp"><a><span class="glyphicon glyphicon-menu-up"></span></a></div>
        <div class="yr_list-view">
            <ul class="list-unstyled yr_list-inner menu-timeline">
                <?php foreach($tag_list as $key => $value): ?>
                    <li data-tag="<?php echo $key ?>"><a  data-scroll data-options='{ "easing": "Quart" }' data-href="#ptl-tag-<?php echo $key ?>"><span><?php echo $value ?></span></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="btn-bottom ptl-btn temp"><a><span class="glyphicon glyphicon-menu-down"></span></a></div>
    </div>
    <?php endif; ?>
    <!-- head End -->
    
    <div class="container main-timeline post-timeline">
        <?php include $template_file; ?>
    </div>
     <!-- Paginate -->
    <?php if($ptl_response['paginate']): ?>
    <div class="ptl-pagination">
        <?php echo $ptl_response['paginate']; ?>
    </div>
    <?php endif; ?>
</div>
<!-- END of timeline -->

 <?php
    if($nav_class == "style-1"):
    ?>
        <style>
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-1 .ptl-btn a,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-1 li a{color: <?php echo $color_nav ?> !important;border: 0px solid  <?php echo $color_nav ?> !important;box-shadow: 0 0 1px 1px <?php echo $color_nav ?>, 0 0 1px 1px  <?php echo $color_nav ?> inset !important;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-1 .ptl-btn a:hover,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-1 li a.active,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-1 li a:hover{color: #fff !important;background: <?php echo $color_nav ?> !important;}
        </style>
    <?php
        elseif($nav_class == "style-2"):
    ?>
        <style>
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-2 .ptl-btn a,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-2 li a{color: <?php echo $color_nav ?> !important;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-2 .ptl-btn a:hover,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-2 li a.active,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-2 li a:hover{color: #fff !important;background: <?php echo $color_nav ?> !important;}
        </style>
    <?php
        elseif($nav_class == "style-3"):
    ?>
        <style>
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-3 .ptl-btn a,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-3 li a{color: <?php echo $color_nav ?> !important;border-color: <?php echo $color_nav ?> !important;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-3 .ptl-btn a:hover,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-3 li a.active,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-3 li a:hover{color: #fff !important;border-color: <?php echo $color_nav ?> !important;background: <?php echo $color_nav ?> !important;}
        </style>
    <?php
        elseif($nav_class == "style-4"):
    ?>
        <style>
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-4  .ptl-btn a,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-4 li a.active,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-4 li a{color: <?php echo $color_nav ?> !important;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-4 li a:before,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-4 li a.active:before{background: <?php echo $color_nav ?> !important;}
        </style>
    <?php
        elseif($nav_class == "style-5"):
    ?>
        <style>
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 .ptl-btn  a,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a.active,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a{color: <?php echo $color_nav ?> !important;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 .ptl-btn a:after,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a.active:after,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a:after{border-color: <?php echo $color_nav ?> !important;z-index: -1;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 .ptl-btn a:hover:after,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a.active:after,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a:hover:after{border-color: <?php echo $color_nav ?> !important;background:  <?php echo $color_nav ?> !important;}
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 .ptl-btn a:hover,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a.active,
            .p-tl-cont#p-tl-cont.timeline_section .yr_list.style-5 li a:hover{color:#fff !important;}
        </style>
    <?php
        endif;
    ?>

<style>
    .p-tl-cont#p-tl-cont.timeline_section .ptl-more-btn{color: <?php echo $color_nav ?> !important;border-color: <?php echo $color_nav ?> !important;}
    .p-tl-cont#p-tl-cont.timeline_section .ptl-pagination .page-numbers{box-shadow: 0 0 1px 0 <?php echo $color_nav ?>, 0 0 1px 0 <?php echo $color_nav ?> inset !important;color: <?php echo $color_nav ?> !important;border: 1px solid <?php echo $color_nav ?> !important;line-height: 25px !important;}
    body #p-tl-cont.p-tl-cont.timeline_section ul.menu-timeline {top: 0px}
</style>