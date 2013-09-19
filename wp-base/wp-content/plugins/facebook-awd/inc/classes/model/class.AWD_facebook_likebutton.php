<?php 
/**
 * 
 * @author alexhermann
 *
 */
class AWD_facebook_likebutton
{

	/**
	 * href 
	 * the URL to like. The XFBML version defaults to the current page
	 */
	protected $href;
	/**
	 * send 
	 * specifies whether to include a Send button with the Like button. This only works with the XFBML version
	 */
	protected $send;
	/**
	 * layout 
	 * there are three options
	 *   standard - displays social text to the right of the button and friends' profile photos below. Minimum width: 225 pixels. Minimum increases 
	 *              by 40px if action is 'recommend' by and increases by 60px if send is 'true'. Default width: 450 pixels. Height: 35 pixels (without photos) or 80 pixels (with photos).
	 *   button_count - displays the total number of likes to the right of the button. Minimum width: 90 pixels. Default width: 90 pixels. Height: 20 pixels
	 *   box_count - displays the total number of likes above the button. Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels
	 */
	protected $layout;
	/**
	 * show_faces 
	 * specifies whether to display profile photos below the button (standard layout only)
	 */
	protected $show_faces;
	/**
	 * width 
	 * the width of the Like button
	 */
	protected $width;
	/**
	 * height 
	 * the verb to display on the button. Options: 'like', 'recommend'
	 */
	protected $height;
	/**
	 * action 
	 * the width of the plugin in pixels. Default width: 300px
	 */
	protected $action = 'like';
	/**
	 * font 
	 * the font to display in the button. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	 */
	protected $font;
	/**
	 * colorscheme 
	 * the color scheme for the like button. Options: 'light', 'dark'
	 */
	protected $colorscheme;
	/**
	 * ref 
	 * a label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_). The ref attribute causes two parameters to be added to the referrer URL when a user clicks a link from a stream story about a Like action:
     *   fb_ref - the ref parameter
     *   fb_source - the stream type ('home', 'profile', 'search', 'other') in which the click occurred and the story type ('oneline' or 'multiline'), concatenated with an underscore.
     *
	 */
	protected $ref;
	/**
	 * type 
	 * template to use, xfbml, html5, or iframe
	 */
	protected $type;
	
	/**
	 * Constructor
	 * @param array $options
	 */ 
 	public function __construct($options)
 	{
		$this->setHref($options['href']);
		$this->setSend($options['send']);
		$this->setLayout($options['layout']);
		$this->setShowFaces($options['show_faces']);
		$this->setWidth($options['width']);
		$this->setHeight($options['height']);
		$this->setAction($options['action']);
		$this->setFont($options['font']);
		$this->setColorscheme($options['colorscheme']);
		$this->setRef($options['ref']);
		$this->setType($options['type']);
 	}

	/**
	 * Setter: href
	 * @param String $href
	 * @return void
	 */
	public function setHref( $href )
	{
		if(empty($href))
			throw new Exception('Url for Like button can not be empty');
		$this->href = $href;
	}
	
	/**
	 * Setter: send
	 * @param String $send
	 * @return void
	 */
	public function setSend( $send )
	{
		$this->send = $send;
	}
	
	/**
	 * Setter: layout
	 * @param String $layout
	 * @return void
	 */
	public function setLayout( $layout )
	{
		$this->layout = $layout;
	}
	
	/**
	 * Setter: show_faces
	 * @param String $show_faces
	 * @return void
	 */
	public function setShowFaces( $show_faces )
	{
		$this->show_faces = $show_faces;
	}
	
	/**
	 * Setter: width
	 * @param String $width
	 * @return void
	 */
	public function setWidth( $width )
	{
		$this->width = $width;
	}
	
	/**
	 * Setter: height
	 * @param String $height
	 * @return void
	 */
	public function setHeight( $height )
	{
		$this->height = $height;
	}

	/**
	 * Setter: action
	 * @param String $action
	 * @return void
	 */
	public function setAction( $action )
	{
		$this->action = $action;
	}
	
	/**
	 * Setter: font
	 * @param String $font
	 * @return void
	 */
	public function setFont( $font )
	{
		$this->font = $font;
	}
	
	/**
	 * Setter: colorscheme
	 * @param String $colorscheme
	 * @return void
	 */
	public function setColorscheme( $colorscheme )
	{
		$this->colorscheme = $colorscheme;
	}
	
	/**
	 * Setter: ref
	 * @param String $ref
	 * @return void
	 */
	public function setRef( $ref )
	{
		$this->ref = $ref;
	}
	
	/**
	 * Setter: template
	 * @param String $template
	 * @return void
	 */
	public function setType( $type )
	{
		$this->type = $type;
	}

	/**
	 * Getter: href
	 * @return String
	 */
	public function getHref()
	{
		return $this->href;
	}
	
	/**
	 * Getter: send
	 * @return String
	 */
	public function getSend()
	{
		return $this->send;
	}
	
	/**
	 * Getter: layout
	 * @return String
	 */
	public function getLayout()
	{
		return $this->layout;
	}
	
	/**
	 * Getter: show_faces
	 * @return String
	 */
	public function getShowFaces()
	{
		return $this->show_faces;
	}
	
	/**
	 * Getter: width
	 * @return String
	 */
	public function getWidth()
	{
		return $this->width;
	}
	
	/**
	 * Getter: height
	 * @return String
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * Getter: action
	 * @return String
	 */
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	 * Getter: font
	 * @return String
	 */
	public function getFont()
	{
		return $this->font;
	}
	
	/**
	 * Getter: colorscheme
	 * @return String
	 */
	public function getColorscheme()
	{
		return $this->colorscheme;
	}
	
	/**
	 * Getter: ref
	 * @return String
	 */
	public function getRef()
	{
		return $this->ref;
	}
	 
	/**
	 * Getter: type
	 * @return String
	 */
	public function getType()
	{
		return $this->type;
	}

	
	/**
	 * Getter: likebutton
	 * @return likebutton html/xfbml/iframe
	 */
	public function get()
	{	
		if($this->type == 'iframe')
		{
			return $this->likeButtonIframe();
		}
		else if($this->type == 'html5')
		{
			return $this->likeButtonHtml5();
		}
		else if($this->type == 'xfbml')
		{
			return $this->likeButtonXfbml();
		}
		return false;
	}
	
	//******VIEWS******//
	protected function likeButtonHtml5()
	{
		return '<div class="fb-like" data-href="'.$this->href.'" data-send="'.$this->send.'" data-layout="'.$this->layout.'" data-width="'.$this->width.'" data-show-faces="'.$this->show_faces.'" data-action="'.$this->action.'" data-colorscheme="'.$this->colorscheme.'" data-font="'.$this->font.'" data-ref="'.$this->ref.'"></div>';
	}
	protected function likeButtonXfbml()
	{
		return '<fb:like href="'.$this->href.'" send="'.$this->send.'" width="'.$this->width.'" colorscheme="'.$this->colorscheme.'" layout='.$this->layout.' show_faces="'.$this->show_faces.'" font="'.$this->font.'" action="'.$this->action.'" ref="'.$this->ref.'"></fb:like>';
	}
	protected function likeButtonIframe()
	{
		return '<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($this->href).'&amp;send='.$this->send.'&amp;layout='.$this->layout.'&amp;width='.$this->width.'&amp;show_faces='.$this->show_faces.'&amp;action='.$this->action.'&amp;colorscheme='.$this->colorscheme.'&amp;font='.$this->font.'&amp;height='.$this->height.'&ref='.urlencode($this->ref).'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$this->width.'px; height:'.$this->height.'px;" allowTransparency="true"></iframe>';
	}
}
?>