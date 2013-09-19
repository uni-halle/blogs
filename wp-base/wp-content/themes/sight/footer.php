            </div>
            <!-- /Content -->

            <?php get_sidebar(); ?>

            </div>
            <!-- /Container -->

            <div class="footer">
                <p class="copyright">&copy; 2010 <a href="<?php bloginfo('home'); ?>"><?php bloginfo('name'); ?></a>. All Rights Reserved.<br /><span>Powered by <a
                        href="http://wordpress.org">WordPress</a>.</span></p>
                <p class="credits">Designed by <a href="http://wpshower.com">WPSHOWER</a></p>
            </div>
        </div>
        <!-- Page generated: <?php timer_stop(1); ?> s, <?php echo get_num_queries(); ?> queries -->
        <?php wp_footer(); ?>

        <?php echo (get_option('ga')) ? get_option('ga') : '' ?>

	</body>
</html>