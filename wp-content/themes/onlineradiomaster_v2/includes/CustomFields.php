<?php

define( 'ACF_LITE', true );

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_lecturer-details',
		'title' => 'Lecturer Details',
		'fields' => array (
			array (
				'key' => 'field_5579b5d6de44b',
				'label' => 'Module',
				'name' => 'module',
				'type' => 'select',
				'required' => 1,
				'choices' => array (
					'1-1' => '1.1 Medientheoretisches Propädeutikum',
					'1-2' => '1.2 Multimediale Produktionspraxis und Prozessmanagement',
					'1-3' => '1.3 Brückenmodul',
					'' => '',
				),
				'default_value' => '',
				'allow_null' => 0,
				'multiple' => 1,
			),
			array (
				'key' => 'field_5579b69ade44c',
				'label' => 'Biography',
				'name' => 'biography',
				'type' => 'wysiwyg',
				'instructions' => 'Eine Kurzbiografie des Dozenten.',
				'default_value' => '',
				'toolbar' => 'basic',
				'media_upload' => 'no',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'person',
					'order_no' => 0,
					'group_no' => 0,
				),
				array (
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => '7242',
					'order_no' => 1,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_person-basics',
		'title' => 'Person Basics',
		'fields' => array (
			array (
				'key' => 'field_5579b27b9fab1',
				'label' => 'Profile Picture',
				'name' => 'profile_picture',
				'type' => 'image',
				'instructions' => 'Upload a nice profile picture of this person.',
				'required' => 1,
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'person',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_student-specific-details',
		'title' => 'Student-specific Details',
		'fields' => array (
			array (
				'key' => 'field_5579b3a0c3bbf',
				'label' => 'Testimonial',
				'name' => 'testimonial',
				'type' => 'textarea',
				'instructions' => 'Hier das Testimonial des Studenten oder Alumni eintragen.',
				'default_value' => '',
				'placeholder' => 'Ein prägnantes Testimonial.',
				'maxlength' => '',
				'rows' => '',
				'formatting' => 'br',
			),
			array (
				'key' => 'field_5582c1c5d189e',
				'label' => 'Status',
				'name' => 'status',
				'type' => 'radio',
				'instructions' => 'Student oder Alumni?',
				'required' => 1,
				'choices' => array (
					'Student' => 'Student',
					'Alumni' => 'Alumni',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => '',
				'layout' => 'horizontal',
			),
			array (
				'key' => 'field_558a8f4d08329',
				'label' => 'Beruf',
				'name' => 'occupation',
				'type' => 'text',
				'instructions' => 'Derzeitiger Beruf, besonders für Alumni relevant.',
				'default_value' => '',
				'placeholder' => 'Geschäftsführer*in bei SUPER MEGA RADIO Corp. Worldwide',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'person',
					'order_no' => 0,
					'group_no' => 0,
				),
				array (
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => '7240',
					'order_no' => 1,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'person',
					'order_no' => 0,
					'group_no' => 1,
				),
				array (
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => '7241',
					'order_no' => 1,
					'group_no' => 1,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_team-details',
		'title' => 'Team Details',
		'fields' => array (
			array (
				'key' => 'field_5579b709e654e',
				'label' => 'Job Title',
				'name' => 'job_title',
				'type' => 'text',
				'instructions' => 'Tätigkeitsfeld im Studiengangsteam.',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'Mädchen für alles',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => 80,
			),
			array (
				'key' => 'field_5579b74ae654f',
				'label' => 'E-mail address',
				'name' => 'email',
				'type' => 'email',
				'instructions' => 'Mailadresse für die Kontaktformulare und Teamseite.',
				'required' => 1,
				'default_value' => '',
				'placeholder' => 'name@onlineradiomaster.de',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5579b795e6550',
				'label' => 'Phone number',
				'name' => 'phone',
				'type' => 'number',
				'instructions' => 'Die Büro-Telefonnummer, falls das regelmäßig besetzt ist.',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5579b7e1e6551',
				'label' => 'Biography',
				'name' => 'biography',
				'type' => 'wysiwyg',
				'instructions' => 'Die Biografie des Team-Mitglieds. Hier können auch aktuelle Tätigkeiten in anderen Projekten, Auszeichnungen etc. festgehalten werden.',
				'required' => 1,
				'default_value' => '',
				'toolbar' => 'basic',
				'media_upload' => 'no',
			),
			array (
				'key' => 'field_557b0380ea64a',
				'label' => 'Contact',
				'name' => 'contact',
				'type' => 'acf_cf7',
				'disable' => array (
					0 => 0,
				),
				'allow_null' => 0,
				'multiple' => 0,
				'hide_disabled' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'person',
					'order_no' => 0,
					'group_no' => 0,
				),
				array (
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => '7239',
					'order_no' => 1,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
