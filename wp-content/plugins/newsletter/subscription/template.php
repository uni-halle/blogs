<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();
$module = NewsletterSubscription::instance();

if (!$controls->is_action()) {
    $controls->data = $module->get_options('template');
} else {
    if ($controls->is_action('save')) {
        $module->save_options($controls->data, 'template');

        if (strpos($controls->data['template'], '{message}') === false) {
            $controls->errors = 'Warning: the tag {message} is missing in your template';
        }
        
        $controls->messages = 'Saved.';
    }
    if ($controls->is_action('reset')) {
        $controls->data['template'] = file_get_contents(dirname(__FILE__) . '/email.html');
        $controls->messages = 'Done.';
    }

    if ($controls->is_action('test')) {

        $users = NewsletterUsers::instance()->get_test_users();
        if (count($users) == 0) {
            $controls->errors = 'There are no test subscribers. Read more about test subscribers <a href="http://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#test" target="_blank">here</a>.';
        } else {
            $template = $controls->data['template'];
            if (strpos($template, '{message}') === false) {
                $template .= '{message}';
            }
            $message = '<p>This is a generic example of message embedded inside the template.</p>';
            $message .= '<p>Subscriber data can be referenced by messages with tags. See the <a href="http://www.thenewsletterplugin.com">plugin documentation</a>.</p>';
            $message .= '<p>First name: {name}</p>';
            $message .= '<p>Last name: {surname}</p>';
            $message .= '<p>Email: {email}</p>';
            
            $message = str_replace('{message}', $message, $template);
            $addresses = array();
            foreach ($users as &$user) {
                $addresses[] = $user->email;
                Newsletter::instance()->mail($user->email, 'Newsletter Messages Template Test', $newsletter->replace($message, $user));
            }
            $controls->messages .= 'Test emails sent to ' . count($users) . ' test subscribers: ' .
                    implode(', ', $addresses) . '. Read more about test subscribers <a href="http://www.thenewsletterplugin.com/plugins/newsletter/subscribers-module#test" target="_blank">here</a>.';
        }
    }
}
?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_DIR . '/tnp-header.php'; ?>

    <div id="tnp-heading">

        <h2><?php _e('Messages template', 'newsletter') ?></h2>
        <p>
            Edit the default template of confirmation, welcome and cancellation emails. Add the {message} tag where you
            want the specific message text to be included.
        </p>

    </div>

    <div id="tnp-body">

        <form method="post" action="">
            <?php $controls->init(); ?>
            <p>
                <?php $controls->button_save(); ?>
            </p>
            <table class="form-table">
                <tr valign="top">
                    <th>Enabled?</th>
                    <td>
                        <?php $controls->yesno('enabled'); ?>
                        <p class="description">
                            When not enabled, the old templating system is used (see the file
                            wp-content/plugins/newsletter/subscription/email.php).
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th>Email template</th>
                    <td>
                        <?php $controls->textarea_preview('template', '100%', '700'); ?>
                        <?php $controls->button_reset(); ?>
                        <?php $controls->button('test', 'Send a test'); ?>
                    </td>
                </tr>
            </table>
            <p>
                <?php $controls->button_save(); ?>
            </p>
        </form>
    </div>

    <?php include NEWSLETTER_DIR . '/tnp-footer.php'; ?>

</div>