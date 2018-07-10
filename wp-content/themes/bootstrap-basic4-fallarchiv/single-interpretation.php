<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>


<?php
/** 
 * The single post.<br>
 * This file works as display full post content page and its comments.
 * 
 * @package bootstrap-basic4
 */


// begins template. -------------------------------------------------------------------------
get_header();
get_sidebar('left');
?> 
          <main id="main" class="col-md-6 site-main" role="main">
	        <div id="fall"> 
					<div class="klein" >
						<i class="fa fa-flag" aria-hidden="true"></i> Interpretation zu <?php $zumfall = get_field('zumfall');
							if( $zumfall ): ?>
							<?php foreach( $zumfall as $post): // variable must be called $post (IMPORTANT) ?>
							<?php setup_postdata($post); ?>
								<a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>, 
							<?php endforeach; ?>
							<?php endif;
						?>
	            </div><br />   
             <?php
               if (have_posts()) {
                  $Bsb4Design = new \BootstrapBasic4\Bsb4Design();
                     while (have_posts()) { the_post();?> 
                   <header class="entry-header">
					<?php if ( is_single() ) : ?>
						<h1 class="entry-title"><?php the_title(); ?>
						</h1>
					<?php else : ?>
						<h1 class="entry-title">
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
						</h1>
					<?php endif; // is_single() ?>
				</header><!-- .entry-header -->
		        
	            <hr />
	<!-- Autor -->
				<div id="autor">  
	                <?php if( have_rows('autor') ): ?> 	
	                 	<div class="row klein">        	                     
		                <?php while( have_rows('autor') ): the_row();                 
			                 // vars                 
			                 $titel = get_sub_field('titel');                 
			                 $vorname = get_sub_field('vorname');      
			                 $nachname = get_sub_field('nachname');   
			                 $status = get_sub_field('status');  
			                 $mail = get_sub_field('mail');
			                 ?>                 
							 <div class="col-4">Autor*in</div>
							 <div class="col-8">                         
 								<?php if( $titel ){echo $titel; }?> <?php if( $vorname ){echo $vorname; }?>  
 								<?php if( $nachname ){echo $nachname; }?> | <?php if($status = get_the_term_list( $post->ID, 'status', '' )):?><?=$status?><?php endif ?>
 								<?php if( $mail ){echo '| <a href="mailto:' . $mail . '">' . $mail . '</a>';}?>

							 </div>         
						<?php endwhile; ?>         
						</div> <!-- row klein -->
					<?php endif; ?>
	            	
		        	<div class="row klein">
	            			<div class="col-4">Auswertungsmethode/n</div>
							<div class="col-8"><?php if($auswertungsmethode = get_the_term_list( $post->ID, 'auswertungsmethode', '', ', ' )):?><?=$auswertungsmethode?><?php endif ?></div>         
	            	</div> <!-- row klein -->
				</div> <!-- autor -->			<hr />	
<!-- 	  Kurztext (Eingabe in Standard-Editor) -->
				<?php the_content($Bsb4Design->continueReading(true)); ?> 
			<hr />
				<!-- Download -->				
					<?php if( have_rows('dateien') ): ?>       
	                  <ul class="interpretation">         
		                <?php while( have_rows('dateien') ): the_row();                 
			                 // vars                 
// 			                 $titel_interpretation = get_sub_field('titel_interpretation');                
			                 $datei_interpretation = get_sub_field('datei_datei');                 
// 			                 $autor_interpretation = get_sub_field('autor_interpretation');     			                           
			                 ?>                 
							 <li class="datei">                         
 								<p class="klein"><?php if( $datei_interpretation ): ?>
									<a href="<?php echo $datei_interpretation['url']; ?>" target="_blank"><?php echo $datei_interpretation['filename']; ?></a>
								<?php endif; ?></p>
							 </li>         
						<?php endwhile; ?>         
					  </ul> 
					<?php endif; ?>
			<hr>

			<div id="accordion" role="tablist" aria-multiselectable="true">
            	<div class="card">
					<div class="card-header" role="tab" id="headingOne">
						<h5><i class="fa fa-caret-down" aria-hidden="true"></i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								<span>Kommentare</span>
								<span class="entry-meta-comment-tools" style="float:right">
								<?php if (! post_password_required() && (comments_open() || '0' != get_comments_number())) { ?> 
								<span class="comments-link"><?php $Bsb4Design->commentsLink(); ?></span>
								<?php } //endif; ?></span> 
							</a>
            			</h5>
            		</div> 
					<div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="card-block">  
							<?php comments_template( '', true ); ?>
						</div>
	                </div>
				</div>
			</div>
	     

	
		<!-- Redaktion -->
<!--
	    		<div id="redaktion">  
	    		  <div class="faq unit">
					<div class="faq question">Redaktion</div>
					<div class="faq answer">
		            <?php $redaktion = get_field( 'redaktion' );
						if( $redaktion ) {?>
						<div class="row klein">                
							 <div class="col-4"> Redaktion</div>
							<div class="col-8"><?php echo '<ul>';    
                        foreach($redaktion as $redaktion)
                            {
                                echo '<li>' . $redaktion . '</li>';
                            }

                                echo '</ul>'; ?><?php    }  ?></div>        
	            		</div>	            
					</div>
	    		  </div>
	    		</div>
-->
	<?php    }// endwhile;

                        unset($Bsb4Design);
                    } else {
                        get_template_part('template-parts/section', 'no-results');
                    }// endif;
                    ?> 
                 </div>
                </main>
<?php
get_sidebar('right');
get_footer();