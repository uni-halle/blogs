<div id="tabs">

    <ul id="idTabs" class="tabs clearfix">
        <li class="nav1"><a href="#comm"><img src="<?php bloginfo('template_directory'); ?>/images/ico-1.gif" alt="Comments" /></a></li>
        <li class="nav2"><a href="#pop"><img src="<?php bloginfo('template_directory'); ?>/images/ico-2.gif" alt="Popular" /></a></li>
        <li class="nav3"><a href="#tagcloud"><img src="<?php bloginfo('template_directory'); ?>/images/ico-5.gif" alt="TAgs" /></a></li>												
    </ul>
    <div class="inside">

        <ul id="comm">
            <?php include(TEMPLATEPATH . '/includes/comments.php' ); ?>                    
        </ul>

        <ul id="pop">
            <?php include(TEMPLATEPATH . '/includes/popular.php' ); ?>                    
        </ul>

        <div id="tagcloud">
            <?php wp_tag_cloud('smallest=12&largest=20'); ?>
        </div>

    </div><!--inside-->

</div><!--tabs-->
<br />