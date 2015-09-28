<footer id="footer" class=" footer">
<div class="container">
<article id="impressum">
 <div id="impressum_close" class="glyphicon glyphicon-remove"></div>

	 <?php

$id = 188;
$p = get_page($id);
echo apply_filters('the_content', $p->post_content);
?>
<a href="http://www.plandelta.de" target="_blank">Webdesign: Plandelta.de</a>
<p>Eine Seite von <a href="https://blogs.urz.uni-halle.de/">Blogs@MLU</a>, dem Blog-Dienst des <a href="http://itz.uni-halle.de/">IT-Servicezentrums</a></p>
	</article>
    <ul>
        <li><a id="impressum_link" href="#">Impressum</a></li>
        <li><a href="http://www.japanologie.uni-halle.de/">Institut für Politikwissenschaft &amp; Japanologie</a></li>
        <li><a href="http://www.uni-halle.de" target="_blank"><img src="<?php echo esc_url( get_template_directory_uri() );?>/img/Logo_MLU_Halle-Wittenberg.svg" alt="Logo der MLU Halle-Wittenberg" /></a></li>
    </ul>
    
 </div>   
</footer>

</body>
</html>
<script>
$( document ).ready(function() {
   var dwidth = $( document ).width();
   var imgwidth = $(".slides li img").width();
   if (imgwidth != dwidth)  {
      $(".slides li img").width() = dwidth;
    }
    
});
</script>
<?php wp_footer();?>
