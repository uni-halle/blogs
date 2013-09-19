<?php
/**
 * 
 * @author alexhermann
 *
 */
$fields['like_button'] = array(
	'title_config' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Configure the button',$this->ptd).'</h1>
		',
		'widget_no_display' => true
	),
	
	'before_config' => array(
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
	
	'action'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'like', 'label'=>__('Like',$this->ptd)),
			array('value'=>'recommend', 'label'=>__('Recommend',$this->ptd))						
		),
		'label'=> __('Action',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'layout'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'standard', 'label'=>__('Standard',$this->ptd)),
			array('value'=>'button_count', 'label'=>__('Button Count',$this->ptd)),							
			array('value'=>'box_count', 'label'=>__('Box Count',$this->ptd))					
		),
		'label'=> __('Layout style',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
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
		'attr'=> array('class'=>'span2 like_button_type')
	),
	
	'send'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Send button ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2 depend_like_button_type','disabled'=>($this->options['like_button']['type'] != "xfbml" ? 'disabled' : ''))
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
		'label'=> __('Show Faces ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'width'=> array(
		'type'=> 'text',
		'label'=> __('Width of button',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'height'=> array(
		'type'=> 'text',
		'label'=> __('Height of button',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'ref'=> array(
		'type'=> 'text',
		'label'=> __('Ref',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2')
	),
	
	'after_config' => array(
		'type'=>'html',
		'html'=> '
			</div>
		'
	),
	
	'title_usage' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Define a default usage',$this->ptd).'</h1>
		',
		'widget_no_display' => true
		
	),
	
	'before_url' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		'
	),
	
	'href'=> array(
		'type'=> 'text',
		'label'=> __('Default Url to like',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4')
	),
	
	'after_url' => array(
		'type'=>'html',
		'html'=> '
			</div>
		'
	),
	
	'before_on_pages' => array(
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
		'label'=> __('Display on pages ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
		
	),
	
	'place_on_pages'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'top', 'label'=>__('Top',$this->ptd)),
			array('value'=>'bottom', 'label'=>__('Bottom',$this->ptd)),							
			array('value'=>'both', 'label'=>__('Both',$this->ptd)),							
		),
		'label'=> __('Where ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2 depend_like_button_on_pages', 'disabled'=>($this->options['like_button']['on_pages'] == "0" ? 'disabled' : '')),
		'widget_no_display' => true
	),
	
	'after_on_pages' => array(
		'type'=>'html',
		'html'=> '
			</div>
		',
		'widget_no_display' => true
	),
	
	'before_on_posts' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		',
		'widget_no_display' => true
	),
	
	'on_posts'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('Display on posts ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
	
	'place_on_posts'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'top', 'label'=>__('Top',$this->ptd)),
			array('value'=>'bottom', 'label'=>__('Bottom',$this->ptd)),							
			array('value'=>'both', 'label'=>__('Both',$this->ptd)),							
		),
		'label'=> __('Where ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2 depend_like_button_on_posts', 'disabled'=>($this->options['like_button']['on_posts'] == "0" ? 'disabled' : '')),
		'widget_no_display' => true
	),
	
	'after_on_posts' => array(
		'type'=>'html',
		'html'=> '
				</div>
		',
		'widget_no_display' => true
	),
	
	'before_on_posts_types' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		',
		'widget_no_display' => true
	),
	
	'on_custom_post_types'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'0', 'label'=>__('No',$this->ptd)),
			array('value'=>'1', 'label'=>__('Yes',$this->ptd)),							
		),
		'label'=> __('On custom posts ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2'),
		'widget_no_display' => true
	),
	
	'place_on_custom_post_types'=> array(
		'type'=> 'select',
		'options' => array(
			array('value'=>'top', 'label'=>__('Top',$this->ptd)),
			array('value'=>'bottom', 'label'=>__('Bottom',$this->ptd)),							
			array('value'=>'both', 'label'=>__('Both',$this->ptd)),							
		),
		'label'=> __('Where ?',$this->ptd),
		'class'=>'span2',
		'attr'=> array('class'=>'span2 depend_like_button_on_custom_post_types', 'disabled'=>($this->options['like_button']['on_custom_post_types'] == "0" ? 'disabled' : '')),
		'widget_no_display' => true
	),
	
	'after_on_posts_types' => array(
		'type'=>'html',
		'html'=> '
				</div>
		',
		'widget_no_display' => true
	),
	
	'before_on_exclude' => array(
		'type'=>'html',
		'html'=> '
			<div class="row">
		',
		'widget_no_display' => true
	),

	'exclude_post_type'=> array(
		'type'=> 'text',
		'label'=> __('Exclude Post types',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4'),
		'widget_no_display' => true
	),
	
	'exclude_terms_slug'=> array(
		'type'=> 'text',
		'label'=> __('Exclude Categories or other terms',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4'),
		'widget_no_display' => true
	),
	
	'exclude_post_id'=> array(
		'type'=> 'text',
		'label'=> __('Exclude Posts or Pages ID',$this->ptd),
		'class'=>'span4',
		'attr'=> array('class'=>'span4'),
		'widget_no_display' => true
	),
	
	'after_exclude' => array(
		'type'=>'html',
		'html'=> '
				</div>
		',
		'widget_no_display' => true
	),
	
	'preview' => array(
		'type'=>'html',
		'html'=> '
			<h1>'.__('Preview',$this->ptd).'</h1>			
			<div class="well">'.$this->get_the_like_button().'</div> 
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
					<tr><td>send</td><td>0 or 1</td></tr>
					<tr><td>width</td><td>number</td></tr>
					<tr><td>height</td><td>number</td></tr>
					<tr><td>colorscheme</td><td>light or dark</td></tr>
					<tr><td>faces</td><td>0 or 1</td></tr>
					<tr><td>fonts</td><td>string</td></tr>
					<tr><td>action</td><td>like or recommend</td></tr>
					<tr><td>layout</td><td>standard, box_count or button_count</td></tr>
					<tr><td>type</td><td>xfbml or iframe or html5</td></tr>
					<tr><td>ref</td><td>string</td></tr>
				</tbody>
				<tfoot>
					<tr><th colspan="2">[AWD_likebutton option="value"]</th></tr>
				</tfoot>
			</table>  
		',
		'widget_no_display' => true
	)
);
?>