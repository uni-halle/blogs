<?php
/**
 * AWD_facebook_activity
 *
 * This class generate activity box for facebook.
 *
 * @package facebook-awd
 * @author AHWEBDEV (Alexandre Hermann)
 */
class AWD_facebook_activity
{
	/**
	 * Domain
	 *
	 * A comma separated list of domains to show activity for.
	 * The XFBML version defaults to the current domain
	 * @var string
	 */
	protected $domain;

	/**
	 * Width 
	 * 
	 * The width of the plugin in pixels. Default width: 300px
	 * @var integer
	 */
	protected $width = 300;

	/**
	 * Height 
	 * 
	 * The height of the plugin in pixels. Default height: 300px
	 * @var integer
	 */
	protected $height = 300;

	/**
	 * Header 
	 * 
	 * Specifies whether to show the Facebook header
	 * @var boolean
	 */
	protected $header;

	/**
	 * Colorscheme 
	 * 
	 * The color scheme for the plugin. Options: 'light', 'dark'
	 * @var string
	 */
	protected $colorscheme;

	/**
	 * Font 
	 *
	 * the font to display in the plugin. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	 * @var string
	 */
	protected $font;

	/**
	 * Border Color
	 *  
	 * the border color of the plugin
	 * @var string
	 */
	protected $border_color;

	/**
	 * Recommendations
	 * 
	 * specifies whether to always show recommendations in the plugin.
	 * If recommendations is set to true, the plugin will display recommendations in the bottom half
	 * @var string
	 */
	protected $recommendations;

	/**
	 * Filter 
	 * 
	 * Allows you to filter which URLs are shown in the plugin.
	 * The plugin will only include URLs which contain the filter string in the first two path parameters of the URL.
	 * If nothing in the first two path parameters of the URL matches the filter,
	 * the URL will not be included. 
	 * For example, if the 'domain' parameter is set to 'www.example.com' and the 'filter' parameter was set to '/section1/section2' 
	 * then only pages which matched 'http://www.example.com/section1/section2/*' would be included in the activity feed section of this plugin.
	 * The filter parameter does not apply to any recommendations which may appear in this plugin (see above); Recommendations are based only on 'domain' parameter
	 * @var string
	 */
	protected $filter;

	/**
	 * Linktarget 
	 * 
	 * This specifies the context in which content links are opened.
	 * By default all links within the plugin will open a new window.
	 * If you want the content links to open in the same window, you can set this parameter to _top or _parent.
	 * Links to Facebook URLs will always open in a new window.
	 * @var string
	 */
	protected $linktarget;

	/**
	 * Ref
	 *  
	 * Must be a comma separated list, consisting of at most 5 distinct items, each of length at least 1 and at most 15 characters drawn from the set '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_'.
	 * Specifying a value for the ref attribute adds the 'fb_ref' parameter to the any links back to your domain which are clicked from within the plugin.
	 * Using different values for the ref parameter for different positions and configurations of this plugin within your pages allows you to track which instances are performing the best.
	 * @var string
	 */
	protected $ref;

	/**
	 * Max Age 
	 * 
	 * The maximum age of a URL to show in the plugin.
	 * The default is 0 (we don’t take age into account).
	 * Otherwise the valid values are 1-180, which specifies the number of days.
	 * For example, if you specify '7' the plugin will only show URLs which were created in the past week.
	 * @var integer
	 */
	protected $max_age;

	/**
	 * Type 
	 * 
	 * type to use, xfbml, html5, or iframe
	 * @var string
	 */
	protected $type;

	/**
	 * Constructor
	 * 
	 * @param array $options
	 */
	public function __construct($options)
	{
		$this->setDomain($options['domain']);
		$this->setWidth($options['width']);
		$this->setHeight($options['height']);
		$this->setHeader($options['header']);
		$this->setColorscheme($options['colorscheme']);
		$this->setFont($options['font']);
		$this->setBorderColor($options['border_color']);
		$this->setRecommendations($options['recommendations']);
		$this->setFilter($options['filter']);
		$this->setLinktarget($options['linktarget']);
		$this->setRef($options['ref']);
		$this->setMaxAge($options['max_age']);
		$this->setType($options['type']);
	}

	/**
	 * Set Domain
	 * 
	 * @param string $domain
	 */
	public function setDomain($domain)
	{
		if (empty($domain))
			throw new Exception('Url for Activity box can not be empty');
		$this->domain = $domain;
	}

	/**
	 * Set Width
	 * 
	 * @param integer $width
	 */
	public function setWidth($width)
	{
		$this->width = $width;
	}

	/**
	 * Set Height
	 * 
	 * @param integer $height
	 */
	public function setHeight($height)
	{
		$this->height = $height;
	}

	/**
	 * Set Header
	 * 
	 * @param boolean $header
	 */
	public function setHeader($header)
	{
		$this->header = $header;
	}

	/**
	 * Set Colorscheme
	 * 
	 * @param string $colorscheme
	 */
	public function setColorscheme($colorscheme)
	{
		$this->colorscheme = $colorscheme;
	}

	/**
	 * Setter: font
	 * @param String $font
	 */
	public function setFont($font)
	{
		$this->font = $font;
	}

	/**
	 * Setter: border_color
	 * @param String $border_color
	 */
	public function setBorderColor($border_color)
	{
		$this->border_color = $border_color;
	}

	/**
	 * Setter: recommendations
	 * @param String $recommendations
	 */
	public function setRecommendations($recommendations)
	{
		$this->recommendations = $recommendations;
	}

	/**
	 * Setter: filter
	 * @param String $filter
	 */
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}

	/**
	 * Setter: linktarget
	 * @param String $linktarget
	 */
	public function setLinktarget($linktarget)
	{
		$this->linktarget = $linktarget;
	}

	/**
	 * Setter: ref
	 * @param String $ref
	 */
	public function setRef($ref)
	{
		$this->ref = $ref;
	}

	/**
	 * Setter: max_age
	 * @param String $max_age
	 */
	public function setMaxAge($max_age)
	{
		$this->max_age = $max_age;
	}

	/**
	 * Setter: type
	 * @param String $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Getter: domain
	 * @return String
	 */
	public function getDomain()
	{
		return $this->domain;
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
	 * Getter: header
	 * @return String
	 */
	public function getHeader()
	{
		return $this->header;
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
	 * Getter: font
	 * @return String
	 */
	public function getFont()
	{
		return $this->font;
	}

	/**
	 * Getter: border_color
	 * @return String
	 */
	public function getBorderColor()
	{
		return $this->border_color;
	}

	/**
	 * Getter: recommendations
	 * @return String
	 */
	public function getRecommendations()
	{
		return $this->recommendations;
	}

	/**
	 * Getter: filter
	 * @return String
	 */
	public function getFilter()
	{
		return $this->filter;
	}

	/**
	 * Getter: linktarget
	 * @return String
	 */
	public function getLinktarget()
	{
		return $this->linktarget;
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
	 * Getter: max_age
	 * @return String
	 */
	public function getMaxAge()
	{
		return $this->max_age;
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
	 * Getter: activity
	 * @return activity html/xfbml/iframe
	 */
	public function get()
	{
		if ($this->type == 'iframe') {
			return $this->activityIframe();
		} else if ($this->type == 'html5') {
			return $this->activityHtml5();
		} else if ($this->type == 'xfbml') {
			return $this->activityXfbml();
		}
		return false;

	}

	//******VIEWS******//
	public function activityHtml5()
	{
		return '<div class="fb-activity" data-site="' . $this->domain . '" data-width="' . $this->width . '" data-height="' . $this->height . '" data-header="' . $this->header . '" data-colorscheme="' . $this->colorscheme . '" data-linktarget="' . $this->linktarget . '" data-border-color="' . $this->border_color . '" data-font="' . $this->font . '" data-recommendations="' . $this->recommendations . '" data-max_age="' . $this->max_age . '" data-ref="' . $this->ref . '" data-filter="'
				. $this->filter . '"></div>';
	}
	public function activityXfbml()
	{
		return '<fb:activity site="' . $this->domain . '" width="' . $this->width . '" height="' . $this->height . '" header="' . $this->header . '" colorscheme="' . $this->colorscheme . '" linktarget="' . $this->linktarget . '" border-color="' . $this->border_color . '" font="' . $this->font . '" recommendations="' . $this->recommendations . '" max_age="' . $this->max_age . '" ref="' . $this->ref . '" filter="' . $this->filter . '"></fb:activity>';
	}
	public function activityIframe()
	{
		return '<iframe src="http://www.facebook.com/plugins/activity.php?site=' . $this->domain . '&amp;width=' . $this->width . '&amp;height=' . $this->height . '&amp;header=' . $this->header . '&amp;colorscheme=' . $this->colorscheme . '&amp;border_color=' . urlencode($this->border_color) . '&amp;recommendations=' . $this->recommendations . '&amp;linktarget=' . $this->linktarget . '&amp;max_age=' . $this->max_age . '&amp;ref=' . $this->ref . '&amp;filter=' . $this->filter
				. '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $this->width . 'px; height:' . $this->height . 'px;" allowTransparency="true"></iframe>';
	}
}
?>