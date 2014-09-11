<?php
/**
 * Der Footer.
 *
 * Zeigt alles nach dem schließenden </div> -> id="main"
 *
 * @package WordPress
 * @subpackage Deutschdidaktik
 * @since Deutschdidaktik 1.0
 */
?>

	</div><!-- #main -->
	<footer role="contentinfo">
        <!-- Template footer -->
    	<div class="liste">
            <p>Designed by <a href="http://timoleich.de" target="_new">timoleich.de</a></p>
            <ul>
                <?php wp_list_categories('title_li=&hide_empty=0&orderby=id&child_of=1'); ?>
            </ul>
        </div>
       	<div class="clear"></div>
	</footer><!-- #colophon -->
</div><!-- #wrapper -->

<?php wp_footer(); ?>
<!--<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/behavior.js"></script>-->
<?php //include_once('/www/data/www.deutschdidaktik.uni-halle.de/wp-content/plugins/theme-inc.php'); ?>
</body>
</html>
