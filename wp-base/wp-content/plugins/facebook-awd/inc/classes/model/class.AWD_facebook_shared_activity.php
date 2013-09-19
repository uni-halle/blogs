<?php
/**
 * 
 * @author alexhermann
 *
 */

class AWD_facebook_shared_activity
{
	/**
	 * width 
	 * the width of the plugin in pixels. Default width: 300px
	 */
	protected $width = 300;

	/**
	 * height 
	 * the height of the plugin in pixels. Default height: 300px
	 */
	protected $height = 300;

	/**
	 * font 
	 * the font to display in the plugin. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	 */
	protected $font;

	/**
	 * Constructor
	 * @param array $options
	 */
	public function __construct($options)
	{
		$this->setWidth($options['width']);
		$this->setHeight($options['height']);
		$this->setFont($options['font']);
	}

	/**
	 * Setter: width
	 * @param String $width
	 * @return void
	 */
	public function setWidth($width)
	{
		$this->width = $width;
	}

	/**
	 * Setter: height
	 * @param String $height
	 * @return void
	 */
	public function setHeight($height)
	{
		$this->height = $height;
	}

	/**
	 * Setter: font
	 * @param String $font
	 * @return void
	 */
	public function setFont($font)
	{
		$this->font = $font;
	}

	/**
	 * Getter: activity
	 * @return activity html/xfbml/iframe
	 */
	public function get()
	{
		return $this->activityHtml5();
	}

	//******VIEWS******//
	public function activityHtml5()
	{
		return '<div class="fb-shared-activity" data-width="'.$this->width.'" data-height="'.$this->height.'" data-font="'.$this->font.'"></div>';
	}
}
?>