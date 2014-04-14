<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Bizantine
 */
?>

    		</div><!-- .content-container -->
        
    	</div><!-- .Responsive-Container -->
        
	</div><!-- #main -->
    
    <div class="footer-social">
    
    	<div class="responsive-container">
        
                                    <ul class="footer-social-icons">
                                    
                                        <?php if(of_get_option('twitter_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('twitter_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter.png" alt="Twitter" /></a></li>
                                        <?php endif; ?>
                                        
                                        <?php if(of_get_option('facebook_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('facebook_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook.png" alt="redit" /></a></li>
                                        <?php endif; ?>                                                                          
    
                                        <?php if(of_get_option('redit_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('redit_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/reddit.png" alt="redit" /></a></li>
                                        <?php endif; ?>
    
                                        <?php if(of_get_option('stumble_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('stumble_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/stumble.png" alt="stumble" /></a></li>
                                        <?php endif; ?>
    
                                        <?php if(of_get_option('flickr_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('flickr_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/flickr.png" alt="flickr" /></a></li>
                                        <?php endif; ?>
    
                                        <?php if(of_get_option('linkedin_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('linkedin_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/linkedin.png" alt="linkedin" /></a></li>
                                        <?php endif; ?>
    
                                        <?php if(of_get_option('google_id')) : ?>
                                        <li><a href="<?php echo esc_url( of_get_option('google_id') ); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/gplus.png" alt="google" /></a></li>
                                        <?php endif; ?>

                                                                        
                                    </ul>         
        
        </div><!-- .Responsive-Container -->
    
    </div><!-- .footer-social-icons -->

	<!-- Footer Starts Here -->
	<?php 
								
		if( !of_get_option('footer_layout') || of_get_option('footer_layout') == 'one' ) {
			$footer_layout = 'one';
		}else {
			$footer_layout = 'two';
		}
								
		get_template_part( 'footer', $footer_layout );
								
	?>
    <!-- Footer ends Here -->
</div><!-- #inner-container -->    
</div><!-- #page -->
</div><!-- #wrapper-one -->
</div><!-- #wrapper-two -->
</div><!-- #wrapper-three -->

<?php wp_footer(); ?>

</body>
</html>