<?php
/*
Template Name: be-up Standard
*/
get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>
<div class="main-container">
	<div class="main-grid">
		</div>
	</div>
<main class="main-content-full-width">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template-parts/content', 'page' ); ?>
		<?php //get_template_part( 'template-parts/demo-content-standard', 'page' ); ?>
		<?php comments_template(); ?>
	<?php endwhile; ?>
</main>



<script>
function chkTextareaMaxlength(xObj){
   //alert('chkTextareaMaxlength');
   var cn = "#" + xObj.id + "Counter";
   var max = parseInt($(xObj).attr("maxlength"));
   var l = $(xObj).val().length;
   if(l > max) $(xObj).val($(xObj).val().substr(0, max));
   $(cn).html("Rest: " + (max - l) + " Zeichen");
   if(l == 0){
      $(cn).hide();
   }else{
      $(cn).slideDown();
   }
}
function resizeTextarea(xObj) {
   if(!document.all){
      xObj.style.height = "71px";
      xObj.style.height = xObj.scrollHeight/2 + 'px';
   }
   xObj.style.height = xObj.scrollHeight+10 + 'px';
   if($(xObj).height() < 71) xObj.style.height = "71px";
}
$(document).ready(function(){
    // ggf. Counter ausblenden
    $(".wpcf7 textarea").each(function(n,item){
       chkTextareaMaxlength(item);
       if($(item).val().length > 0) resizeTextarea(item);
    });
    // Counter-Funktion und Groessenanpassung
    $(".wpcf7 textarea[maxlength]").keyup(function(){
       chkTextareaMaxlength(this);
       resizeTextarea(this);
    });
    $(".wpcf7 textarea").keyup(function(){ resizeTextarea(this) });
    $(".wpcf7 textarea").keydown(function(){ resizeTextarea(this) });
    $(".wpcf7 textarea").keypress(function(){ resizeTextarea(this) });
 });
</script>


<?php get_footer();