
	<div id="pageextend"></div></div>
				<div class="clear">
 <center><div id="footer"> <a href="<?php echo home_url(); ?>/">

		<?php 
$time = time () ; 
//This line gets the current time off the server

$year= date("Y",$time) . ""; 
//This line formats it to display just the year

echo "&copy; " . $year;
//this line prints out the copyright date range, you need to edit 2002 to be your opening year
?> <?php bloginfo('name');?>
 </a> <br />
<?php _e("theme by ", "magazinetheme"); ?><a href="http://www.adazing.com/">adazing web design</a>	  </div>
 </center>

</div></div></div>
  <?php wp_footer(); ?>
	

</body>
</html>
