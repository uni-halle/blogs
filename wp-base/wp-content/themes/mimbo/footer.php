</div><!--END WRAPPER-->

<div id="footer" class="clearfloat">
  <div id="copyright">
  &#169; <?php echo date('Y'); ?> <span class="url fn org"><?php bloginfo('name'); ?></span> &bull; 

<?php _e('Powered by','Mimbo'); ?> <a href="http://wordpress.org/" target="_blank">WordPress</a> &amp; <a href="http://www.darrenhoyt.com/2007/08/05/wordpress-magazine-theme-released/" target="_blank" title="By Darren Hoyt">Mimbo</a>

<?php wp_footer(); ?>
</div>

<div id="rss">
<img src="<?php bloginfo('template_url'); ?>/images/rss.gif" alt="rss" /><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Blog Entries','Mimbo'); ?></a> 
&bull;  <a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments','Mimbo'); ?></a>
</div> 

</div><!--END FOOTER-->
</div><!--END PAGE-->
</body>
</html>