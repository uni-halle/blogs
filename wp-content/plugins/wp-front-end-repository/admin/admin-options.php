<?php

$meatGeneral = array('allowed-types'	=> array(	'label'		=> __('Allowed file types', $repo -> plugin_shortname),
																								'desc'		=> __('', $repo -> plugin_shortname),
																								'id'			=> $repo -> plugin_shortname.'_files_allowed',
																								'type'			=> 'text',
																								'default'		=> '*.jpg;*.png',
																								'help'			=> __('Enter the files types e.g: <strong>*.jpg;*.png</strong>', $repo -> plugin_shortname)
																								),
					'size-limit'		=>  array(	'label'		=> __('Size limit', $repo -> plugin_shortname),
																								'desc'		=> __('Enter size limit in Bytes, leave blank for server default', $repo -> plugin_shortname),
																								'id'			=> $repo -> plugin_shortname.'_size_limit',
																								'type'			=> 'text',
																								'default'		=> '',
																								'help'			=> __('102400', $repo -> plugin_shortname)
																								),
					);

$meatDialog = array('file-uploaded'	=> array(	'label'		=> __('File saved message', $repo -> plugin_shortname),
		'desc'		=> __('This message will be shown when file is saved', $repo -> plugin_shortname),
		'id'			=> $repo -> plugin_shortname.'_file_saved',
		'type'			=> 'textarea',
		'default'		=> '',
		'help'			=> ''),
		
		'dir-created'	=> array(	'label'		=> __('Directory created message', $repo -> plugin_shortname),
		'desc'		=> __('This message will be shown when directory is created', $repo -> plugin_shortname),
		'id'			=> $repo -> plugin_shortname.'_dir_created',
		'type'			=> 'textarea',
		'default'		=> '',
		'help'			=> ''),);

$proFeatures = '<ol>';
$proFeatures .= '<li>'.__('Receive uploaded file(s) in email as attachment', $repo -> plugin_shortname).'</li>';
$proFeatures .= '<li>'.__('Download all files and directories as zip for each user', $repo -> plugin_shortname).'</li>';
$proFeatures .= '<li>'.__('Attach umlimited additional input fields with file', $repo -> plugin_shortname).'</li>';
$proFeatures .= '<li>'.__('Search files and directory option', $repo -> plugin_shortname).'</li>';
$proFeatures .= '<li>'.__('Secure files from unauthorised download', $repo -> plugin_shortname).'</li>';
$proFeatures .= '<li>'.__('Allow multiple file upload', $repo -> plugin_shortname).'</li>';
$proFeatures .= '</ol>';

$proFeatures .= '<br><br>Purchase URL: <a href="http://www.najeebmedia.com/n-media-repository-manager-wp-plugin-pro/">Here</a>';
$proFeatures .= '<br>More information contact: <a href="mailto:sales@najeebmedia.com">sales@najeebmedia.com</a>';


$meatPro = array('pro-feature'	=> array(	'desc'		=> $proFeatures,
		'type'		=> 'para',
		'help'			=> ''
),);

$repo_options = array('general-settings'	=> array(	'name'		=> __('Basic Setting', $repo -> plugin_shortname),
														'type'	=> 'tab',
														'desc'	=> __('Set options as per your need', $repo -> plugin_shortname),
														'meat'	=> $meatGeneral,
														
													),
						'email-template'	=> array(	'name'		=> __('Dialog Messages', $repo -> plugin_shortname),
								'type'	=> 'tab',
								'desc'	=> __('Set message as per your need', $repo -> plugin_shortname),
								'meat'	=> $meatDialog,
								
						),
		
						'pro-features'	=> array(	'name'		=> __('Pro Features', $repo -> plugin_shortname),
								'type'	=> 'tab',
								'desc'	=> __('Following features will be enabled for Pro Version', $repo -> plugin_shortname),
								'meat'	=> $meatPro,
						
						),
					);

//print_r($repo_options);