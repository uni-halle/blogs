<?php
/**
 * 
 * @author alexhermann
 *
 */
$fields['login_button'] = array(

	'title_config' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Configure the button',$this->ptd).'</h1>
		',
		'widget_no_display' => true
	),
	
	'start_config' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		'
	),
		
	'widget_title'=> array(
		'type'=> 'text',
		'label'=> __('Title',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4'),
		'widget_only' => true
	),
	
	'display_on_login_page'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),		
		'label'=> __('Display on Login',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
		
	'display_on_register_page'=> array(
		'type'=> 'select',
		'options' => array(
				array('value'=>'0', 'label'=>__('No',$this->ptd)),
				array('value'=>'1', 'label'=>__('Yes',$this->ptd)),
		),
		'label'=> __('Display on Register',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
		
	'login_redirect_url'=> array(
		'type'=> 'text',
		'label'=> __('Url after login',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'logout_redirect_url'=> array(
		'type'=> 'text',
		'label'=> __('Url after logout',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'logout_label'=> array(
		'type'=> 'text',
		'label'=> __('Logout Label',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'show_profile_picture'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),		
		'label'=> __('Show profile picture',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'show_faces'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),		
		'label'=> __('Show faces',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2 login_button_show_faces')
	),
	
	'maxrow'=> array(
		'type'=> 'text',
		'label'=> __('Max row',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2 depend_login_button_show_faces', 'disabled'=>($this->options['login_button']['show_faces'] == "0" ? 'disabled' : ''))
	),
	
	'width'=> array(
		'type'=> 'text',
		'label'=> __('Width of button',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'image'=> array(
		'type'=> 'media',
		'label'=> __('Button Image',$this->ptd),
		'class'=> 'span8',
		'attr'=> array('class'=>'span6')
	),

	'end_config' => array(
		'type'=>'html',
		'html'=> '
			</div>
		'
	),
	
	'preview' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Preview',$this->ptd).'</h1>
			<div class="well">'.$this->get_the_login_button().'</div>
			<h1>'.__('Options List',$this->ptd).'</h1>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Option</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>profile_picture</td><td>0 or 1</td></tr>
					<tr><td>faces</td><td>0 or 1</td></tr>
					<tr><td>maxrow</td><td>number (only if faces = 1)</td></tr>
					<tr><td>login_url</td><td>string</td></tr>
					<tr><td>logout_url</td><td>string</td></tr>
					<tr><td>width</td><td>number</td></tr>
					<tr><td>image</td><td>url</td></tr>
				</tbody>
				<tfoot>
					<tr><th colspan="2">[AWD_loginbutton option="value"]</th></tr>
				</tfoot>
			</table>
		',
		'widget_no_display' => true
	)
);
?>