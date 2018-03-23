# check-wp-requirements

[![Build Status](https://travis-ci.org/mundschenk-at/check-wp-requirements.svg?branch=master)](https://travis-ci.org/mundschenk-at/check-wp-requirements)
[![Latest Stable Version](https://poser.pugx.org/mundschenk-at/check-wp-requirements/v/stable)](https://packagist.org/packages/mundschenk-at/check-wp-requirements)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mundschenk-at/check-wp-requirements/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mundschenk-at/check-wp-requirements/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/mundschenk-at/check-wp-requirements/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mundschenk-at/check-wp-requirements/?branch=master)
[![License](https://poser.pugx.org/mundschenk-at/check-wp-requirements/license)](https://packagist.org/packages/mundschenk-at/check-wp-requirements)

A helper class for WordPress plugins to check PHP version and other requirements.

## Requirements

*   PHP 5.2.0 or above
*   WordPress 4.6 or higher (for proper translations).

## Installation

The best way to use this package is through Composer:

```BASH
$ composer require mundschenk-at/check-wp-requirements
```

## Basic Usage

1.  Create a `Mundschenk_WP_Requirements` object and set the requirements in the constructor.
2.  Call the `Mundschenk_WP_Requirements::check()` method and start your plugin normally if it
    returns `true`.

```PHP
require_once dirname( __FILE__ ) . '/vendor/mundschenk-at/check-wp-requirements/class-mundschenk-wp-requirements.php';

/**
 * Load the plugin after checking for the necessary PHP version.
 *
 * It's necessary to do this here because main class relies on namespaces.
 */
function run_your_plugin() {

	$requirements = new Mundschenk_WP_Requirements( 'Your Plugin Name', __FILE__, 'your-textdomain', array(
		'php'       => '5.6.0',
		'multibyte' => true,
		'utf-8'     => false,
	) );

	if ( $requirements->check() ) {
		// Autoload the rest of your classes.
		require_once __DIR__ . '/vendor/autoload.php';

		// Create and start the plugin.
		...
	}
}
run_your_plugin();
```

## License

check-wp-requirements is licensed under the GNU General Public License 2 or later - see the [LICENSE](LICENSE) file for details.
