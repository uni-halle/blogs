<script src="http://code.jquery.com/jquery-latest.min.js"; type="text/javascript"></script>

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
          <main id="main" class="col-md-<?php echo \BootstrapBasic4\Bootstrap4Utilities::getMainColumnSize(); ?> site-main" role="main">
	        <div id="fall">

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
				<?php if( !post_password_required( $post )): ?>
		        <div class="entry-meta">
					<?php $categories_list = get_the_category_list(__(' | ', 'bootstrap-basic4'));
						if (!empty($categories_list)) {	?> 
						<span class="cat-links">
							<?php $Bsb4Design->categoriesList($categories_list); ?> 
						</span>
							<?php } // End if categories ?>  
						<div class="klein"> 
						<?php 	$schulform = get_field('schulform');
								$klasse = get_field('klasse');
								$fach = get_field('fach');
								$einrichtung = get_field('einrichtung');
								$handlungsfeldau = get_field('handlungsfeld-au');
								$handlungsfeldkj = get_field('handlungsfeld-kj');
								$handlungsfeldeb = get_field('handlungsfeld-eb');
								if( $schulform or $klasse or $fach or $einrichtung or $handlungsfeldau or $handlungsfeldkj or $handlungsfeldeb): ?>
							<i class="fa fa-info-circle" aria-hidden="true" title="Basis-Info"> </i><?php endif ?> 
							<?php if($schulform = get_the_term_list( $post->ID, 'schulform', '', ', ' )):?><?=$schulform?> | <?php endif ?>
							<?php if($klasse = get_the_term_list( $post->ID, 'klasse', 'Klasse ', ', ' )):?><?= $klasse?> | <?php endif ?>
							<?php if($fach = get_the_term_list( $post->ID, 'fach', '', ', ' )):?><?=$fach?> | <?php endif ?>
							<?php if($einrichtung = get_the_term_list( $post->ID, 'einrichtung', '', ', ' )):?><?=$einrichtung?> | <?php endif ?>
							<?php if($handlungsfeldau = get_the_term_list( $post->ID, 'handlungsfeld-au', '', ', ' )):?><?=$handlungsfeldau?> <?php endif ?> 
							<?php if($handlungsfeldkj = get_the_term_list( $post->ID, 'handlungsfeld-kj', '', ', ' )):?><?=$handlungsfeldkj?> <?php endif ?> 
							<?php if($handlungsfeldeb = get_the_term_list( $post->ID, 'handlungsfeld-eb', '', ', ' )):?><?=$handlungsfeldeb?> <?php endif ?> 
							 
							
						</div>
	            </div>  <hr />
				<?php endif ?>
	           
<!-- 	  Kurztext (Eingabe in Standard-Editor) -->
				<?php the_content($Bsb4Design->continueReading(true)); ?> 
				
<!--	 Kategorie + Tags -->   
		    <div class="entry-meta">
				<?php if ('post' == get_post_type()) { // Hide category and tag text for pages on Search ?> 
					<div class="entry-meta-category-tag"><?php if( !post_password_required( $post )): ?>
						<?php
						/* translators: used between list items, there is a space after the comma */
						$tags_list = get_the_tag_list('', __(', ', 'bootstrap-basic4'));
						if ($tags_list) {
            			?>
            			<hr>
						<span class="tags-links">
							<?php $Bsb4Design->tagsList($tags_list); ?> 
						</span>
					<?php } // End if $tags_list ?> 
				<?php endif ?>
        		</div><!--.entry-meta-category-tag-->
				<?php } // End if 'post' == get_post_type() ?> 
			<div class="klein">	
			<?php if( !post_password_required( $post )): ?>
				<?php if($sozialform = get_the_term_list( $post->ID, 'sozialform', '<i class="fa fa-arrows" aria-hidden="true" title="Sozialform"></i> ', ', ' )):?><?=$sozialform?><br /><?php endif ?>
				<?php if($werhandeltsch = get_the_term_list( $post->ID, 'werhandelt-sch', '<i class="fa fa-user-circle" aria-hidden="true" title="handelnde Personen"></i> ', ', ' )):?><?=$werhandeltsch?><?php endif ?>
				<?php if($werhandeltkj = get_the_term_list( $post->ID, 'werhandelt-kj', '<i class="fa fa-user-circle" aria-hidden="true" title="handelnde Personen"></i> ', ', ' )):?><?=$werhandeltkj?><?php endif ?>
				<?php if($werhandelteb = get_the_term_list( $post->ID, 'werhandelt-eb', '<i class="fa fa-user-circle" aria-hidden="true" title="handelnde Personen"></i> ', ', ' )):?><?=$werhandelteb?><?php endif ?>
				<?php if($sozialform or $werhandeltsch or $werhandeltkj or $werhandelteb):?><hr /><?php endif ?>
				<?php endif ?>	
			</div>	
<!--
		<div class="klein"><i class="fa fa-file" aria-hidden="true" title="Format/e"> </i><?php if($format = get_the_term_list( $post->ID, 'format', ' ', ', ' )):?><?=$format?> <?php endif ?></div>
-->
		</div> 	<!-- .entry-meta --> 
		
<!--      Inhalt  -->  
		<?php if( !post_password_required( $post )): ?>
			<div id="accordion" role="tablist" aria-multiselectable="true">
<!--  Transkript -->
			<?php $replik = get_field('replik');
			if( $replik ): ?>  
            	<div class="card">
					<div class="card-header" role="tab" id="heading1a">
						<h4><i class="fa fa-caret-down" aria-hidden="true"></i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1a" aria-expanded="false" aria-controls="collapse1a">
							Transkript
	        				</a>
            			</h4>
            		</div> 
					<div id="collapse1a" class="collapse" role="tabpanel" aria-labelledby="heading1a">
						<div class="card-block"> 
						<?php if( have_rows('dateien') ): ?>  
						<br>   
						<ul class="dateien">         
							<?php while( have_rows('dateien') ): the_row();                 
			                 // vars                 
 			                 $titel_datei = get_sub_field('titel_datei');                
			                 $datei_datei = get_sub_field('datei_datei');                 
/*
			                 $datei_rechtliches = get_sub_field('datei_rechtliches');      
			                 $datei_weitere_informationen = get_sub_field('datei_weitere_informationen');
*/              
			                 ?>                 
							 <li class="datei">                         
 								<p class="klein"><?php if( $titel_datei ){echo $titel_datei; }?> | 
								<?php if( $datei_datei ): ?>
									<a href="<?php echo $datei_datei['url']; ?>" target="_blank"><?php echo $datei_datei['filename']; ?></a>
								<?php endif; ?></p>

								<hr />
							 </li>         
						<?php endwhile; ?>         
						</ul> 
						<?php endif; ?> 	
<!-- Text -->							
						<?php if($textblock = get_field( 'textblock' )):?><?=$textblock?> <?php endif ?>  
<!-- Dialog  -->          	                  
						<?php if( have_rows('replik') ): ?>     	                     
							<?php while( have_rows('replik') ): the_row();                 
			                 // vars                 
			                 $person = get_sub_field('person');                
			                 $text = get_sub_field('text');
			                 ?>         
							 <div class="row">                
								<div class="col-3">                         
									 <?php if( $person ){echo $person; }?>
							 	</div>
							 	<div class="col-9"> 
									<?php if( $text ){echo $text; }?>
								</div>
			              	</div>
						  	<?php endwhile; ?> 
						<?php endif; ?>
						
						</div>       
					</div>
            	</div>
            <?php endif;?>
<!-- Protokoll  -->
            <?php $protokoll = get_field('protokoll');
			if( $protokoll ): ?>  
            	<div class="card">
					<div class="card-header" role="tab" id="heading1b">
						<h4><i class="fa fa-caret-down" aria-hidden="true"></i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1b" aria-expanded="false" aria-controls="collapse1b">
							Protokoll
	        				</a>
            			</h4>
            		</div> 
					<div id="collapse1b" class="collapse" role="tabpanel" aria-labelledby="heading1b">
						<div class="card-block"> 
						<?php if( have_rows('dateien') ): ?>  
						<br>   
						<ul class="dateien">         
							<?php while( have_rows('dateien') ): the_row();                 
			                 // vars                 
 			                 $titel_datei = get_sub_field('titel_datei');                
			                 $datei_datei = get_sub_field('datei_datei');                 
/*
			                 $datei_rechtliches = get_sub_field('datei_rechtliches');      
			                 $datei_weitere_informationen = get_sub_field('datei_weitere_informationen');
*/              
			                 ?>                 
							 <li class="datei">                         
 								<p class="klein"><?php if( $titel_datei ){echo $titel_datei; }?> | 
								<?php if( $datei_datei ): ?>
									<a href="<?php echo $datei_datei['url']; ?>" target="_blank"><?php echo $datei_datei['filename']; ?></a>
								<?php endif; ?></p>

								<hr />
							 </li>         
						<?php endwhile; ?>         
						</ul> 
						<?php endif; ?> 	
<!-- Protokoll -->							
						<?=$protokoll?>  
						
						</div>       
					</div>
            	</div>
            	<?php endif;?>
<!-- Bild  --> 
			<?php $bild = get_field('bild');
				if( $bild ): ?>          	
            	<div class="card">
					<div class="card-header" role="tab" id="heading2a">
						<h4><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse2a" aria-expanded="false" aria-controls="collapse2a">
							Video
							</a>
            			</h4>
            		</div> 
					<div id="collapse2a" class="collapse" role="tabpanel" aria-labelledby="heading2a">
						<div class="card-block">              	                  
						<?=$video?> 
						</div>       
					</div>
            	</div>
				<?php endif ?>	
<!-- Video  --> 
			<?php $video = get_field('video');
				if( $video ): ?>          	
            	<div class="card">
					<div class="card-header" role="tab" id="heading2b">
						<h4><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse2b" aria-expanded="false" aria-controls="collapse2b">
							Video
							</a>
            			</h4>
            		</div> 
					<div id="collapse2b" class="collapse" role="tabpanel" aria-labelledby="heading2b">
						<div class="card-block">              	                  
						<?=$video?> 
						</div>       
					</div>
            	</div>
				<?php endif ?>	
<!-- Audio  --> 
			<?php $audio = get_field('audio');
				if( $audio ): ?>          	
            	<div class="card">
					<div class="card-header" role="tab" id="heading2c">
						<h4><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse2c" aria-expanded="false" aria-controls="collapse2c">
							Audio
							</a>
            			</h4>
            		</div> 
					<div id="collapse2c" class="collapse" role="tabpanel" aria-labelledby="heading2c">
						<div class="card-block">              	                  
						<?=$audio?> 
						</div>       
					</div>
            	</div>
				<?php endif ?>	
				<br>	

<!-- 	  Interpretationen  -->
			<?php $interpretation = get_field('interpretation');
				if( $interpretation ): ?>
			
				<div class="card">
					<div class="card-header" role="tab" id="heading3a">
						<br />
						<h5><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse3a" aria-expanded="false" aria-controls="collapse3a"> 
								<span> 
									Interpretationen
								</span>
								<span style="float:right">
									<?php $hat_interpretation = get_field( 'hat_interpretation' );?>
									<?php if( $hat_interpretation == true ) {?><i class="fa fa-flag" aria-hidden="true" title="Interpretation/en vorhanden"></i> <?php }?>
								</span>
							</a>
						</h5>
            		</div> 
					<div id="collapse3a" class="collapse" role="tabpanel" aria-labelledby="heading3a">
						<div class="card-block">
						<ul>
						<?php foreach( $interpretation as $post): // variable must be called $post (IMPORTANT) ?>
						<?php setup_postdata($post); ?>
							<li>
								<a class="klein" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a><br /><br />
<!-- 								<span>Post Object Custom Field: <?php the_field('field_name'); ?></span> -->
							</li>
						<?php endforeach; ?>
    					</ul>
						<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
					  	</div>
					</div>
				</div>
				<?php endif;?>
<!-- zusammenhängende fälle -->
				<?php $zusfaelle = get_field('zusfaelle');
					if( $zusfaelle ): ?>
				<div class="card">
					<div class="card-header" role="tab" id="heading3b">
						<h5><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse3b" aria-expanded="false" aria-controls="collapse3b"> 
								zusammenhängende / ähnliche Fälle
							</a>
						</h5>
            		</div> 
					<div id="collapse3b" class="collapse" role="tabpanel" aria-labelledby="heading3b">
					<div class="card-block">
						<ul>
						<?php foreach( $zusfaelle as $post): // variable must be called $post (IMPORTANT) ?>
						<?php setup_postdata($post); ?>
							<li>
								<a class="klein" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a><br />
<!-- 								<span>Post Object Custom Field: <?php the_field('field_name'); ?></span> -->
							</li>
						<?php endforeach; ?>
    					</ul>
						<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
					  </div>
					</div>
				</div>
				<?php endif;?>
<!-- Kommentare -->	
			<?php if (comments_open()):?>
				<div class="card">		
					<div class="card-header" role="tab" id="heading3c">
						<h5><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse3c" aria-expanded="false" aria-controls="collapse3c"> 
								<span>
									Kommentare
								</span>
								<span class="entry-meta-comment-tools" style="float:right" title="Kommemtar/e vorhanden">
									<?php if (! post_password_required() && (comments_open() || '0' != get_comments_number())) { ?> 
									<span class="comments-link" title="Kommemtar/e vorhanden"><?php $Bsb4Design->commentsLink(); ?></span>
									<?php } //endif; ?>
								</span> 
							</a>
						</h5>
            		</div> 
					<div id="collapse3c" class="collapse" role="tabpanel" aria-labelledby="heading3c">
						<div class="card-block">
							<?php comments_template( '', true ); ?>
						</div>
	                 </div>
				</div>
			<?php endif;?>
<!-- Hintergrund-Info -->
					<?php $autor = get_field('autor');
						$erhebungskontext = get_field('erhebungskontext');
						$erhebungsmethode = get_field('erhebungsmethode');
						$notizen_fall = get_field('notizen-fall');
					if( $autor or $erhebungskontext or $erhebungsmethode or $notizen_fall): ?>
				<div class="card">		
					<div class="card-header" role="tab" id="heading4a">
						<br />
						<h6><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse4a" aria-expanded="false" aria-controls="collapse4a"> 
								&nbsp;Erhebung</a>
						</h6>
            		</div> 
					<div id="collapse4a" class="collapse" role="tabpanel" aria-labelledby="heading4a">
						<div class="card-block">
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
							 		<div class="col-3">Autorschaft</div>
							 		<div class="col-9">                         
								 	<?php if( $titel ){echo $titel; }?> <?php if( $vorname ){echo $vorname; }?>  
									<?php if( $nachname ){echo $nachname; }?> <!-- | <?php if($status = get_the_term_list( $post->ID, 'status', ' ' )):?><?=$status?><?php endif ?> --> | <?php if( $mail ){echo $mail; }?>
							 		</div>         
							 		<?php endwhile; ?>         
								</div>
							<?php endif; ?>
							<div class="row klein">
	            				<div class="col-3">Erhebungskontext</div>
								<div class="col-9"><?php if($erhebungskontext = get_the_term_list( $post->ID, 'erhebungskontext', '', ', ' )):?><?=$erhebungskontext?><?php endif ?></div>
							</div>
							<div class="row klein">
	            				<div class="col-3">Erhebungsmethode</div>
								<div class="col-9"><?php if($erhebungsmethode = get_the_term_list( $post->ID, 'erhebungsmethode', '', ', ' )):?><?=$erhebungsmethode?>, <?php endif ?></div>
							</div>
							<?php if($notizen_fall = get_field('notizen-fall')):?>
							<div class="row klein">
	            				<div class="col-3">Notizen</div>
								<div class="col-9"> <?=$notizen_fall?></div>
	            			</div>
							<?php endif ?>
	          			</div>
				  	</div>
				</div>		
				<?php endif;?>
		<!-- Redaktion 
	    		<div class="card">		
					<div class="card-header" role="tab" id="headingSix">
						<h6><i class="fa fa-caret-down" aria-hidden="true"> </i>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix"> 
								&nbsp;Redaktion</a>
						</h6>
            		</div> 
					<div id="collapseSix" class="collapse" role="tabpanel" aria-labelledby="headingSix">
						<div class="card-block">
							<?php $redaktion = get_field( 'redaktion' );
								if( $redaktion ) {?>
								<div class="row klein">                
									<div class="col-3"> Redaktion</div>
									<div class="col-9">
										<?php echo '<ul>';    
										foreach($redaktion as $redaktion)
										{
										echo '<li>' . $redaktion . '</li>';
										}

										echo '</ul>'; ?><?php    }  ?>
									</div>        
	            				</div>	            
								<?php if ($notizen_redaktion = get_field('notizen-redaktion')): ?>
								<div class="row klein">                
									<div class="col-3">Notizen</div>
									<div class="col-9"> <?=$notizen_redaktion?></div>
	            				</div>
								<?php endif ?>
						</div>
	    		  	</div>
	    		</div>
	    		-->
			</div>	
		<?php endif ?>		
		<?php $Bsb4Design->editPostLink(); ?>    		

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