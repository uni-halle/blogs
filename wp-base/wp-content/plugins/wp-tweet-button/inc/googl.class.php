<?php
/**
 * This file is part of googl-php
 *
 * http://googl-php.googlecode.com
 *
 * Copyright 2011 by Sebastian Wyder <sebastian.wyder@sunrise.ch>
 *
 * googl-php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Googl
{
    public $extended;
    private $target;
    private $apiKey;
    private $oauthToken;
    private $ch;

    function __construct($apiKey = null, $oauthToken = null)
    {
        $extended = false;
        
        # Set Google Shortener API target
        $this->target = 'https://www.googleapis.com/urlshortener/v1/url?';

        # Set API key if available
        if ( $apiKey != null )
        {
                $this->apiKey = $apiKey;
                $this->target .= 'key='.$apiKey.'&';
        }

        if ( $oauthToken != null )
        {
                $this->oauthToken = $oauthToken;
        }
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->target);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public function shorten($url, $extended = false)
    {
        $data = array( 'longUrl' => $url );
        $data_string = '{"longUrl": "'.$url.'"}';
        curl_setopt($this->ch, CURLOPT_POST, count($data));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_string);
		if ($this->oauthToken == null){
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, Array('Content-Type: application/json'));
		} else {
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, Array('Content-Type: application/json', 'Authorization: AuthSub token="' . $this->oauthToken . '"'));
		}
        if ( $extended || $this->extended)
        {		$result = curl_exec($this->ch);
                return json_decode($result);
        }
        else
        {
				$result = curl_exec($this->ch);
                return json_decode($result)->id;
        }
    }

    public function expand($url, $extended = false)
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->target.'shortUrl='.$url);

        if ( $extended || $this->extended )
        {
            return json_decode(curl_exec($this->ch));
        }
        else
        {
            return json_decode(curl_exec($this->ch))->longUrl;
        }
    }

    function __destruct()
    {
        # Close the curl handle
        curl_close($this->ch);
        # Nulling the curl handle
        $this->ch = null;
    }

}
?>