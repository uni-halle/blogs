<footer class="footer" role="navigation" itemscope itemtype="http://schema.org/WPFooter">
    
<?php 
                       
    // get id of current page        
    $currentid = get_the_ID();
    
    //call pagelist function for further reference
    $pagelist = page_list_by_main_nav();  
    
    //get $currentcat as current topical category
    foreach($pagelist as $page){
        if($page['id'] == $currentid) {
            $currentcat = $page['topical_catid'];
            $page_count = $page['topicat_count'];
        }
    };
    
    ?>    
    
    <!-- call function for color categries from functions.php  -->

    <?php
    $category = get_the_category();
    $the_category_id = $category[0]->cat_ID;

    if(function_exists('rl_color')){
        $rl_category_color = rl_color($currentcat);
    }
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
                                       
//$frontpage_ID = get_option('page_on_front');

// array of only the page ids for easy prev-next function
$pages = array_column($pagelist, 'id');                
                
// check the position of the current page's id in the list                
$current = array_search($currentid, $pages);
                
// get id of empty parent page "Themen" to later exclude from prev-next navigation
$themen = get_page_by_path( 'themen');
$themen_ID = $themen->ID;            

//check if current page is the one before or after the "Themen" and exclude  
//there's probably an easy way to do this but that's the one I got
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
        //$page_count is number of pages in this topical category
        //$currentid is id of current page (from above function)
        //$currentcat is current topical category id
        //$currentpos is the position of the current page                            

        $pos = 1; //start counting from 1    
        foreach($pagelist as $page) {
            if($page['topical_catid'] == $currentcat) {
                if($page['id'] == $currentid) {
                    $currentpos = $pos; //get position of this page in the array
                }
                else {
                    $pos++;
                }
            }
        }
        
         // doing the math… as seen here: http://bytes.babbel.com/en/articles/2015-03-19-radial-svg-progressbar.html
            $rad = 1.3; //set the radius
            $cir = round(($rad * 2 * pi()), 2); //Umfang (circumference )
            if($page_count!=0){
                $offset = round((-($cir / $page_count) * $currentpos), 2); //needs to be negative to move the circle clockwise
            }
            
        ?>

                                <div class="statuscow" id="cont">
                                    <a href="<?php echo get_home_url(); ?>" title="Zur Startseite" class="svgscalefix-circle">
                                    
                                    <svg version="1.1" id="Ebene_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50" preserveAspectRatio="xMidYMid meet">
                                        <!-- background circle and the one who then looks like the progress -->
                                        <circle r="<?php echo $rad . 'em' ?>" cx="25" cy="25" fill="transparent" stroke-dasharray="0" stroke-dashoffset="0" class="circle-bgrd" stroke-width="0.5em" stroke="<?php echo $rl_category_color; ?>"></circle>

                                        <!-- foreground circle that gets offset -->
                                        <circle r="<?php echo $rad . 'em' ?>" cx="25" cy="25" fill="transparent" stroke-dasharray="<?php echo $cir . 'em' ?>" stroke-dashoffset="<?php echo $offset . 'em' ?>" class="circle-cover" stroke-width=".54em"></circle>
                                    </svg>
                                        </a>


                                </div>

                                <div class="tricontainer">
                                    
                                    <a href="<?php echo get_permalink($nextID); ?>" class="nextbtn" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
                                        <div class="svgscalefix">
                                            <svg version="1.1" id="nexttriangle" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50" preserveAspectRatio="xMidYMid meet">
                                                <path class="svgbutton nextsvg" d="M1.5 34.3c0-5 0-13.1 0-18V12c0-5 3.7-7 8-4.5l3.7 2.1c4.3 2.5 11.3 6.6 15.6 9l3.7 2.1c4.3 2.5 4.3 6.6 0 9L29 32c-4.3 2.5-11.3 6.6-15.6 9l-3.9 2.1c-4.3 2.5-8 0.5-8-4.5V34.3z" />
                                            </svg>
                                        </div>
                                    </a>
                            </div> <!-- end tricontainer -->
                        </div> <!-- end fnavcenter -->

                        <!-- display and link name of next page -->
                        <div class="nextname">
                            <a href="<?php echo get_permalink($nextID); ?>" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
                                <?php echo get_the_title($nextID); ?>
                            </a>
                        </div>

                    </div>

            </nav>

        </div> <!-- end of #inner-footer -->

</footer>

</div>
<?php wp_footer(); ?>
</body>
</html>