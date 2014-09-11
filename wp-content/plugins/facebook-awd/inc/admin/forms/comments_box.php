<?php
/**
 * 
 * @author alexhermann
 *
 */
$fields['comments_box'] = array(
	'title_config' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Configure the comments box',$this->ptd).'</h1>
		',
		'widget_no_display' => true
	),
	
	'before_url' => array(
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
		'label'=> __('Default Url',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4')
	),
	
	
	'after_url' => array(
		'type'=>'html',
		'html'=> '
			</div>
		'
	),
	
	
	'before_config' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		'
	),
	
	'type'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'xfbml', 'label'=>__('Xfbml',$this->ptd)),							
			array('value'=>'html5', 'label'=>__('html5',$this->ptd)),				
		),
		'label'=> __('Type',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	
	'num_posts'=> array(
		'type'=> 'text',
		'label'=> __('Nb of comments',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'mobile'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Mobile version',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'after_config' => array(
		'type'=>'html',
		'html'=> '
			</div>
		'
	),
	
	'before_on_posts' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		',
		'widget_no_display' => true
	),
	
	'on_pages'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Add Comments to pages',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
	
	'on_posts'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Add Comments to posts',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
	
	'on_custom_post_types'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Add Comments to custom posts',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
	
	
	'after_on_posts' => array(
		'type'=>'html',
		'html'=> '
			</div>
		',
		'widget_no_display' => true
	),
	
	
	'before_config2' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		'
	),
	
	'place'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'before', 'label'=>__('Before comments form',$this->ptd)),
			array('value'=>'top', 'label'=>__('Top of comments form',$this->ptd)),
			array('value'=>'after', 'label'=>__('After comments form',$this->ptd)),
			array('value'=>'before_fields', 'label'=>__('Before comments form fields',$this->ptd)),
			array('value'=>'after_fields', 'label'=>__('After comments form fields',$this->ptd)),
			array('value'=>'replace', 'label'=> __('Replace comments form',$this->ptd))
		),
		'label'=> __('Where ?',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4 comments_box_place'),
		'widget_no_display' => true
	),
	
	'comments_template_path'=> array(
		'type'=> 'text',
		'label'=> __('Default comments template path (used only if replace is enabled)',$this->ptd),
		'class'=>'span6',
		'attr'=> array('class'=>'span6 depend_on_comments_box_place')
	),
	
	'exclude_post_id'=> array(
		'type'=> 'text',
		'label'=> __('Exclude Posts or Pages ID (example: 12,46,234)',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4'),
		'widget_no_display' => true
	),
	
	'width'=> array(
		'type'=> 'text',
		'label'=> __('Width',$this->ptd),
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
	
	'after_config2' => array(
		'type'=>'html',
		'html'=> '
			</div>
		',
	),

	'link_manage' => array(
		'type'=> 'html',
		'html'=> ($this->options['app_id'] != '' ?
			'<a class="btn" target="_blank" href="https://developers.facebook.com/tools/comments?id='.$this->options['app_id'].'">'.__('Manage comments',$this->ptd).'</a>
		':''),
		'widget_no_display' => true
	),
	
	'preview' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Preview',$this->ptd).'</h1>			
			<div class="well">'.$this->get_the_comments_box("",array("width"=>"420")).'</div> 
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
					<tr><td>nb</td><td>number</td></tr>
					<tr><td>colorscheme</td><td>light or dark</td></tr>
					<tr><td>mobile</td><td>0 or 1</td></tr>
					<tr><td>type</td><td>xfbml or iframe or html5</td></tr>
				</tbody>
				<tfoot>
					<tr><th colspan="2">[AWD_comments option="value"]</th></tr>
				</tfoot>
			</table>  
		',
		'widget_no_display' => true
	)
);
?>