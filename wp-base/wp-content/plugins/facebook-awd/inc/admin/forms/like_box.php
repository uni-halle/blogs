<?php
/**
 * 
 * @author alexhermann
 *
 */
$fields['like_box'] = array(
	'title_config' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Configure the box',$this->ptd).'</h1>
		',
		'widget_no_display' => true
	),
	
	'start_url' => array(
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
	
	'href'=> array(
		'type'=> 'text',
		'label'=> __('Url of the page',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4')
	),
	
	'end_url' => array(
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
	
	'show_faces'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Show faces',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),

	'stream'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Show Stream',$this->ptd),
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
	
	'force_wall'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Force Wall ?',$this->ptd),
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
			<div class="well">'.$this->get_the_like_box().'</div>
			<h1>'.__('Options List',$this->ptd).'</h1>
			<table class="table table-bordered table-condensed table-striped">
				<thead>
					<tr>
						<th>Option</th>
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>url</td><td>string</td></tr>
					<tr><td>width</td><td>number</td></tr>
					<tr><td>height</td><td>number</td></tr>
					<tr><td>colorscheme</td><td>light or dark</td></tr>
					<tr><td>faces</td><td>0 or 1</td></tr>
					<tr><td>border_color</td><td>hexadecimal string (ex: #ffffff for white)</td></tr>
					<tr><td>stream</td><td>like or recommend</td></tr>
					<tr><td>header</td><td>0 or 1</td></tr>
					<tr><td>type</td><td>xfbml or iframe or html5</td></tr>
					<tr><td>force_wall</td><td>string</td></tr>
				</tbody>
				<tfoot>
					<tr><th colspan="2">[AWD_likebox option="value"]</th></tr>
				</tfoot>
			</table>
		',
		'widget_no_display' => true
	)
);
?>