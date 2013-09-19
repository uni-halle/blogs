var $j = jQuery.noConflict();

$j(function() {

    $j("#explore-area").hide();
    
    $j("#toggle-explore").click(function(){
        $j("#explore-area").stop().slideToggle(500, function() {
            if ($j("#explore-area").is(":visible")) {
                var theHeight = $j("#explore-area").height();
                $j("#wrap").animate({
                    paddingTop: theHeight + "px"
                });
            } else {
                $j("#wrap").animate({
                    paddingTop: "0px"
                });
            }
        });
        return false;
    });
    
});