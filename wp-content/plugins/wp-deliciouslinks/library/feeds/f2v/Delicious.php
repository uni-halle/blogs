<?php
class f2v_Delicious extends b8v_Http {
	// keep track of when last command finished, to throttle calls. made static to make sure it applies to all instances
	private static $lastCommandTime = 0;
	public function get()
	{
		set_time_limit(300);
		if(self::$lastCommandTime+10>time())
		{
			sleep(1);
		}
		$return = parent::get();
		self::$lastCommandTime = time();
		return $return;

	}
	public function add_post($url,$description,$extended = null,$tags = null,$dt = null,$replace = true,$shared = true)
	{
		$data = array(); 
		$data[] = "url=$url";
		$data[] = "description=$description";
		if(null!==$extended)
		{
			$data[] = "extended=$extended";
		}
		if(null!==$tags)
		{
			foreach($tags as $key=>$value)
			{
				$tags[$key] = urlencode(strtolower(str_replace(' ','-',$value)));
			}
			$data[] = "tags=".implode('+',$tags);
		}
		if(null!==$dt)
		{
			$dt=date('Y-m-d',strtotime($dt)).'T'.date('H:m:s',strtotime($dt))."Z";
			$data[] = "dt=$dt";
		}
		if($replace===false)
		{
			$data[] = "replace=no";
		}
		if($shared===false)
		{
			$data[] = "shared=no";
		}
		$this->url('http://api.del.icio.us/v1/posts/add');
		$this->data(implode('&',$data));
		$this->tag->get('post',$this->get());
		return true;
			}
	public function get_recent_posts()
	{
		throw new Exception('Not yet implemented');
		$this->url('http://api.del.icio.us/v1/posts/recent');
		return $this->get();
	}

	public function get_update_posts()
	{
		$this->url('http://api.del.icio.us/v1/posts/update');
		$return = $this->get();
		$return = $this->tag->get('update',$return);
		$return = $return[0]['attributes'];
		return $return;
	}
	public function get_add_posts($url,$description,$extended,$tags,$dt,$replace="no",$shared="no")
	{
		throw new Exception('Not yet implemented');
		$this->url('http://api.del.icio.us/v1/posts/add?');
		return $this->get();
	}
	public function get_delete_posts($url)
	{
		throw new Exception('Not yet implemented');
		$this->url('http://api.del.icio.us/v1/posts/delete?');
		return $this->get();
	}
	public function get_posts($hashes)
	{
		$this->url('http://api.del.icio.us/v1/posts/get');
		$this->data('hashes='.$hashes.'&meta=yes');
		$return = $this->justattribs($this->tag->get('post',$this->get()));
		return $return;
	}
	public function get_dates_posts($tag)
	{
		throw new Exception('Not yet implemented');
		$this->url('http://api.del.icio.us/v1/posts/dates?');
		return $this->get();
	}
	public function get_all_posts($tag = array())
	{
		if(!is_array($tag))
		{
			$tag=(array)$tag;
		}
		$data = array(); 
		$data[]='tag='.implode('+',$tag);
		$this->url('http://api.del.icio.us/v1/posts/all');
		$this->data(implode('&',$data));
		$return = $this->justattribs($this->tag->get('post',$this->get()));
		return $return;
	}
	public function get_all_hashes_posts()
	{
		$this->url('http://api.del.icio.us/v1/posts/all');
		$this->data('hashes');
		$return = $this->justattribs($this->tag->get('post',$this->get()));
		return $return;
	}
	private function justattribs($tags)
	{
		$return = array();
		foreach($tags as $tag)
		{
			$return[]=$tag['attributes'];
		}
		return $return;
	}
	private $tag = null;
	public function get_tags()
	{
		$this->url('http://api.del.icio.us/v1/tags/get');
		$return = $this->justattribs($this->tag->get('post',$this->get()));
		return $return;
	}
	public function __construct()
	{
		parent::__construct();
		$this->tag = new b8v_Tag();
	}
}
