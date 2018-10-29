<?php
/**
 * Display the post content in "generic" or "standard" format.
 * This will be use in the loop and full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<!--
    <div class="entry-meta"> 
    	<span style="float:right">      
	        <div class="klein"><?php if($format = get_the_term_list( $post->ID, 'format', ' ', ', ' )):?><?=$format?> <?php endif ?></div>
	    </span>
   </div> 
       <br>
-->
    <header class="entry-header"> 
    <h1 class="entry-title">
	       <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a> 
	</h1>
	<hr />
	<!-- Autor -->
	 	<div class="entry-meta">
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
				</div> <!-- autor -->	 
	 	</div>		
		<hr />	
    </header><!-- .entry-header -->

    <?php if (is_search()) { // Only display Excerpts for Search ?> 
    <div class="entry-summary">
        <?php the_excerpt(); ?> 
        <div class="clearfix"></div>
    </div><!-- .entry-summary -->
    <?php } else { ?> 
    <div class="entry-content">
        <?php the_content($Bsb4Design->continueReading(true)); ?> 
        <div class="clearfix"></div>
        <?php 
        /**
         * This wp_link_pages option adapt to use bootstrap pagination style.
         */
        wp_link_pages(array(
            'before' => '<div class="page-links">' . __('Pages:', 'bootstrap-basic4') . ' <ul class="pagination">',
            'after'  => '</ul></div>',
            'separator' => ''
        ));
        ?> 
    </div><!-- .entry-content -->
    <?php } //endif; ?> 

    <footer>
       

    </footer><!-- .entry-meta -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 