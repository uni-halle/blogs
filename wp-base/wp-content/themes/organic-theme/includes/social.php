<div id="social">
<?php if (get_option('organic_theme_social') === "true") { ?>
<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/facebook.png"></a>
<a href="http://twitthis.com/twit?url=<?php the_permalink(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/twitter.png"></a>
<a href="http://del.icio.us/post?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/delicious.png"></a>
<a href="http://technorati.com/faves?add=<?php the_permalink(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/technorati.png"></a>
<a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/stumbleupon.png"></a>
<a href="http://digg.com/submit?phase=2&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/digg.png"></a>
<a href="http://reddit.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/reddit.png"></a>
<a href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/google.png"></a>
<a href="http://www.friendfeed.com/share?title=<?php the_title(); ?>&link=<?php the_permalink(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/friendfeed.png"></a>
<a href="http://www.netvibes.com/share?title=<?php the_title(); ?>&url=<?php the_permalink(); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/netvibes.png"></a>
<a href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>" title="E-mail this story to a friend!" target="_blank"><img src="<?php bloginfo('template_url');?>/img/social/email_link.png"></a>
<?php } ?>
</div>
