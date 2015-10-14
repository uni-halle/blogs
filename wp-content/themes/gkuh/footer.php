			<footer class="footer" role="navigation" itemscope itemtype="http://schema.org/WPFooter">
                
                <div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
                        <div class="maincontainer">
                                    
    <?php if(function_exists('bcn_display')) {
        bcn_display();
    }?>
                       </div> </div>

				<div id="inner-footer" class="maincontainer">
                    
					<nav role="navigation"> 
					
<!-- Previous and Next Page Functionality -->
                    
                    <?php
$pagelist = get_pages('sort_column=menu_order&sort_order=asc');
$pages = array();
foreach ($pagelist as $page) {
   $pages[] += $page->ID;
}

$current = array_search(get_the_ID(), $pages);
$prevID = $pages[$current-1];
$nextID = $pages[$current+1];
?>

                    <!-- container of prev-next elements -->
<div class="fnavcontainer">
    
                    <!-- display and link name of previous page -->
<div class="prevname">
<a href="<?php echo get_permalink($prevID); ?>"
  title="Zurück zur Seite: <?php echo get_the_title($prevID); ?>"><?php echo get_the_title($prevID); ?></a> 
</div>
    
    <div class="fnavcenter">
                    <!-- triangle buttons and status circle -->
    <div class="tricontainer"><a href="<?php echo get_permalink($prevID); ?>" class="prevbtn" title="Zurück zur Seite: <?php echo get_the_title($prevID); ?>">
        <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50">
  <path class="svgbutton prevsvg" d="M47.5 16.3c0 5 0 13 0 18v4.3c0 5-3.5 7-7.8 4.5L36 41c-4.3-2.5-11.3-6.6-15.6-9l-3.7-2.1c-4.3-2.5-4.3-6.6 0-9l3.7-2.1c4.3-2.5 11.3-6.6 15.6-9l3.7-2.1C44 5 47.5 7 47.5 12V16.3z"/>
</svg>
</a></div>
        
    <div class="statuscow">

            <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50">
                <circle cx="25" cy="25" r="20" stroke="grey" stroke-width="8" fill="none" />
            </svg>

    </div>
        
        <div class="tricontainer"><a href="<?php echo get_permalink($nextID); ?>" class="nextbtn" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
            <svg version="1.1" id="nexttriangle" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50">
  <path class="svgbutton nextsvg" d="M1.5 34.3c0-5 0-13.1 0-18V12c0-5 3.7-7 8-4.5l3.7 2.1c4.3 2.5 11.3 6.6 15.6 9l3.7 2.1c4.3 2.5 4.3 6.6 0 9L29 32c-4.3 2.5-11.3 6.6-15.6 9l-3.9 2.1c-4.3 2.5-8 0.5-8-4.5V34.3z"/>
</svg>
</a></div>    
    </div>
    
                    <!-- display and link name of next page -->
<div class="nextname">
<a href="<?php echo get_permalink($nextID); ?>" 
 title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>"><?php echo get_the_title($nextID); ?></a>
</div>
    
</div>
                        
                    </nav>

				</div> <!-- end of #inner-footer -->
                
                

			</footer>

		</div>

		<?php // all js scripts are loaded in library/bones.php ?>

		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->