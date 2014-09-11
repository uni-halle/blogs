# soundcloud-shortcode

WordPress plugin for converting SoundCloud shortcodes into Embedded Players.


## Running Unit Tests

1. Install [PHPUnit](https://github.com/sebastianbergmann/phpunit)

    - OS X:

            $ brew install phpunit

2. Run tests

        $ phpunit


## Pusing to [WordPress Plugins](https://wordpress.org/plugins/)

1. Set up your local svn config:

        $ git svn init http://plugins.svn.wordpress.org/soundcloud-shortcode/ -T trunk -b branches -t tags --no-minimize-url

2. Bump version numbers in `readme.txt` and `soundcloud-shortcode.php` files and commit them.

        $ git commit -am "Version bump"

3. Push your latest code changes to wordpress.org:

        $ git svn dcommit --username=YOUR_WORDPRESS_ORG_USERNAME

4. Tag your release:

        $ git svn tag 1.0.2

    This will create /tags/1.0.2 in the remote SVN repository and copy all the files from the remote /trunk into that tag. This allows people to use an older version of the plugin.

5. Tag your release in Git and push latest changes:

        $ git tag 1.0.2 && git push origin master --tags
