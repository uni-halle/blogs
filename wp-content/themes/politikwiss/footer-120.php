<footer id="footer" class=" footer">
<div class="container">
<section id="impressum">
 <div id="impressum_close" class="glyphicon glyphicon-remove"></div>

	 <?php

$id = 188;
$p = get_page($id);
echo apply_filters('the_content', $p->post_content);
?>

	</section>
    <ul>
        <li><a id="impressum_link" href="#">Impressum</a></li>
        <li><a href="http://www.japanologie.uni-halle.de/">Institut für Politikwissenschaft &amp; Japanologie</a></li>
        <li><a href="/masterpolitikwissenschaften/?p=17">Zum Master 45/75</a></li>
        <li><a href="http://www.uni-halle.de" target="_blank"><img src="<?php echo esc_url( get_template_directory_uri() );?>/img/Logo_MLU_Halle-Wittenberg.svg" alt="Logo der MLU Halle-Wittenberg" /></a></li>
    </ul>
    
 </div>   
</footer>
</body>
<script>
<!--Beseitigt den FirefoxImageResizeBug --> 
$( document ).ready(function() {
   var dwidth = $( document ).width();
   var imgwidth = $(".slides li img").width();
   if (imgwidth != dwidth)  {
      $(".slides li img").width() = dwidth;
    }
    
});
</script>
</html>
<?php wp_footer();?>