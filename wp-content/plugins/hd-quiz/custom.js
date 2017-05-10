$hdQuJ=jQuery.noConflict();

if (jPagination != "yes" && jPagination != "no") {jPagination ="no";}
if (hdQuizTimer != "yes" && hdQuizTimer != "no") {hdQuizTimer ="no";}

// if paginated and next page link is clicked
var currentScore = $hdQuJ("#currentScore").val(); 
var maxValue = $hdQuJ("#maxValue").val(); 

var nextLink = $hdQuJ("#hdQuNext").attr("href"); 
$hdQuJ("#hdQuNext").attr("href", nextLink + currentScore);

$hdQuJ("#hdQuNext").click(function(e){	

	e.preventDefault();

	currentScore = $hdQuJ("#currentScore").val(); 
	if (currentScore == "NaN") {currentScore = 0;}

	maxValue = $hdQuJ("#maxValue").val(); 

	$hdQuJ('#questionnaire1 *').filter(":input[type='radio']:checked").each(function(){
		var id = $hdQuJ(this).attr('id');
		if ($hdQuJ(this).val() == "1") { currentScore++;}
	});

	$hdQuJ('#questionnaire1 *').filter(".hdHidden").each(function(){
		currentScore = parseInt(currentScore) + parseInt($hdQuJ(this).val());
	});


	if (currentScore == 0) {currentScore = "NaN";}

	$hdQuJ("#hdQuNext").attr("href", nextLink + currentScore + "&maxValue=" + maxValue); 
	var goTo = $hdQuJ(this).attr('href');

	setTimeout(function(){
		window.location = goTo;
	},100); 
 
});


// for jQuery pagination 
jPage = 0;
jPageFinish = "no";
if (jPagination == "yes") {
	$hdQuJ("#hdQuFinish").hide();
	$hdQuJ(".jPaginate").nextAll(".question").hide();

	$hdQuJ("#jPaginateNext").click(function() {
		$hdQuJ(".jPaginate:visible").prevAll(".question").hide();
		$hdQuJ(".jPaginate:eq(" + jPage + ")").nextUntil(".jPaginate").show();
		jPage++;

		if (parseInt(jPaged) == jPage){
			$hdQuJ("#jPaginateNext").hide();
			$hdQuJ("#hdQuFinish").show();
		}		

		$hdQuJ('html, body').delay(200).animate({
			scrollTop: $hdQuJ("#hdQuestionnaireContent").offset().top - 100
		}, 150);
	});

}


// If question uses image answers, only allow for image selection
$hdQuJ(".imageAnswer").click(function() {
	$hdQuJ(this).closest('.question').children('.imageAnswer.selected').removeClass("selected");
	$hdQuJ( this ).toggleClass( "selected" );

	// Get the index of selected image so we can select the correct radio box
	var listItem = $hdQuJ( this );
	var listItem2 = $hdQuJ(this).closest('.question').children('.imageAnswer').index( listItem );
	
	if ( $hdQuJ( this ).hasClass( "imageCorrect" ) ) {
		$hdQuJ(this).closest('.question').children('.hdHidden').val("1");
	}
	else {$hdQuJ(this).closest('.question').children('.hdHidden').val("0");}

});

// Countdown Timer

$hdQuJ.fn.countdown = function (callback, durationS, durationM) {
    durationM = durationM || "";

    var container = $hdQuJ(this[0]).html(durationS);
    var countdown = setInterval(function () {
        // If seconds remain
        if (--durationS) {
            // Update our container's message
           if (quizCompleted != "yes") { container.html(durationS + durationM); }
	   if (durationS == 5) {$hdQuJ('.hdCountdown').addClass("warning");}
        } else {
            // Clear the countdown interval
            clearInterval(countdown);
	    callback.call(container); 
        }
    }, 1000);

};

function submitForm () {
	if (quizCompleted != "yes") {
		this.html("0s");
   		$hdQuJ("#hdQuFinish").click();
	}
}

if (hdQuizTimer == "yes") { $hdQuJ(".hdCountdown").countdown(submitForm, quizTimerS, "s"); }




// Submit the form
var quizCompleted = "no";

$hdQuJ("#questionnaire1").submit(function() {
    $hdQuJ("#results").fadeIn('slow');

    var url = pluginURL + "result.php"; // the script where you handle the form input.

    $hdQuJ.ajax({
           type: "POST",
           url: url,
           data: $hdQuJ("#questionnaire1").serialize(), // serializes the form's elements.
           success: function(data)
           {

		quizCompleted = "yes";

                $hdQuJ('#resultsTotal').html(data);

		$hdQuJ('.hdQuFin').addClass("hd-animate");

		$hdQuJ('html, body').delay(1200).animate({
			scrollTop: $hdQuJ("#results").offset().top - 100
		}, 1500);
		
		var showResults = $hdQuJ('#showResults').val();

		if (showResults == "yes") {
			if (jPagination == "yes") {$hdQuJ(".question").show();}			
			// Test normal questions	
			$hdQuJ('#questionnaire1 *').filter(":input[type='radio']:checked").each(function(){
				var id = $hdQuJ(this).attr('id');
				if ($hdQuJ(this).val() == "1") { $hdQuJ(this).next('label').addClass("correct"); }
				else $hdQuJ(this).next('label').addClass("wrong");
			});
			// Test questions with image answers
			$hdQuJ('#questionnaire1 *').filter(".hdHidden").each(function(){				
				var id = $hdQuJ(this).attr('id');
				if ($hdQuJ( this ).hasClass( "hdHidden" ) && $hdQuJ(this).val() == "1") { 					$hdQuJ(this).closest('.question').children('.imageAnswer.selected').addClass("correct2");}
				else { $hdQuJ(this).closest('.question').children('.imageAnswer.selected').addClass("wrong2"); }				
			});

			var questionContentShow = $hdQuJ("#showQuestionContent").val(); 			
			// search all questions, see if question was answered incorrectly (or not at all), and display the questionContent if there is one
			$hdQuJ('#questionnaire1 *').filter(".question").each(function(){
				// if the user wants to show this regardless if the answer was correct or not				
				if (questionContentShow == "yes"){
					if ($hdQuJ(this).children(".imageAnswer")){
						$hdQuJ(this).closest(".question").children('.questionContent').fadeIn("slow");					
					}
					if ($hdQuJ(this).children("label")){					
						$hdQuJ(this).closest(".question").children('.questionContent').fadeIn("slow");				
					}
				}

				// default - only show on wrong answers
				if ($hdQuJ(this).children(".imageAnswer").hasClass("wrong2")){
					$hdQuJ(this).closest(".question").children('.questionContent').fadeIn("slow");					
				}
				if ($hdQuJ(this).children("label").hasClass("wrong")){					
					$hdQuJ(this).closest(".question").children('.questionContent').fadeIn("slow");				
				}
			});



		}
		if (showResults == "yes" && hdQuizShowResultsCorrect == "yes") {			
			// Test normal questions	
			$hdQuJ('#questionnaire1 *').filter(":input[type='radio']").each(function(){
				var id = $hdQuJ(this).attr('id');
				if ($hdQuJ(this).val() == "1") { $hdQuJ(this).next('label').addClass("correct correctAfter"); }
			});
			// Test questions with image answers
			$hdQuJ('#questionnaire1 *').filter(".imageCorrect").each(function(){
				$hdQuJ(this).addClass("correct2");				
			});
		}
           }
         });
    return false; // avoid to execute the actual submit of the form.
});