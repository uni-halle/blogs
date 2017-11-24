<div id="author-<?php the_author_meta( 'ID' ); ?>" <?php post_class(array( 'clearfix', 'page', 'author' ) ); ?>>
    <div class="entry author-entry clearfix">
    	<?php do_action( 'graphene_author_entry' ); ?>
    
        <div class="author-heading col-md-12 clearfix">
    	
            <div class="col-sm-3">
                <?php 
        			if ( get_the_author_meta( 'graphene_author_imgurl' ) ) echo '<img class="avatar" src="' . get_the_author_meta( 'graphene_author_imgurl' ) . '" alt="" />';
        			else echo get_avatar( get_the_author_meta( 'user_email' ), 150 ); 
        		?>
            </div>

            <div class="col-sm-9">
                <h1 class="post-title"><?php echo get_the_author_meta( 'user_firstname' ) . ' ' . get_the_author_meta( 'user_lastname' ); ?></h1>
                <?php $location = get_the_author_meta( 'graphene_author_location' ); if ( $location ) : ?>
                    <p class="location"><?php echo $location; ?></p>
                <?php endif; ?>

                <?php graphene_author_social_links( get_the_author_meta( 'ID' ) ); ?>
                <?php do_action( 'graphene_author_details' ); ?>
            </div>

        </div>
                            
        <div class="row">
            <?php /* Post content */ ?>
            <div <?php graphene_grid( 'entry-content', 12 ); ?>>
                
    			<?php if (get_the_author_meta( 'description' ) != '' ) : ?>
    			<p><?php the_author_meta( 'description' ); ?></p>
                
                <?php do_action( 'graphene_author_desc' ); ?>
                <?php endif; ?>
                
                <?php /* Lists the author's most commented posts */ ?>
                <?php 
    			$args = array(
    				'posts_per_page'    => 5,
    				'author'            => get_the_author_meta( 'ID' ),
    				'orderby'           => 'comment_count',
    				'suppress_filters'  => 0,
				);
    			$postsQuery = new WP_Query( apply_filters( 'graphene_author_popular_posts_args', $args ) ); 
    			
    			// Check if at least one of the author's post has comments
    			$have_comments = NULL;
                            $comments_ol_html = '';
                            while ( $postsQuery->have_posts() ){
                                $postsQuery->the_post();
                                setup_postdata( $post);
                                $nr_comments = get_comments_number();
                                /* List the post only if comment is open 
                                 * and there's comment(s) 
                                 * and the post is not password-protected */
                                if (comments_open() && empty( $post->post_password) && $nr_comments != 0){
                                    $have_comments = TRUE;
                                    $comments_ol_html .= '<li><a href="'. get_permalink() .'" rel="bookmark" title="'. sprintf(esc_attr__( 'Permalink to %s', 'graphene' ), the_title_attribute( 'echo=0' ) ) .'">'. ( get_the_title() == '' ? __( '(No title)','graphene' ) : get_the_title() ) . '</a> &mdash; '. ( sprintf( _n( '%s comment', '%s comments', $nr_comments, 'graphene' ), number_format_i18n( $nr_comments ) ) ). '</li>';
                                }
                            }
    			
    			if ( $have_comments) :
    			?>
                    
                <h3><?php _e( 'Most commented posts', 'graphene' ); ?></h3>
                <ol><?php echo $comments_ol_html; ?></ol>
                <?php do_action( 'graphene_author_popularposts' ); ?>
                
                <?php endif; wp_reset_postdata(); ?>
            </div>            
        </div>
    </div>
</div>	