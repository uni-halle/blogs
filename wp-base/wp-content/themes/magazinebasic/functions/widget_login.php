<?php 
function widget_login() { ?>
        <?php global $user_ID, $user_identity, $user_level ?>
        <?php if ( $user_ID ) : ?>
        <h2>Control panel</h2>
        <ul>
            <li>Logged in as: <strong><?php echo $user_identity ?></strong></li>
            <li><a href="<?php bloginfo('url') ?>/wp-admin/">Dashboard</a></li>

            <?php if ( $user_level >= 1 ) : ?>
            <li><a href="<?php bloginfo('url') ?>/wp-admin/post-new.php">Write</a></li>
            <?php endif // $user_level >= 1 ?>

            <li><a href="<?php bloginfo('url') ?>/wp-admin/profile.php">Profile</a></li>
            <li><a href="<?php echo wp_logout_url() ?>&amp;redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>">Log Out</a></li>
        </ul>

        <?php else : ?>

        <h2>User Login</h2>
            <form action="<?php bloginfo('url') ?>/wp-login.php" method="post">
                <p>
                <label for="log">User</label><br /><input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" style="margin-bottom: 5px;" /><br />
                <label for="pwd">Password</label><br /><input type="password" name="pwd" id="pwd" size="20" style="margin-bottom: 5px;" /><br />
                <input type="submit" name="submit" value="Send" class="button" />
                <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> Remember me</label><br />
                </p>
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
            </form>
        <ul>
            <?php if ( get_option('users_can_register') ) { ?><li><a href="<?php bloginfo('url') ?>/wp-register.php">Register</a></li><?php } ?>
            <li><a href="<?php bloginfo('url') ?>/wp-login.php?action=lostpassword">Lost your password</a></li>
        </ul>
        <?php endif // get_option('users_can_register') ?>
<?php
}

function widget_myLogin($args) {
	extract($args); 
	echo $before_widget;
	widget_login();
	echo $after_widget; 
}

if (get_option('uwc_user_login') == "widget" || get_option('uwc_user_login') == "topwidget" ) {
	register_sidebar_widget(__('User Login'), 'widget_myLogin');
}
?>