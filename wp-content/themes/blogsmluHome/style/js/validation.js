/* =============================================================================================== 
	jQuery Effects and Form Validation for Blogs@MLU 
	Version 0.9
	Copyright (c) 2009 Matthias Kretschmann | krema@jpberlin.de | http://matthiaskretschmann.com
================================================================================================== */

$(function() {
	
	//Form accordion
	var $content = $('form#postsend,form#postupload');
	var $trigger = $('h3#upload,h3#formular')
	
	$content.hide();  
  	$trigger.click(function() {
    var $nextDiv = $(this).next();
    var $visibleSiblings = $nextDiv.siblings('form:visible');
 
    if ($visibleSiblings.length ) {
      $visibleSiblings.slideUp(300, function() {
        $nextDiv.slideToggle(300);
      });
    } else {
       $nextDiv.slideToggle(300);
    }
  });
	
	//Form Validation Stuff
	$('#postsend #submit').click(function(){		   				   
		$('.error').hide();
		
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var title = $('#postsend input#title').val();
		if(title == '') {
			$('#postsend input#title').prev().before('<span class="error">Bitte einen Titel angeben.</span>');
			$('#postsend input#title').focus();  
      		return false; 
		}

		var author = $('#postsend input#author').val();
		if(author == '') {
			$('#postsend input#author').prev().before('<span class="error">Bitte den/die AutorIn angeben.</span>');
			$('#postsend input#author').focus();  
      		return false; 
		}
		
		var mail = $('#postsend input#mail').val();
		if(mail == '') {
			$('#postsend input#mail').prev().before('<span class="error">Bitte eine E-Mail-Adresse angeben.</span>');
			$('#postsend input#mail').focus();
			return false;
		} else if(!emailReg.test(mail)) {	
			$('#postsend input#mail').prev().before('<span class="error">Bitte eine gültige E-Mail Adresse angeben.</span>');
			$('#postsend input#mail').focus();
			return false; 
		}
		
		var message = $('#postsend textarea#article').val();
		if(message == '') {
			$('#postsend textarea#article').before('<span class="error">Bitte Artikel eingeben.</span>');
			$('#postsend textarea#article').focus();
			return false; 
		}
		
		var dataString = $('form').serialize();  
		
		//start the AJAX fun
		$.ajax({  
		  type: "POST",  
		  url: "_includes/sendcomment.php",
		  data: dataString,
		  success: function() {
		  	$('#postsend fieldset').animate({opacity: 0.5});
		    $('#postsend textarea#article').parent().after("<div id='output'></div>");
		    $('#postsend #submit').hide();
		    $('#postsend #output').html("<h3 class='contact-title'>Success!</h3><h4 class='contact-title'>Your comment was sent successfully.</h4>")
		    .hide()
		    .fadeIn(1500);
		  },
		  error: function() {
		  	$('#postsend fieldset').animate({opacity: 0.5});
		    $('#postsend textarea#article').parent().after("<div id='output'></div>");
		    $('#postsend #output').html("<h4 class='contact-title'>Your Message was not sent.</h4>")
		    .append("<h4 class='contact-title'>Please try submitting the form again.</h4>")
		    .hide()
		    .fadeIn(1500);
		  }
		  
		});  
		return false;
	});
	
	$('#postupload #submit').click(function(){		   				   
		$('.error').hide();
		
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var title = $('#postupload input#title').val();
		if(title == '') {
			$('#postupload input#title').prev().before('<span class="error">Bitte einen Titel angeben.</span>');
			$('#postupload input#title').focus();  
      		return false; 
		}

		var author = $('#postupload input#author').val();
		if(author == '') {
			$('#postupload input#author').prev().before('<span class="error">Bitte den/die AutorIn angeben.</span>');
			$('#postupload input#author').focus();  
      		return false; 
		}
		
		var mail = $('#postupload input#mail').val();
		if(mail == '') {
			$('#postupload input#mail').prev().before('<span class="error">Bitte eine E-Mail-Adresse angeben.</span>');
			$('#postupload input#mail').focus();
			return false;
		} else if(!emailReg.test(mail)) {	
			$('#postupload input#mail').prev().before('<span class="error">Bitte eine gültige E-Mail Adresse angeben.</span>');
			$('#postupload input#mail').focus();
			return false; 
		}
		
		var file = $('#postupload input#file').val();
		if(file == '') {
			$('#postupload input#file').before('<span class="error">Bitte eine Datei aussuchen.</span>');
			$('#postupload input#file').focus();
			return false; 
		}
		
		var dataString = $('form').serialize();  
		
		//start the AJAX fun
		$.ajax({  
		  type: "POST",  
		  url: "_includes/sendcomment.php",
		  data: dataString,
		  success: function() {
		  	$('#postupload fieldset').animate({opacity: 0.5});
		    $('#postupload textarea#article').parent().after("<div id='output'></div>");
		    $('#postupload #submit').hide();
		    $('#postupload #output').html("<h3 class='contact-title'>Success!</h3><h4 class='contact-title'>Your comment was sent successfully.</h4>")
		    .hide()
		    .fadeIn(1500);
		  },
		  error: function() {
		  	$('#postupload fieldset').animate({opacity: 0.5});
		    $('#postupload textarea#article').parent().after("<div id='output'></div>");
		    $('#postupload #output').html("<h4 class='contact-title'>Your Message was not sent.</h4>")
		    .append("<h4 class='contact-title'>Please try submitting the form again.</h4>")
		    .hide()
		    .fadeIn(1500);
		  }
		  
		});  
		return false;
	});

});//I'm pretty important