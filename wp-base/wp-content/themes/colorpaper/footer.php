					<div id="footer" class="clearfix">
						<div>
							<ul class="footer-pages clearfix">
								<li class="first"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?> Home">Home</a></li>
								<?php wp_list_pages('title_li=&depth=1'); ?>
							</ul>
							<br><p><big><strong>&copy; Copyright <?php bloginfo('name'); ?>. All rights reserved.</strong></big><br>
								Designed by FTL <a href="http://www.freethemelayouts.com/" style="color: #dce7c0;text-decoration: none;">Wordpress Themes</a> brought to you by <a href="http://smashingmagazine.com/" style="color: #dce7c0;text-decoration: none;">Smashing Magazine</a>
								</p>
						</div>
						<a href="#top" class="btn btn-footer right">Back to Top</a>
					</div>
				</div>
			</div>
		</div>
	</div></div>
	<?php wp_footer(); ?>
</body>
</html>