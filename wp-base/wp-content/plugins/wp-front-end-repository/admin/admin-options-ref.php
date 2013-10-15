<?php

$repo_options = array('generay-setting'		=>	array(	'name'		=> __('General setting', $repo -> plugin_shortname),
														'type'	=> 'tab',
														'desc'	=> __('this is description about this setting', $repo -> plugin_shortname),
														'meat'	=> array('allowed-types'	=>	array(	'label'		=> __('Allowed file types', $repo -> plugin_shortname),
																								'desc'		=> __('', $repo -> plugin_shortname),
																								'id'			=> $repo -> plugin_shortname.'_filesAllowed',
																								'type'			=> 'text',
																								'default'		=> '*.jpg;*.png',
																								'help'			=> __('here will be help text', $repo -> plugin_shortname)
																								),
																		'send-email'		=> array(	'label'		=> '',
																								'desc'		=> __('do you want to send email?', $repo -> plugin_shortname),
																								'id'			=> $repo -> plugin_shortname.'_sendEmail',
																								'type'			=> 'checkbox',
																								'default'		=> '',
																								'help'			=> __('here will be help text', $repo -> plugin_shortname),
																								'options'		=> array('yes'	=> __('Yes', $repo -> plugin_shortname),
																														'no'	=> __('No', $repo -> plugin_shortname)
																														)
																								),
																		'site-category'		=> array(	'label'		=> __('Select your category', $repo -> plugin_shortname),
																				'desc'		=> __('Under which domain you want to show site?', $repo -> plugin_shortname),
																				'id'			=> $repo -> plugin_shortname.'_siteCategory',
																				'type'			=> 'select',
																				'default'		=> __('Select category', $repo -> plugin_shortname),
																				'help'			=> '',
																				'options'		=> array('12'	=> __('Art', $repo -> plugin_shortname),
																										'25'	=> __('Fashion', $repo -> plugin_shortname)
																										)
																								),
																		'key-notes'	=>	array(	'label'		=> __('Key notes', $repo -> plugin_shortname),
																				'desc'		=> __('', $repo -> plugin_shortname),
																				'id'			=> $repo -> plugin_shortname.'_keyNotes',
																				'type'			=> 'textarea',
																				'default'		=> '',
																				'help'			=> __('here will be help text', $repo -> plugin_shortname)
																								),
																		'screen-type'		=> array('label'		=> '',
																				'desc'		=> __('What should be screen size?', $repo -> plugin_shortname),
																				'id'			=> $repo -> plugin_shortname.'_screenSize',
																				'type'			=> 'radio',
																				'default'		=> '',
																				'help'			=> __('here will be help text', $repo -> plugin_shortname),
																				'options'		=> array('full'	=> __('Full', $repo -> plugin_shortname),
																								'half'	=> __('Half', $repo -> plugin_shortname)
																										)
																								),
																		)
													),
					'my-other-tabls'		=> array(	'name'		=> __('My other settings', $repo -> plugin_shortname),
														'type'	=> 'tab',
														'desc'	=> __('this is description about file size limit', $repo -> plugin_shortname),
														'meat'	=> ''
													)
					);

//print_r($repo_options);