<?php

/*
 * @template  Mystique
 * @revised   December 20, 2011
 * @author    digitalnature, http://digitalnature.eu
 * @license   GPL, http://www.opensource.org/licenses/gpl-license
 */

// Document footer.
// This is a template part which is displayed on every page of the website.

?>

         </div>
       </div>
       <!-- /main -->

       <?php atom()->action('after_main'); ?>

       <?php if(atom()->MenuExists('footer')): ?>
       <div class="nav nav-footer page-content">
          <?php atom()->Menu($location = 'footer', $class = 'slide-up'); ?>
       </div>
       <?php endif; ?>

       <!-- footer -->
       <div class="shadow-left page-content">
         <div class="shadow-right">

           <div id="footer">

             <div class="impressum foot-widget">
		<span><em><br /></em><p style="color: #000000;text-align: left;margin:0;padding:0;font-style:bold;font-size:100%; text-transform:uppercase;"><a title="Impressum" href="/impressum">Impressum </a>|<a title="Odcisk" href="/impressum-pl"> Odcisk</a></p><p style="color: #000000;text-align: left;margin:0;padding:0;font-style:bold;font-size:100%; text-transform:uppercase;"><a title="Sitemap" href="/sitemap">Sitemap </a>|<a title="Mapa strony" href="/sitemap-pl"> Mapa strony</a></p></span>
             </div>

             <div class="uni-info foot-widget" style="margin-left: 10px; width: 220px;">
		<span style="padding-top:3em; text-align: right; font-size:90%;line-height:50%"><a title="Deutsch-Polnische Wissenschaftsstiftung" href="http://www.dpws.de/index.php" target="_blank"><p style="margin-top:14px;color: #000000;">Deutsch-Polnische Wissenschaftsstiftung</p></a><a title="Polsko-Niemiecka Fundacja na rzecz Nauki" href="http://www.pnfn.pl/pl/index.php" target="_blank"><p style="margin-top: -10px; color: #000000;">Polsko-Niemiecka Fundacja na rzecz Nauki</p></a></span>
             </div>

             <div class="uni-logo foot-widget" style="margin-left: 15px; margin-top: -10px; width: 150px;">
		<img class="alignleft size-full wp-image-169" title="Deutsch-Polnische Wissenschaftsstiftung | Polsko-Niemiecka Fundacja na rzecz Nauki" src="files/design/index.gif" alt="Deutsch-Polnische Wissenschaftsstiftung | Polsko-Niemiecka Fundacja na rzecz Nauki" /></a>
             </div>

             <div class="designer-logo foot-widget">
		<a href="http://www.uni-halle.de/" target="_blank"><img class="alignright size-full wp-image-287" title="uni-halle.de" src="files/design/uni-logo.jpg" alt="uni-halle.de" width="145" height="80" /></a>
             </div>

           </div>
             <div id="copyright">
               Copyright © 2012 Prof. Dr. Florian Steger.  Created by <a href="http://christopherhohlbaum.com" target="_blank">CHRISTOPHER HOHLBAUM</a> and <a href="http://design.ernestocostanzo.com" target="_blank">ERNESTO COSTANZO</a>
             </div>

         </div>
       </div>
       <!-- /footer -->

       <a class="go-top" href="#page"><?php atom()->te('Go to Top'); ?></a>

     </div>
    <!-- /page-ext -->


    <!-- <?php echo do_shortcode('[load]'); ?> -->

  </div>
  <!-- page -->

  <?php atom()->end(); ?>

</body>
</html>
