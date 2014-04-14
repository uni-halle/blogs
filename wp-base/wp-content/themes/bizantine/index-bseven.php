<div class="bizseven">

			<?php if( !of_get_option('show_bseven_welcome_section') || of_get_option('show_bseven_welcome_section') == 'true' ) : ?>
    		<div class="bizseven-welcome">
    
                    <h1>
                        <?php 
                            if( of_get_option('bseven_welcome_headline') ){
                                echo esc_html( of_get_option('bseven_welcome_headline') );
                            }else {
                                _e('Welcome Headline Here',  'Bizantine');
                            }
                        ?>                           
                    </h1>
                    
                    <div class="bizseven-welcome-desc">
                        <?php 
                            if( of_get_option('bseven_welcome_text') ){
                                echo esc_html( of_get_option('bseven_welcome_text') );
                            }else {
                                _e('Curabitur placerat gravida tortor eget lacinia. Maecenas a eros nec eros venenatis placerat eu sit amet purus. Nunc molestie risus eget nunc dictum fermentum. Cras vitae convallis ante, a facilisis lacus. Pellentesque iaculis nisi varius odio convallis, ac cursus leo venenatis.',  'Bizantine');
                            }
                        ?>                                                         
                    </div>
                    
			</div><!-- .bizseven-welcome -->
            <?php endif; ?>
			
            <?php if( of_get_option('bseven_work_show') != 'false' ) : ?>
    		<div class="bizseven-work">
            
    			<div class="bizseven-work-intro">            
    
                    <h1>
                        <?php 
                            if( of_get_option('bseven_work_headline') ){
                                echo esc_html( of_get_option('bseven_work_headline') );
                            }else {
                                _e('Our Work',  'Bizantine');
                            }
                        ?>    
                    </h1>
                    
                    <div class="bizseven-work-intro-desc">
                        <?php 
                            if( of_get_option('bseven_work_text') ){
                                echo esc_html( of_get_option('bseven_work_text') );
                            }else {
                                _e('You can change this text in welcome text box of welcome section block in Biz one tab of theme options page. You can change this text in welcome text box of welcome section block in Biz two tab of theme options page.',  'Bizantine');
                            }
                        ?>                                
                    </div>
                    
				</div><!-- .bizseven-work-intro -->  
                
    			<div class="bizseven-work-items">
                
					<ul id="da-thumbs" class="da-thumbs">
                                
								<?php
                                    if( of_get_option('bseven_port_image_1') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_1') ).'" /></li>';
                                    }
                                ?>
                                
								<?php
                                    if( of_get_option('bseven_port_image_2') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_2') ).'" /></li>';
                                    }
                                ?>  
                                
								<?php
                                    if( of_get_option('bseven_port_image_3') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_3') ).'" /></li>';
                                    }
                                ?> 
                                
								<?php
                                    if( of_get_option('bseven_port_image_4') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_4') ).'" /></li>';
                                    }
                                ?>  
                                
								<?php
                                    if( of_get_option('bseven_port_image_5') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_5') ).'" /></li>';
                                    }
                                ?>
                                
								<?php
                                    if( of_get_option('bseven_port_image_6') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_6') ).'" /></li>';
                                    }
                                ?>  
                                
								<?php
                                    if( of_get_option('bseven_port_image_7') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_7') ).'" /></li>';
                                    }
                                ?> 
                                
								<?php
                                    if( of_get_option('bseven_port_image_8') ) {
                                       echo '<li><img src="'.esc_url( of_get_option('bseven_port_image_8') ).'" /></li>';
                                    }
                                ?>
                    
                    </ul>
                    
                                  
                
				</div><!-- .bizseven-work-items -->                                                  
                    
			</div><!-- .bizseven-work -->
            <?php endif; ?>
            
            <?php if( of_get_option('bseven_about_show') != 'false' ) : ?>
    		<div class="bizseven-about">
            
                <div class="bizseven-about-title">
                
                	<h1>
                        <?php 
                            if( of_get_option('bseven_about_headline') ){
                                echo esc_html( of_get_option('bseven_about_headline') );
                            }else {
                                _e('About Us',  'Bizantine');
                            }
                        ?>                     
                    </h1>
                    
                </div><!-- .bizseven-about-title -->  
                
                <div class="bizseven-about-content">
                
                        <?php 
                            if( of_get_option('bseven_about_text') ){
                                echo esc_html( of_get_option('bseven_about_text') );
                            }else {
                                _e('You can change this text in welcome text box of welcome section block in Biz one tab of theme options page. You can change this text in welcome text box of welcome section block in Biz two tab of theme options page.',  'Bizantine');
                            }
                        ?>                                      
                    
                </div><!-- .bizseven-about-content -->                          
            
			</div><!-- .bizseven-about -->                        
            <?php endif; ?>
            
</div><!-- .bizseven -->


<?php if( !of_get_option('show_bizseven_posts') || of_get_option('show_bizseven_posts') == 'true' ) : ?>
<div class="bizseven">
	
		<?php 
			
			if( 'page' == get_option( 'show_on_front' ) ){	
				get_template_part('index', 'page');
			}else {
				get_template_part('index', 'standard');
			}			 
			
		?>
		
</div><!-- .bizseven -->
<?php endif; ?>