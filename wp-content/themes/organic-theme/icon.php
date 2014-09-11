<?php
 header("Content-Type:text/javascript");
 require('../../../wp-load.php');
 $current = get_option("organic_theme_logo");
?>
window.onload=function()
 {
  var
  base='../wp-content/themes/organic-theme/img/logos/',
  current = '<?php echo $current; ?>',
  thumb=document.getElementById('thumb'),
  sel=document.getElementById('organic_theme_logo');
  if(sel != null) {
  sel.onchange=function()
  {
  thumb.src=base+this.value+'.gif';
  }
  thumb.src=base+current+'.gif';
}
   }
