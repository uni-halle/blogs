<?php
/**
 * Template Name: Contact Page
 *
 * Contact Page Template.
 */
?>

<?php 
//If the form is submitted
if(isset($_POST['submitted'])) {

	//Check to see if the honeypot captcha field was filled in
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = 'You forgot to enter your name.';
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = 'You forgot to enter your email address.';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = 'You entered an invalid email address.';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
		
		//Check to make sure that the Subject field is not empty 
		if(trim($_POST['contactSubject']) === '') {
			$subjectError = 'You forgot to enter your subject.';
			$hasError = true;
		} else {
			$mailsubject = trim($_POST['contactSubject']);
		}	
		
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = 'You forgot to enter your comments.';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {
			$blogname = get_bloginfo ('name');
			$emailTo = stripslashes (get_option('p2h_contact_email'));
					if (!isset($emailTo) || ($emailTo == '') ){
						$emailTo = get_option('admin_email');
					}
			$subject = $blogname.'--'.$name.'--'.$mailsubject;
			$sendCopy = trim($_POST['sendCopy']);
			$body = "From: $name \n\nEmail: $email \n\nComments: $comments";
			$headers = 'From:'.$blogname.' Contact<'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			mail($emailTo, $subject, $body, $headers);
	
			if($sendCopy == true) {
				$subject = 'Thanks for contacting '.$blogname;
				$headers = 'From: '.$blogname.'<'.$emailTo.'>';
				mail($email, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>

<?php get_header(); ?>





<div id="content" class="narrow">
	
<?php if(isset($emailSent) && $emailSent == true) { ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-header">
		<h1 class="page-title">Thanks, <?php echo $name;?></h1>
		</div>
	<div id="contact">
		<p class="successbox">
		<strong>Thanks for writing!</strong><br/>
		Your email was successfully sent. We will get in touch with you soon.
		</p>
	</div>
	</div>


<?php } else { ?>

	<?php if (have_posts()) : ?>
	
	<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-header">
		<h1 class="page-title"><?php the_title(); ?></h1>
		</div>
		
		<?php the_content(); ?>

		<div id="contact">
			<?php if(isset($hasError) || isset($captchaError)) { ?>
			<p class="alertbox"><strong>Error submitting the form!</strong><br />
			Did you leave any field blank? Was the email wrong? Please try again.<p>
		<?php } ?>
	

		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
	
				<div class="contact-info">
				<p>
				<label for="contactName">Name</label>
						<?php if(isset ($nameError) ) { ?>
							<span class="error"><?php echo $nameError;?></span> 
						<?php } ?>
					<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="requiredField" />
				</p>
				
				<p>
				<label for="email">Email</label>
					<?php if (isset ($emailError) ) { ?>
						<span class="error"><?php echo $emailError;?></span>
					<?php } ?>
					<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="requiredField email" />
				</p>
				
				<p>
				<label for="contactSubject">Subject</label>
					<?php if(isset ($subjectError) ) { ?>
						<span class="error"><?php echo $subjectError;?></span>
					<?php } ?>
					<input type="text" name="contactSubject" id="contactSubject" value="<?php if(isset($_POST['contactSubject']))  echo $_POST['contactSubject'];?>" class="requiredField" />
				</p>
				</div>
				<p class="textarea"><label for="commentsText">Comments</label>
					<?php if(isset ($commentError) ) { ?>
						<span class="error"><?php echo $commentError;?></span> 
					<?php } ?>
					<textarea name="comments" id="commentsText" rows="8" cols="45" class="requiredField"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
				</p>

				<p class="checkmail"><input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) echo ' checked="checked"'; ?> /><label for="sendCopy">Send a copy of this email to yourself</label></p>

				<input type="hidden" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" />
				
				<p class="contact-submit"><input type="hidden" name="submitted" id="submitted" value="true" /><button type="submit">Send Email&raquo;</button></p>
			</form>
			
	</div>

	</div><!--#posts-->

		<?php endwhile; ?>
		
		<?php endif; ?>

<?php } ?>
		

	</div><!-- #content -->
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
