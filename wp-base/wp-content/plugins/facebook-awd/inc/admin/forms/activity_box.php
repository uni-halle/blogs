<?php
/**
 * 
 * @author alexhermann
 *
 */
$fields['activity_box'] = array(
	'title_config' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Configure the box',$this->ptd).'</h1>
		',
		'widget_no_display' => true
	),
	
	'start_domain' => array(
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
	
	'domain'=> array(
		'type'=> 'text',
		'label'=> __('Domain',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4')
	),
	
	'end_domain' => array(
		'type'=>'html',
		'html'=> '
			</div>
		'
	),
	
	'start_config' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		'
	),
	
	'type'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'iframe', 'label'=>__('Iframe',$this->ptd)),
			array('value'=>'xfbml', 'label'=>__('Xfbml',$this->ptd)),							
			array('value'=>'html5', 'label'=>__('html5',$this->ptd)),				
		),
		'label'=> __('Type',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'filter'=> array(
		'type'=> 'text',
		'label'=> __('Filter',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'ref'=> array(
		'type'=> 'text',
		'label'=> __('Ref',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'max_age'=> array(
		'type'=> 'text',
		'label'=> __('Max Age',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	
	'linktarget'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'_blank', 'label'=>__('Blank',$this->ptd)),
			array('value'=>'_top', 'label'=>__('Top',$this->ptd)),							
			array('value'=>'_parent', 'label'=>__('Parent',$this->ptd)),				
		),
		'label'=> __('Links Target',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'width'=> array(
		'type'=> 'text',
		'label'=> __('Width of box',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	
	'height'=> array(
		'type'=> 'text',
		'label'=> __('Height of box',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'header'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Show Header ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'recommendations'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Recommendations',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'colorscheme'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'light', 'label'=>__('light',$this->ptd)),
			array('value'=>'dark', 'label'=>__('Dark',$this->ptd)),							
		),
		'label'=> __('Colors',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'font'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'arial', 'label'=>__('Arial',$this->ptd)),
			array('value'=>'lucida grande', 'label'=>__('Lucida grande',$this->ptd)),
			array('value'=>'segoe ui', 'label'=>__('Segoe ui',$this->ptd)),
			array('value'=>'tahoma', 'label'=>__('Tahoma',$this->ptd)),
			array('value'=>'trebuchet ms', 'label'=>__('Trebuchet ms',$this->ptd)),
			array('value'=>'verdana', 'label'=>__('Verdana',$this->ptd))							
		),
		'label'=> __('Fonts',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'border_color'=> array(
		'type'=> 'text',
		'label'=> __('Border color',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
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
			<div class="well">'.$this->get_the_activity_box().'</div>
			<h1>'.__('Options List',$this->ptd).'</h1>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Option</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>domain</td><td>string</td></tr>
					<tr><td>width</td><td>number</td></tr>
					<tr><td>height</td><td>number</td></tr>
					<tr><td>colorscheme</td><td>light or dark</td></tr>
					<tr><td>fonts</td><td>string</td></tr>
					<tr><td>border_color</td><td>hexadecimal string (ex: #ffffff for white)</td></tr>
					<tr><td>recommendations</td><td>0 or 1</td></tr>
					<tr><td>header</td><td>0 or 1</td></tr>
					<tr><td>type</td><td>xfbml or iframe or html5</td></tr>
					<tr><td>max_age</td><td>string</td></tr>
					<tr><td>ref</td><td>string</td></tr>
					<tr><td>linktarget</td><td>_blank or _top or _parent</td></tr>
					<tr><td>filter</td><td>string</td></tr>
				</tbody>
				<tfoot>
					<tr><th colspan="2">[AWD_activitybox option="value"]</th></tr>
				</tfoot>
			</table>
		',
		'widget_no_display' => true
	)
);
?>