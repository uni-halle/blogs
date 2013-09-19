<?php
/**
 * Creates a WordPress user account from an LDAP response specified by
 * $ldapUserData.  Assumes that a user account $newUserName does not already
 * exist.
 *
 * Code courtesy of dwang99 via post at
 * <code>http://patcavit.com/2005/05/11/wordpress-ldap-and-playing-nicely/</code>
 *
 * @author - dwang99
 */
function wpmuLdapCreateWPUserFromLdap($opts) {
	global $base, $error, $wpdb, $current_site;

	// Extract Inputs
        extract($opts);
	if (!isset($newUserName)) 	$newUserName = '';
	if (!isset($newUserPassword)) 	$newUserPassword = '';
	if (!isset($ldapUserData)) 	$ldapUserData = false;
	if (!isset($createBlog))		$createBlog = true;

	// Check to see if email is empty
	if ( empty($ldapUserData[LDAP_INDEX_EMAIL]) )
		return new WP_Error('ldapcreate_emailempty', sprintf(__('<strong>ERROR</strong>: <strong>%s</strong> does not have an email address associated with the ldap record.  All wordpress accounts must have a unique email address.'),$newUserName));

	// Check to see if email already exists
        if ( email_exists($ldapUserData[LDAP_INDEX_EMAIL]) )
		return new WP_Error('ldapcreate_emailconflict', sprintf(__('<strong>ERROR</strong>: <strong>%s</strong> (%s) is already associated with another account.  All accounts (including the admin account) must have an unique email address.'),$ldapUserData[LDAP_INDEX_EMAIL],$newUserName));

	// we don't actually care about the WP password (since it's LDAP), but we need one for WP database
	$sPassword = generate_random_password();
	$user_id = wpmu_create_user( $newUserName, $sPassword, $ldapUserData[LDAP_INDEX_EMAIL] );

	if ( $user_id === false ) {
		return new WP_Error('ldapcreate_failed', __('<strong>ERROR</strong>: Account creation from LDAP failed.'));
	}

	//Update their first and last name from ldap
	update_usermeta( $user_id, 'first_name', $ldapUserData[LDAP_INDEX_GIVEN_NAME] );
	update_usermeta( $user_id, 'last_name', $ldapUserData[LDAP_INDEX_SURNAME] );
	update_usermeta( $user_id, 'ldap_login', 'true' );

	//Set Public Display Name
	$displayName = get_site_option('ldapPublicDisplayName');
	$display_name = '';
	$ldapnick = $ldapUserData[LDAP_INDEX_NICKNAME];
	if (!empty($ldapnick))
		$display_name = $ldapnick;
	else if (!empty($displayName)) {
		if ($displayName == 'username')		$display_name = $newUserName;
		if ($displayName == 'first')		$display_name = $ldapUserData[LDAP_INDEX_GIVEN_NAME];
		if ($displayName == 'firstlast')	$display_name = $ldapUserData[LDAP_INDEX_GIVEN_NAME].' '.$ldapUserData[LDAP_INDEX_SURNAME];
		if ($displayName == 'lastfirst') 	$display_name = $ldapUserData[LDAP_INDEX_SURNAME].' '.$ldapUserData[LDAP_INDEX_GIVEN_NAME];
	} else $display_name = $newUserName;

	if (!empty($display_name)) $wpdb->update( $wpdb->users, compact( 'display_name' ), array( 'ID' => $user_id ) );

	//This is for plugin events
	do_action('wpmu_activate_user', $user_id, $newUserPassword, false);

	$domain = strtolower( wp_specialchars( $newUserName ) );
	if( constant( "VHOST" ) == 'yes' ) {
		$newdomain = $domain . "." . $current_site->domain;
		$path = $base;
	}
	else {
		$newdomain = $current_site->domain;
		$path = $base . $domain . '/';
	}

	// is it configured to create WP blogs from LDAP accounts?
	$ldapCreateBlog = get_site_option("ldapCreateBlog");

	if ($createBlog && $ldapCreateBlog) {
		// Create and update the user's blog.
		$meta = apply_filters('signup_create_blog_meta', array ('lang_id' => 'en', 'public' => 0));
		$blog_id = wpmu_create_blog($newdomain, $path, $newUserName . "'s blog", $user_id, $meta);

		if ( is_a($blog_id, "WP_Error") ) {
			return new WP_Error('blogcreate_failed', __('<strong>ERROR</strong>: Blog creation from LDAP failed.'));
		}

		do_action('wpmu_activate_blog', $blog_id, $user_id, $newUserPassword, $newUserName . "'s blog", $meta);
	}

	// Add user as subscriber to blog #1
	wpmuUpdateBlogAccess($user_id);
	return new WP_User($user_id);
}


function wpmuLdapAuthenticate($ldapString, $loginUserName, $loginPassword) {
	$errors = new WP_Error;
        // Check that user is not flagged as a ldap account
	if ( username_exists($loginUserName) ) {
		$loginObj = get_user_by('login',$loginUserName);
        	$ldapMeta = get_user_meta($loginObj->ID,'ldap_login',true);
	        if ($ldapMeta != 'true') {
			$errors->add('invalid_userpass', __('<strong>ERROR</strong>: Wrong username / password combination. LDAP Access Denied.'));
			return array('result' => false,'errors' => $errors);
		}
	}

        $server = new LDAP_ro($ldapString);
	if (LDAP_DEBUG_MODE) {
		echo "DEBUG: Attempting to authenticate user: $loginUserName<br/>";
	        $server->DebugOn();
	} else $server->DebugOff();
        // undefined now - going to populate it in $server->Authenticate
        $userDataArray = null;
        $result = $server->Authenticate ($loginUserName, $loginPassword, $userDataArray);
        if ($result == LDAP_OK) {
		return array('result' => true,'userdata' => $userDataArray);
        }
        // handle both at once, for security
        else if ( ($result == LDAP_ERROR_USER_NOT_FOUND || $result == LDAP_ERROR_WRONG_PASSWORD) ) {
		if (LDAP_DEBUG_MODE) echo "DEBUG: Attempting to authenticate user: Wrong user/pass<br/>";
		$errors->add('invalid_userpass',__('<strong>ERROR</strong>: Wrong username / password combination.'));
                return array('result' => false,'errors' => $errors);
        }
	// check security group
	else if ( $result == LDAP_ERROR_ACCESS_GROUP ){
		if (LDAP_DEBUG_MODE) echo "DEBUG: Attempting to authenticate user: not found in security group<br/>";
		$errors->add('wrong_group',__('<strong>ERROR</strong>: Access denied - user not found in security access group(s).'));
                return array('result' => false,'errors' => $errors);
        }
	elseif ($result == LDAP_ERROR_DENIED_GROUP) {
		if (LDAP_DEBUG_MODE) echo "DEBUG: Attempting to authenticate user: denied via securtiy groups<br/>";
		$errors->add('deny_group',__('<strong>ERROR</strong>: Access denied - user found in security deny group(s).'));
                return array('result' => false,'errors' => $errors);
	}
        // the trickle-through catch-all
        else {
		if (LDAP_DEBUG_MODE) echo "DEBUG: Attempting to authenticate user: unknown error (not user/password or security group based - something else is wrong<br/>";
                $errors->add('unknown_error',__('<strong>ERROR</strong>: Unknown error in LDAP Authentication.'));
                return array('result' => false,'errors' => $errors);
        }
}

/**
 * Authenticates an LDAP account using credentials $loginUserName and
 * $loginPassword, returned from an LDAP server accessible from $ldapString.  If
 * a WordPress user account does not exist, and this plugin is configured to
 * create new accounts from LDAP logins, an account will be created.
 */
function wpmuLdapProcess(&$loginObj, $loginUserName, $loginPassword, $userDataArray) {
	global $error;
        // is it configured to create WP accounts from LDAP accounts?
        $ldapCreateAcct = get_site_option("ldapCreateAcct");

	// call the registration function to create a wordpress user account for this
	// successfully authenticated user
	require_once( ABSPATH . WPINC . '/registration.php');

	// if the account doesn't already exist
	if ( !username_exists( $loginUserName ) ) {

		// Make the WP users automatically if we're configured to do so
		if ($ldapCreateAcct ) {

			//Setup redirection to user's home directory.
			if (!strpos($_REQUEST['redirect_to'], $loginUserName)) {
				$_REQUEST['redirect_to'] = $loginUserName . "/" . $_REQUEST['redirect_to'];
			}
			return wpmuLdapCreateWPUserFromLdap(array(	'newUserName' => $loginUserName,
									'newUserPassword' => $loginPassword,
									'ldapUserData' => $userDataArray));
		}

		// but if not configured to create 'em, exit with an error
		else {
		        return new WP_Error('account_noexist', __('<strong>ERROR</strong>: A blogging account does not exist - contact your administrator.'));
		}
	}

	// otherwise, the account *does* exist already, so just get the account info
	else $loginObj = get_user_by('login',$loginUserName);

	// At this point we must have a login object, but just in case something went wrong
	if (!$loginObj) {
	        return new WP_Error('unknown_error', __('<strong>ERROR</strong>: Unknown error in LDAP Authentication.'));
	}

	// Since the login was successful, lets set a meta object to know we are using ldap
	$ldapMeta = get_user_meta($loginObj->ID,'ldap_login',true);
	if ($ldapMeta != 'true') {
		if (!update_usermeta($loginObj->ID, 'ldap_login', 'true')) {
		        return new WP_Error('update_usermeta', __('<strong>ERROR</strong>: Error updating user meta information.'));
		}
	}

	// Handle blog removal for various reasons
	if(is_super_admin($loginObj->ID) === false) {
        	if ($primary_blog = get_user_meta($loginObj->ID, "primary_blog", true)) {
			$details = get_blog_details( $primary_blog );
			if( is_object( $details ) && $details->archived == 1 || $details->spam == 1 || $details->deleted == 1 ) {
				// reset primary blog to #1 (or dashboard) and add subscriber role
				wpmuUpdateBlogAccess($loginObj->ID);
			}
		} else {
			// make sure user is subscribed to blog #1 or dashboard blog
			wpmuUpdateBlogAccess($loginObj->ID);
		}
	}

	// if we get to here - they're authenticated, they have a WP account, so it's all set
	return new WP_User($loginObj->ID);
}

/**
 * Searches the LDAP directory for the specified user
 */
function wpmuLdapSearch($ldapString = array(),$in_username,&$userDataArray) {
        $server = new LDAP_ro($ldapString);
        $server->DebugOff();

        $attributes_to_get = array (get_site_option('ldapAttributeMail',LDAP_DEFAULT_ATTRIBUTE_MAIL),
                                    get_site_option('ldapAttributeGivenname',LDAP_DEFAULT_ATTRIBUTE_GIVENNAME),
                                    get_site_option('ldapAttributeSn',LDAP_DEFAULT_ATTRIBUTE_SN),
                                    get_site_option('ldapAttributePhone',LDAP_DEFAULT_ATTRIBUTE_PHONE));
	$userDataArray = null;

	if ($server->DoSearchUsername($in_username, $attributes_to_get, $userDataArray) == LDAP_OK) {
		return true;
	} 
	return false;
}

/**
 * Searches for a username.  If found, adds the user and returns user data.
 */
function wpmuLdapSearchUser($opts) {

        // Extract Inputs
        extract($opts);
	if (!isset($username))		$username = '';
	if (!isset($blog_id))		$blog_id = 1;
	if (!isset($new_role))		$new_role = 'subscriber';
	if (!isset($createUser))	$createUser = true;
	if (!isset($createBlog))	$createBlog = true;

	// Bind to directory, search for username
	$ldapString = wpmuSetupLdapOptions();
	$userDataArray = null;
	if (wpmuLdapSearch($ldapString,$username,$userDataArray)) {
		if ($createUser) {
			if ($user_id = username_exists($username)) {
				if (wpmuLdapAddUserToBlog($user_id,$blog_id,$new_role)) {
					return array( true, $user_id );
				}
			}
			$user = wpmuLdapCreateWPUserFromLdap(array(	'newUserName' => $username,
								'ldapUserData' => $userDataArray,
								'createBlog' => $createBlog));
			if ( is_wp_error($user) ) {
				return $user;
			}
		        if ( is_a($user, 'WP_User') ) {
				if ( $user_id = username_exists($username) ) {
					add_user_to_blog($blog_id, $user_id, $new_role);

					// Update User Meta
					update_usermeta($user_id, 'primary_blog', $blog_id );
				}
				return array( true, $user_id );
			} else {
				return array( false );
			}
		}
		return array ( true );
	}
	return array( false );
}

/**
 * If users already exists (Local or LDAP) access will be granted to the specified blog
 */
function wpmuLdapAddUserToBlog($user_id,$blog_id,$new_role = 'subscriber') {
        add_user_to_blog($blog_id, $user_id, $new_role);
	return true;
}

/**
 * Overrides display and handling of the WPMU signup form.  Simply
 * displays a message to indicate to users that they should use the login form
 * for signup, as the LDAP plugin will automatically create WPMU user accounts
 * and blogs for them.
 *
 * Based loosely on code from a post by Jeremy Visser at
 * <code>http://mu.wordpress.org/forums/topic.php?id=2361&replies=15</code>
 *
 * @author Sean Wedig (www.thecodelife.net)
 */
function wpmuLdapDisableSignup() {
        wp_redirect(get_option('siteurl').'/wp-login.php?action=signupdisabled');

	$msg = stripslashes(get_site_option('ldapSignupMessage'));
}

function wpmuLdapDisableSignupMessage() {
	if (isset($_GET['action']) && $_GET['action'] == 'signupdisabled') {
		global $error;
		$error = '<strong>ERROR:</strong> '.stripslashes(get_site_option('ldapSignupMessage'));
	}
}

/**
* Checks to make sure the user is added to the dashboard blog (if set) or else blog #1
*/
function wpmuUpdateBlogAccess($userid) {
	// reset primary blog to #1 (or dashboard) and add subscriber role
	if ($dashboard = get_site_option( 'dashboard_blog' )) {
        	add_user_to_blog( $dashboard, $userid, get_site_option( 'default_user_role', 'subscriber' ) );
        	update_usermeta($userid, "primary_blog", $dashboard);
	} else {
		add_user_to_blog( '1', $userid, get_site_option( 'default_user_role', 'subscriber' ) );
		update_usermeta($userid, "primary_blog", 1);
	}
}

function wpmuLdapUsernamePasswordAuthenticate($user, $username, $password) {
	if ( is_a($user, 'WP_User') ) return $user;

	// check that username and password are not empty
	if ( (empty($username) || empty($password)) ) {
		return $user; // probably an WP_Error object, set in "wp_authenticate_username_password()"
	}

	// setup ldap string
	$ldapString = wpmuSetupLdapOptions();

	// Authenticate via LDAP, potentially creating a WP user
	$ldapauthresult = wpmuLdapAuthenticate($ldapString, $username, $password);

	if ($ldapauthresult['result']) {
		return wpmuLdapProcess($user, $username, $password, $ldapauthresult['userdata']);
	} else {
		return $ldapauthresult['errors'];
	}
}

function wpmuLdapCheckLdapMeta($userdata) {
	$ldapMeta = get_user_meta($userdata->ID,'ldap_login',true);
        if (isset($ldapMeta) && $ldapMeta == 'true')
		return new WP_Error('invalid_userpass', __('<strong>ERROR</strong>: Wrong username / password combination. Local Access Denied.'));
	return $userdata;
}

function wpmuLdapSSOAuthenticate($user, $username, $password) {
	if ( is_a($user, 'WP_User') ) return $user;

	// only try SSO if we have not just logged out and
	// we're not trying to log in with a different username
	if ( empty($username) && empty($password) && empty($_GET['loggedout'])) {
		$username = wpmuLdapSSOGetUsername();
		if (empty($username)) return $user; // can't log in without a username

		//$password = wp_generate_password(); //create a random password for the local user

		$ldapString = wpmuSetupLdapOptions();
		$userDataArray = null;
		$result = wpmuLdapSearch($ldapString,$username,$userDataArray);
		$ldapauthresult = array('result' => $result, 'userdata' => $userDataArray);

		if ($ldapauthresult['result']) {
			return wpmuLdapProcess($user, $username, $password, $ldapauthresult['userdata']);
		} else {
			return new WP_Error('sso_failed', sprintf(__('Single Sign-On as user <em>%s</em> failed. Please login using the form below.'),$username));
		}
	}

	return $user;
}

/**
 * Retrieve username from server variable for Single Sign-On.
 *
 * The variable is taken from one of these three variables:
 *
 * - AUTH_USER: The name of the user as it is derived from the authorization
 *   header sent by the client, before the user name is mapped to a Windows
 *   account. This variable is no different from REMOTE_USER. If you have an
 *   authentication filter installed on your Web server that maps incoming users
 *   to accounts, use LOGON_USER to view the mapped user name.
 *
 * - LOGON_USER: The Windows account that the user is impersonating while
 *   connected to your Web server. Use REMOTE_USER or AUTH_USER to view the raw
 *   user name that is contained in the request header. The only time LOGON_USER
 *   holds a different value than these other variables is if you have an
 *   authentication filter installed.
 *
 * - REMOTE_USER: The name of the user as it is derived from the authorization
 *   header sent by the client, before the user name is mapped to a Windows
 *   account. If you have an authentication filter installed on your Web server
 *   hat maps incoming users to accounts, use LOGON_USER to view the mapped user
 *   name.
 */
function wpmuLdapSSOGetUsername() {
	$username = '';
	if (!empty($_SERVER['LOGON_USER'])) $username = $_SERVER['LOGON_USER'];
	elseif (!empty($_SERVER['REMOTE_USER'])) $username = $_SERVER['REMOTE_USER'];
	elseif(!empty($_SERVER['AUTH_USER'])) $username = $_SERVER['AUTH_USER'];

	// strip user account domain
	if (strpos($username, '\\\\') !== FALSE) {
		$username = substr($username, strpos($username, '\\\\') + 2);
	}

	return $username;
}

/*
when in SSO mode we don.t need to forse a relog in so theis stops that
*/
function wpmuLdapSSODisableReauth($login_url){
	return str_replace('&reauth=1','',$login_url);
}

/*
 * Override the add user functionality in the admin toolbar to use the
 * LDAP Add User page instead of the default WordPress add user page
 */
function wpmuLdap_custom_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('new-user');
	$wp_admin_bar->add_menu( array(
		'id' => 'new-user',
		'title' => 'User',
		'href' => admin_url() . "users.php?page=wpmu_ldap_adduser.functions.php",
		'parent' => 'new-content',
		'meta' => array(
			'title' => _x( 'User', 'add new from admin bar' ), 'new-user' ),
		));
}
