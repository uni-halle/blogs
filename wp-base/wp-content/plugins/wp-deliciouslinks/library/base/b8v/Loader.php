<?php
class b8v_Loader extends b8v_base {
	private $_includepath = null;
	private static $_include_path = null;
	private $_subfolders = null;
	private static $roots = array ();
	
	private function sanitize_path($path) {
		return rtrim ( $path, DIRECTORY_SEPARATOR );
	}
	
	public function includepath($folders = null,$reverse=false) {
		if (null === $this->_includepath) {
			$this->set_includepath ();
		}
		if (null !== $folders) {
			$dirs = array ();
			foreach ( $this->_includepath as $path ) {
				foreach ( ( array ) $folders as $folder ) {
					$newfolder = $path . DIRECTORY_SEPARATOR . $this->sanitize_path ( $folder );
					if (is_dir ( $newfolder )) {
						$dirs [$newfolder] = $newfolder;
					}
				}
			}
			if($reverse)
			{
				$dirs = array_reverse($dirs);
			}
			return $dirs;
		}
		return $this->_includepath;
	}
	public static function getDirs($subdirs = null) {
		if (null === self::$_include_path) {
			$top = array ('library', 'application','application/Models' );
			self::$_include_path = array ();
			$first = true;
			foreach ( self::roots as $root ) {
				foreach ( $top as $t ) {
					if ($first || strpos ( $t, 'library' ) !== 0) {
						$dir = $root . DIRECTORY_SEPARATOR . $t . DIRECTORY_SEPARATOR;
						if (is_dir ( $dir )) {
							self::$_include_path [] = $dir;
						}
					}
				}
				$first = false;
			}
		}
		if (null !== $subdirs) {
			$dirs = array ();
			foreach ( self::$_include_path as $dir ) {
				foreach ( ( array ) $subdirs as $sdir ) {
					if (is_dir ( $dir . $sdir )) {
						$dirs [] = $dir . $sdir;
					}
				}
			}
			return $dirs;
		}
		return self::$_include_path;
	}
	
	private function set_includepath() {
		$this->_includepath = array ();
		$path = $this->sanitize_path ( dirname ( $this->application ()->filename () ) );
		$root = dirname ( $path );
		$this->_includepath [$root] = $root;
		$this->_includepath [$path] = $path;
		foreach ( $this->subfolders () as $subfolder ) {
			$newfolder = $path . DIRECTORY_SEPARATOR . $subfolder;
			if (is_dir ( $newfolder )) {
				$this->_includepath [$newfolder] = $newfolder;
			}
		}
		;
	}
	
	public function subfolders() {
		if (null === $this->_subfolders) {
			$this->set_subfolders ();
		}
		return $this->_subfolders;
	}
	
	private function set_subfolders() {
		$this->_subfolders = array ();
		$this->add_subfolder ( 'library/base' );
		$this->add_subfolder ( 'library/wordpress' );
		$this->add_subfolder ( 'library/survey' );
		$this->add_subfolder ( 'library/feeds' );
	}
	
	public function add_subfolder($folder) {
		$folder = $this->sanitize_path ( $folder );
		if (null === $this->_subfolders) {
			$this->set_subfolders ();
		}
		$this->_subfolders [$folder] = $folder;
		$this->_includepath = null;
	}
	public function load_class($class, $dirs = null) {
		if (class_exists ( $class, false )) {
			return;
		}
		if (null === $dirs) {
			$dirs = $this->includepath ();
		}
		$className = ltrim ( $class, '\\' );
		$file = '';
		$namespace = '';
		if ($lastNsPos = strripos ( $className, '\\' )) {
			$namespace = substr ( $className, 0, $lastNsPos );
			$className = substr ( $className, $lastNsPos + 1 );
			$file = str_replace ( '\\', DIRECTORY_SEPARATOR, $namespace ) . DIRECTORY_SEPARATOR;
		}
		$file .= str_replace ( '_', DIRECTORY_SEPARATOR, $className ) . '.php';		
		if (! empty ( $dirs )) {
			// use the autodiscovered path
			$dirPath = dirname ( $file );
			if (is_string ( $dirs )) {
				$dirs = explode ( PATH_SEPARATOR, $dirs );
			}
			foreach ( $dirs as $key => $dir ) {
				if ($dir == '.') {
					$dirs [$key] = $dirPath;
				} else {
					$dir = rtrim ( $dir, '\\/' );
					$dirs [$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
				}
			}
			$file = basename ( $file );
		} else {
			$dirs = self::getDirs ();
		}
		$incPath = get_include_path ();
		set_include_path ( implode ( PATH_SEPARATOR, $dirs ) );
		include_once $file;
		set_include_path ( $incPath );			
		if (! class_exists ( $class, false )) {
			throw new Exception ( "File \"$file\" does not exist or class \"$class\" was not found in the file" );
		}
	}
	
	public function find_file($filename, $quiet = false, $include_path = null) {
		if (null === $include_path) {
			$include_path = $this->includepath ();
		}
		if (file_exists ( $filename )) {
			return $filename;
		}
		foreach ( $include_path as $dir ) {
			if (file_exists (rtrim( $dir, DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR . $filename )) {
				return rtrim( $dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
			}
		}
		if (! $quiet) {
			throw new Exception ( $filename . ' Not Found ' . print_r ( $include_path, true ) );
		}
		return false;
	}
	public function file($filename) {
		$filename = $this->find_file ( $filename );
		return file_get_contents ( $filename );
	}
	public function addRoot($file) {
		$path = dirname ( $file );
		self::$roots [$file] = dirname ( dirname ( dirname ( $file ) ) );
		self::$_include_path = null;
	}
}

