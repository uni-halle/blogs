<?php
/**
 * 
 * @author alexhermann
 *
 */
class AWD_facebook_widget extends WP_Widget {
	
	/**
	 * Facebook AWD instance
	 */
	protected $AWD_facebook;
	
	/**
	 * string $id
	 */
	public $id_base;
	
	/**
	 * Array $model
	 */
	protected $model;
	
	/**
	 * mixed $self_callback
	 */
	protected $self_callback;
	
	/**
	 * string $plugin_text_domain
	 */
	protected $plugin_text_domain;
	
	/**
	 * Constructor widget
	 */
	public function __construct($options=array())
	{
		global $AWD_facebook;
			
		$default = array(
			'name'			=> '',
			'id_base'		=> '',
			'description' 	=> '',
			'model' 		=> array(),
			'self_callback' => array($this, 'content'),
			'preview'		=> false,
			'text_domain' 	=> ''
		);
		$options = wp_parse_args($options, $default);

		$this->AWD_facebook = $AWD_facebook;
		$this->name = $options['name'];
		$this->id_base = $options['id_base'];
		$this->model = $options['model'];
		$this->self_callback = $options['self_callback'];
		$this->ptd = $options['text_domain'];
		$this->preview = $options['preview'];
				
        parent::WP_Widget($this->id_base, $this->name , $options);
    }
    
    /**
	 * Form
	 */
	public function form($instance)
	{
	
		$form = new AWD_facebook_form($this);
		$instance = $this->default_instance($instance);
		$html = '<div class="AWD_facebook_wrap">';
		$html .= $form->proccessFields($this->id_base, $this->model, $instance);
		$html .= '</div>';
		//hack for css classes bootstrap.
		$html = str_replace(array('span1','span2','span3','span4','span5','span6','span7','span8','span9','span10','span11'),'span3', $html);
		
		if($this->preview){
			//adjust settings to fit widget size.
			$html .= '<h2>Preview</h2>';
			$instance['width'] = 218;
			$instance['type'] = 'iframe';
			$html .= call_user_func_array($this->self_callback, array($instance));
		}
		echo $html;
	}
	
	/**
	 * Update
	 */
	public function update($new_instance, $old_instance)
	{	
		return stripslashes_deep($new_instance);
	}
	
	/**
	 * Content
	 */
	public function widget($args, $instance)
	{
		extract($args);
		$instance = $this->default_instance($instance);		
		echo $before_widget;
		if($instance['widget_title']){
			echo $before_title . $instance['widget_title'] . $after_title;
		}
		echo call_user_func_array($this->self_callback, array($instance));
		echo $after_widget;
    }
    
    /**
     * DefaultOptions
     */
    public function default_instance($instance)
    {
    	if(!is_array($instance))
    		$instance = array();
    	if(isset($this->AWD_facebook->options[$this->id_base])){
    		if(!isset($this->AWD_facebook->options[$this->id_base]['widget_title'])){
    			$this->AWD_facebook->options[$this->id_base]['widget_title'] = '';
    		}
    		$instance = wp_parse_args($instance, $this->AWD_facebook->options[$this->id_base]);
    	}
    	return $instance;
    }
    
    /**
     * Default Callback function
     */
    public function content($instance)
    {
		throw new RuntimeException('You must define a valid callback in the widget Facebook AWD'.$this->id_base);
    }
    
}
?>