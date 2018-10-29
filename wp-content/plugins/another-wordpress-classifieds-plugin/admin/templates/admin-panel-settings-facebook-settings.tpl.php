<?php if ( isset( $errors ) && $errors ): ?>
<?php foreach ( $errors as $err ): ?>
	<?php echo awpcp_print_error( $err ); ?>
<?php endforeach; ?>
<?php endif; ?>

<div class="awpcp-facebook-inline-documentation">

<?php echo awpcp_html_admin_second_level_heading( array( 'content' => __( 'Facebook Integration', 'another-wordpress-classifieds-plugin' ) ) ); // XSS Ok. ?>

<p><?php esc_html_e( 'We currently support two methods for posting new ads to Facebook: Facebook API and Zapier/IFTTT Webhooks.', 'another-wordpress-classifieds-plugin' ); ?></p>

<p><?php esc_html_e( 'Support for Facebook API will be removed in the future. Facebook significantly reduced access to their APIs across all apps on Apr, 2018. Now it takes longer and is more difficult to configure Facebook Apps that can post content to pages or groups. As a result, it is unlikely that this integration is going to work for new users. We introduced support for Zapier/IFTTT Webhooks as an alternative and expect customers to migrate to that integration method.', 'another-wordpress-classifieds-plugin' ); ?></p>

<p><?php
    $content = esc_html__( 'You can read more about Facebook changes here: {facebook_post_link}', 'another-wordpress-classifieds-plugin' );
    $content = str_replace( '{facebook_post_link}', '<a href="https://developers.facebook.com/blog/post/2018/04/04/facebook-api-platform-product-changes/">https://developers.facebook.com/blog/post/2018/04/04/facebook-api-platform-product-changes/</a>', $content );

    echo $content; // XSS Ok. ?></p>

<p><?php echo esc_html_e( "If you are currently using the Facebook API integration method and not having any issues, you don't have to do anything right now. If you are having issues, please read the Diagnostics section below to try to fix them.", 'another-wordpress-classifieds-plugin' ); ?></p>

<h3><?php _e( 'Facebook API', 'another-wordpress-classifieds-plugin' ); ?></h3>

<p><?php
    $content = esc_html__( 'This integration method allows you to post ads to Facebook using a Facebook Application. Please read {link_to_how_to_register_facebook_apps}How to Register and Configure a Facebook Application{/link_to_how_to_register_facebook_apps} and follow {link_to_facebook_integration_documentation}these instructions{/link_to_facebook_integration_documentation}.', 'another-wordpress-classifieds-plugin' );
    $content = str_replace( '{link_to_how_to_register_facebook_apps}', '<a href="https://developers.facebook.com/docs/web/tutorials/scrumptious/register-facebook-application/">', $content );
    $content = str_replace( '{/link_to_how_to_register_facebook_apps}', '</a>', $content );
    $content = str_replace( '{link_to_facebook_integration_documentation}', '<a href="https://awpcp.com/documentation/#facebook-integration">', $content );
    $content = str_replace( '{/link_to_facebook_integration_documentation}', '</a>', $content );

    echo $content; // XSS Ok.?></p>

<p><?php esc_html_e( 'Add the following URL to the list of Valid OAuth Redirect URIs for the configuration of the Facebook Application:', 'another-wordpress-classifieds-plugin' ); ?></p>

<pre><code><?php echo esc_html( $redirect_uri ); ?></code></pre>

<h3><?php _e( 'Zapier/IFTTT Webhooks', 'another-wordpress-classifieds-plugin' ); ?></h3>

<p><?php esc_html_e( 'Webhooks allow the plugin to send a request to one of the configured webhook URLs the first time an ad becomes publicly available on the website. That is, as soon as any visitor is able to see the ad on the frontend. You can then add a task on Zapier or IFTTT to process the submitted information (url, title and description) and create a post on a Facebook Page you control.', 'another-wordpress-classifieds-plugin' ); ?></p>

<p><?php
    $content = esc_html__( 'Follow the instructiones to create the tasks on {webhook_instructions_for_zapier}Zapier{/webhook_instructions_for_zapier} or {webhook_instructions_for_ifttt}IFTTT{/webhook_instructions_for_ifttt}. Then update the settings at the bottom of this page to enter the webhook URLs associated with those tasks.', 'another-wordpress-classifieds-plugin' );
    $content = str_replace( '{webhook_instructions_for_zapier}', '<a href="https://awpcp.com/documentation/#facebook-integration">', $content );
    $content = str_replace( '{/webhook_instructions_for_zapier}', '</a>', $content );
    $content = str_replace( '{webhook_instructions_for_ifttt}', '<a href="https://awpcp.com/documentation/#facebook-integration">', $content );
    $content = str_replace( '{/webhook_instructions_for_ifttt}', '</a>', $content );

    echo $content; // XSS Ok.?></p>

<h3><?php _e( 'Facebook Cache', 'another-wordpress-classifieds-plugin' ); ?></h3>

<p><?php
    $content = esc_html__( 'If you are using Webhooks to send ads to Facebook, a Facebook Application can still be used to ask Facebook to clear the cache it has stored for ads pages. This is useful to ensure users always see the latest version when the ad is shared on Facebook Pages, Groups and user feeds. If you decide to use this feature, please read {link_to_how_to_register_facebook_apps}How to Register and Configure a Facebook Application{/link_to_how_to_register_facebook_apps} and follow {link_to_facebook_integration_documentation}these instructions{/link_to_facebook_integration_documentation}.', 'another-wordpress-classifieds-plugin' );
    $content = str_replace( '{link_to_how_to_register_facebook_apps}', '<a href="https://developers.facebook.com/docs/web/tutorials/scrumptious/register-facebook-application/">', $content );
    $content = str_replace( '{/link_to_how_to_register_facebook_apps}', '</a>', $content );
    $content = str_replace( '{link_to_facebook_integration_documentation}', '<a href="https://awpcp.com/documentation/#facebook-integration">', $content );
    $content = str_replace( '{/link_to_facebook_integration_documentation}', '</a>', $content );

    echo $content; // XSS Ok.?></p>

<p><?php esc_html_e( 'Add the following URL to the list of Valid OAuth Redirect URIs for the configuration of the Facebook Application:', 'another-wordpress-classifieds-plugin' ); ?></p>

<pre><code><?php echo esc_html( $redirect_uri ); ?></code></pre>

<h3><?php _e( 'Diagnostics', 'another-wordpress-classifieds-plugin' ) ?></h3>

<p><?php esc_html_e( 'If you see the following error after trying to get a valid User Access Token:', 'another-wordpress-classifieds-plugin' ); ?></p>

<p><strong>Invalid Scopes: manage_pages, publish_pages, publish_to_groups. This message is only shown to developers. Users of your app will ignore these permissions if present. Please read the documentation for valid permissions at: https://developers.facebook.com/docs/facebook-login/permissions</strong></p>

<p><?php esc_html_e( 'You have to submit your Facebook Application for Review and ask for those permissions or use the Webhooks integration method to send ads to Facebook using Zapier or IFTTT Webhooks.', 'another-wordpress-classifieds-plugin' ); ?></p>

<p><?php esc_html_e( 'If you see the following error trying to send ads to a Facebook Group:', 'another-wordpress-classifieds-plugin' ); ?></p>

<p><strong>(#200) Requires either publish_to_groups permission and app being installed in the group, or manage_pages and publish_pages as an admin with sufficient administrative permission.</strong></p>

<p><?php
    $content = esc_html__( 'Please make sure the Facebook Application {link_to_how_to_add_apps_to_a_group}was added to the group{/link_to_how_to_add_apps_to_a_group}.', 'another-wordpress-classifieds-plugin' );
    $content = str_replace( '{link_to_how_to_add_apps_to_a_group}', '<a href="https://www.facebook.com/help/261149227954100">', $content );
    $content = str_replace( '{/link_to_how_to_add_apps_to_a_group}', '</a>', $content );

    echo $content; // XSS Ok. ?></p>

<form  method="post">
    <p>
        <?php wp_nonce_field( 'awpcp-facebook-settings' ); ?>
        <?php echo __( 'If you are having additional problems with Facebook API, click "Diagnostics" to check your settings.', 'another-wordpress-classifieds-plugin' ); ?>
        <input type="submit" class="button-secondary" name="diagnostics" value="<?php _e( 'Diagnostics', 'another-wordpress-classifieds-plugin' ); ?>" />
    </p>
</form>

<hr />

</div>
