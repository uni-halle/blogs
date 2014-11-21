<div id="item-<?php the_ID(); ?>" <?php post_class(); ?> >
	<div class="item-inner">
    	<div class="item-ribbon"></div> <!-- @llz - Fähnchen eingefügt -->
		<?php if( has_post_format('gallery') ) : ?>

			<?php echo wallpress_gallery(); ?>

		<?php else : ?>

			<?php if( has_post_thumbnail() ) : ?>
			<?php $grid = get_post_meta($post->ID, 'grid', true); ?>
			<div class="item-thumbnail">
				<!-- <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'dw-wallpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"> @llz - Link zur Einzelseite entfernt -->
                	<div class="item-thumbnail-overlay"></div> <!-- @llz - Kleines, weißes Dreieck eingefügt -->
					<?php the_post_thumbnail(); // @llz - Größenoptionen aufgrund von Anzeigefehlern entfernt ?>
				<?php if(has_post_format('video')) '<span class="video-play"><i class="fa fa-play"></i></span>'; ?>
				<!-- </a> @llz - Link zur Einzelseite entfernt -->
				<?php if(has_post_format('image')) : ?>
				<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" rel="lightbox" class="image-btn zoom" title="<?php printf( esc_attr__( '%s', 'dw-wallpress' ), the_title_attribute( 'echo=0' ) ); ?>" ><i class="fa fa-arrows-alt"></i></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
		<?php endif; ?>

		<?php if(has_post_format('audio')) wallpress_audio_player( get_the_ID() ); ?>
        
		<div class="item-main">
			<div class="item-header">
				<h2 class="item-title">
				<!--<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'dw-wallpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"> @llz - Link zur Einzelseite entfernt --><?php the_title(); ?><!-- </a> --> <?php edit_post_link('<i class="fa fa-pencil"></i>'); // @llz - Bearbeiten-Link als Icon ?>
				</h2>

				<div class="item-meta meta-top clearfix">
					<span class="item-author">
						<?php _e( 'By', 'dw-wallpress' );?>
						<?php the_author_posts_link(); ?>
					</span>
					<?php
						$categories_list = get_the_category_list( __( ', ', 'dw-wallpress' ) );
						if ( $categories_list ):
					?>
					<span class="item-category">
						<?php 
							printf( __( '<span class="%1$s">in</span> %2$s', 'dw-wallpress' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
							$show_sep = true; 
						?>
					</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="item-content">
			<?php if ( is_search() ) : ?>
				<?php the_excerpt(); ?>
			<?php else : ?>
				<?php
					// @llz - Content-Ausgabe verändert, damit ab 520 Zeichen der Beiträg getrennt wird
					$content = get_the_content_with_formatting();
					if (strlen($content) > 520) {
					  $content = substr($content, 0, 520);
					  $content .= ' <a href="'.get_the_permalink().'">[mehr...]</a>';					
					} 
					echo $content;
				?>
				<?php wp_link_pages( array( 'before' => '<div class="item-link-pages"><span> ' . __( 'Pages:', 'dw-wallpress' ) . '</span>', 'after' => '</div>' ) ); ?>
			<?php endif; ?>
			</div>

			<!-- @llz - Meta-Bereich komplett überarbeitet (Beginn) -->
			<div class="item-meta meta-bottom">
				<div class="item-meta-left">
                    <span class="item-meta-date">Vor <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?></span>
                    
                </div>
            	<div class="item-meta-right">
                <span class="item-meta-favorite">
                    	<?php if (function_exists('wpfp_link')) { wpfp_link(); } ?>
                    </span>
                    <span class="item-meta-likes">
               	    	<?php echo getPostLikeLink( $post->ID ); ?>
                    </span>

                    <span class="comments-link item-meta-comments">
                        <?php comments_popup_link( '<span class="leave-reply"><i class="fa fa-comments"></i> ' . __( '0', 'dw-wallpress' ) . '</span>', __( '<i class="fa fa-comments"></i> 1', 'dw-wallpress' ), __( '<i class="fa fa-comments"></i> %', 'dw-wallpress' ) ); ?>
                    </span>
				</div>
                <div class="clearing"></div>
                

			</div>
            <!-- @llz - (Ende) -->
            
            
            <!-- @llz - Kommentare eingefügt -->
            <div class="item-comments">
                 <?php
					global $withcomments;
					$withcomments = true;
					comments_template();
				?>
            </div>
            <!-- @llz - (Ende) -->
                    

		</div>
		
	</div>
</div>
<!-- #item-<?php the_ID(); ?> -->
