<?php

function ldap_addmenuuser() {
        $objCurrUser = wp_get_current_user();
        $objUser = wp_cache_get($objCurrUser->ID, 'users');

        if (function_exists('add_submenu_page')) {
                // does not use add_options_page, because it is site-wide configuration,
                //  not blog-specific config, but side-wide
		$ldapAddUser = get_site_option('ldapAddUser');
		$ldapBulkAdd = get_site_option('ldapBulkAdd');
		if (is_super_admin() || 
			($ldapAddUser == 'enabled' || empty($ldapAddUser)) ||
			($ldapBulkAdd && is_admin($current_user->username))) {
		        add_submenu_page('users.php',__('LDAP Add User','wpmuldap'),__('Add User','wpmuldap'),'manage_options',basename(__FILE__),'ldapAddUserOptions');
		}
        }
}

function ldapAddUserResult($options) {
	extract($options);
	if (!empty($username))
		$user = '<b>'.$username.'</b>';
	if ( $updated == 'true' ) {
		?>
		<div id="message" class="updated fade"><p>
			<?php
			switch ($action) {
			case 'delete':
				printf(__('User %s deleted!'),$user);
			break;
			case 'add':
				printf(__('User %s added!'),$user);
			break;
			case 'exists':
				printf(__('User %s exists!'),$user);
			break;
			default:
				_e('Options saved !');
			break;
		}
		?>
		</p></div>
		<?php
	} elseif ( $updated == 'false' ) {
                ?>
                <div id="message" class="error fade"><p>
                        <?php
			if (is_wp_error($error)) {
				$wp_error = $error;
				if ( $wp_error->get_error_code() ) {
					$errors = '';
					$messages = '';
					foreach ( $wp_error->get_error_codes() as $code ) {
						$severity = $wp_error->get_error_data($code);
						foreach ( $wp_error->get_error_messages($code) as $error ) {
							$errors .= '    ' . $error . "<br />\n";
						}
					}
					if ( !empty($errors) ) echo $errors;
        			}
			} else {
                        	switch ($action) {
                	        case 'exists':
					printf(__('User %s exists!'),$user);
	                        break;
                        	case 'notfound':
					printf(__('User %s not found in LDAP Directory!'),$user);
        	                break;
	                        case 'add':
					printf(__('Error adding user %s!'),$user);
                        	break;
                	        default:
        	                        _e('Error!');
	                        break;
			}
                }
                ?>
                </p></div>
                <?php
	}
}

function ldapAddUserOptions() {
	global $blog_id, $current_user;

	if ($_POST['addUser']) {
		// Process the post request
		$user = $_POST['user'];
                if ( empty($user['username']) && empty($user['email']) ) {
                        wp_die( __("<p>Missing username.</p>") );
                }
		$username = strtolower($user['username']);

                // try finding a WP account for this user name
                $login = get_user_by('login',$username);
		if (!$login) {
			$result = wpmuLdapSearchUser(array(	'username' => $username,
								'blog_id' => $blog_id,
								'new_role' => $user['new_role']));

			if (is_wp_error($result)) {
				ldapAddUserResult(array('updated' => 'false','error' => $result,'username' => $username));
			} else {
			        $ldapCreateLocalUser = get_site_option('ldapCreateLocalUser');
				if ($result[0]) {
                	                wp_new_user_notification($result[1]);
					ldapAddUserResult(array('updated' => 'true','action' => 'add','username' => $username));
				} elseif ($ldapCreateLocalUser || is_super_admin())  {
                		        ?>
        		                <div id='message' class='updated'>
		                        <form method='post'>
                        	        	<p><b><?php echo $username ?></b> not found in LDAP directory.  To create a local user, enter the users email:
                	        	        <input type='text' name='user[email]' size='15' />
        	        	                <input type='hidden' name='user[username]' value='<?php echo $username ?>' />
	        	                        <input type='hidden' name='user[role]' value='<?php echo $user['new_role'] ?>' />
        	                        	<?php wp_nonce_field('add-local-user') ?>
	                        	        <input type='submit' class='button' name='addLocalUser' value='Create Local User' />
                		        </form></p>
        		                </div>
		                        <?php
				} else {
					ldapAddUserResult(array('updated' => 'false','action' => 'notfound','username' => $username));
				}
			}
		} else {
			// Add User to Blog
			if (wpmuLdapAddUserToBlog($login->ID,$blog_id,$user['new_role'])) {
                                wp_new_user_notification($login->ID);
				ldapAddUserResult(array('updated' => 'true','action' => 'add','username' => $username));
			} else
				ldapAddUserResult(array('updated' => 'false','action' => 'exists','username' => $username));
		}
	} elseif ($_POST['addUserBulk']) {
		// Check Access
	        $ldapBulkAdd = get_site_option('ldapBulkAdd');
        	if (is_super_admin() || ($ldapBulkAdd && is_admin())) {
	 		$user = $_POST['user'];
			$usernames = array();
			if ( !empty($user['bulk_username']) ) {
				$usernames = explode("\n", $user['bulk_username']);
				$usernames = array_filter(array_map('trim', $usernames)); // trim whitespace from usernames and remove empty lines
				$usernames = array_map('strtolower', $usernames);
			}
	
			foreach ($usernames as $username) {
	        	        // try finding a WP account for this user name
        		        $login = get_user_by('login',$username);
				if (!$login) {
					$result = wpmuLdapSearchUser(array(	'username' => $username,
										'blog_id' => $blog_id,
										'new_role' => $user['bulk_new_role'],
										'createBlog' => false));
					if (is_wp_error($result)) {
						ldapAddUserResult(array('updated' => 'false','error' => $result,'username' => $username));
					} else {
						if ($result[0]) {
			                                wp_new_user_notification($result[1]);
							ldapAddUserResult(array('updated' => 'true','action' => 'add','username' => $username));
						} else {
							ldapAddUserResult(array('updated' => 'false','action' => 'notfound','username' => $username));
						}
					}
				} else {
					// Add User to Blog
					if (wpmuLdapAddUserToBlog($login->ID,$blog_id,$user['bulk_new_role'])) {
                                                wp_new_user_notification($login->ID);
						ldapAddUserResult(array('updated' => 'true','action' => 'add','username' => $username));
					} else
						ldapAddUserResult(array('updated' => 'false','action' => 'exists','username' => $username));
				}
			}
		} else {
			ldapAddUserResult(array('updated' => 'false','action' => 'auth'));
		}
	} elseif ($_POST['addLocalUser']) {
               	check_admin_referer('add-local-user');
		$ldapCreateLocalUser = get_site_option('ldapCreateLocalUser');
		if ($ldapCreateLocalUser || is_super_admin())  {
                	$user = $_POST['user'];
                	if ( empty($user['username']) && empty($user['email']) ) {
                        	wp_die( __("<p>Missing username and email.</p>") );
                	} elseif ( empty($user['username']) ) {
                        	wp_die( __("<p>Missing username.</p>") );
                	} elseif ( empty($user['email']) ) {
                        	wp_die( __("<p>Missing email.</p>") );
                	}

                	$password = generate_random_password();
                	$user_id = wpmu_create_user(wp_specialchars( strtolower( $user['username'] ) ), $password, wp_specialchars( $user['email'] ) );

                	if( false == $user_id ) {
                        	wp_die( __("<p>Duplicated username or email address.</p>") );
                	} else {
                        	wp_new_user_notification($user_id, $password);
               		}

			// Update User Meta
			update_usermeta($user_id, 'primary_blog', $blog_id );				

			// Configure User Role
			add_user_to_blog($blog_id, $user_id, $user['role']);

			ldapAddUserResult(array('updated' => 'true','action' => 'add','username' => $user['username']));
		} else {
                        wp_die( __("<p>Access denied.</p>") );
		}
	} 
	?>

	<div class="wrap">
	<?php
	// Add User
	$ldapAddUser = get_site_option('ldapAddUser');
	if (is_super_admin() || ($ldapAddUser == 'enabled' || empty($ldapAddUser))) {
	?>
	<div id="icon-users" class="icon32">
		<br />
	</div>
	<h2><?php _e('Add User') ?></h2>
	<?php
	$ldapCreateLocalUser = get_site_option('ldapCreateLocalUser'); 
	if ($ldapCreateLocalUser) {
		echo "<p>Local User Creation Enabled</p>";
	}
	?>
	<p>
	Using the following fields below to search out LDAP users and add them into the blog.  
	<?php if ($ldapCreateLocalUser) { ?>
	If the user does not exist in the LDAP Directory, you will have the option to create a local account for them.
	<?php } ?>
	</p>

	<form method="post" id="ldap_add_user">
		<?php wp_nonce_field('add-user') ?>
		<fieldset class="options">
                <table class="form-table" cellpadding="3" cellspacing="3">
                        <tr valign="top">
                                <th scope='row'><label for="addusername"><?php _e('Username:') ?></label></th>
                                <td><input type="text" id="addusername" name="user[username]" /></td>
                        </tr>
			<tr valign="top">
 				<th scope="row"><label for="new_role"><?php _e('Role:') ?></label></th>
				<td><?php wpmuLdapAddGenRoleBox('new_role') ?></td>
			</tr>
                </table>
                <p class="submit">
                        <input class="button" type="submit" name="addUser" value="<?php _e('Add User') ?>" />
		</p>
		</fieldset>
	</form>
	<?php } ?>
	<!-- Bulk Add User -->
	<?php
	$ldapBulkAdd = get_site_option('ldapBulkAdd');
	if (is_super_admin() || ($ldapBulkAdd && is_admin())) {
	?>
	<h3><?php _e('Add Bulk Users') ?></h3>
	<p>Using the below fields, you can bulk add LDAP users.  Separate multiple users by a new line.  Local user creation is not available in bulk.  The auto create blog for new users function will be disabled for bulk adds.</p>
	<form method="post" id="ldap_add_user_bulk">
		<?php wp_nonce_field('add-user-bulk') ?>
		<fieldset class="options">
                <table class="form-table" cellpadding="3" cellspacing="3">
                        <tr valign="top">
                                <th scope='row'><label for="addbulkusername"><?php _e('Usernames:') ?></label></th>
                                <td><textarea name="user[bulk_username]" id="addbulkusername" rows="15" cols="40"></textarea></td>
                        </tr>
			<tr valign="top">
 				<th scope="row"><label for="bulk_new_role"><?php _e('Role:') ?></label></th>
				<td><?php wpmuLdapAddGenRoleBox('bulk_new_role') ?></td>
			</tr>
                </table>
                <p class="submit">
                        <input class="button" type="submit" name="addUserBulk" value="<?php _e('Add Bulk Users') ?>" />
		</p>
		</fieldset>
	</form>
	<?php } ?>
	</div>
<?php
}

function wpmuLdapAddGenRoleBox($id) {
	global $wp_roles;
	echo '<select name="user['.$id.']" id="'.$id.'">';
	foreach($wp_roles->role_names as $role => $name) {
		$name = translate_with_context($name);
		$selected = '';
		if( $role == 'subscriber' )
			$selected = 'selected="selected"';
			echo "<option {$selected} value=\"{$role}\">{$name}</option>";
               	}
        echo '</select>';
}
