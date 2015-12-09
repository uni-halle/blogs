<footer class="footer" role="navigation" itemscope itemtype="http://schema.org/WPFooter">
    
    <!-- call function for color categries from functions.php  -->

    <?php
    $category = get_the_category();
    $the_category_id = $category[0]->cat_ID;

    if(function_exists('rl_color')){
        $rl_category_color = rl_color($the_category_id);
    }
else {
    // nevermind
};
?>

        <div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
            <div class="maincontainer">

                <?php if(function_exists('bcn_display')) {
        bcn_display();
    }?>
            </div>
        </div>

        <div id="inner-footer" class="maincontainer">

            <nav role="navigation">

                <!-- Previous and Next Page Functionality -->

                <?php
$frontpage_ID = get_option('page_on_front');
$themen = get_page_by_path( 'themen');
$themen_ID = $themen->ID;
                $prevnextargs = array(
                    'sort_column'   => 'menu_order',
                    'sort_order'    => 'asc',
                    'exclude'       => '24');
$pagelist = get_pages($prevnextargs);
//$pages = array();
foreach ($pagelist as $page) {
   $pages[] += $page->ID;
}

$current = array_search(get_the_ID(), $pages);
$beforethemen = array_search($themen_ID, $pages)-1;
$afterthemen = array_search($themen_ID, $pages)+1;
if($current == $beforetheme){
    $prevID = $pages[$current-1];
    $nextID = $pages[$current+2];
}  
elseif($current == $afterthemen){
    $prevID = $pages[$current-2];
    $nextID = $pages[$current+1];
}                
else {
    $prevID = $pages[$current-1];
    $nextID = $pages[$current+1];
}            
?>

                    <!-- container of prev-next elements -->
                    <div class="fnavcontainer">

                        <!-- display and link name of previous page -->
                        <div class="prevname">
                            <a href="<?php echo get_permalink($prevID); ?>" title="Zurück zur Seite: <?php echo get_the_title($prevID); ?>">
                                <?php echo get_the_title($prevID); ?>
                            </a>
                        </div>

                        <div class="fnavcenter">
                            <!-- triangle buttons and status circle -->
                            <div class="tricontainer">
                                <a href="<?php echo get_permalink($prevID); ?>" class="prevbtn" title="Zurück zur Seite: <?php echo get_the_title($prevID); ?>">
                                    <div class="svgscalefix">
                                    <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50">
                                        <path class="svgbutton prevsvg" d="M47.5 16.3c0 5 0 13 0 18v4.3c0 5-3.5 7-7.8 4.5L36 41c-4.3-2.5-11.3-6.6-15.6-9l-3.7-2.1c-4.3-2.5-4.3-6.6 0-9l3.7-2.1c4.3-2.5 11.3-6.6 15.6-9l3.7-2.1C44 5 47.5 7 47.5 12V16.3z" />
                                    </svg>
                                    </div>
                                </a>
                            </div>

                            <!-- progress circle magic happening here -->

                            <?php 
        
        //$page_count shows total number of pages in this category
        //$pos is the position of this page
        
        
        //find category (slug and id) of this page (only the topic-related one which is a child category of 'lektion')
        
    $topid = get_cat_ID( 'lektion' ); //@todo should work without because of global variables set in header - or isnt that how it works?
    if($topid){                  //check if the required category exists
/*        foreach((get_the_category()) as $childcat) {
                if (cat_is_ancestor_of($topid, $childcat)) {
                $topicat_slug = $childcat->slug;
                $topicat_id = $childcat->term_id;  
                    }
        }*/
        //echo $topid; //debug
        //get object with pages in this category
        $cat_pages = get_posts('orderby=menu_order&&child_of=0&sort_order=asc&post_type=page&category=' . $topicat_id );
        
            
         
        //count number of pages in this category            
            $page_count = count($cat_pages);
            //echo $page_count; //debug
                    
        //find position of this page in the object
            $page_id = get_the_ID(); //get id of this page
        
            $position = 1; //start counting at 1
            if ($cat_pages){ 
            foreach($cat_pages as $catpage) {
                if ($catpage->ID === $page_id) {
                    $pos = $position; //set final position variable
                }
                else {$position++;} //continue couting if id not found
                };
                }
                else {$pos = 0;} //set to 0 if page is not a catpage
        
        //for debugging: calculate percentage of already seen pages per category
            if($page_count) { //prevent division by 0
            $progress = $pos * 100 / $page_count;
                }
            else $page_count = 1; // if there are no pages set count to 1 to prevent division by 0
            //echo $pos . '/' . $page_count . '<br>=' . $progress . '%'; //debug
        
         // doing the math… as seen here: http://bytes.babbel.com/en/articles/2015-03-19-radial-svg-progressbar.html
            $rad = 1.3; //set the radius
            $cir = $rad * 2 * pi(); //Umfang (circumference )
            $offset = -($cir / $page_count) * $pos; //needs to be negative to move the circle clockwise
            
            } // here ends the check for $topid
        else error_log('This theme needs a parent category called "lektion" and child categories for each section. Please check if they are missing or the parent category has been renamed.'); //log error if categories are messed up
       
        ?>

                                <div class="statuscow" id="cont">
                                    <div class="svgscalefix-circle">

                                    <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50" preserveAspectRatio="xMidYMid meet">
                                        <!-- background circle and the one who then looks like the progress -->
                                        <circle r="<?php echo $rad . 'em' ?>" cx="25" cy="25" fill="transparent" stroke-dasharray="0" stroke-dashoffset="0" class="circle-bgrd" stroke-width="0.5em" stroke="<?php echo $rl_category_color; ?>"></circle>

                                        <!-- foreground circle that gets offset -->
                                        <circle r="<?php echo $rad . 'em' ?>" cx="25" cy="25" fill="transparent" stroke-dasharray="<?php echo $cir . 'em' ?>" stroke-dashoffset="<?php echo $offset . 'em' ?>" class="circle-cover" stroke-width=".54em"></circle>
                                    </svg>
                                        </div>


                                </div>

                                <div class="tricontainer">
                                    
                                    <a href="<?php echo get_permalink($nextID); ?>" class="nextbtn" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
                                        <div class="svgscalefix">
                                        <svg version="1.1" id="nexttriangle" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50" preserveAspectRatio="xMidYMid meet">
                                            <path class="svgbutton nextsvg" d="M1.5 34.3c0-5 0-13.1 0-18V12c0-5 3.7-7 8-4.5l3.7 2.1c4.3 2.5 11.3 6.6 15.6 9l3.7 2.1c4.3 2.5 4.3 6.6 0 9L29 32c-4.3 2.5-11.3 6.6-15.6 9l-3.9 2.1c-4.3 2.5-8 0.5-8-4.5V34.3z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- display and link name of next page -->
                        <div class="nextname">
                            <a href="<?php echo get_permalink($nextID); ?>" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
                                <?php echo get_the_title($nextID); ?>
                            </a>
                        </div>

                    </div>

            </nav>

        </div>
        <!-- end of #inner-footer -->



</footer>

</div>

<?php // all js scripts are loaded in library/bones.php ?>

    <?php wp_footer(); ?>

        </body>

        </html>
        <!-- end of site. what a ride! -->