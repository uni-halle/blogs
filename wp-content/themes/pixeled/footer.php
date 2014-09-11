<div id="morefoot">

<div class="col1">
<h3>Suchst du was?</h3>
<p>Du kannst hier deine Suchw&ouml;rter eingeben.</p>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
<p>Immernoch nichts gefunden zu dem was du suchst? Dann schreibe ein Kommentar an einen Beitrag oder kontaktiere uns. Wir werden deine Anfrage so schnell wie m&ouml;glich bearbeiten.</p>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_left') ) : ?>
<?php endif; ?>
</div>

<div class="col2">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_middle') ) : ?>
<h3>Besuche unsere Freunde!</h3><p>Unsere Partner und Freunde...</p><ul><?php wp_list_bookmarks('title_li=&categorize=0'); ?></ul>
<?php endif; ?>
</div>

<div class="col3">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_right') ) : ?>
<h3>Archiv</h3><p>Nach chronologischer Reihenfolge:</p><ul><?php wp_get_archives('type=monthly&limit=12'); ?> </ul>
<?php endif; ?>
</div>

<div class="cleared"></div>
</div><!-- Closes morefoot -->



<div id="footer">
<div id="footerleft">
<!-- Es ist dir nicht erlaubt den folgenden Abschnitt zu bearbeiten. -->
<p>Blog Software: <a href="http://www.wordpress.org/">WordPress</a>. Theme <a href="http://samk.ca/freebies/" title="WordPress theme">pixeled</a> von <a href="http://samk.ca/" title="WordPress theme design">samk</a>. Die Icons stammen von <a href="http://famfamfam.com/">famfamfam</a>. Deutsche &Uuml;bersetzung: <a href="http://www.wp-uebersetzer.de" title="Kostenlos Wordpress Themes &uuml;bersetzen lassen">WP-Uebersetzer</a>. <a href="#main">Nach oben &uarr;</a>.
<!-- Ende des Bearbeitungs-Verbots -->
</div>

<div id="footerright">
<a href="http://wordpress.org" title="WordPress platform"><img src="<?php bloginfo('template_directory'); ?>/images/wpfooter-trans.png" alt="WordPress" width="34" height="34" /></a>
</div>

<div class="cleared"></div>
<?php wp_footer(); ?>
</div><!-- Closes footer -->

</div><!-- Closes wrapper -->
</body>
</html>