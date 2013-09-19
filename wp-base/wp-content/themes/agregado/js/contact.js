	function submitContactForm (form) {
		var the_button =  document.getElementById('submit');
		errors = new Array();
		if(!document.getElementById('user-name').value.length) {
			errors.push('Please enter your name.');
		}
		if(!document.getElementById('user-email').value.length) {
			errors.push('Please enter your email.');
		}
		if(!document.getElementById('user-comment').value.length) {
			errors.push('Please enter your message.');
		}
		if(errors.length) {
			var message = "Unable to submit your form due to the following errors:\n\n";
			for(i = 0; i < errors.length; i++) {
				message += errors[i] + "\n";
			}
			alert(message);
			return false;
		}
		return true;
	}
