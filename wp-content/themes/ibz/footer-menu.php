<?php
/**
 * The template for displaying copyright information and 
 * social profiles, defined inside theme option panel.
 */
?>

<?php $maja_option = maja_get_global_options(); ?>

<footer>

    <div id="social_wrapper">
        <ul id="social_icons">
        
            <?php if ($maja_option['maja_forrst'] !='') { ?>         
                <li><a title="<?php _e('Forrst', 'maja'); ?>" class="maja_social-forrst" href="<?php echo $maja_option['maja_forrst'] ?>" target="_blank"></a></li>
            <?php } ?>
            
             <?php if ($maja_option['maja_facebook'] !='') { ?> 
                <li><a title="<?php _e('Facebook', 'maja'); ?>" class="maja_social-facebook" href="<?php echo $maja_option['maja_facebook'] ?>" target="_blank"></a></li>
            <?php } ?>          
            
            <?php if ($maja_option['maja_dribbble'] !='') { ?>     
                <li><a title="<?php _e('Dribbble', 'maja'); ?>" class="maja_social-dribbble" href="<?php echo $maja_option['maja_dribbble'] ?>" target="_blank"></a></li>
            <?php } ?>   
            
            <?php if ($maja_option['maja_youtube'] !='') { ?>        
                <li><a title="<?php _e('You Tube', 'maja'); ?>" class="maja_social-youtube" href="<?php echo $maja_option['maja_youtube'] ?>" target="_blank"></a></li>
            <?php } ?>     
                
            <?php if ($maja_option['maja_vimeo'] !='') { ?>         
                <li><a title="<?php _e('Vimeo', 'maja'); ?>" class="maja_social-vimeo" href="<?php echo $maja_option['maja_vimeo'] ?>" target="_blank"></a></li>
            <?php } ?>          
            
            <?php if ($maja_option['maja_twitter'] !='') { ?>         
                <li><a title="<?php _e('Twitter', 'maja'); ?>" class="maja_social-twitter" href="<?php echo $maja_option['maja_twitter'] ?>" target="_blank"></a></li>
            <?php } ?> 
                            
            <?php if ($maja_option['maja_rssfeed'] =='1') { ?> 
                <li><a title="<?php _e('RSS Feed', 'maja'); ?>" class="maja_social-feed" href="<?php bloginfo('rss2_url'); ?>"></a></li>
            <?php } ?>      
           
        </ul>
    </div>
    
	<p id="copyright_info"><?php echo $maja_option['maja_copyright'] ?></p>    
    
</footer>