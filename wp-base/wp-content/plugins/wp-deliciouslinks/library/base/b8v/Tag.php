<?php
class b8v_Tag extends b8v_Base {
	const anchor_pattern = '|<[\s]*a[\s\w]*(.*)[\s\w]*>([\w\W]*)<\s*/\s*a\s*>|Ui';
	public function get($tag, $content, $comment = false) {
		$comment_start = '';
		$comment_end = '';
		if ($comment) {
			$comment_start = '!--';
			$comment_end = '--';
		}
		$retval = array ();
		$patterns = array ();
		$patterns [] = '|<' . $comment_start . '\s*' . $tag . '\s\s*(.*)\s*' . $comment_end . '>([\w\W\s\S]*)<' . $comment_start . '\s*\/' . $tag . '\s*' . $comment_end . '>|Ui';
		$patterns [] = '|<' . $comment_start . '\s*' . $tag . '\s\s*(.*)\s*' . $comment_end . '\/?>|Ui';
		if ($comment) {
			$patterns [] = '|\[\s*' . $tag . '\s*(.*)\s*\]([\w\W\s\S]*)\[\s*\/' . $tag . '\s*\]|Ui';
			$patterns [] = '|\[\s*' . $tag . '\s*(.*)\s*\/?\]|Ui';
		}
		$all_matches = null;
		foreach ( ( array ) $patterns as $pattern ) {
			preg_match_all ( $pattern, $content, $matches, PREG_SET_ORDER );
			foreach ( ( array ) $matches as $match ) {
				$content = str_replace ( $match [0], '', $content );
			}
			$all_matches = array_merge ( ( array ) $all_matches, ( array ) $matches );
		}
		$patterns = array ();
		$patterns [] = '|\s*(.*)\s*=\s*"(.*)"\s*|Ui';
		$patterns [] = '|\s*(.*)\s*=\s*\'(.*)\'\s*|Ui';
		$patterns [] = '|\s*(.*)\s*=\s*&#8217;(.*)&#8217;\s*|Ui';
		$patterns [] = '|\s*(.*)\s*=\s*&#8221;(.*)&#8221;\s*|Ui';
		$complete_matches = array ();
		foreach ( ( array ) $all_matches as $all_match ) {
			$complete_match = "";
			$complete_match ['match'] = $all_match [0];
			$complete_match ['tag'] = $tag;
			if ($comment)
				$complete_match ['comment'] = true;
			$complete_match ['open'] = $all_match [0] [0];
			$complete_match ['close'] = substr ( $all_match [0], - 1 );
			if (array_key_exists ( 2, $all_match ))
				$complete_match ['innerhtml'] = $all_match [2];
			$complete_match ['attributes'] = ( array ) self::attributes ( $all_match [1] );
			//change lone checked into checked=checked or selected
			foreach ( ( array ) array_keys ( ( array ) $complete_match ['attributes'] ) as $key ) {
				switch ($complete_match ['attributes'] [$key]) {
					case 'checked' :
					case 'selected' :
						$complete_match ['attributes'] [$complete_match ['attributes'] [$key]] = $complete_match ['attributes'] [$key];
						unset ( $complete_match ['attributes'] [$key] );
						break;
				}
			}
			$complete_matches [] = $complete_match;
		}
		return $complete_matches;
	}
	public static function attributes($text) {
		$atts = array ();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace ( "/[\x{00a0}\x{200b}]+/u", " ", $text );
		if (preg_match_all ( $pattern, $text, $match, PREG_SET_ORDER )) {
			foreach ( ( array ) $match as $m ) {
				if (! empty ( $m [1] ))
					$atts [strtolower ( $m [1] )] = stripcslashes ( $m [2] );
				elseif (! empty ( $m [3] ))
					$atts [strtolower ( $m [3] )] = stripcslashes ( $m [4] );
				elseif (! empty ( $m [5] ))
					$atts [strtolower ( $m [5] )] = stripcslashes ( $m [6] );
				elseif (isset ( $m [7] ) and strlen ( $m [7] ))
					$atts [] = stripcslashes ( $m [7] );
				elseif (isset ( $m [8] ))
					$atts [] = stripcslashes ( $m [8] );
			}
		} else {
			$atts = ltrim ( $text );
		}
		return $atts;
	}
	protected static $_instance = null;
	public function instance() {
		if (null === self::$_instance) {
			self::$_instance = new self ( );
		}
		return self::$_instance;
	}
	public function tokenise(&$post) {
		$cnt = 0;
		$patterns = array ();
		$patterns [] = '|<(.*)>|Ui';
		$patterns [] = '|\[(.*)]|Ui';
		$source = $post;
		$tokens = array ();
		foreach ( ( array ) $patterns as $pattern ) {
			preg_match_all ( $pattern, $source, $matches, PREG_SET_ORDER );
			foreach ( ( array ) $matches as $match ) {
				$token = "#" . str_pad ( $cnt, 4, "0", STR_PAD_LEFT ) . "#";
				$tokens [$cnt] = $match [0];
				$post = str_replace ( $match [0], " $token ", $post );
				$cnt ++;
			}
		}
		$retval = null;
		$retval->text = $post;
		$retval->tokens = $tokens;
		$post = $retval;
		return $retval;
	}
	public function detokenise(&$text) {
		$retval = $text->text;
		$cnt = 0;
		foreach ( ( array ) $text->tokens as $tag ) {
			$token = "#" . str_pad ( $cnt, 4, "0", STR_PAD_LEFT ) . "#";
			$retval = str_replace ( $token, $tag, $retval );
			$cnt ++;
		}
		$text = $retval;
		return $retval;
	}
}