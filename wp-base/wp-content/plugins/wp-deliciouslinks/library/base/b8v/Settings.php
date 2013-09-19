<?php
if (! defined ( 'MARKDOWN_VERSION' )) {
	require_once dirname ( dirname ( __FILE__ ) ) . '/external/PHP Markdown 1.0.1n/markdown.php';
}
class b8v_Settings extends b8v_Controller_Action {
	private $settings = null;
	public function __construct($application) {
		parent::__construct ( $application );
		$plugin_file = dirname ( $this->application ()->filename () ) . '/application/Settings/Settings.ini';
		$libraries = $this->libraries ();
		$return ['libraries'] = array ();
		foreach ( $libraries as $library ) {
			$library_file = $library . '/Settings/Settings.ini';
			if (file_exists ( $library_file )) {
				$obj = new b8v_Data_INI ( $this->application (), $library_file );
				$ini = $obj->load ()->getArray ();
				$return ['libraries'] [dirname ( $library_file )] = $ini;
			}
		}
		uasort ( $return ['libraries'], array ($this, 'cmp' ) );
		$obj = new b8v_Data_INI ( $this->application (), $plugin_file );
		$ini = $obj->load ()->getArray ();
		$ini ['lib_name'] = 'application';
		$return ['libraries'] [dirname ( $plugin_file )] = $ini;
		$return ['combined'] = array ();
		foreach ( $return ['libraries'] as $library ) {
			foreach ( $library as $key => $value ) {
				if ($key == 'tags') {
					$value = explode ( ',', $value );
					if (isset ( $return ['combined'] [$key] )) {
						foreach ( $return ['combined'] [$key] as $tkey => $tvalue ) {
							$value [$tkey] = $tvalue;
						}
					}
				}
				$return ['combined'] [$key] = $value;
			}
		}
		if (isset ( $return ['combined'] [$key] )) {
			$return ['combined'] ['tags'] = array_unique ( $return ['combined'] ['tags'] );
			sort ( $return ['combined'] ['tags'] );
		}
		$return ['combined'] ['settings_folders'] = array_keys ( $return ['libraries'] );
		$return ['combined'] ['libraries'] = array ();
		foreach ( $return ['combined'] ['settings_folders'] as $library ) {
			$name = explode ( '/', $library );
			$return ['combined'] ['libraries'] [] = $name [count ( $name ) - 2];
		}
		unset ( $return ['combined'] ['priority'] );
		$this->settings = $return ['combined'];
		if (! isset ( $this->settings ['slug'] )) {
			if(isset ( $this->settings ['wpslug'] ))
			{
				$this->settings ['slug']=$this->settings ['wpslug'];
			}
			else
			{
				$this->settings ['slug']=strtolower ( $this->settings ['name'] );
			}
			$this->settings ['slug'] = str_replace ( 'wp-', '', $this->settings ['slug'] );
			$this->settings ['slug'] = str_replace ( '-', '', $this->settings ['slug'] );
			$this->settings ['slug'] = str_replace ( '', '', $this->settings ['slug'] );
		}
		$this->ConfigFiles ();
	}
	public function ConfigFiles() {
		if (! b8v_Debug::dodebug () || basename($this->application()->filename())=='application') {
			return;
		}
		$this->view->options = $this->get_full ();
		$this->view->preloads = trim ( implode ( "','", explode ( ',', $this->view->options ['preload'] ) ) );
		if ($this->view->preloads != '') {
			$this->view->preloads = "'" . $this->view->preloads . "'";
		}
		$page2 = '<?php ' . $this->renderScript ( 'Common/plugin.phtml' );
		$page = $this->renderScript ( 'Common/readme.phtml' );
		$plugin = $this->application ()->filename ();
		$readme = dirname ( $plugin ) . '/readme/readme.txt';
		if (file_get_contents ( $readme ) != $page) {
			file_put_contents ( $readme, $page );
		}
		if (file_get_contents ( $plugin ) != $page2) {
			file_put_contents ( $plugin, $page2 );
		}
		return $page;
	}
	public function cmp($a, $b) {
		if (( int ) $a ['priority'] == $b ['priority']) {
			return 0;
		}
		return (( int ) $a ['priority'] < ( int ) $b ['priority']) ? - 1 : 1;
	}
	public function get() {
		return $this->settings;
	}
	public function __get($key) {
		return $this->settings [$key];
	}
	private function faq($string) {
		$string .= "\n= ";
		$pattern = '/ (.*) =\n([\w\W]*)=/Ui';
		preg_match_all ( $pattern, $string, $matches, PREG_SET_ORDER );
		$return = null;
		foreach ( ( array ) $matches as $match ) {
			if (null === $return) {
				$return = array ();
			}
			$return [trim ( $match [1] )] = trim ( $match [2] );
		}
		return $return;
	}
	public function get_full($html = false) {
		$phtml = array ();
		$phtml_ext = array ();
		$this->view->options = $this->settings;
		$fs = new b8v_FS($this->application());
		foreach ( $this->settings ['settings_folders'] as $folder ) {
			$d = dir ( $folder . '/Views/' );
			$return = array ();
			
			while ( false !== ($entry = $d->read ()) ) {
				$name = $d->path . $entry;
				if ($fs->fnmatch ( '*.phtml', $entry )) {
					$pi = pathinfo ( $name );
					$fname = $pi ['filename'];
					$index = $fname;
					if ($fs->fnmatch ( '*_ext.phtml', $entry )) {
						$index = substr ( $fname, 0, strlen ( $fname ) - 4 );
					}
					if (! isset ( $phtml [$index] )) {
						$phtml [$index] = '';
					}
					if (! isset ( $phtml_ext [$index] )) {
						$phtml_ext [$index] = '';
					}
					if ($fs->fnmatch ( '*_ext.phtml', $entry )) {
						$phtml_ext [$index] .= $this->renderScript ( $name );
					} else {
						$phtml [$index] .= $this->renderScript ( $name );
					}
				}
			}
			$d->close ();
		}
		foreach ( $phtml_ext as $key => $value ) {
			$phtml [$key] .= $value;
		}
		$return = $this->settings;
		$sections = array ();
		$adhoc = array ();
		foreach ( $phtml as $key => $value ) {
			if ($html) {
				$value = Markdown ( $value );
			}
			$order = array ('Description', 'Installation', 'Frequently Asked Questions', 'Screenshots', 'Changelog', 'Upgrade Notice' );
			if (in_array ( $key, $order )) {
				$sections [$key] = $value;
			
			} else {
				$adhoc [$key] = $value;
			}
		}
		$return ['sections'] = array ();
		foreach ( $order as $key ) {
			$return ['sections'] [$key] = $sections [$key];
		}
		foreach ( $adhoc as $key => $value ) {
			$return ['sections'] [$key] = $value;
		}
		unset ( $return ['sections'] ['Copyright'] );
		$return ['sections'] ['Copyright'] = $adhoc ['Copyright'];
		return $return;
	}
	private function libraries() {
		$library_folder = dirname ( $this->application ()->filename () ) . '/library/';
		$d = dir ( $library_folder );
		$return = array ();
		while ( false !== ($entry = $d->read ()) ) {
			$name = $d->path . $entry;
			if (is_dir ( $name ) && ! in_array ( $entry, array ('.', '..', '.svn' ) )) {
				$return [] = $name;
			}
		}
		$d->close ();
		return $return;
	}
	public function __set($key, $value) {
	}
}