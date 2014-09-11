<?php
/**
 * Adds a link to the CSS for the LDAP authentication administative options.
 * Simply echoes the HTML to link the stylesheet to the current document.
 *
 * @return null - does not actively return a value
 */
function ldap_addstylesheet() {
	$base = WP_PLUGIN_DIR.'/'.basename(dirname(dirname(__FILE__)));
        $myStyleUrl = plugins_url('css/ldap_auth.css', $base.'/ldap_auth.php'); // Respects SSL, Style.css is relative to the current file
        $myStyleFile = WP_PLUGIN_DIR . '/wpmuldap/css/ldap_auth.css';
        if ( file_exists($myStyleFile) ) {
            wp_register_style('wpmu-ldap-css', $myStyleUrl);
            wp_enqueue_style('wpmu-ldap-css');
        }
}

/**
 * Displays the LDAP options panel and handles changes made to the LDAP
 * settings.  Retrieves all the LDAP options from WP via get_site_option, and
 * only updates them by checking for the existence of $_POST['ldapOptionsSave'].
 *
 * @return null - does not actively return a value
 * @see ldap_addmenu
 */
function ldapOptionsPanel() {
        global $current_blog;

	// Process POST Updates
	if ($_SERVER['REQUEST_METHOD'] == 'POST') wpmuProcessUpdates();

	$tab = $_GET['ldaptab'];
	$allowedtabs = array('general','connection','attributes','updates');
?>
<div class="wrap">
	<?php 
	wpmuLdapOptionsMenu($tab);
	if ($tab == 'attributes') {
		ldapAttributeMapping();
	} elseif ($tab == 'upgrade') {
		ldapOptionsPanelUpdates();
	} elseif ($tab == 'general') {
		ldapOptionsPanelGeneral();
	} elseif ($tab == 'group') {
		ldapOptionsPanelGroup();
	} else {
		ldapOptionsPanelConnection();
	}
	?>
</div>
<?php
}

function wpmuLdapOptionsMenu($tab) {
        echo '<h2>'._('Ldap Authentication Options').'</h2>';
        echo '<p>';
        echo '<a href="?page=wpmu_ldap_admin.functions.php"'.((empty($tab) || $tab == 'connection') ? ' class="wpmuLdapOptionMenuSelected"' : '').'>Connection Settings</a> | ';
        echo '<a href="?page=wpmu_ldap_admin.functions.php&ldaptab=general"'.($tab == 'general' ? ' class="wpmuLdapOptionMenuSelected"' : '').'>General Settings</a> | ';
        echo '<a href="?page=wpmu_ldap_admin.functions.php&ldaptab=attributes"'.($tab == 'attributes' ? ' class="wpmuLdapOptionMenuSelected"' : '').'>Attribute Mapping</a> | ';
        echo '<a href="?page=wpmu_ldap_admin.functions.php&ldaptab=group"'.($tab == 'group' ? ' class="wpmuLdapOptionMenuSelected"' : '').'>Group Settings</a> | ';
        echo '<a href="?page=wpmu_ldap_admin.functions.php&ldaptab=upgrade"'.($tab == 'upgrade' ? ' class="wpmuLdapOptionMenuSelected"' : '').'>Upgrade</a>';
        echo '</p><hr/>';
}

function wpmuProcessUpdates() {
        if($_POST['ldapOptionsSave']) {
		foreach ($_POST as $key => $item) {
			if (stripos($key,'attribute')) $item = strtolower($item);
			if ($key != 'ldapOptionsSave' || $key != 'ldapTestConnection') update_site_option($key,stripslashes($item));
		}

		# Test Ldap Connection
		if ($_POST['ldapTestConnection']) {
			if (wpmuLdapTestConnection())
		                echo "<div id='message' class='updated fade'><p><b>LDAP Connection Test:</b> Successful!</p></div>";
			else			
		                echo "<div id='message' class='error fade'><p><b>LDAP Connection Test:</b> Failed</p></div>";
		}

                echo "<div id='message' class='updated fade'><p>Saved Options!</p></div>";
	} else if ($_POST['ldapGroupsSave']) {
		$allow = explode("\n", $_POST['ldapGroupAllowLogin']);
		$allow = array_filter(array_map('trim', $allow));
		update_site_option('ldapGroupAllowLogin',$allow);

		#$allowCreate = explode("\n", $_POST['ldapGroupAllowLoginCreate']);
		#$allowCreate = array_filter(array_map('trim', $allowCreate));
		#update_site_option('ldapGroupAllowLoginCreate',$allowCreate);

		$deny = explode("\n", $_POST['ldapGroupDenyLogin']);
		$deny = array_filter(array_map('trim', $deny));
		update_site_option('ldapGroupDenyLogin',$deny);

                echo "<div id='message' class='updated fade'><p>Saved Options!</p></div>";
        } else if ($_POST['ldapFixMeta']) {
                wpmuLdapFixMeta();
                update_site_option('ldapfixmetafor15','true');
                echo "<div id='message' class='updated fade'><p>All users ldap_auth meta values updated!</p></div>";
        } else if ($_POST['ldapFixDisplayName']) {
                wpmuLdapFixDisplayName();
                update_site_option('ldapfixdisplayname','true');
                echo "<div id='message' class='updated fade'><p>All users display_name meta values have been removed and set in the users table!</p></div>";		
	}

}

function getWpmuLdapSiteOptions() {
	$defaultSignupMessage = 'Public sign-up has been disabled.';

	$sysAdminEmail = get_site_option('admin_email');
	$defaultGetPasswordMessage = <<<GetPasswordMsg
Your account is tied to an account in the central directory.  You cannot
retrieve your password via email.  Please contact the
<a href="mailto:$sysAdminEmail">system administrator</a> for information on how
to reset your password.
GetPasswordMsg;

	$defaultLDAPEmailSubj = $defaultLocalEmailSubj = 'Blogging Account Created';
	$defaultLDAPEmailMessage = 'Dear User,

You have just been permitted to access a new blog!

Username: USERNAME
Login: LOGINLINK

We hope you enjoy your new weblog.
 Thanks!

--Wordpress';
	$defaultLocalEmailMessage = 'Dear User,

You have just been permitted to access a new blog!

Username: USERNAME
Password: PASSWORD
Login: LOGINLINK

We hope you enjoy your new weblog.
 Thanks!

--Wordpress';

	$ret = array();
	$ret['ldapAuth']		= get_site_option('ldapAuth');
	$ret['ldapSSOEnabled']		= get_site_option('ldapSSOEnabled');
	$ret['ldapCreateAcct']		= get_site_option('ldapCreateAcct');
	$ret['ldapCreateBlog']		= get_site_option('ldapCreateBlog');
	$ret['ldapLinuxWindows']	= get_site_option('ldapLinuxWindows');
	$ret['ldapServerAddr']		= get_site_option('ldapServerAddr');
	$ret['ldapServerPort']		= get_site_option('ldapServerPort');
	$ret['ldapServerOU']		= get_site_option('ldapServerOU');
	$ret['ldapServerCN']		= get_site_option('ldapServerCN');
	$ret['ldapEnableSSL']		= get_site_option('ldapEnableSSL');
	$ret['ldapServerPass']		= get_site_option('ldapServerPass');
	$ret['ldapDisableSignup']	= get_site_option('ldapDisableSignup');
	$ret['ldapLocalEmail']		= get_site_option('ldapLocalEmail');
	$ret['ldapLocalEmailSubj']	= get_site_option('ldapLocalEmailSubj',$defaultLocalEmailSubj);
	$ret['ldapLocalEmailMessage']	= stripslashes(get_site_option('ldapLocalEmailMessage', $defaultLocalEmailMessage));
	$ret['ldapLDAPEmail']		= get_site_option('ldapLDAPEmail');
	$ret['ldapLDAPEmailSubj']	= get_site_option('ldapLDAPEmailSubj',$defaultLDAPEmailSubj);
	$ret['ldapLDAPEmailMessage']	= stripslashes(get_site_option('ldapLDAPEmailMessage', $defaultLDAPEmailMessage));
	$ret['ldapCreateLocalUser']	= get_site_option('ldapCreateLocalUser');
	$ret['ldapSignupMessage']	= stripslashes(get_site_option('ldapSignupMessage', $defaultSignupMessage));
	$ret['ldapGetPasswordMessage']	= stripslashes(get_site_option('ldapGetPasswordMessage', $defaultGetPasswordMessage));
	$ret['ldapfixmetafor15']	= get_site_option('ldapfixmetafor15');
	$ret['ldapfixdisplayname']	= get_site_option('ldapfixdisplayname');
	$ret['ldapBulkAdd']		= get_site_option('ldapBulkAdd');
	$ret['ldapAddUser']		= get_site_option('ldapAddUser');
	$ret['ldapPublicDisplayName']	= get_site_option('ldapPublicDisplayName');
	$ret['ldapAttributeMail']	= get_site_option('ldapAttributeMail',LDAP_DEFAULT_ATTRIBUTE_MAIL);
	$ret['ldapAttributeGivenname']	= get_site_option('ldapAttributeGivenname',LDAP_DEFAULT_ATTRIBUTE_GIVENNAME);
	$ret['ldapAttributeNickname']	= get_site_option('ldapAttributeNickname',LDAP_DEFAULT_ATTRIBUTE_NICKNAME);
	$ret['ldapAttributeSn']		= get_site_option('ldapAttributeSn',LDAP_DEFAULT_ATTRIBUTE_SN);
	$ret['ldapAttributePhone']	= get_site_option('ldapAttributePhone',LDAP_DEFAULT_ATTRIBUTE_PHONE);
	$ret['ldapAttributeHomedir']	= get_site_option('ldapAttributeHomedir',LDAP_DEFAULT_ATTRIBUTE_HOMEDIR);
	$ret['ldapAttributeMember']	= get_site_option('ldapAttributeMember',LDAP_DEFAULT_ATTRIBUTE_MEMBER);
	$ret['ldapAttributeMemberNix']	= get_site_option('ldapAttributeMemberNix',LDAP_DEFAULT_ATTRIBUTE_MEMBERNIX);
	$ret['ldapAttributeMacaddress']	= get_site_option('ldapAttributeMacaddress',LDAP_DEFAULT_ATTRIBUTE_MACADDRESS);
	$ret['ldapAttributeDn']		= get_site_option('ldapAttributeDN',LDAP_DEFAULT_ATTRIBUTE_DN);
	$ret['ldapAttributeNixSearch']	= get_site_option('ldapAttributeNixSearch',LDAP_DEFAULT_ATTRIBUTE_NIXSEARCH);
	$ret['ldapAttributeWinSearch']	= get_site_option('ldapAttributeWinSearch',LDAP_DEFAULT_ATTRIBUTE_WINSEARCH);
	$ret['ldapAttributeGroupObjectclass']	= get_site_option('ldapAttributeGroupObjectclass',LDAP_DEFAULT_ATTRIBUTE_GROUP_OBJECTCLASS);
	$ret['ldapAttributeGroupObjectclassNix']= get_site_option('ldapAttributeGroupObjectclassNix',LDAP_DEFAULT_ATTRIBUTE_GROUP_OBJECTCLASSNIX);

	$ret['ldapGroupAllowLogin'] 		= wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupAllowLogin','display' => 'web'));
	$ret['ldapGroupAllowLoginCreate'] 	= wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupAllowLoginCreate','display' => 'web'));
	$ret['ldapGroupDenyLogin'] 		= wpmuLdapGroupsGet(array('siteoption' => 'ldapGroupDenyLogin','display' => 'web'));

	return $ret;
}

function ldapOptionsPanelGeneral() {
	extract(getWpmuLdapSiteOptions());

	// default values to avoid PHP notices about unset values
	$tAcctChecked = ''; $fAcctChecked = '';
	$tBlogChecked = ''; $fBlogChecked = '';
	$tDisableSignup = ''; $fDisableSignup = '';
	$tCreateLocalUser = '';	$fCreateLocalUser = '';

	if ($ldapSSOEnabled) $tSSOChecked = "checked='checked'";
	else $fSSOChecked = "checked='checked'";

	if($ldapCreateAcct) $tAcctChecked = "checked='checked'";
	else $fAcctChecked = "checked='checked'";

	if($ldapCreateBlog) $tBlogChecked = "checked='checked'";
	else $fBlogChecked = "checked='checked'";

	if($ldapBulkAdd) $tBulkAdd = "checked='checked'";
	else $fBulkAdd = "checked='checked'";

	if($ldapAddUser == 'enabled' || empty($ldapAddUser)) $tAddUser = "checked='checked'";
	else $fAddUser = "checked='checked'";

	if($ldapDisableSignup) 	$tDisableSignup = "checked='checked'";
	else $fDisableSignup = "checked='checked'";

	if($ldapLocalEmail) $tLocalEmail = "checked='checked'";
	else $fLocalEmail = "checked='checked'";

	if($ldapLDAPEmail) $tLDAPEmail = "checked='checked'";
	else $fLDAPEmail = "checked='checked'";

	if($ldapCreateLocalUser) $tCreateLocalUser = "checked='checked'";
	else $fCreateLocalUser = "checked='checked'";

	if($ldapPublicDisplayName) $displayNameSelect = $ldapPublicDisplayName;
	else $displayNameSelect = false;

?>

	<form method="post" id="ldap_auth_options">
		<h3>General Settings</h3>
		<table class="form-table">
			<tr valign="top">
			   <th scope="row">Use Single Sign-On?</th>
			   <td>
				<input type='radio' name='ldapSSOEnabled' id='ldapSSOEnabledYes' value='1' <?php echo $tSSOChecked ?>/> <label for="ldapSSOEnabledYes">Yes</label>
				<input type='radio' name='ldapSSOEnabled' id='ldapSSOEnabledNo' value='0' <?php echo $fSSOChecked ?>/> <label for="ldapSSOEnabledNo">No</label>
				<br/>
				If "Yes", the system will try to automatically log users into their accounts
				using NTLM Authentication. In order for this to work "Windows Authentication"
				needs to be activated on the file "wp-login.php".
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Auto-Create WPMU Accounts?</th>
			   <td>
				<input type='radio' name='ldapCreateAcct' id='createAcctYes' value='1' <?php echo $tAcctChecked ?>/> <label for="createAcctYes">Yes</label>
				<input type='radio' name='ldapCreateAcct' id='createAcctNo' value='0' <?php echo $fAcctChecked ?>/> <label for="createAcctNo">No</label>
				<br/>
				If "Yes", this will automatically create a WPMU account for any user
				that successfully authenticates against the LDAP server. The WPMU user
				account will be named the same as the LDAP username.
				<br/><br/>
				If "No", then a
				Site Admin must create a WPMU user account for the user to be able to
				log in.  The WPMU user account must be named the same as the LDAP
				username for LDAP authentication to function.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Auto-Create WPMU Blogs?</th>
			   <td>
				<input type='radio' name='ldapCreateBlog' id='createBlogYes' value='1' <?php echo $tBlogChecked; ?>/> <label for="createBlogYes">Yes</label>
				<input type='radio' name='ldapCreateBlog' id='createBlogNo' value='0' <?php echo $fBlogChecked; ?>/> <label for="createBlogNo">No</label>
				<br/>
				If "Yes", this will automatically create a WPMU blog for any user that successfully authenticates against the LDAP server. The blog will be named the same as the LDAP username.
				<br/><br/>
				If "No", then a Site Admin must create a WPMU blog for the user to be able to log in.  
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Create local users?</th>
			   <td>
				<input type='radio' name='ldapCreateLocalUser' id='createLocalUserYes' value='1' <?php echo $tCreateLocalUser ?>/> <label for="createLocalUserYes">Yes</label>
				<input type='radio' name='ldapCreateLocalUser' id='createLocalUserNo' value='0' <?php echo $fCreateLocalUser ?>/> <label for="createLocalUserNo">No</label>
				<br/>
				This will either allow or disallow the creation of local accounts.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Allow blog admins to add users?</th>
			   <td>
				<input type='radio' name='ldapAddUser' id='adduseryes' value='enabled' <?php echo $tAddUser; ?>/> <label for="adduseryes">Yes</label>
				<input type='radio' name='ldapAddUser' id='adduserno' value='disabled' <?php echo $fAddUser; ?>/> <label for="adduserno">No</label>
				<br/>
				This option specifies whether or not the individual blog admins are able to add users.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Allow blog admins to bulk add?</th>
			   <td>
				<input type='radio' name='ldapBulkAdd' id='bulkaddyes' value='1' <?php echo $tBulkAdd; ?>/> <label for="bulkaddyes">Yes</label>
				<input type='radio' name='ldapBulkAdd' id='bulkaddno' value='0' <?php echo $fBulkAdd; ?>/> <label for="bulkaddno">No</label>
				<br/>
				This option specifies whether or not the individual blog admins are able to bulk add users.  Site admins are 
				always able to bulk add regardless of this setting.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Disable Public Signup?</th>
			   <td>
				<input type='radio' name='ldapDisableSignup' id='disableSignupYes' value='1' <?php echo $tDisableSignup; ?>/> <label for="disableSignupYes">Yes</label>
				<input type='radio' name='ldapDisableSignup' id='disableSignupNo' value='0' <?php echo $fDisableSignup; ?>/> <label for="disableSignupNo">No</label>
				<br/>
				This overrides all actions that take place within wp-signup.php, effectively disabling public signup.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for="ldapSignupMessage">Signup-Disabled Message:</label></th>
			   <td>
				<textarea name='ldapSignupMessage' id='ldapSignupMessage' rows="5" cols="45" style="width: 95%;"><?php echo $ldapSignupMessage ?></textarea>
				<br/>
				This is an alternate HTML message that would be displayed in place of any actions at wp-signup.php.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for="ldapGetPasswordMessage">Lost-Password Message:</label></th>
			   <td>
				<textarea name='ldapGetPasswordMessage' id='ldapGetPasswordMessage' rows="5" cols="45" style="width: 95%;"><?php echo $ldapGetPasswordMessage ?></textarea>
				<br/>
				This is the error message that would be displayed when an LDAP-account user submits "Lost Password" requests.
			   </td>
			</tr>
			<tr>
			   <th scope="row"><label for="ldapPublicDisplayName">Public Display Name Format:</label></th>
			   <td>
				<select id="ldapPublicDisplayName" name="ldapPublicDisplayName">
					<option value='username' <?php echo $displayNameSelect == 'username' ? ' selected="selected"' : ''; ?>>username</option>
					<option value='first' <?php echo $displayNameSelect == 'first' ? ' selected="selected"' : ''; ?>>firstname</option>
					<option value='firstlast' <?php echo $displayNameSelect == 'firstlast' ? ' selected="selected"' : ''; ?>>firstname lastname</option>
					<option value='lastfirst' <?php echo $displayNameSelect == 'lastfirst' ? ' selected="selected"' : ''; ?>>lastname firstname</option>
				</select>
				<br/>
				Sets the default display name format to use for new account creations.  If LDAP Nickname attribute mapping is set, that will take precedence over this format.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">New user email notification (Local Users):</th>
			   <td>
				<input type='radio' name='ldapLocalEmail' id='disableLocalEmailYes' value='1' <?php echo $tLocalEmail; ?>/> <label for="disableLocalEmailYes">Yes</label>
				<input type='radio' name='ldapLocalEmail' id='disableLocalEmailNo' value='0' <?php echo $fLocalEmail; ?>/> <label for="disableLocalEmailNo">No</label>
				<br/>
				Controls whether or not local users are emailed on account creation or when receiving access to a new blog.  It is recommended to set this to yes, otherwise local users will not receive their password when created.
				<br/><br/>
				<label for="ldapLocalEmailSubj">Email Subject:</label><br/>
				<input type="text" name="ldapLocalEmailSubj" id="ldapLocalEmailSubj" value="<?php echo $ldapLocalEmailSubj ?>" /><br />
				<label for="ldapLocalEmailMessage">Email Body:</label><br/>
				<textarea name="ldapLocalEmailMessage" id="ldapLocalEmailMessage" rows="5" cols="45" style="width: 95%;"><?php echo $ldapLocalEmailMessage ?></textarea>
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">New user email notification (LDAP Users):</th>
			   <td>
				<input type='radio' name='ldapLDAPEmail' id='disableLDAPEmailYes' value='1' <?php echo $tLDAPEmail; ?>/> <label for="disableLDAPEmailYes">Yes</label>
				<input type='radio' name='ldapLDAPEmail' id='disableLDAPEmailNo' value='0' <?php echo $fLDAPEmail; ?>/> <label for="disableLDAPEmailNo">No</label>
				<br/>
				Controls whether or not ldap users are emailed on account creation or when receiving access to a new blog.
				<br/><br/>
				<label for="ldapLDAPEmailSubj">Email Subject:</label><br/>
				<input type="text" name="ldapLDAPEmailSubj" id="ldapLDAPEmailSubj" value="<?php echo $ldapLDAPEmailSubj ?>" /><br />
				<label for="ldapLDAPEmailMessage">Email Body:</label><br/>
				<textarea name="ldapLDAPEmailMessage" id="ldapLDAPEmailMessage" rows="5" cols="45" style="width: 95%;"><?php echo $ldapLDAPEmailMessage ?></textarea>
			   </td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="ldapOptionsSave" value="Save Options" /></p>
	</form>
<?php
}

function ldapOptionsPanelConnection() {
        extract(getWpmuLdapSiteOptions());

	// default values to avoid PHP notices about unset values
	$tChecked = ''; $fChecked = '';
	$tLinWin = ''; $fLinWin = '';
	$tEnableSSL = ''; $fEnableSSL = '';

	if($ldapAuth) $tChecked = "checked='checked'";
	else $fChecked = "checked='checked'";

	if($ldapEnableSSL == 1) $tEnableSSL = "checked='checked'";
	elseif ($ldapEnableSSL == 2) $tEnableTLS = "checked='checked'";
	else $fEnableSSL = "checked='checked'";

	if($ldapLinuxWindows) $tLinWin = "checked='checked'";
	else $fLinWin = "checked='checked'";

	if (!is_numeric($ldapServerPort))
		$ldapServerPort = 389;

?>
	<p>
	To start allowing users to log in with LDAP credentials, you will need to
	Enable LDAP-Authentication below.  LDAP Authentication is available for all
	accounts.  It is recommended that you still maintain a local <strong>admin</strong>
	account to allow access if the LDAP server is unavailable.
	</p>

	<form method="post" id="ldap_auth_options">
		<h3>Connection Settings</h3>
		<table class="form-table">
			<tr valign="top">
			   <th scope="row">LDAP-Authentication:</th>
			   <td>
				<input type='radio' name='ldapAuth' id='authEnable' value='1' <?php echo $tChecked ?>/> <label for="authEnable">Enabled</label>
				<input type='radio' name='ldapAuth' id='authDisable' value='0' <?php echo $fChecked ?>/> <label for="authDisable">Disabled</label>
				<br/>
				If this is disabled, then entire plugin will be disabled.  Users will need to log in using WPMU user credentials, and will not be able to use LDAP credentials to access their accounts.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Server Encryption:</th>
			   <td>
				<input type='radio' name='ldapEnableSSL' id='sslOff' value='0' <?php echo $fEnableSSL ?>/> <label for="sslOff">None</label>
				<input type='radio' name='ldapEnableSSL' id='sslOn' value='1' <?php echo $tEnableSSL ?>/> <label for="sslOn">SSL</label>
				<input type='radio' name='ldapEnableSSL' id='sslTLS' value='2' <?php echo $tEnableTLS ?>/> <label for="sslTLS">TLS</label>
				<br/>
				Select none to connect over ldap://, Select SSL to connect over ldaps://, Select TLS to connect using TLS encryption
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for="serverAddr">Server Address:</label></th>
			   <td>
				<input type='text' name='ldapServerAddr' id='serverAddr' value='<?php echo $ldapServerAddr ?>' style='width: 300px;' />
				<br/>
				The name or IP address of the LDAP server.  The protocol should be left out. (Ex. ldap.example.com)
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for="serverPort">Server Port:</label></th>
			   <td>
				<input type='text' name='ldapServerPort' id='serverPort' value='<?php echo $ldapServerPort ?>' style='width: 300px;' />
				<br/>
                                Port Number of the LDAP server. (LDAP: Linux=389, Windows=3268) (LDAPS: Linux=636, Windows=3269)
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for="serverOU">Search DN:</label></th>
			   <td>
				<input type='text' name='ldapServerOU' id='serverOU' value='<?php echo $ldapServerOU; ?>' style='width: 450px;' />
				<br/>
				The base DN in which to carry out LDAP searches.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for="serverCN">Search User DN:</label></th>
			   <td>
				<input type='text' name='ldapServerCN' id='serverCN' value='<?php echo $ldapServerCN; ?>' style='width: 450px;' />
			   	<br/>
				Some systems do not allow anonymous searching for attributes, and so this will set the account to use when connecting for searches.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><label for='serverPass'>Search User Password:</label></th>
			   <td>
				<input type='password' name='ldapServerPass' id='serverPass' value='<?php echo $ldapServerPass; ?>' />
				<br/>
				Password for the User DN above.
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">LDAP Type:</th>
			   <td>
				<input type='radio' name='ldapLinuxWindows' id='linux' value='1' <?php echo $tLinWin; ?>/> <label for="linux">Linux</label>
				<input type='radio' name='ldapLinuxWindows' id='windows' value='0' <?php echo $fLinWin; ?>/> <label for="windows">Windows</label>
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row">Test Connection:</th>
			   <td>
				<input type='radio' name='ldapTestConnection' id='testconnectionyes' value='1'> <label for="textconnectionyes">Yes</label>
				<input type='radio' name='ldapTestConnection' checked='checked' id='testconnectionno' value='0'> <label for="textconnectionno">No</label>
				<br/>
				Specifys whether or not to test the ldap server connection on form submit.
			   </td>
			</tr>			
		</table>
		<p class="submit"><input type="submit" name="ldapOptionsSave" value="Save Options" /></p>
	</form>
<?php
}

function ldapOptionsPanelUpdates() {
	extract(getWpmuLdapSiteOptions());
?>
	<form method="post" id="ldap_fix_meta">
		<h3>Upgrade</h3>
		<table class="form-table">
			<tr valign="top">
			   <th scope="row"><?php _e('Update Display Name'); ?></th>
			   <td>
				Migrate all display name values from usermeta values into the users database table.
				<p><?php if ($ldapfixdisplayname) echo "ALREADY PROCESSED"; ?>
				<input type="submit" name="ldapFixDisplayName" value="Fix Display Name"/></p>
			   </td>
			</tr>
			<tr valign="top">
			   <th scope="row"><?php _e('Update Meta'); ?></th>
			   <td>
				WARNING: Clicking on the button will update ALL blog users except admin to be set with the ldap_login meta value. If you have local users, this will also change them.  This is only needed for those users upgrading from the 1.3 series of wordpress.
				<p><?php if ($ldapfixmetafor15) echo "ALREADY PROCESSED"; ?>
				<input type="submit" name="ldapFixMeta" value="Fix Meta (Required if upgrading from WPMU 1.3)"/></p>
			   </td>
			</tr>
		</table>
	</form>
<?php
}

function ldapAttributeMapping() {
        extract(getWpmuLdapSiteOptions());
?>
        <form method="post" id="ldap_auth_options">
                <h3>LDAP Attribute Mapping</h3>
		<p>This page will allow you to modify which ldap attribute the plugin uses to populate default values for the user.</p>
                <b>General Attributes</b>
                <table class="form-table">
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeMail">Email:</label></th>
                           <td>
				<input type="text" name="ldapAttributeMail" id="ldapAttributeMail" value="<?php echo $ldapAttributeMail ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeGivenname">Givenname (Firstname):</label></th>
                           <td>
				<input type="text" name="ldapAttributeGivenname" id="ldapAttributeGivenname" value="<?php echo $ldapAttributeGivenname ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeSn">Surname (Lastname):</label></th>
                           <td>
				<input type="text" name="ldapAttributeSn" id="ldapAttributeSn" value="<?php echo $ldapAttributeSn ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeNickname">Nickname:</label></th>
                           <td>
				<input type="text" name="ldapAttributeNickname" id="ldapAttributeNickname" value="<?php echo $ldapAttributeNickname ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributePhone">Phone:</label></th>
                           <td>
				<input type="text" name="ldapAttributePhone" id="ldapAttributePhone" value="<?php echo $ldapAttributePhone ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeHomedir">Home Directory:</label></th>
                           <td>
				<input type="text" name="ldapAttributeHomedir" id="ldapAttributeHomedir" value="<?php echo $ldapAttributeHomedir ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeMacaddress">Mac Address:</label></th>
                           <td>
				<input type="text" name="ldapAttributeMacaddress" id="ldapAttributeMacaddress" value="<?php echo $ldapAttributeMacaddress ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeDn">Distinguished Name (DN):</label></th>
                           <td>
				<input type="text" name="ldapAttributeDn" id="ldapAttributeDn" value="<?php echo $ldapAttributeDn ?>" />
				<br/>
                           </td>
                        </tr>
		</table>

                <br/><b>Windows Specific Attributes</b>
                <table class="form-table">
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeWinSearch">Search Attribute:</label></th>
                           <td>
				<input type="text" name="ldapAttributeWinSearch" id="ldapAttributeWinSearch" value="<?php echo $ldapAttributeWinSearch ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeMember">Group Attribute:</label></th>
                           <td>
				<input type="text" name="ldapAttributeMember" id="ldapAttributeMember" value="<?php echo $ldapAttributeMember ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeGroupObjectclass">Group Objectclass:</label></th>
                           <td>
				<input type="text" name="ldapAttributeGroupObjectclass" id="ldapAttributeGroupObjectclass" value="<?php echo $ldapAttributeGroupObjectclass ?>" />
				<br/>
                           </td>
                        </tr>
		</table>

                <br/><b>Linux Specific Attributes</b>
                <table class="form-table">
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeNixSearch">Search Attribute:</label></th>
                           <td>
				<input type="text" name="ldapAttributeNixSearch" id="ldapAttributeNixSearch" value="<?php echo $ldapAttributeNixSearch ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeMemberNix">Group Attribute:</label></th>
                           <td>
				<input type="text" name="ldapAttributeMemberNix" id="ldapAttributeMemberNix" value="<?php echo $ldapAttributeMemberNix ?>" />
				<br/>
                           </td>
                        </tr>
                        <tr valign="top">
                           <th scope="row"><label for="ldapAttributeGroupObjectclassNix">Group Objectclass:</label></th>
                           <td>
				<input type="text" name="ldapAttributeGroupObjectclassNix" id="ldapAttributeGroupObjectclassNix" value="<?php echo $ldapAttributeGroupObjectclassNix ?>" />
				<br/>
                           </td>
                        </tr>
		</table>

		<p class="submit"><input type="submit" name="ldapOptionsSave" value="Save Attributes" /></p>
	</form>
<?php
}

function ldapOptionsPanelGroup() {
        extract(getWpmuLdapSiteOptions());
?>
        <form method="post" id="ldap_auth_groups">
                <h3>LDAP Group Settings</h3>
                <p>This page allows you to specify allow and deny groups for site wide blog access.  In the boxes below, enter the 
		full dn to each group.  For multiple groups, enter each group on a new line.  Nested groups are supported.</p>
                <table class="form-table">
                        <tr valign="top">
                           <th scope="row"><label for="ldap">Allow Login:</label></th>
                           <td>
                                <textarea rows="2" cols="70" name="ldapGroupAllowLogin" id="ldapGroupAllowLogin"><?php echo $ldapGroupAllowLogin ?></textarea>
                                <br/>
                           </td>
                        </tr>
<!--                        <tr valign="top">
                           <th scope="row"><label for="ldap">Allow Login w/automatic blog creation:</label></th>
                           <td>
                                <textarea rows="2" cols="70" name="ldapGroupAllowLoginCreate" id="ldapGroupAllowLoginCreate""><?php echo $ldapGroupAllowLoginCreate ?></textarea>
                                <br/>
                           </td>
                        </tr>-->
                        <tr valign="top">
                           <th scope="row"><label for="ldap">Deny Login:</label></th>
                           <td>
                                <textarea rows="2" cols="70" name="ldapGroupDenyLogin" id="ldapGroupDenyLogin"><?php echo $ldapGroupDenyLogin ?></textarea>
                                <br/>
                           </td>
                        </tr>
		</table>
                <p class="submit"><input type="submit" name="ldapGroupsSave" value="Save Groups" /></p>
	</form>
<?php
}

/**
 * Adds a sub menu to the Site Admin panel.  If the currently logged in user is
 * a site-admin, then this menu is created using the ldapOptionsPanel function.
 * Otherwise, nothing happens.
 *
 * @return null - does not actively return a value
 */
function ldap_addmenu() {
	if (function_exists('add_submenu_page') && is_super_admin()) {
		// does not use add_options_page, because it is site-wide configuration,
		//  not blog-specific config, but side-wide
		add_submenu_page('settings.php',__('LDAP Options','wpmuldap'),__('LDAP Options','wpmuldap'),'manage_network',basename(__FILE__),'ldapOptionsPanel');
	}
}

/** 
 * Checks to see if user is allowed to reset password.  If the user has the ldap_login meta set
 * the global password recovery message will be display back to the user.  Local accounts will
 * still retain the ability to reset their passwords.
 */
function ldapPasswordReset($value,$userID) {
        $ldap_login = get_user_meta($userID, 'ldap_login',true);
	if (empty($ldap_login)) return true;
	if ($ldap_login == true) {
		// get the configurable error message:
		return new WP_Error('no_password_reset', __("<strong>ERROR</strong>: ").get_site_option('ldapGetPasswordMessage'));
	} 
}
add_filter('allow_password_reset','ldapPasswordReset',0,2);

/**
 * Returns false if LDAP authentication is turned on, the current user is a site
 * admin and the use being currently edited is a site admin.  Essentially is
 * indended to be used as a filter function on the WP filter
 * show_password_fields so as to hide the "new password" fields from appearing
 * for LDAP-managed accounts or for users that should not see the fields
 * regardless of access role.
 *
 * Provides a means for accounts that are directly managed by WPMU (i.e., site
 * admins) to manage and update their passwords.
 *
 * @param bool $isShowingPwdFields - the filtered value, expected from the
 * filter show_password_fields
 */
function wpmuLdapDisableLdapPassword($isShowingPwdFields) {
	global $userdata, $profileuser;

	$ldap_login = get_user_meta($profileuser->ID,'ldap_login',true);
	if ($ldap_login == 'true') {
		return false;
	} else {
		return $isShowingPwdFields;
	}
}

/**
 * Disable the built in add user form on the users page.
 */
function wpmuLdapDisableShowUser() {
	return false;
}

if ( !function_exists('wp_new_user_notification') ) :
/**
 * Overriding of the new user notification, so that users are not confused by
 * email messages with passwords.
 *
 */
function wp_new_user_notification($user_id, $plaintext_pass = '') {
	global $current_site;
	$user = new WP_User($user_id);

	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);
	$ldap_login = get_user_meta($user_id, 'ldap_login',true);

	if (empty($plaintext_pass)) $plaintext_pass = "Your SITE_NAME Password";

	$msg  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
	$msg .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$msg .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

	$subj = 'Blogging Account Created';

	if ($ldap_login == true) { // LDAP Users
		if (get_site_option('ldapLDAPEmail')) { // Check to see if LDAP email notifications are enabled
			$msg .= sprintf(__('Username: %s'), $user_login) . "\r\n";
			$msg .= get_option('siteurl') . "LOGINLINK\r\n";
			$ldapmsg = get_site_option('ldapLDAPEmailMessage');
			if (!empty($ldapmsg)) $msg = $ldapmsg;
			$ldapsubj = get_site_option('ldapLDAPEmailSubj');
			if (!empty($ldapsubj)) $subj = $ldapsubj;
		} else return;
	} else { // Local Users
		if (get_site_option('ldapLocalEmail')) { // Check to see if local email notifications are enabled
			$msg .= sprintf(__('Username: %s'), $user_login) . "\r\n";
			$msg .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
			$msg .= get_option('siteurl') . "/wp-login.php\r\n";
			$localmsg = get_site_option('ldapLocalEmailMessage');
			if (!empty($localmsg)) $msg = $localmsg;
			$localsubj = get_site_option('ldapLocalEmailSubj');
			if (!empty($localsubj)) $subj = $localsubj;
		} else return;
	}

        $msg = str_replace( "PASSWORD", $plaintext_pass, $msg );
        $msg = str_replace( "SITE_NAME", $current_site->site_name, $msg );
        $msg = str_replace( "USERNAME", $user_login, $msg );
        $msg = str_replace( "USEREMAIL", $user_email, $msg );
        $msg = str_replace( "LOGINLINK", site_url( 'wp-login.php' ), $msg );

	wp_mail($user_email, sprintf(__('[%s] %s'), get_option('blogname'), $subj), $msg);
}
endif;

/**
 * Updates all user meta values to make sure ldap_auth is enabled and set to true.  This will break local
 * user accounts.  The admin user is not touched.
 */
function wpmuLdapFixMeta() {
	global $wpdb;
	$users = $wpdb->get_results("SELECT ID from $wpdb->users WHERE ID > 1");
	foreach ($users as $user) {
	        update_usermeta( $user->ID, 'ldap_login', 'true' );
	}
}

/**
 * Updates displayname for all user accounts that are ldap enabled.  Older version of this plugin stored this in user_meta values, when
 * it should be correctly stored in the users table.
 */
function wpmuLdapFixDisplayName() {
	global $wpdb;
	$users = $wpdb->get_results("SELECT ID from $wpdb->users WHERE ID > 1");
	foreach ($users as $user) {
	        $ldap = get_user_meta( $user->ID, 'ldap_login', true);
		if ($ldap) {
			$display_name = get_user_meta( $user->ID, 'display_name' , true);
			if (!empty($display_name)) {
				$wpdb->update( $wpdb->users, compact( 'display_name' ), array( 'ID' => $user->ID ) );
				delete_usermeta( $user->ID, 'display_name', $display_name);
			}
		}
	}
}

/**
 * Displays the account authentication type options on the edit user form.
 */
function wpmuUserFormLdapOption() {
	global $user_id, $current_user;
	$ldap_login = get_user_meta($user_id, 'ldap_login',true);
	?>
<h3><?php _e('LDAP Options'); ?></h3>

<table class="form-table">
<tr>
	<th><?php _e('Account Authentication Type'); ?></th>
	<td class="regular-text">
	<?php if (is_super_admin() && $user_id > 1) { ?>
		<select name="ldapAccountType">
			<option<?php if ($ldap_login == 'true') echo ' selected="selected"'; ?> value="LDAP"><?php _e('LDAP'); ?></option>
			<option<?php if ($ldap_login != 'true') echo ' selected="selected"'; ?> value="Local"><?php _e('Local'); ?></option>
		</select>
	<?php } else {
		if ($user_id == 1)
			$msg = "Userid #1 cannot be changed.";
		else
			$msg = "Only network admin's can update the account type.";
		if ($ldap_login == 'true') {
			echo "<input type='text' disabled='disabled' value='"._('LDAP')."' />";
		} else {
			echo "<input type='text' disabled='disabled' value='"._('Local')."' />";
		}	
		_e($msg);
	} ?>
	</td>
</tr>
</table>
	<?php 
} // wpmuUserFormLdapOption()

/**
 * Updates ldap_auth user meta value based on option selected on the edit user form 
 */
function wpmuUserFormLdapOptionUpdate() {
	global $user_id, $current_user;

	if ($user_id == 1 || !is_super_admin())
		return;

	if ($_POST['ldapAccountType'] == 'LDAP')
		update_usermeta( $user_id, 'ldap_login', 'true' );
	else
		delete_usermeta( $user_id, 'ldap_login' );
		
} // wpmuUserFormLdapOptionUpdate()

/**
* Remove the Add New menu item added in 2.7
*/
function wpmuRemoveAddNewMenu() {
        global $submenu;
        unset($submenu['users.php'][10]);
}

/**
* Connection Test Function
*/
function wpmuLdapTestConnection() {
        $server = new LDAP_ro(wpmuSetupLdapOptions());
        $server->DebugOff();
        $result = $server->testConnect();
        $server->Disconnect();
        return $result;
}

/**
 * Get Groups from DB 
 */
function wpmuLdapGroupsGet($opts = array()) {
        if (empty($opts['siteoption'])) return;
        if (empty($opts['display'])) $opts['display'] = 'array';
        $groups = get_site_option($opts['siteoption']);
        if (empty($groups)) return;
        if ($opts['display'] == 'array') return  array_filter(array_map('strtolower', $groups));
        elseif ($opts['display'] == 'web') return implode("\n",$groups);
}

/**
 * Configures the ldap options to pass in for authentication/verification
 */
function wpmuSetupLdapOptions() {
        $options = array();
        $options[] = get_site_option("ldapServerAddr");
        $options[] = get_site_option("ldapServerOU");
        $options[] = get_site_option("ldapServerCN");
        $options[] = get_site_option("ldapServerPass");
        $options[] = get_site_option("ldapServerPort");
        $options[] = get_site_option("ldapEnableSSL");
        return $options;
}

