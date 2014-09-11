<?php
/**
 * 
 * @author alexhermann
 *
 */
$fields['shared_activity_box'] = array(
	'title_config' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Configure the box',$this->ptd).'</h1>
		',
		'widget_no_display' => true
	),
	
	'start_title' => array(
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
	
	'end_title' => array(
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
			<div class="well">'.$this->get_the_shared_activity_box().'</div>
			<h1>'.__('Options List',$this->ptd).'</h1>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Option</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>width</td><td>number</td></tr>
					<tr><td>height</td><td>number</td></tr>
					<tr><td>fonts</td><td>string</td></tr>
				</tbody>
				<tfoot>
					<tr><th colspan="2">[AWD_shared_activitybox option="value"]</th></tr>
				</tfoot>
			</table>
		',
		'widget_no_display' => true
	)
);
?>