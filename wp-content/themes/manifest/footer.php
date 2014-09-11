</div>

<div id="footer">

  <!-- Footer Links -->
  
  <h5>Elsewhere</h5>
  <ul class="elsewhere">
    <li><a href="#">Facebook</a></li>
    <li><a href="#">Flickr</a></li>
    <li><a href="#">Last.fm</a></li>
    <li><a href="#">Deli.icio.us</a></li>
    <li><a href="#">Linkedin</a></li>
    <li><a href="#">Twitter</a></li>
    <li class="last"><a href="#">Vimeo</a></li>
  </ul>

  <!-- Search Field -->
  
  <div class="footerContent">
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
      <div id="search">
        <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="Search" />
      </div>
    </form>
    
    
    <p>&copy; <?php bloginfo('name'); ?>. Powered by <a href="http://wordpress.org/">WordPress</a> and <a href="http://jimbarraud.com/manifest/">Manifest</a></p>
  </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
