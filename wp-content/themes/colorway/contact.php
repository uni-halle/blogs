<?php
/*
  Template Name: Contact
 */
$nameError = '';
$emailError = '';
$commentError = '';
//var_dump(isset($_POST['submitted']));
//var_dump(isset( $_POST['_contact_nonce']));
//var_dump(isset( $_POST['contactName']));
//var_dump(isset( $_POST['email']));
//var_dump(isset( $_POST['comments']));

if (isset($_POST['submitted'])) {
    if ( isset( $_POST['_contact_nonce']) && !wp_verify_nonce( sanitize_key($_POST['_contact_nonce']), '_contact_nonce')) {
        $hasError = true;
    }
    if ( isset( $_POST['contactName'] )&& trim(sanitize_text_field(wp_unslash($_POST['contactName']))) === '') {
        $nameError = __('Please enter your name.', 'colorway');
        $hasError = true;
    } else {
        $name = sanitize_text_field(wp_unslash($_POST['contactName']));
    }
    if (isset( $_POST['email'] ) && trim(sanitize_text_field(wp_unslash($_POST['email']))) === '') {
        $emailError = __('Please enter your email address.', 'colorway');
        $hasError = true;
    } else if (!filter_var(wp_unslash($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $emailError = __('You entered an invalid email address.', 'colorway');
        $hasError = true;
    } else {
        $email = sanitize_email(wp_unslash($_POST['email']));
    }
    if (isset( $_POST['comments'] ) && trim(sanitize_text_field(wp_unslash($_POST['comments']))) === '') {
        $commentError = __('Please enter a message.', 'colorway');
        $hasError = true;
    } else {
        $comments = sanitize_text_field(wp_unslash($_POST['comments']));
    }
    if (!isset($hasError)) {
        $emailTo = get_option('tz_email');
        if (!isset($emailTo) || ($emailTo == '')) {
            $emailTo = get_option('admin_email');
        }
        $subject = 'From ' . $name;
        $body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
        $headers = 'From: ' . $name . ' <' . $emailTo . '>' . "\r\n" . 'Reply-To: ' . $email;
        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    }
}
?>
<?php
get_header();
?>
    <!--Start Content Grid-->
    <div class="row content contact">
        <div  class="col-md-8 col-sm-8">
            <div class="content-wrap">
                
                <!--Start Blog Post-->
                <div class="contact">
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header><!-- .entry-header -->  
                    <ul>
                        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                                <li>
                                    <?php if (isset($emailSent) && $emailSent == true) { ?>
                                        <div class="thanks">
                                            <p><?php esc_html_e('Thanks, your email was sent successfully.', 'colorway'); ?></p>
                                        </div>
                                    <?php } else { ?>
                                        <?php the_content(); ?>
                                        <?php if (isset($hasError) || isset($captchaError)) { ?>
                                            <p class="error"><?php esc_html_e('Sorry, an error occured.', 'colorway'); ?>
                                            <p>
                                            <?php } ?>
                                        <form action="<?php the_permalink(); ?>" id="contactForm" method="post">
                                            <?php wp_nonce_field('contact_nonce', '_contact_nonce'); ?>
                                            <ul class="contactform">
                                                <li>
                                                    <label for="contactName"><?php esc_html_e('Name:', 'colorway'); ?></label>
                                                    <input type="text" name="contactName" id="contactName" value="<?php if (isset($_POST['contactName'])) echo esc_attr(sanitize_text_field(wp_unslash($_POST['contactName']))); ?>" class="required requiredField" />
                                                    <?php if ($nameError != '') { ?>
                                                        <span class="error"> <?php echo esc_html($nameError); ?> </span>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <label for="email"><?php esc_html_e('Email', 'colorway'); ?></label>
                                                    <input type="text" name="email" id="email" value="<?php if (isset($_POST['email'])) echo esc_attr(sanitize_email(wp_unslash($_POST['email']))); ?>" class="required requiredField email" />
                                                    <?php if ($emailError != '') { ?>
                                                        <span class="error"> <?php echo esc_html($emailError); ?> </span>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <label for="commentsText"><?php esc_html_e('Message:', 'colorway'); ?></label>
                                                    <textarea name="comments" id="commentsText" rows="20" cols="30" class="required requiredField"><?php
                                                        if (isset($_POST['comments'])) {
                                                            echo esc_html(sanitize_text_field(wp_unslash($_POST['comments'])));
                                                        }
                                                        ?>
                                                    </textarea>
                                                    <?php if ($commentError != '') { ?>
                                                        <span class="error"> <?php echo esc_html($commentError); ?> </span>
                                                    <?php } ?>
                                                </li>
                                                <li>
                                                    <input type="submit" value="<?php esc_attr_e('Send Email', 'colorway'); ?>"/>
                                                </li>
                                            </ul>
                                            <input type="hidden" name="submitted" id="submitted" value="true" />
                                        </form>
                                    <?php } ?>
                                </li>
                                <!-- End the Loop. -->
                            <?php endwhile; ?>
                    </ul>
                </div>
                <div class="hrline"></div>
                <!--End Blog Post-->

                <div class="clear"></div>      
            </div>
        </div>
        <div class="col-md-4">
            <div class="sidebar">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <!--End Content Grid-->
</div>
</div>


<!--End Container Div-->
<?php get_footer(); ?>
