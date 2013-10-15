=== WP Front-End Repository Manager ===
Contributors: nmedia
Donate link: http://www.najeebmedia.com/donate/
Tags: File Manager, Repository manager, member file manager, directory tree, manage files
Requires at least: 3.2.1
Tested up to: 3.4
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Members can upload and download files, create directories up to unlimited level. 

== Description ==
This plugin allow members to upload files, create directories up to unlimited level. This plugin is great tool for those who want to allow site members to upload and manage their documents/files in directories. The interface is very easy to use and light to load.

<h3>How to use</h3>
Create a page and paste this shortcode: `[nm-wp-repo]`

<h3>Features</h3>
<ol>
	<li>Uploader with Progressbar</li>
	<li>Most secure uploader plugin</li>
	<li>Ajax based validation</li>
	<li>File Detail</li>
	<li>Download Files</li>
	<li>Delete File</li>
	<li>Customized dialog messages</li>
	<li>Restrict file types</li>
	<li>Resctrict file size</li>
</ol>


<h3>Pro Features</h3>
Pro version gives you AWSOME control over this plugin on top of free version. You can control file upload behavior with following shortcode
<ol>
	<li>Receive uploaded file(s) in email as attachment</li>
	<li>Download all files and directories as zip for each user</li>
	<li>Attach umlimited additional input fields with file</li>
	<li>Search files and directory option</li>
	<li>Secure files from unauthorised download</li>
	<li>Allow multiple file upload</li>
	<li>AWESOME support</li>
</ol>


<h3>File Meta</h3>
File meta is another set of shortcodes allow site admin to attach unlimited input fields. These are named as `File Meta`. Admin will receive email on every file upload
with `File Meta`. Following four types of input field can be attached:

<ul>
	<li><strong>Text</strong> - e.g: [nm-input-field type="text" label="Title"]</li>
	<li><strong>Textarea</strong> - e.g: [nm-input-field type="textarea" label="File notes"]</li>
	<li><strong>Select</strong> - e.g: [nm-input-field type="select" label="Select color" options="Red,Green,Blue"]</li>
	<li><strong>Checkbox</strong> - e.g: [nm-input-field type="checkbox" label="Shipping by" options="Regular, Air"]</li>
</ul>

<strong><a href="http://www.najeebmedia.com/n-media-repository-manager-wp-plugin-pro/">More information</a></strong>


== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activation, you can set options from `NM FileUploader` menu

== Frequently Asked Questions ==
= How to use this plugin = 
Create a page and paste following shortcode: `[nm-wp-repo]`

= Does this uploader will show progressbar =
Yes nice progressbar with percentage

= Why I see HTTP Error message =
it is because of your server side settings, sometime php.ini does not allow to upload big files. You need to check following two settings in php.ini:<br>
1- post_max_size<br>
2- upload_max_filesize
<a href="http://www.najeebmedia.com/how-increase-file-upload-size-limit-in-wordpress/">check this tutorial</a>

== Screenshots ==

1. How front end looks like
2. Uploading file into current directory
3. Creating directory into current directory
4. Admin: Plugin option for Basic settings
5. Admin: Set dialog messages
6. Admin: Files stats
7. Admin: Expanding files against user

== Changelog ==

= 1.0 =
* It is just born

= 1.1 =
* Fixed a major bug, now plugin options are working

== Upgrade Notice ==

= 1.0 =
Nothing for now.It is just born

= 1.1 =
Fixed a major bug, now plugin options are working
