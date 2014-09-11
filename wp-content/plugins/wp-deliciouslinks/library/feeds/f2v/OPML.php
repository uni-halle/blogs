<?php
class f2v_OPML extends b8v_Http
{
	public function get ()
	{
		$data = parent::get();
		// commented out as page often return 404 even though it return the correct page.
		//if ($headers['HTTP/1.1'] == '200 OK')
		{
			$done = false;
			while (! $done) {
				$parser = xml_parser_create();
				$i_ar = null;
				$d_ar = null;
				$parser = xml_parser_create();
				xml_parse_into_struct($parser, $data, $vals, $index);
				xml_parser_free($parser);
				// convert the parsed data into a PHP datatype
				$return = array();
				$ptrs[0] = & $return;
				$cnt = 0;
				foreach ((array) $vals as $xml_elem) {
					$level = $xml_elem['level'] - 1;
					switch ($xml_elem['type']) {
						case 'open':
						case 'complete':
							$array = null;
							$array['tag'] = $xml_elem['tag'];
							if (array_key_exists('attributes', $xml_elem)) {
								$array['attributes'] = $xml_elem['attributes'];
							}
							if (array_key_exists('value', $xml_elem)) {
								$array['value'] = $xml_elem['value'];
							}
							$ptrs[$level][$cnt] = $array;
							$ptrs[$level + 1] = & $ptrs[$level][$cnt];
							break;
					}
					$cnt ++;
				}
				if (count($return) == 0) {
					$parts = explode("\r\n", $data, 2);
					$data = $parts[1];
				} else {
					$done = true;
				}
			}
		}
		return $return;
	}
	private function OPMLtoArray ($OPML)
	{
		$patterns[0] = '|<outline(.*)>|Ui';
		$patterns[1] = '|(.*)="(.*)"|Ui';
		$RetVal = array();
		preg_match_all($patterns[0], $OPML, $matches, PREG_SET_ORDER);
		foreach ((array) $matches as $match) {
			preg_match_all($patterns[1], $match[1], $smatches, PREG_SET_ORDER);
			$item = array();
			foreach ((array) $smatches as $smatch) {
				$item[trim($smatch[1])] = $smatch[2];
			}
			$RetVal[] = $item;
		}
		return $RetVal;
	}
	private function OPMLexplode ($OPML)
	{
		$RetVal = array();
		$Cats = array();
		$Links = array();
		$cat = 'Uncategorized';
		foreach ((array) $OPML as $Link) {
			switch ($Link['type']) {
				case 'category':
					$cat = $Link['title'];
					$Cats[] = $cat;
					break;
				case 'link':
					$link['link_category'] = $cat;
					$link['link_name'] = $Link['text'];
					$link['link_url'] = $Link['htmlUrl'];
					$link['link_rss'] = $Link['xmlUrl'];
					$link['link_updated'] = $Link['updated'];
					$Links[] = $link;
					break;
			}
		}
		$RetVal['Categories'] = $Cats;
		$RetVal['Links'] = $Links;
		return $RetVal;
	}
}
