<?php 
/**
 * 
 * @author alexhermann
 *
 */
class AWD_facebook_form
{

	protected $id;
	protected $prefix;
	protected $method = 'GET';
	protected $action;
	protected $widget;
	 
	public function __construct($id=null, $method=null, $action=null, $prefix=null)
	{
		$this->id = $id;
		$this->method = $method;
		$this->action = $action;
		$this->prefix = $prefix;
		if($this->isWidget($this->id)){
			$this->id = null;
			$this->widget = $id;
			$this->prefix = null;
		}
	}
	
	public function isWidget($widget = null)
	{
		if(is_object($widget))
			return is_subclass_of($widget,'WP_Widget');
		
		return is_subclass_of($this->widget,'WP_Widget');
	}
	
	public function start()
	{
		$html =
		'
		<form action="'.$this->action.'" method="'.$this->method.'" id="'.$this->prefix.$this->id.'" name="'.$this->prefix.$this->id.'">
		';
		return $html;
	}
	
	public function end()
	{
		$html =
		'
		</form>
		';
		return $html;
	}
	
	public function addInputCheckBox($id, $value, $class='', $attrs = array())
	{
		$field_id = $this->getFieldId($id);
		$html = '<input type="checkbox" id="'.$field_id.'" '.($value == 1 ? 'checked="checked"' : '').'name="'.$this->prefix.$id.'" value="1" '.$this->processAttr($attrs).' />';
		return $html;
	}

	public function addInputHidden($id, $value, $class='', $attrs = array())
	{
		$field_id = $this->getFieldId($id);
		$html = '<input type="hidden" id="'.$field_id.'" name="'.$this->prefix.$id.'" value="'.$value.'" '.$this->processAttr($attrs).' />';
		return $html;
	}
	
	public function addInputTextArea($label, $id, $value, $class='', $attrs = array())
	{
		$field_id = $this->getFieldId($id);
		$html ='
		<div class="'.$class.'">
			<label for="'.$field_id.'">'.$label.'</label>
			<textarea id="'.$field_id.'" name="'.$this->prefix.$id.'" '.$this->processAttr($attrs).'>'.$value.'</textarea>
		</div>';
		return $html;
	}
	
	public function addInputText($label, $id, $value, $class='', $attrs = array(), $prepend = '',$append ='')
	{
		$field_id = $this->getFieldId($id);
		$html ='
		<div class="'.$class.'">';
			if($label != ''){
				$html .='
				<label for="'.$field_id.'">'.$label.'</label>';
			}
			if($prepend != '' OR $append){
				$html .= '
				<div class="'.($append != '' ? 'input-append' : '').' '.($prepend != '' ? 'input-prepend' : '').' ">';
			} 
			if($prepend != ''){
				$html .= '<span class="add-on"><i class="'.$prepend.'"></i></span>';
			}
			$html .= '<input type="text" id="'.$field_id.'" name="'.$this->prefix.$id.'" value="'.$value.'" '.$this->processAttr($attrs).' />';
			if($append != ''){
				$html .= $append;
			}
			if($prepend OR $append){
				$html .= '
				</div>';
			}
		$html .='
		</div>';
		return $html;
	}
	
	public function addSelect($label, $id, $options, $value ,$class='', $attrs = array())
	{
		$field_id = $this->getFieldId($id);
		$html ='
		<div class="'.$class.'">
			<label for="'.$field_id.'">'.$label.'</label>
			<select id="'.$field_id.'" name="'.$this->prefix.$id.'" ';
			$html .= $this->processAttr($attrs);
			$html .= '>
			';
			foreach($options as $option=>$info){
				$html .='<option value="'.$info['value'].'" '.($info['value'] == $value ? 'selected="selected"' : '').' >'.$info['label'].'</option>';
			}
			$html .='
			</select> 
		</div>';	
		return $html;
	}
	
	protected function processAttr($attrs)
	{
		$html = '';
		if(is_array($attrs) && count($attrs)){
			foreach($attrs as $attr=>$value){
				if($value != ''){
					$html .= $attr.'="'.$value.'" ';
				}
			}
		}
		return $html;
	}
	
	public function addMediaButton($label, $id, $value, $class='', $attrs = array(),$datas=array('data-title'=>'Upload Media', 'data-type'=> 'image'), $rm=false)
	{
		$field_id = $this->getFieldId($id);
		$html ='
		<div class="'.$class.'">
			<label for="'.$field_id.'">'.$label.'</label>
			<div class="input-append">
				<input type="text" id="'.$field_id.'" name="'.$this->prefix.$id.'" value="'.$value.'" '.$this->processAttr($attrs).' />
				<button class="btn AWD_button_media" type="button" '.$this->processAttr($datas).' data-field="'.$field_id.'"><i class="icon-upload"></i></button>';
				if($rm == true){
					$html .='<button class="btn btn-warning AWD_delete_media"><i class="icon-minus icon-white"></i></button>';
				}
			$html.='</div>
		</div>';
		return $html;
	}
	
	public function getFieldId($fieldname)
	{
		return $this->prefix.rtrim(str_replace(array('[',']','__'), '_', $fieldname),'_');	
	}
	
	public function proccessFields($fieldset_id, $fields, $widget_instance = null)
	{
		global $AWD_facebook;
		$html = '';
		if(count($fields)>0){
			foreach($fields as $id=>$field)
			{	
				//if we are in a widget, check if we need to display html or not.
				if($this->isWidget() && isset($field['widget_no_display'])){
					if($field['widget_no_display'] == true){
						continue;
					}
				}
				if(!$this->isWidget() && isset($field['widget_only'])){
					if($field['widget_only'] == true){
						continue;
					}
				}
				
				
				//get the value of the field only if it's not a html content
				if($field['type'] != 'html'){
					//if we are in widget mode, we must redefine the name of field, and the associated values
					if($this->isWidget()){
						$fieldname = $this->widget->get_field_name($id);
						$value = $widget_instance[$id];
					}else{
						$fieldname = $fieldset_id.'['.$id.']';
						$value = $AWD_facebook->options[$fieldset_id][$id];
					}
				}
				$help = !$this->isWidget() ? $AWD_facebook->get_the_help($id) : '';
				
				switch($field['type'])
				{
					
					case 'select':
						$html.= $this->addSelect($field['label'].' '.$help, $fieldname, $field['options'], $value, $field['class'], $field['attr']);
					break;
					case 'text':
						$html.= $this->addInputText($field['label'].' '.$help, $fieldname, $value, $field['class'], $field['attr']); 
					break;	
					case 'html':
						$html.= $field['html'];
					break;	
					case 'media':
						$html.= $this->addMediaButton($field['label'].' '.$help, $fieldname, $value, $field['class'], $field['attr']); 
					break;
				}
			}
		}
		return $html;
	}
	
}