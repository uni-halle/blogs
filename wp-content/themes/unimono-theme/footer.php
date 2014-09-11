			<div class="footer">
            	<div class="footer_left">
            	<div class="footer_right">
                	<div class="copy">&copy; <?php bloginfo('name'); ?> <?php 
																			$year=date("Y");
																			if ($year=="2011")
																				echo "2011"; 
																			else 
																				echo "2011 - $year";							
																			?>
						 | based on <a href="http://www.jayhafling.com/">Jay Hafling</a> | modified by Marco H&auml;rtl &amp; <a href="http://matthiaskretschmann.com">Matthias Kretschmann</a>.</div>
                    <div class="copy_support">Powered by <a href="http://www.wordpress.org/">WordPress</a></div>
            	</div>
            	</div>
            </div>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>