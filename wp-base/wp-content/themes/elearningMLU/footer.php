</div><!-- end main -->

<div class="sub-container">
	<div class="column one">
		<div class="category">
		
			<?php include (TEMPLATEPATH . '/extra-content-one.php'); ?>
			<div class="edge-cat"></div>
	
		</div>
	</div>
	<div class="column two">
		<div class="discussed">
			
			<?php include (TEMPLATEPATH . '/extra-content-two.php'); ?>
			<div class="edge-dis"></div>
			
		</div>
	</div>
	<div class="column three">
		<div class="links">
		
			<?php include (TEMPLATEPATH . '/extra-content-three.php'); ?>
			<div class="edge-links"></div>
		
		</div>
		
	</div>

</div><!-- end sub-container -->

<div class="copyright">

	<p>Copyright &copy; <?php 
						$year=date("Y");
						if ($year=="2009")
							echo "2009"; 
						else 
							echo "2009 - $year";							
						?> - <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
	</p><br />
	<p>Ein weiteres tolles Blog von <a href="http://blogs.urz.uni-halle.de" title="Blogs@MLU">Blogs@MLU</a></p>
	<p>Theme von <a href="http://smashingmagazine.com" title="Smashing Magazine">Smashing Magazine</a>, angepasst von <a href="http://www.kremalicious.com/blog" title="Matthias Kretschmann">Matthias Kretschmann</a></p>

</div>

</div><!-- end container -->

<?php wp_footer(); ?>

</body>

</html>
