=== WPMU Ldap Authentication ===
Contributors: axelseaa
Tags: ldap, authentication, multisite
Requires at least: 3.2
Tested up to: 3.4.x
Stable tag: wpmu-ldap_4.0.2

A plugin to override the core WordPress authentication method in order to use a LDAP server for authentication.  Currently only supported on MultiSite.

== Description ==

[New Feature Request Site](http://wpmuldap.uservoice.com/ "WPMU LDAP Authentication Feature Requests")

**Version 4.x introduces new installation instructions.**

Once installed (see below for instructions), the system may be configured to automatically create local WordPress user accounts and blogs.  Both the automatic creation of users and the automatic creation of blogs are configurable options.

LDAP authentication is configured on a site-wide (as opposed to per-blog) level, so only Network Admin accounts have access to the configuration to LDAP connection information.

Please make sure you have PHP compiled with LDAP support.  This will show up as an LDAP section in your phpinfo() if it is correct.

Remember - all the code for the plugin was contributed by volunteers, and you can show your gratitude by giving back to the community!

== Credits ==

Alistair Young - Original LDAP Plug-in
http://www.weblogs.uhi.ac.uk/sm00ay/?p=45

Patrick Cavit - WordPress 1.5.1 Modifcation of LDAP Plug-in
http://patcavit.com/2005/05/11/wordpress-ldap-and-playing-nicely/

Hugo Salgado - WordPress 2.0.3 Patch of WordPress 1.5.1 Modifcation of LDAP Plug-in
http://hugo.vulcano.cl/development/wordpress/ldap-auth-patch/

Alex Barker - WordPress MU 1.0.* Modifcation of LDAP Plug-in
http://wpmudev.org/project/WPMU-LDAP-Authentication-Plug-in

Dexter Arver - Windows LDAP Support Contribution for WordPress MU 1.0.* LDAP Plug-in

Sean Wedig -
http://www.thecodelife.net/category/software-dev/technology/wpmu/wpmu-ldap/

Aaron Axelsen - http://www.frozenpc.net

== How It Works ==

When enabled, this plugin can automatically create WordPress user accounts and blogs for LDAP-authenticated users.  Assuming user credentials authenticate against the LDAP server, creating local accounts and blogs follows this
algorithm:

Create a new WPMU User, with LDAP username and a randomly generated password.
Some user information, such as first and last name, is extracted from the
  information returned from the LDAP server.
Actions for user creation and activation are triggered.
The user's domain / URL are created depending on plugin configuration (i.e.,
  VHost vs SubDir).
If the option is set, a blog is created, with path and name based on the LDAP
  username and the blog is activated with the user being Administrator, and
  appropriate actions are triggered.

It should be noted that even though a random password is created for a user (for WPMU accounts), it is never displayed to the user.  This is intentional so that there is no confusion as to which password should be used; it will always be using LDAP credentials.  As a result, though, if ever LDAP is disabled or if the server is unavailable, users created with LDAP authentication will be unable to log in unless their passwords are reset.

== Installation ==

= New Installation = 

This section describes how to install the plugin and get it working.

1. Unzip the plugin contents to the `/wp-content/plugins/post-expirator/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

It is recommended to also change the welcome message that is sent to users upon account creation.  By default, WordPress includes the user's password in the message, even though this password is *not* used by this plugin.  The password sent is purely the password attached to the WPMU account that ties to the user's LDAP login.  To change the welcome message, log in as a Network Admin and go to Network Admin -> Options, and edit "Welcome Email" appropriately.

= Migrating from older versions =

If you currently have the WPMU Ldap Authentication plugin configured, migration is fairly simple

1. Make sure you have a network admin enabled non-ldap account setup and available in the blog
2. Remove the current plugin from mu-plugins (all configuration options will stay in the WP database)
3. Install version 4.x of the plugin into the standard plugins folder
4. Network Activate the plugin
5. Thats it! The plugin should now be re-activated with all of your previous settings.

== Changelog ==

**4.0.2**

* Fixed bug introduced by code cleanup that was preventing blog admins from adding users

**4.0.1**

* Fixed admin bar to remove default add users page and use the plugins page
* Fixed deprected function calls

**4.0**

* Plugin now must be installed into the `/plugins/` folder and activated network wide.  Installation into `/mu-plugins/` is no longer supported.
* Plugin files and svn repositiory is now hosted at wordpress.org.  This will allow for easy one-click installation and automatic update notifications.
* Minor code cleanup and verbage updates

**3.1.1**

* is_super_admin bugfix
* proper documentation revision bump
* fix where ldap/local role dropdown was not displaying on current user profile page

**3.1**

*Changes for 3.1 Network Admin

**3.0**

* Basic deny/allow group logic
* Changed bulk add logic to not automatically create blogs if the option is enabled - wordpress does not support this functionality
* Fixed issues with connection check not working when plugin is not enabled
* Fixed typo in default ldaps port in the documentation
* Fixed issue where local users would still attempt to authenticate against ldap
* Added better error checking on failure when adding users from the add user menu

**2.9**

* Now possible to disable the add user function for non site admin users
* Fixed problem with connection test function
* Fixed problem preventing blog admins from being able to bulk add users when enabled

**2.8.4**

* Modified plugin to use authenticate hook instead of wp_authenticate function
* Fixed problem with reset password link on local account
* Fixed improper constant definitions

**2.8.2**

* Fixed login error message on first load of wp-login

**2.8.1**

* LDAP Attributes converted to lowercase on save
* Experimental SSO Support
* Changed ldap_connect attributes to match as documented
* Removed default "Add New" option in 2.7
* Fixed issue where display_name cannot be edited, added upgrade function to migrate existing values

**2.7.1.1**

* Bug: Fixed issue where site admins were having wp_1_capabilities set on login
* New Feature: Ability to map nickname field to LDAP attribute.  If attribute is not set for a given user, the default convention is used.

**2.7.1**

* Ability for site admins to bulk add users - also configurable for blog admins to bulk add
* Revamped administration pages
* Added simple connection test option
* Added new error check to report unique message back on creation failure if there is an email address conflict
* Remove stale css entries
* LDAP TLS Support
* Ability for site admins to convert ldap users to local users and vice versa
* Added ldap attribute mapping via configuration pages
* Added config option to set the default display name format

**2.7**

* Public signup disabled message now appears as an error on the login form - thanks bforchhammer
* Updated action call for wpmu_activate_user - now requires 3 atrributes
* Changed internal handling of ldap server string to be array based - this was causing issues with passwords containing special characters - thanks gravelpot

**2.6**

* Removed ugly hacks for the retrieve password form utilizing a new filter in the trunk.
* Freshened up the look of the admin pages

**1.5.0**

* Remove override of wp_setcookie function - no longer needed!  This also means no more conflict with the admin ssl plugin!
* Removed experimental wp_munge hooks - no longer needed!
* Custom pluggable.php is no longer needed, and is totally remove from the release.
* Revamps logic for local users - removed chunks of unnecessary code!
* Enhanced error reporting sent back on authentication failures
* Support for local users!  You can now create local users and use them as well!  Local users can be regular users or admins, it doesn't matter, they all work!
* Using the new "Add User" screen, it's now possible to LDAP users to the blog that have never logged into WPMU.  As long as they exist in your LDAP directory, they can be added!

== Upgrade Notice ==

= 4.0.2 =
Fixed bug introduced by code cleanup that was preventing blog admins from adding users

= 4.0.1 =
Fixed deprecated functions and add user link on admin bar

