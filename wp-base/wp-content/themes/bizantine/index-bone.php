<div class="biz0ne">

        <div class="biz0ne-welcome">
        
            <h1>
                <?php 
                    if( of_get_option('welcome_headline') ){
                        echo esc_html( of_get_option('welcome_headline') );
                    }else {
                        _e('Welcome Headline Comes Here',  'Bizantine');
                    }
                ?>    
            </h1>
            
            <p>
                <?php 
                    if( of_get_option('welcome_text') ){
                        echo esc_html( of_get_option('welcome_text') );
                    }else {
                        _e('You can change this text in welcome text box of welcome section block in Biz one tab of theme options page. You can change this text in welcome text box of welcome section block in Biz two tab of theme options page.',  'Bizantine');
                    }
                ?>                                
            </p>    
        
        </div><!-- .biz0ne-welcome -->
        
        <div class="biz0ne-products-services">
        
            <div class="biz0ne-products-services-item">
            
                <div class="biz0ne-products-services-img">
                
                                            <a href="<?php if( of_get_option('left_section_link') ){ echo esc_url( of_get_option('left_section_link') );}else { echo '#';}?>">
                                            <?php 
                                                if( of_get_option('left_section_image') ){
                                                    echo '<img class="" src="'.esc_url( of_get_option('left_section_image') ).'" />';
                                                }else {
                                                    echo '<img class="" src="'.get_stylesheet_directory_uri().'/images/fetimg2.png"  />';
                                                }
                                            ?>                                    
                                            </a>        
                
                </div><!-- .biz0ne-products-services-img -->
                
                <div class="biz0ne-products-services-name">
                                                    <a href="<?php if( of_get_option('left_section_link') ){ echo esc_url( of_get_option('left_section_link') );}else { echo '#';}?>">
                                                    <?php 
                                                        if( of_get_option('left_section_headline') ){
                                                            echo esc_html( of_get_option('left_section_headline') );
                                                        }else {
                                                            _e('Design',  'Bizantine');
                                                        }
                                                    ?> 
                                                    </a>        
                </div><!-- .biz0ne-products-services-name -->
                
                <div class="biz0ne-products-services-description">
                                                    <?php 
                                                        if( of_get_option('left_section_text') ){
                                                            echo esc_html( of_get_option('left_section_text') );
                                                        }else {
                                                            _e('You can change this text in description box of left section block in Biz two tab of theme options page.',  'Bizantine');
                                                        }
                                                    ?>        
                </div><!-- .biz0ne-products-services-description -->                
            
            </div><!-- .biz0ne-products-services-item -->
            
            <div class="biz0ne-products-services-item">
            
                <div class="biz0ne-products-services-img">
                                            <a href="<?php if( of_get_option('center_section_link') ){ echo esc_url( of_get_option('center_section_link') );}else { echo '#';}?>">
                                            <?php 
                                                if( of_get_option('center_section_image') ){
                                                    echo '<img class="" src="'.esc_url( of_get_option('center_section_image') ).'" />';
                                                }else {
                                                    echo '<img class="" src="'.get_stylesheet_directory_uri().'/images/fetimg2.png"  />';
                                                }
                                            ?>
                                            </a>        
                </div><!-- .biz0ne-products-services-img -->
                
                <div class="biz0ne-products-services-name">
                                                    <a href="<?php if( of_get_option('center_section_link') ){ echo esc_url( of_get_option('center_section_link') );}else { echo '#';}?>">
                                                    <?php 
                                                        if( of_get_option('center_section_headline') ){
                                                            echo esc_html( of_get_option('center_section_headline') );
                                                        }else {
                                                            _e('Development',  'Bizantine');
                                                        }
                                                    ?>
                                                    </a>       
                </div><!-- .biz0ne-products-services-name -->
                
                <div class="biz0ne-products-services-description">
                                                    <?php 
                                                        if( of_get_option('center_section_text') ){
                                                            echo esc_html( of_get_option('center_section_text') );
                                                        }else {
                                                            _e('You can change this text in description box of center section block in Biz two tab of theme options page.',  'Bizantine');
                                                        }
                                                    ?>       
                </div><!-- .biz0ne-products-services-description -->                
            
            </div><!-- .biz0ne-products-services-item -->
            
            <div class="biz0ne-products-services-item">
            
                <div class="biz0ne-products-services-img">
                                            <a href="<?php if( of_get_option('right_section_link') ){ echo esc_url( of_get_option('right_section_link') );}else { echo '#';}?>">
                                            <?php 
                                                if( of_get_option('right_section_image') ){
                                                    echo '<img class="" src="'.esc_url( of_get_option('right_section_image') ).'" />';
                                                }else {
                                                    echo '<img class="" src="'.get_stylesheet_directory_uri().'/images/fetimg2.png"  />';
                                                }
                                            ?>
                                            </a>        
                </div><!-- .biz0ne-products-services-img -->
                
                <div class="biz0ne-products-services-name">
                                                    <a href="<?php if( of_get_option('right_section_link') ){ echo esc_url( of_get_option('right_section_link') );}else { echo '#';}?>">
                                                    <?php 
                                                        if( of_get_option('right_section_headline') ){
                                                            echo esc_html( of_get_option('right_section_headline') );
                                                        }else {
                                                            _e('Marketing',  'Bizantine');
                                                        }
                                                    ?>
                                                    </a>        
                </div><!-- .biz0ne-products-services-name -->
                
                <div class="biz0ne-products-services-description">
                                                    <?php 
                                                        if( of_get_option('right_section_text') ){
                                                            echo esc_html( of_get_option('right_section_text') );
                                                        }else {
                                                            _e('You can change this text in description box of right section block in Biz two tab of theme options page.',  'Bizantine');
                                                        }
                                                    ?>        
                </div><!-- .biz0ne-products-services-description -->                
            
            </div><!-- .biz0ne-products-services-item -->        
        
        </div><!-- .biz0ne-products-services -->
        
        <?php if( !of_get_option('show_Bizantine_quote_bizone') || of_get_option('show_Bizantine_quote_bizone') == 'true' ) : ?>
        <div class="biz0ne-quote">
        
            <div class="biz0ne-quote-text">
                
                <p>
                    <?php 
                         if( of_get_option('quote_section_text') ){
                              echo esc_html( of_get_option('quote_section_text') );
                         }else {
                              _e('You can change this text in quote box of quote section block in Biz two tab of theme options page. You can change this text in quote box of quote section block in Biz two tab of theme options page.',  'Bizantine');
                         }
                    ?>
                </p> 
                    
            </div><!-- .biz0ne-quote-text -->
            
            <p class="biz0ne-quote-name">
            
                <span>
                    <?php 
                        if( of_get_option('quote_section_name') ){
                             echo esc_attr( of_get_option('quote_section_name') );
                        }else {
                             _e('Mac Taylor',  'Bizantine');
                        }
                    ?>
                </span>   
            </p>    
        
        </div><!-- .biz0ne-quote -->
        <?php endif; ?>
		
</div><!-- .biz0ne -->  

<?php if( !of_get_option('show_bizone_posts') || of_get_option('show_bizone_posts') == 'true' ) : ?>
<div class="biz0ne">
	
		<?php 
			
			if( 'page' == get_option( 'show_on_front' ) ){	
				get_template_part('index', 'page');
			}else {
				get_template_part('index', 'standard');
			}			 
			
		?>
		
</div><!-- .biz0ne -->
<?php endif; ?>  