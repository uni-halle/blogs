Typograph - a no-images WordPress theme
© 2008-2009 

Designed by Morten Rand-Hendriksen of Pink & Yellow Media
Author URI: http://www.pinkandyellow.com
Theme URI: http://blog.pinkandyellow.com/free-wordpress-themes/typograph/
Licence: GNU General Public Licence version 3: http://www.gnu.org/licenses/gpl.txt

*************************************************************************************

Changelog:
0.8.6:
- Updated to fix conflict with WordPress 2.8 present in earlier versions of the theme.
- MooTools code moved to tabbedBox.php file to isolate all code associated with the
sidebar box entirely from the rest of the code. As a result all that's needed to deactivate
the tabbed box is to delete or comment out line 4 in sidebar.php.
- Classes updated in search.php to allow pages in search results to be displayed properly.
- General code cleanup to make things tighter and neater.

0.8.5: 
- The tabbed box in the sidebar is now powered by MooTools to avoid clashing with 
popular JQuery-powered plug-ins such as Lightbox. Tested and works for Lightbox 2.
- Threaded comments (up to 5 levels) have been added. To activate threaded comments
go to Settings -> Discussion and check "Enable threaded (nested) comments"
- Commented out the Search Widget in the default sidebar widget layout. To reactivate
it, simply remove the comments around it in the sidebar.php file.
- Added nofollow to footer links 

0.8.4: 
- Updated functions.php to comply with new WP 2.7 Coltrane - fixed conflict with
old post_class() and comment_class() functions.

*************************************************************************************

The Typograph theme is as simple as possible with clear separation between the content
and the sidebar, a calm grey and white design with popping red links, threaded comments, 
a tabbed sidebar box with navigation, search and other important elements and some other 
styling for increased readability and better navigation. 

Typograph is fully XHTML and CSS standards compliant.

Right before I began the design of this theme, Spyremag published an article about 5
 ways to break your design habits, one of which was to design a site using no images. 
Seeing as I’ve become somewhat obsessed with CSS over the last year it seemed only 
appropriate to follow this advice and create a no-images theme. Not only would this 
be a bit of a challenge because I ususually use a lot of images to make my designs 
more vibrant, but it would also put my coding skills and my understsanding of 
WordPress themes to the test.

Over the last several months I’ve been refining and customizing a copy of the Sandbox
WordPress theme to develop an ideal platform for quick and easy WordPress theme design. 
The plan is to create a “God Theme” if you will that has all the bells and whistles 
installed and ready to go so that new theme design is quick and efficient. To put the 
alpha version of this foundation theme to the test I used it to style Typograph from 
scratch.

When I created the new theme for Design is Philosophy I spent quite a bit of time 
developing and perfecting a MooTools and CSS based tabbed sidebar box that would contain 
navigation as well as other useful information for the visitor. For Typograph I further 
developed the tabbed box and isolated it in it’s own file to simplify customization for 
the user. It can also easily be deactivated by commenting out a single line of code in 
the sidebar.php template. The tabbed box contains navigation for pages and categories 
along with an about section, RSS link and search box by default. It takes standard 
WordPress tags and can be customized to include pretty much anything by editing the 
tabbedBox.php file found in the theme directory.

You are free to use, modify and mess around with this theme in any way you want. 
Although a link back to me, the author, would be preferred it is not mandatory.

Typograph is loosely based on The Sandbox, an excellent theme you can download here:
http://www.plaintxt.org/themes/sandbox/

Customizing the tabbed sidebar box
----------------------------------
The tabbed sidebar box is contained within it's own file found in the theme directory
called tabbedBox.php. It is controlled by the MooTools library which is hosted by Google API.
To change the contents of each of the tabs, edit the contents within the tabbedBox.php file. 