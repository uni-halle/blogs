            
			<div class="footer <?php if ( !get_option('bizzthemes_right_sidebar') ) { echo 'footer_right'; } else { echo 'footer_left'; } ?>">
            
                <div class="copyright <?php if ( !get_option('bizzthemes_right_sidebar') ) { echo 'fr'; } else { echo 'fl'; } ?>">

			        <div class="fl">&copy; <?php echo date("Y") ?> <?php bloginfo(); ?>.</div>
				
				    <div class="fr"><a href="http://bizzartic.com/bizzthemes/inuitypes/"><em>Inuit Types</em></a> by <a href="http://bizzartic.com"><span></span></a></div>
				
                </div>
			
            </div>

        </div>

    </div>

<?php wp_footer(); ?>

<script  type="text/javascript"  >
/* <![CDATA[ */

sfHover = function() {
//alert('hai');
	var sfEls = document.getElementById("pagenav").getElementsByTagName("li");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
/* ]]> */

</script>

    <?php if ( get_option('bizzthemes_google_analytics') <> "" ) { echo stripslashes(get_option('bizzthemes_google_analytics')); } ?>

</body>
</html>