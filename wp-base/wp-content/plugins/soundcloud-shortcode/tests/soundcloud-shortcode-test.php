<?php

/**
 * Mocks
 */

function wp_oembed_add_provider() { return; }
function add_shortcode() { return; }
function add_filter() { return; }
function plugin_basename() { return; }
function add_action() { return; }
function get_option($name) {
  switch ($name) {
    case 'soundcloud_player_iframe':
      return '';
    case 'soundcloud_player_width':
      return '100%';
    case 'soundcloud_player_height':
      return '100%';
    case 'soundcloud_auto_play':
    case 'soundcloud_show_comments':
    case 'soundcloud_theme_color':
    default:
      return '';
  }

}


require_once('./soundcloud-shortcode.php');

class SC_Widget_Test extends PHPUnit_Framework_TestCase {

  /**
   * Basic soundcloud_shortcode() tests
   */
  public function testShortcode() {

    $expected = '<iframe width="500" height="200" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true"></iframe>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054',
      'iframe' => true,
      'width'  => 500,
      'height' => 200
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Simple visual widget');

    $expected = '<object width="500" height="200"><param name="movie" value="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fsoundcloud.com%2Fforss%2Fflickermood"></param><param name="allowscriptaccess" value="always"></param><embed width="500" height="200" src="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fsoundcloud.com%2Fforss%2Fflickermood" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';
    $params = array(
      'url'    => 'http://soundcloud.com/forss/flickermood',
      'iframe' => false,
      'width'  => 500,
      'height' => 200
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Simple Flash widget');

    $expected = '<iframe width="500" height="200" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true&color=ff0000"></iframe>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054',
      'iframe' => true,
      'width'  => 500,
      'height' => 200,
      'params' => 'color=ff0000'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Visual Widget with extra parameters');

    $expected = '<iframe width="500" height="116" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054"></iframe>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054',
      'iframe' => true,
      'width'  => 500,
      'height' => 116,
      'params' => 'visual=false'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Simple HTML5 Widget');

    $expected = '<object width="300" height="300"><param name="movie" value="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F45036121&auto_play=false&player_type=artwork&color=00ff3b"></param><param name="allowscriptaccess" value="always"></param><embed width="300" height="300" src="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F45036121&auto_play=false&player_type=artwork&color=00ff3b" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/45036121',
      'iframe' => false,
      'width'  => 300,
      'height' => 300,
      'params' => 'auto_play=false&player_type=artwork&color=00ff3b'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Flash Widget with extra parameters');

    $expected = '<object width="300" height="300"><param name="movie" value="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F41770793&auto_play=false&player_type=artwork&color=ff7700"></param><param name="allowscriptaccess" value="always"></param><embed width="300" height="300" src="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F41770793&auto_play=false&player_type=artwork&color=ff7700" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';
    $params = array(
      'url'     => 'http://api.soundcloud.com/tracks/41770793',
      'iframe'  => false,
      'params'  => 'auto_play=false&player_type=artwork&color=ff7700',
      'width'   => '300',
      'height'  => '300',
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Classic Flash artwork player');
  }

  /**
   * Default values tests
   */
  public function testShortcodeDefaults() {

    $expected = '<iframe width="500" height="200" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true"></iframe>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054',
      'width'  => 500,
      'height' => 200
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Check if Visual widget is the default');

    $expected = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true"></iframe>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Default height and width for Visual widget');

    $expected = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054"></iframe>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054',
      'params' => 'visual=false'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Default height and width for HTML5 widget');

    $expected = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=https%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F1815863&visual=true"></iframe>';
    $params = array(
      'url'    => 'https://api.soundcloud.com/playlists/1815863'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Default height and width for Visual playlist widget');

    $expected = '<object width="100%" height="81"><param name="movie" value="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054"></param><param name="allowscriptaccess" value="always"></param><embed width="100%" height="81" src="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';
    $params = array(
      'url'    => 'http://api.soundcloud.com/tracks/38987054',
      'iframe' => false
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Default height and width for Flash widget');

    $expected = '<object width="100%" height="255"><param name="movie" value="https://player.soundcloud.com/player.swf?url=https%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F1815863"></param><param name="allowscriptaccess" value="always"></param><embed width="100%" height="255" src="https://player.soundcloud.com/player.swf?url=https%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F1815863" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';
    $params = array(
      'url'    => 'https://api.soundcloud.com/playlists/1815863',
      'iframe' => false
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Default height and width for Flash playlist widget');

    $expected = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fsoundcloud.com%2Fforss%2Fflickermood&visual=true"></iframe>';
    $params = array(
      'url'    => 'http://soundcloud.com/forss/flickermood',
      'iframe' => true
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Support for permalink urls');
  }

  /**
   * Bad values tests
   */
  public function testShortcodeBadValues() {

    $expected = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true"></iframe>';
    $params = array(
      'url'     => 'http://api.soundcloud.com/tracks/38987054',
      'width'   => '',
      'height'  => 'onebillionpixels!'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Check bad input');

    $expected = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true"></iframe>';
    $params = array(
      'url'     => ' http://api.soundcloud.com/tracks/38987054',
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Trim whitespace');

    $expected = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054&visual=true"></iframe>';
    $params = array(
      'url'     => 'http://api.soundcloud.com/tracks/38987054',
      'iframe'  => 'true'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Check iframe true as string');

    $expected = '<object width="100%" height="81"><param name="movie" value="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054"></param><param name="allowscriptaccess" value="always"></param><embed width="100%" height="81" src="https://player.soundcloud.com/player.swf?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F38987054" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>';
    $params = array(
      'url'     => 'http://api.soundcloud.com/tracks/38987054',
      'iframe'  => 'false'
    );
    $this->assertEquals($expected, soundcloud_shortcode($params), 'Check iframe false as string');

  }

}

?>
