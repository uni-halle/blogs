<?php get_header(); ?>

<div id="content" class="error-page">
 <h2><span>Area 404</span> <br /> Nonexistent Area</h2>
 <p><strong>This is  a restricted area. No trespassing beyond this point. This place does not exist. You were never here.</strong><br />
</p>
<dl>
	<dt>Leave now by either:</dt>
    	<dd> Going <a href="<?php echo home_url(); ?>">HOME</a> 
 		or try doing a Search.</dd>
 </dl>

 
     <form id="searchform" method="get" action="<?php echo home_url();  ?>">
	    <div>
		    <input type="text" name="s" id="s" size="25" />
		    <input type="submit" value="<?php _e('Search'); ?>" id="error-search" />
	    </div>
	    </form>

 
</div> <!-- end #content -->


<?php get_footer(); ?>