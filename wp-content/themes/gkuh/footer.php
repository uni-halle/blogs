<footer class="footer" role="navigation" itemscope itemtype="http://schema.org/WPFooter">
    
<?php 
                       
    // get id of current page        
    $currentid = get_the_ID();
    
    //call pagelist function for further reference
    $pagelist = page_list_by_main_nav();  
    
    $currid = searchForID($currentid, $pagelist);
    //mebug($currid);
    
    //get $currentcat as current topical category
    foreach($pagelist as $page){
        if($page['id'] == $currentid) {
            $currentcat = $page['topical_catid'];
            $page_count = $page['topicat_count'];
        }
    };
    
    ?>    
    
    <!-- call function for color categories from functions.php  -->

    <?php
    if(function_exists('rl_color')){
        if($currentcat!=0){
            $rl_category_color = rl_color($currentcat);
        }
        else $rl_category_color = '#008d9a';
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
                                       
// array of only the page ids for easy prev-next function
$pages = array_column($pagelist, 'id');      
                
// check the position of the current page's id in the list                
$current = array_search($currentid, $pages);
                
$themen = get_page_by_path( 'themen');
if($themen != 0) {//check if there is a empty-ish 'themen' page in the menu as parent for all topical pages 
    $themen_ID = $themen->ID;   

    //check if current page is the one before or after the "Themen" and exclude  
    $themenposition = array_search($themen_ID, $pages);
        if($themenposition > 0){
            $beforethemen = $themenposition - 1;
            $afterthemen = $themenposition + 1;
        }
        else {
            $beforethemen = $themenposition;
            $afterthemen = $themenposition + 1;
        }

    if($current == $beforethemen){//for the page before 'themen'
        if($current != 0){//if it's not the front page...
            $prevID = $pages[$current-1]; //...prev is the one before
            $nextID = $pages[$current+2]; //...next jumps 'themen'
        }
        else { //if it's the front page... 
            $prevID = $pages[0]; //... prev is same page
            $nextID = $pages[2]; //... next still jumps
        }
    }
    elseif($current == $afterthemen){ //for the page after 'themen'
        $prevID = $pages[$current-2]; //... jump one for prev
        $nextID = $pages[$current+1]; 
    }
    else { //if not on the page before of after themen just count
        $prevID = $pages[$current-1];
        $nextID = $pages[$current+1];
    }
    } // end themen-check
                
else { //if there is no themen page just count regularly
    if($current = 0){
        $prevID = $pages[0];
        $nextID = $pages[$current+1];
    }
    else{
        $prevID = $pages[$current-1];
        $nextID = $pages[$current+1];
        }
};                
           
//get colors for prev and next buttons
    foreach($pagelist as $page){
        if($page['id'] == $prevID) {//find previous item
            $prevcat = $page['topical_catid'];
                if($prevcat!=0) { //only if there is a previous item
                }
                
        }
        elseif($page['id'] == $nextID) {
            $nextcat = $page['topical_catid'];
            
            if(function_exists('rl_color')&&($nextcat!=0)) {
                $next_category_color = rl_color($nextcat);
            }
            else $next_category_color = '#7f7f7f';
        }
    };
                
                //mebug($prevID . ' / ' . $nextID);
?>

                    <!-- container of prev-next elements -->
                    <div class="fnavcontainer">

                        <!-- display and link name of previous page -->
                        <div class="prevname previtem">
                            <a href="<?php echo get_permalink($prevID); ?>" class="cc-<?php echo $prevcat ?>-hov" title="Zurück zur Seite: <?php echo get_the_title($prevID); ?>">
                                <?php echo get_the_title($prevID); ?>
                            </a>
                        </div>

                        <div class="fnavcenter">
                            
                            <!-- triangle buttons and status circle -->
                            <div class="tricontainer">
                                <a href="<?php echo get_permalink($prevID); ?>" class="previtem prevbtn" title="Zurück zur Seite: <?php echo get_the_title($prevID); ?>">
                                    
                                    
                                    <svg height="50px" width="50px" version="1.1" id="prevsvg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50">
                                        <path class="svgbutton prevpath previtem cc-<?php echo $prevcat ?>-fill-hov" d="M47.5 16.3c0 5 0 13 0 18v4.3c0 5-3.5 7-7.8 4.5L36 41c-4.3-2.5-11.3-6.6-15.6-9l-3.7-2.1c-4.3-2.5-4.3-6.6 0-9l3.7-2.1c4.3-2.5 11.3-6.6 15.6-9l3.7-2.1C44 5 47.5 7 47.5 12V16.3z" />
                                    </svg>
                                </a>
                            </div>

        <!-- progress circle magic happening here -->

        <?php 
        //$page_count is number of pages in this topical category
        //$currentid is id of current page (from above function)
        //$currentcat is current topical category id
        //$currentpos is the position of the current page
        //$progval is the value of the progress for the circle
                            

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
        };
                            
        if($page_count!=0){
           $progval = round(($currentpos / $page_count), 2);
       }
        else $progval = 1;
                            
        ?>

                                <div class="statuscow" id="cont">
                                    <a href="<?php echo get_home_url(); ?>" title="Zur Startseite">

                                        <!-- circle goes here -->
                                        <div id="circle" data-value="<?php echo $progval; ?>" data-fill="{&quot;color&quot;: &quot;<?php echo $rl_category_color; ?>&quot;}"></div>

                                            <script>
                                                var $ = jQuery.noConflict(); //don't ask
                                                $('#circle').circleProgress({
                                                    animation: false,
                                                    startAngle: -Math.PI / 2,
                                                    size: 100,
                                                    thickness: 14,
                                                    emptyFill: "#7f7f7f",
                                                });
                                            </script>
                                        </a>


                                </div>

                                <div class="tricontainer">
                                    
                                    <a href="<?php echo get_permalink($nextID); ?>" class="nextitem nextbtn" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
                                            <svg height="50px" width="50px" version="1.1" id="nextsvg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 50 50" xml:space="preserve" enable-background="new 0 0 50 50" preserveAspectRatio="xMidYMid meet">
                                                <path class="svgbutton nextpath nextitem cc-<?php echo $nextcat ?>-fill-hov" d="M1.5 34.3c0-5 0-13.1 0-18V12c0-5 3.7-7 8-4.5l3.7 2.1c4.3 2.5 11.3 6.6 15.6 9l3.7 2.1c4.3 2.5 4.3 6.6 0 9L29 32c-4.3 2.5-11.3 6.6-15.6 9l-3.9 2.1c-4.3 2.5-8 0.5-8-4.5V34.3z" />
                                            </svg>
                                    </a>
                            </div> <!-- end tricontainer -->
                        </div> <!-- end fnavcenter -->

                        <!-- display and link name of next page -->
                        <div class="nextitem nextname">
                            <a href="<?php echo get_permalink($nextID); ?>" class="cc-<?php echo $nextcat ?>-hov" title="Zur nächsten Seite: <?php echo get_the_title($nextID); ?>">
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