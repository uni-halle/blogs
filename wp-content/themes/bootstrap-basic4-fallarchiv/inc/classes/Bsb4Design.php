<?php
/**
 * The Bootstrap Basic 4 design functions (template tags).
 * 
 * @package bootstrap-basic4
 */

namespace BootstrapBasic4;

if (!class_exists('\\BootstrapBasic4\\Bsb4Design')) {

  /**
   * This class works on template tags or design functions. Such as link to categories or tags from the post page, post on date/time, post by ..., comment fields, and more.
   */
  class Bsb4Design {

    /**
     * Return or display attachment.
     * 
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return attachment.
     */
    public function attachment($return = false) {
      $post = get_post();
      $metadata = wp_get_attachment_metadata();
      $attachment_size = apply_filters('bootstrap_basic4_attachment_size', array(1140, 1140));
      $next_attachment_url = wp_get_attachment_url();

      /**
       * Grab the IDs of all the image attachments in a gallery so we can get the
       * URL of the next adjacent image in a gallery, or the first image (if
       * we're looking at the last image in a gallery), or, in a gallery of one,
       * just the link to that image file.
       */
      $attachment_ids = get_posts(array(
          'post_parent' => $post->post_parent,
          'fields' => 'ids',
          'numberposts' => -1,
          'post_status' => 'inherit',
          'post_type' => 'attachment',
          //'post_mime_type' => 'image',
          'order' => 'ASC',
          'orderby' => 'menu_order ID'
      ));

      // If there is more than 1 attachment in a gallery...
      if (count($attachment_ids) > 1) {
        foreach ($attachment_ids as $attachment_id) {
          if ($attachment_id == $post->ID) {
            $next_id = current($attachment_ids);
            break;
          }
        }
        unset($attachment_id);
        if ($next_id) {
          // get the URL of the next image attachment...
          $next_attachment_url = get_attachment_link($next_id);
        } else {
          // or get the URL of the first image attachment.
          $next_attachment_url = get_attachment_link(array_shift($attachment_ids));
        }
        unset($next_id);
      }

      $output = '';
      $type = get_post_mime_type($post->ID);
      switch (strtolower($type)) {
        case 'audio/mp3':
        case 'audio/mpeg':
        case 'audio/mpeg3':
        case 'audio/mpg':
        case 'audio/wav':
        case 'audio/wave':
        case 'audio/webm':
        case 'audio/x-wav':
          $output = do_shortcode('[audio ' . $metadata['fileformat'] . '="' . wp_get_attachment_url($post->ID) . '"][/audio]');
          break;
        case 'image/bmp':
        case 'image/gif':
        case 'image/jpeg':
        case 'image/pjpeg':
        case 'image/png':
        case 'image/x-png':
          $output = sprintf(
                  '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>', esc_url($next_attachment_url), the_title_attribute(array('echo' => false)), wp_get_attachment_image($post->ID, $attachment_size, false, array('class' => 'img-fluid aligncenter'))
          );
          break;
        case 'video/mp4':
        case 'video/mpeg':
        case 'video/quicktime':
        case 'video/webm':
        case 'video/x-ms-wmv':
        case 'video/x-msvideo':
          $output = do_shortcode('[video width="' . $metadata['width'] . '" height="' . $metadata['height'] . '" ' . $metadata['fileformat'] . '="' . wp_get_attachment_url($post->ID) . '"][/video]');
          break;
        default:
          $output = '<div class="card card-block"><i class="fa fa-download"></i> ' . wp_get_attachment_link() . '</div>';
          break;
      }// endswitch;
      unset($type);

      unset($attachment_ids, $attachment_size, $metadata, $next_attachment_url, $post);
      if ($return === true) {
        return $output;
      } else {
        echo $output;
        unset($output);
      }
    }

// attachment

    /**
     * Return or display categories list.
     * 
     * @param string $categories_list The categories html.
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return categories list.
     */
    public function categoriesList($categories_list, $return = false) {
      $output = sprintf('<span class="categories-icon fa fa-th-list" title="' . __('Posted in', 'bootstrap-basic4') . '"></span> %1$s', $categories_list);

      if ($return === true) {
        return $output;
      } else {
        echo $output;
      }
    }

// categoriesList

    /**
     * Display comments link.
     */
    public function commentsLink() {
      $comment_icon = '<i class="comment-icon fa fa-comment"></i> <small class="comment-total">%d</small>';
      $comments_icon = '<i class="comment-icon fa fa-comments"></i> <small class="comment-total">%s</small>';
      comments_popup_link(sprintf($comment_icon, ''), sprintf($comment_icon, '1'), sprintf($comments_icon, '%'), 'btn btn-light btn-sm');
    }

// commentsLink

    /**
     * Return or display continue reading message.
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return continue reading message.
     */
    public function continueReading($return = false) {
      $output = __('Continue reading <span class="meta-nav">&rarr;</span>', 'bootstrap-basic4');

      if ($return === true) {
        return $output;
      } else {
        echo $output;
      }
    }

// continueReading

    /**
     * Display the comments
     * 
     * @param object $comment
     * @param array $args
     * @param integer $depth
     */
    public function displayComments($comment, $args, $depth) {
      $GLOBALS['comment'] = $comment;

      if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) {
        echo '<li id="comment-';
        comment_ID();
        echo '" ';
        comment_class('comment-type-pt');
        echo '>';
        echo '<div class="comment-body media">';
        echo '<div class="media-body">';
        _e('Pingback:', 'bootstrap-basic4');
        comment_author_link();
        edit_comment_link(__('Edit', 'bootstrap-basic4'), '<span class="edit-link">', '</span>');
        echo '</div>';
        echo '</div>';
      } else {
        echo '<li id="comment-';
        comment_ID();
        echo '" ';
        comment_class(empty($args['has_children']) ? '' : 'parent');
        echo '>';

        echo '<article id="div-comment-';
        comment_ID();
        echo '" class="comment-body media">';

        // footer
        echo '<footer class="comment-meta media-left">';
        if (0 != $args['avatar_size']) {
          echo get_avatar($comment, $args['avatar_size']);
        }
        echo '</footer><!-- .comment-meta -->';
        // end footer
        // comment content
        echo '<div class="comment-content media-body">';
        echo '<div class="comment-author vcard">';
        echo '<div class="comment-metadata">';

        // date-time
        echo '<a href="';
        echo esc_url(get_comment_link($comment->comment_ID));
        echo '">';
        echo '<time datetime="';
        comment_time('c');
        echo '">';
        printf(_x('%1$s at %2$s', '1: date, 2: time', 'bootstrap-basic4'), get_comment_date(), get_comment_time());
        echo '</time>';
        echo '</a>';
        // end date-time

        echo ' ';

        edit_comment_link('<span class="fa fa-pencil-square-o "></span>' . __('Edit', 'bootstrap-basic4'), '<span class="edit-link">', '</span>');

        echo '</div><!-- .comment-metadata -->';

        // if comment was not approved
        if ('0' == $comment->comment_approved) {
          echo '<div class="comment-awaiting-moderation text-warning"> <span class="fa fa-info-circle"></span> ';
          _e('Your comment is awaiting moderation.', 'bootstrap-basic4');
          echo '</div>';
        } //endif;
        // comment author says
        printf(__('%s <span class="says">says:</span>', 'bootstrap-basic4'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link()));
        echo '</div><!-- .comment-author -->';

        // comment content body
        comment_text();
        // end comment content body
        // reply link
        comment_reply_link(array_merge($args, array(
            'add_below' => 'div-comment',
            'depth' => $depth,
            'max_depth' => $args['max_depth'],
            'reply_text' => '<span class="fa fa-reply"></span> ' . __('Reply', 'bootstrap-basic4'),
            'login_text' => '<span class="fa fa-reply"></span> ' . __('Log in to Reply', 'bootstrap-basic4')
        )));
        // end reply link
        echo '</div><!-- .comment-content -->';
        // end comment content

        echo '</article><!-- .comment-body -->';
      } //endif;
    }

// displayComments

    /**
     * Return or display edit post link.
     * 
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return edit post link.
     */
    public function editPostLink($return = false) {
      $edit_post_link = get_edit_post_link();
      if ($edit_post_link != null) {
        $edit_btn = '<a class="post-edit-link btn btn-light btn-sm" href="' . $edit_post_link . '" title="' . __('Edit', 'bootstrap-basic4') . '" role="button"><i class="edit-post-icon fa fa-pencil" title="' . __('Edit', 'bootstrap-basic4') . '"></i></a>';
        unset($edit_post_link);

        if ($return === true) {
          return $edit_btn;
        } else {
          echo $edit_btn;
        }
      }
      unset($edit_btn, $edit_post_link);
    }

// editPostLink

    /**
     * Return or display pagination.
     * 
     * @global \WP_Query $wp_query WordPress query class.
     * @param string $pagination_align_class The pagination css class.
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return pagination html.
     */
    public function pagination($pagination_align_class = 'justify-content-center', $return = false) {
      global $wp_query;
      $max_num_pages = $wp_query->max_num_pages;
      if ($max_num_pages > 1) {
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        ?>
        <div class="pagination">
          <div class="nav-previous"><?php previous_posts_link('<i class="fas fa-arrow-circle-left"></i>', $max_num_pages); ?></div>
          <div class="pagination-text">Seite <?php echo $paged; ?> von <?php echo $max_num_pages; ?></div>
          <div class="nav-next"><?php next_posts_link('<i class="fas fa-arrow-circle-right"></i>'); ?></div>
        </div>
        <?php
        return;
      }
    }

    public function numberOfPosts() {
      global $searchandfilter, $wp_query, $query_vars;
      $sfid = \BootstrapBasic4\Bsb4SearchAndFilter::get_sfid();
      $args = array();
      //$args = $wp_query->query_vars;
      $args['paged'] = 0;
      $args['posts_per_page'] = -1;
      $args['post_type'] = $wp_query->get('post_type');
      $args['post_status'] = 'publish';
      $args['meta_query'] = $wp_query->get('meta_query');
      $args['tax_query'] = $wp_query->get('tax_query');
      $args['category_name'] = $wp_query->get('category_name');
      $args['cat'] = $wp_query->get('cat');
      $args['tag'] = $wp_query->get('tag');
      $args['tag_id'] = $wp_query->get('tag_id');
      $args['category__in'] = $wp_query->get('category__in');
      $args['post__in'] = $wp_query->get('post__in');
      $args['term'] = $wp_query->get('term');
      $args['taxonomy'] = $wp_query->get('taxonomy');
      if (!empty($wp_query->get('s'))) {
        $args['s'] = $wp_query->get('s');
      }
      $new_query = new \WP_Query($args);
      //var_dump(($wp_query->query_vars));
      $numberOfProtectedPosts = 0;
      foreach ($new_query->posts as $p) {
        if (!empty($p->post_password)) {
          $numberOfProtectedPosts++;
        }
      }
      if (!empty($wp_query->get('s'))) {
        echo '<span>' . $wp_query->found_posts . ' Ergebnisse ';
        if ($numberOfProtectedPosts) {
          echo '(davon ' . $numberOfProtectedPosts . ' geschützt)</span>';
        } else {
          echo '</span>';
        }
      } else if ($sfid != 2048) {
        echo '<span>' . $wp_query->found_posts . ' Fälle ';
        if ($numberOfProtectedPosts) {
          if ($numberOfProtectedPosts > 1) {
            $numberOfProtectedPosts.= ' Fälle';
          } else {
            $numberOfProtectedPosts.= ' Fall';
          }
          echo '(davon ' . $numberOfProtectedPosts . ' geschützt)</span>';
        } else {
          echo '</span>';
        }
      } else if ($sfid) {
        echo '<span>' . $wp_query->found_posts . ' Interpretationen ';
        if ($numberOfProtectedPosts) {
          if ($numberOfProtectedPosts > 1) {
            $numberOfProtectedPosts.= ' Interpretationen';
          } else {
            $numberOfProtectedPosts.= ' Interpretation';
          }
          echo '(davon ' . $numberOfProtectedPosts . ' geschützt)</span>';
        } else {
          echo '</span>';
        }
        //(/' . wp_count_posts('interpretation')->publish . ')</span>';
      } else {
        
      }
    }

// pagination

    /**
     * Return or display post date/time and the author.
     * 
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return post date/time and the author.
     */
    public function postOn($return = false) {
      $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
      if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
      }

      $time_string = sprintf($time_string, esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_attr(get_the_modified_date('c')), esc_html(get_the_modified_date())
      );

      $output = sprintf(__('<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'bootstrap-basic4'), sprintf('<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>', esc_url(get_permalink()), esc_attr(get_the_time()), $time_string
              ), sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'bootstrap-basic4'), get_the_author())), esc_html(get_the_author())
              )
      );

      unset($time_string);
      if ($return === true) {
        return $output;
      } else {
        echo $output;
      }
    }

// postOn

    /**
     * Return or display tags list
     * 
     * @param string $tags_list The tags list html.
     * @param boolean $return If set to true it will use return the value, if set to false it will be display immediately.
     * @return string Return tags list.
     */
    public function tagsList($tags_list, $return = false) {
      $output = sprintf('<span class="tags-icon fa fa-tags" title="' . __('Tagged', 'bootstrap-basic4') . '"></span> %1$s', $tags_list);

      if ($return === true) {
        return $output;
      } else {
        echo $output;
      }
    }

// tagsList
    
    public function mainContainerClasses() {
      return 'col-md-8 col-lg-6 col-xl-6 site-main';
      
    }
  }

}