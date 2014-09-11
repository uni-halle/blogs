<?php

/*
 * @template  Mystique
 * @revised   December 22, 2011
 * @author    digitalnature, http://digitalnature.eu
 * @license   GPL, http://www.opensource.org/licenses/gpl-license
 */

// This is a generic template that styles post teasers on homepage and archive pages (or all pages except those of "singular" type).
//
// Priority (highest to lowest):
//   - teaser-ID.php
//   - teaser-postType-postFormat.php
//   - teaser-postType.php
//   - teaser-postFormat.php
//   - teaser.php
//
// Notes:
//  1) The atom()->post methods are not required, you can choose to use default WP functions instead. Atom methods are more flexible, and theoretically faster...
//
//  2) Notice the difference between getMethod() and Method().
//     The first one will return the value, while the second one will output the value to the screen
//
//  3) In case you're writing your own template inside a child theme, you don't have to do any option checks, just choose to display what you want.
//     That's the purpose of creating and using a child theme...

?>


<?php if(atom()->options('post_title', 'post_date', 'post_category', 'post_tags', 'post_author', 'post_comments', 'post_content') === FALSE && atom()->options('post_thumbs')): ?>

<!-- post -->
<div id="post-<?php the_ID(); ?>" <?php post_class('thumb-only'); ?>>
  <a class="post-thumb tt" id="thumb-<?php the_ID(); ?>" href="<?php atom()->post->URL(); ?>" title="<?php atom()->post->title(); ?>">
    <?php atom()->post->thumbnail(); ?>
  </a>
</div>
<!-- /post -->

<?php else: ?>

<!-- post -->
<div id="post-<?php the_ID(); ?>" <?php post_class('clear-block'); ?>>

  <div class="post-details">


    <?php if(atom()->options('post_title')): ?>
    <h1 class="title">
      <a href="<?php atom()->post->URL(); ?>" rel="bookmark" title="<?php atom()->te('Permanent Link: %s', atom()->post->getTitle()); ?>"><?php atom()->post->title(); ?></a>
    </h1>
    <?php endif; ?>

    <?php if(atom()->options('post_comments') && comments_open()): ?>
    <a class="comments" href="<?php atom()->post->URL(); ?>#comments"><?php atom()->post->commentCount(); ?></a>
    <?php endif; ?>


    <?php if(atom()->options('post_content')): ?>
    <div class="post-content clear-block">
      <?php (is_sticky() && is_home()) ? the_content() : atom()->post->content(); ?>
    </div>
    <?php endif; ?>

    <?php if(atom()->options('post_tags') && atom()->post->getTerms()): ?>
    <div class="post-extra">
       <div class="post-tags"> <?php atom()->post->terms(); ?> </div>
    </div>
    <?php endif; ?>

  </div>

  <?php atom()->controls('post-edit'); ?>
</div>
<!-- /post -->

<?php endif; ?>