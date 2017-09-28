=== Aesop Story Engine ===
Contributors: nphaskins, etcio, michaelbeil, hyunster, peiche
Author URI:  http://aesopstoryengine.com
Plugin URI: http://aesopstoryengine.com
Donate link: http://aesopstoryengine.com/donate
Tags: aesop, story, business, education, parallax, interactive, shortcode, gallery, grid gallery, thumbnail gallery,
Requires at least: 3.8
Tested up to: 4.8
Stable tag: 1.9.8.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Suite of components that enables the creation of interactive longform stories in WordPress.

== Description ==

Aesop Story Engine is a suite of open-sourced tools and components that empower developers and writers to build feature-rich, interactive, long-form storytelling themes for WordPress. At the heart of ASE are the suite of storytelling multimedia components, which are created on the fly while crafting posts within WordPress.

[http://aesopstoryengine.com](http://aesopstoryengine.com)

[youtube http://www.youtube.com/watch?v=84BFGxkHby0]


Utilizing these components, writers and developers can build feature-rich, visually compelling WordPress posts and themes. 


**Demos and More Info**

[http://aesopstoryengine.com/about/](http://aesopstoryengine.com/about/)

** Check the latest new features of Aesop Story Engine **

[http://aesopstoryengine.com/blog/](http://aesopstoryengine.com/blog/)

Reveal Animation: New features of Aesop Story Engine v 1.9.6 

[http://aesopstoryengine.com/releases/aesop-story-engine-1-9-6-new-features/](http://aesopstoryengine.com/releases/aesop-story-engine-1-9-6-new-features/)


**Audio** -
Display an audio player with support for MP3 that can be optionally hidden. This is great for showcasing audio interviews.

**Video** -
Showcase a fullscreen video with support for Kickstarter, Viddler, YouTube, Vimeo, Daily Motion, and Blip.TV with support for captions and alignment.

**Content** -
The content component is a multi-purpose component that can display a background image, background color, or can split the content into multiple magazine type columns.

**Character** -
Display a character avatar, title, and small bio to help readers be reminded of key story characters.

**Gallery** -
The ASE Gallery component allows you to create and manage unlimited story galleries. Each gallery can be displayed as a grid, a thumbnail gallery, stacked, or sequential type gallery, all with caption support.

**Chapter** -
Creates scroll-to points with large full-screen images as headings.

**Image** -
The image component displays an image and caption, with optional lightbox. Also allows you to align the image, as well as offset the image so it hangs outside of the content column.

**Map** -
This component allows you to create a map for your story. You can add markers to the map with custom messages, and even have the map scroll to points as you scroll through the story.

**Parallax** -
A fullwidth image component with caption and lightbox. As you scroll, the image moves slightly to provide a parallax effect. Includes optional floater parallax item to use for multiple levels of parallax engagement.

**Quote** -
Show a fullwidth quote with large text, or a standard pull-quote. Control the color and background of the quote component, add parallax effects, and more.

**Timeline** -
Create a story with a timeline that sticks to the bottom. The timeline works a bit like chapters.

**Document** -
This component allows you to upload a PDF or image, that is shown to the user once they click the component.

**Collection** -
The 13th component is meant to be used on a page of your site, and allows you to display stories from a specific collection (category).

** Sign up for news and exclusive offers **

[http://aesopstoryengine.com/be-social/](http://aesopstoryengine.com/be-social/)


Here’s documentation on Aesop Story Engine: [http://aesopstoryengine.com/help](http://aesopstoryengine.com/help).

= Theme Implementation =

It’s important to know that the plugin only produces very basic CSS for the components. The theme is responsible for making the components appear different ways. For this reason, the Timeline and Chapter components may not function as intended. Refer to your themes documentation to see if it fully supports Aesop.

Theme authors and developers will find documentation covering everything from the markup that is generated, to actions, filters, and instructions for full Aesop integration here: [http://aesopstoryengine.com/developers](http://aesopstoryengine.com/developers).

** Update 7.31.14 **
Aesop Story Engine 1.0.9 now features full theme compatibility with a simple code snippet that will load styles based on the components that you decide. While a dedicated theme is required to run components full-width, this will at least load all of the additional styles to give a basic design. Simply remove the component that you do not want to load additional styles for.

`add_theme_support("aesop-component-styles", array("parallax", "image", "quote", "gallery", "content", "video", "audio", "collection", "chapter", "document", "character", "map", "timeline") );`

We recommend placing this in a WordPress theme's functions.php, or use a plugin like [Code Snippets](https://wordpress.org/plugins/code-snippets) and put the snippet in there.

= Developers =
All components are pluggable, and there are ample filters and actions to manipulate just about everything you can imagine. Refer to the documentation for more: [http://aesopstoryengine.com/developers](http://aesopstoryengine.com/developers).

If you think something is missing, we want to hear from you. Post your request and bugs on [Github](https://github.com/bearded-avenger/aesop-core).

= Languages =
Aesop Story Engine is currently available in 36 languages. We work closely with the folks over at [WP-Translations](https://www.transifex.com/wp-translations/aesop-story-engine), and it's because of them that these translations are available. You're welcome to jump in.

* العربية (Arabic)
* Български (Bulgarian)
* Burmese (Myanmar)
* čeština‎ (Czech)
* 中文 (Chinese (China))
* Dansk (Danish (Denmark))
* Nederlands (Dutch)
* English (United Kingdom)
* English (US)
* Suomi (Finnish)
* Français (French (France))
* Deutsch (German)
* Ελληνικά (Greek)
* עִבְרִית (Hebrew)
* Magyar (Hungarian)
* Indonesian (Indonesia)
* Italiano (Italian)
* 日本語 (Japanese)
* ភាសាខ្មែរ (Khmer)
* 한국어 (Korean)
* Bokmål (Norwegian)
* فارسی (Persian)
* Polski (Polish)
* Português do Brasil (Portuguese (Brazil))
* Română (Romanian)
* Русский (Russian)
* Српски језик (Serbian)
* Slovenčina (Slovak)
* slovenščina (Slovenian)
* Español (Spanish (Argentina))
* Español (Spanish (Chile))
* Español (Spanish (Mexico))
* Español (Spanish (Spain))
* ไทย (Thai)
* Türkçe (Turkish)
* Tiếng Việt (Vietnamese)

== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to 'Add New' in the Plugin dashboard
2. Navigate to the 'Upload' area
3. Select `aesop-core.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `aesop-core.zip`
2. Extract the `aesop-core` directory to your computer
3. Upload the `aesop-core` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Frequently Asked Questions ==

= Does this work with all themes? =
Most of the components will work with most themes without any issues. It’s very important to know that this plugin only applies basic styles, and to take full advantage, a theme built for Aesop Story Engine is probably a good idea.

= Where can I find themes for Aesop Story Engine? =
Various theme shops in the industry are actively creating Aesop Story Engine Themes, in addition to the official Aesop Story Engine themes located at [http://aesopstoryengine.com/library](http://aesopstoryengine.com/library).

= Where can I find more information on making my theme fully compatible? =
Full documentation can be found here: [http://aesopstoryengine.com/developers](http://aesopstoryengine.com/developers).

== Screenshots ==

1. The component generator triggered within the edit post screen.
2. Story Engine components and their descriptions
/Users/Nick/Sites/wp-aesop/wp-content/plugins/aesop-core/README.txt

== Upgrade Notice ==

= 1.0 =
* Initial Release

== Changelog ==
= 1.9.8.5 =
* NEW Option to select multiple categories for the Collection Component
* FIX Map marker hidden option fixed.

= 1.9.8.4 =
* Fixed the Google Map API Key setting.
* Staggered animation for overlay elements

= 1.9.8.2 =
* Improved the menu behavior for the Video Component
* Hero image texts can include HTML now
* FIX Fixed a styling issue for the play button on self hosted videos.

= 1.9.8.1 =
* FIX Fixed an issue in the hero gallery

= 1.9.8 =
* NEW "Picture in Picture" exit option for Video Component. Thanks to Paul Eiche (peiche@gmail.com)
* NEW Reveal animations for component overlay contents
* NEW Options to set text source for lightboxes for Grid and Photoset Galleries
* NEW Options to set image text for Hero Galleries

= 1.9.7.3 =
* FIX Chapter Video Background for Self Hosted Video

= 1.9.7.2 =
* FIX Chapter Video Options

= 1.9.7.1 =
* FIX Audio Component hide control option fixed

= 1.9.7.0 =
* NEW Video Component now supports Disable for Mobile options for all videos.
* NEW The backend Video Component editor has been changed to hide options based on context 
* NEW The backend Chapter Component editor has been changed to hide options based on context 
* FIX Parallex Component's "fixed background" option fixed for Firefox
* FIX Some code errors have been fixed
* FIX An issue with multiple autoplaying YouTube videos in the same page has been fixed.
* FIX Chapter data field location has been changed

= 1.9.6.9 =
* NEW Video Component now supports Autoplay, Loop, In-View Play, Out-Of-View Pause options for Vimeo
* NEW Video Component now supports In-View Play, Out-Of-View Pause options for YouTube
* FIX Audio Component play control is made to be always visible on mobile
* FIX Video Component aspect ratio defaults to 16/9 if no other information is available
* FIX Fixed a bug in the Collection Component where "loading" message was not updated
* FIX Content reveal animation is enabled

= 1.9.6.8 =
* FIX An occasional bug on Parallax component where the component is resized before the image is fully loaded
* NEW Option for Chapter Component to limit the height of the component.
* German translations updated.

= 1.9.6.7 =
* Video Component now supports Autoplay, Loop, and Controls Off options for Youtube Videos
* Some Spanish translations added.

= 1.9.6.6 =
* FIX When editing paramters containing HTML tags, the text should not be sanitized.

= 1.9.6.5 =
* NEW Added an option to Hero Gallery to preserve width/height ratio
* NEW Added an option to Parallax to preserve width/height ratio


= 1.9.6.4 =
* FIX incorrect loading of the Google api key option

= 1.9.6.3 =
* NEW Added Google API Key for the backend editing of Map Component. This key can be edited from Appearance->Customize->Aesop Story Engine menu

= 1.9.6.2 =
* FIX Fixed the editor issues when attributes include HTML
* FIX Fixed the transition time value for Hero gallery not being saved
* NEW Quote component can use HTML for texts
* NEW Content box component now supports Reveal Animation

= 1.9.6 =
* NEW Gallery component (except Parallax) now support Reveal Animation.

= 1.9.5.5 =
* FIX disable parallax scrolling on mobile devices for compatibility

= 1.9.5.1 =
* FIX 1.9.5 had a shortcode interpretation bug. Fixed.

= 1.9.5 =
* NEW Image, Video, Chapter components have new option : Overlay Content. You can use HTML tags, like the overlay content for Hero Gallery and Parallax.
* NEW Hero Gallery has an option to add navigation controls.

= 1.9.4 =
* NEW Image, Quote, Chapter and Video components have new option : Reveal Effect (Animation)
* FIX Collection Component style fixes. Also added "Loading" text
* Update POT file updated
 
= 1.9.3 =
* NEW Collection Component has an option to add "Load More" using AJAX
* NEW Collection item adds "aesop-has-image" class if the post has featured image. (An improvement for styling customization.)
* NEW At the backend editor, Component graphics show caption or title for easier identification. This change improves the editing process.

= 1.9.2 =
* FIX Fixed setting conflicts between Thumbnail and Hero galleries.
* FIX Fixed Collection Component grid style errors

= 1.9.1 =
* FIX Fixed a syntax error for Parallax component when the height is not set.

= 1.9.0 =
* NEW Hero Gallery Type has been added. Great thanks to peiche (peiche@gmail.com).
* NEW FIXED background type has been added to Parallax Component.
* NEW Parallax Component has been overhauled and fixed many issues.
* Update Many default options were changed to be more intuitive.
* Update Documentation changes.


= 1.7.11 =
* FIX fixed the alignment option in Parallax component. Now center and right alignments work.
* FIX If square brackets are put in as parameters, they get converted to html codes.
* FIX Quote component checks the scroll direction to see if it makes sense.
* Update Documentation changes.

= 1.7.10 =
* FIX fixed the bug where image credits can appear twice

= 1.7.9 =
* FIX Fixed the image credit for the Image Component was not visible when the caption was not set. Fixed.
* FIX The lightbox controls were restored
* FIX A css fix for parallax galleries

= 1.7.8 =
* NEW Added the option to disable Video playing when the viewer is a mobile device and the source is Self. 
* NEW Added the alternate static image option for the Video Chapter components when the viewer is a mobile device.

= 1.7.7 =
* FIX Chapter navigation keys were changed from arrows keys to page up/page down.
* Update Updated the lightbox swipe codes.

= 1.7.6 =
* FIX Lightboxes for  the Photoset gallery was disabled. Fixed.

= 1.7.5 =
* FIX The video didn't show when poster frame is on and the controls are off. Fixed.

= 1.7.4 =
* NEW Added the poster frame option for the video when the source is "self"
* NEW Added the solid color background option and the minimum height option for Chapters
* NEW Added "force circle" option for the Character component
* FIX Some deprecated JQuery codes are replaced thanks to Gibson Starkweather (boaf)
* FIX Some style fixes thanks to peiche

= 1.7.3 =
* FIX - Fixed the default map ID

= 1.7.2 =
* FIX - Fixed timeline compatibility with Aesop themes

= 1.7.1 =
* FIX - Fixed chapter compatibility with Aesop themes

= 1.7.0 =
* FIX - Default color behavior for non-block quote is restored
* FIX - Default mapbox url is set to v4
* FIX - Fixed the height for default Timeline bar
* FIX - Aesop Lazy Loader compatability is added to more gallery types
* FIX - Self target video doesn't play automatically if not visible and "start play when in view" is on

= 1.6.12 =
* FIX - Fixed the logic to load default component styles
* FIX - Fixed the compatibility issue with themes that define aesop-entry-content class
* FIX - Fixed the issue where the Quote text color is ignored

= 1.6.11 =
* FIX - Fixed the Chapter and Timeline navigation for non Aesop themes
* FIX - Adjusted the Content Box
* NEW - Added the background speed option for the Parallax component 

= 1.6.10 =
* FIX - Fixed the Video and Video Chapter visibility issues
* FIX - Fixed the incorrect video source menu item.
* FIX - Use the Character default style if the theme doesn't supply custom styles.  

= 1.6.9 =
* FIX - Fixed a check in error in 1.6.8

= 1.6.8 =
* FIX - Fixed the issue where component settings were not loaded correctly under WordPress 4.4
* FIX - Fixed the map search box functionality

= 1.6.6 =
* FIX - Fixed improper function name introduced with 1.6.4 which causes Editus to not be able to edit Aesop components

= 1.6.5 =
* FIX - Fixed the timeline shortcode that was broken by our shortcode fix
* TWEAK - i18n updates

= 1.6.4 =
* FIX - Fixed the gallery shortcodes that were broken by WordPress 4.3
* TWEAK - i18n updates
* TWEAK - Renamed Lasso to Editus

= 1.6.3 =
* TWEAK - improved Document component markup which also fixes an issue with editing using Lasso
* TWEAK - i18n updates

= 1.6.2 =
* FIX - Typo in 1.6.1 changelog
* FIX - Fixed a bug with backward compatibilty function causing slow/crashing admin on sites with several thousand posts
* FIX - Fixed a bug where updating a component would send it to the top of the post editor in Firefox - props @etcook
* FIX - Added a fallback for gallery image thumbnails in admin if thumbnail size isn't available
* TWEAK - Removed iframe border around Kickstarter videos - props @dryanmedia
* TWEAK - Fixed image component failing validation - props @peiche
* TRANSLATIONS - Added English (United Kingdom), Indonesian, Spanish (Mexico), Burmese, and Arabic. Aesop is now available in 35 languages thanks to WP Translations!

= 1.6.1 =
* FIX - Patched XSS vulnerability with not properly escaping add_query_arg(). Only an attacker with admin privileges would have been able to take advantage of this vulnerability.

= 1.6 =
* FIX - PHP notice being triggered from not padding in an ID for current_user_can('edit_post')
* FIX - Better detection of Lasso being activated due to autoloaders in Lasso
* FIX - Fixed the quote cite markup being escaped, thus not being styled correctly
* FIX - Height not triggering correctly on Parallax component if Parallax is set to off
* TWEAK - Height of the parallax component now respects height of image if parallax is set to off
* TWEAK - Improved the responsive nature of the stacked gallery component
* TWEAK - All actions now have $atts and $unique attributes added for fine grain control over adding things to specific components
* TRANSLATIONS - Added Bulgarian, Chinese, Danish, Dutch, Finnish, German, Greek, Hungarian, Khmer, Korean, Norwegian, Persian, Slovak, Slovenian, Spanish (Argentina), Spanish (Spain), Thai, and Vietnamese. Aesop is now available in 29 languages thanks to WP Translations!

= 1.5.2 =
* FIX - Fixed an issue with the Photoset gallery breaking with the last update
* FIX - Added a capability check so admin_notices aren't shown to non-admins

= 1.5.1 =
* FIX - Massive codebase overhaul bringing Aesop Story Engine close to WordPress VIP plugin standards
* FIX - Combed through the codebase and removed all unused vars and updated php docs per Scrutinizer
* FIX - Undefined $classes variable in Quote component
* FIX - Fixed an issue with the Parallax component where the height would sometimes not be calculated correctly
* FIX - Added additional logic to the Mapbox upgrade process sent with 1.5 to check for an empty value to ensure better upgrade notifications
* FIX - Fixed the welcome page on plugin activation not firing correctly
* ADDED - Added headings to the aesop_component_media_filter

= 1.5 =
* NEW - Welcome screen on plugin activation
* NEW - New "Type" option for Quote Component which allows the quote to be displayed as a standard pull quote
* FIX - Blank map tiles with new Mapbox IDs. Mapbox changed things and now requires a public key for the map tiles. We're using our public key, but have introduced a filter should you need to change this. On this update, we've changed our mapbox id, and have written an upgrade script that will ensure you have a smooth transition in this update
* FIX - The document component css class has been renamed! This was inevitable. It was mis-labeled as document component from day one, so we've fixed it to the proper spelling, of "document" component

= 1.4.2 =
* NEW - Compatibility with Lasso - our soon to be released front-end editor add-on
* NEW - Now available in 14 languages - props wp-translations.org
* FIX - Numerous i18n fixes - props wp-translations.org
* FIX - Fixed improper audio title formatting
* FIX - Fixed audio component attributes filter name
* FIX - Fixed an error within the Gallery admin affecting PHP 5.4
* FIX - Fixed bug with TinyMCE load dependency
* TWEAK - Better compatibility with the Aesop Lazy Loader add-on
* TWEAK - Prevent "Upgrade Galleries" notice from showing if you've already upgraded galleries
* TWEAK - The Parallax component has gotten a significant overhaul. The most important being that the height attribute is no longer used. Instead, the height of the parallax component is not only fluid and responsive, but it's automatically calculated based on the height of the image that you upload. In this regard it should always be sized perfectly. It's best to use an image at least 800px tall. In addition, the parallax image width is now respected, which means there's no more clipping on left and right. Although we hate to remove the "fixed height" option, and although we realize this might be seen as a jarrying change, we hope you'll enjoy this significant but necessary improvement.

= 1.4.1 =
* FIXED - Yandex in Fotorama : A few updates back we attempted to block Fotorama from inserting its Yandex tracker. Since we noticed that this sometimes fails to block, we've modified their source code and have removed it completely. It's also worth noting that they've gotten a lot of heat from this, and have since removed it all together from their script. This should no longer be an issue, and we apologize for any inconviences that we may have inadvertantly caused.

= 1.4 =
* NEW - Redesigned gallery admin - makes creating and managing galleries easier than ever before
* NEW - Chapter component slideout - fully compatible with all WordPress themes ( with extended css support snippet ).
* NEW - Chapter component placeholders now show Chapter titles in the editor - props @crowjonah
* NEW - Map markers (used with Sticky Maps) now shows Marker textin the placeholder in the editor - propes @crowjonah
* FIXED - Bug with maps not correctly displaying in admin in Firefox
* FIXED - Sticky map styles bleeding out of single posts
* FIXED - Image upload bug when using multiple image fields (only affects 3rd party plugins)
* FIXED - Transparent issue with YouTube video player in IE 11 - props @artjosimon
* FIXED - Stacked Parallax gallery bug
* NOTE - With the new Galleries in 1.4, the metabox library has been removed from Aesop Story Engine, saving space and reducing the size of the code base. This will only affect 3rd party developers who are relying on our library. Visit Github repo for more details on fixes.

= 1.3.2 =
* FIXED - Code showing in Chapter Component
* FIXED - Parallax floater markup
* FIXED - Sequence gallery images not showing

= 1.3.1 =
* HOTFIX - Fix syntax not supported by PHP older than 5.4

= 1.3 =
* NEW - Freshly designed user interface with light color scheme to match WordPress design patterns
* NEW - Map component admin with ability to click the map to add markers instead of manually adding GPS coordinates
* NEW - Map component "sticky" mode that changes map markers as you scroll down the story
* NEW - Map component tile filter aesop_map_tile_provider that allow you to specify a different tile provider per post (or globally) [ref](https://github.com/bearded-avenger/aesop-core/pull/172#issuecomment-63518448)
* NEW - Components can now be cloned
* NEW - New filter aesop_quote_component_unit to change unit size of blockquote
* FIXED - All variables now properly escaped within components
* FIXED - The "used in" column of the Galleries edit screen
* FIXED - Additional spaces being added on the front end after saving components
* FIXED - Timeline scrollnav build failing on certain occassions
* FIXED - Some parts of the component placeholder highlighting after clicking the edit button
* FIXED - JS error that shows if the visual editor is turned off in options (props @wavetree)
* FIXED - Self hosted videos not stretching to 100% width
* FIXED - Zero height on an aligned video component
* FIXED - Only show grid caption markup if captions present (props @artjomsimon)
* TWEAK - Related videos at the end of YouTube videos now off by default (props @artjomsimon)
* TWEAK - Improved video markup
* UPDATED - Fotorama, fitvids, scrollnav, and images loaded to their respective current versions

= 1.2.1 ==
* FIXED - lightbox gallery images not opening in grid gallery
* FIXED - warnings with custom meta boxes if wp-admin is set to SSL
* UPDATED - custom meta boxes to 1.2
* NOTE - The next update we will be moving from Custom Meta Boxes by Humanmade to CMB2 by WebDev Studios.

= 1.2 =
* FIXED - Width on videos so that they remain responsive
* FIXED - Undefined variable in thumbnail gallery
* FIXED - Gallery images not respecting sizes
* FIXED - Issue of overlapping placeholders when updating a component thats next to another component - #138 on GH
* ADDED - New action "aesop_gallery_type" so 3rd party components can add new gallery types
* ADDED - New filter aesop_generator_loads_on which accepts an array of admin pages to load the generator
* TWEAK - Cleaned up the gallery creation process including removing core options from the gallery settings modal that Aesop doesn't use, along with only running our modifications on Aesop Galleries
* TWEAK - Added additional checks to ensure $post is set before loading map components

= 1.1 =
* NEW - Complete compatibilty with WordPress 4.0
* NEW - New user interface
* NEW - Components are now editable
* NEW - API for creating addons to sell or give away
* NEW - RTL support
* ADDED - Filters for Audio and Video component waypoints
* ADDED - Filters for timeline and location offsets
* ADDED - Filter to let Map component run on pages
* ADDED - Gallery Component: added to the component generator with a dropdown to select gallery to insert
* ADDED - Gallery Component: added captions to grid gallery items if a caption is set
* ADDED - Content Component: added Floater Position option for parallax floater
* TWEAK - Content Component: parallax code optimized and offsets automatically calculated
* TWEAK - Map Component: automatically fall back to the first marker entered if the starting coordinate is missing and warn the user
* TWEAK - Collection Component: mo longer have to input collection ID they are now automatically fed into a dropdown tand selectable by name
* TWEAK - Parallax Component: floater item offset now automatically calculated - this means offset and speed options no longer necessary and have been removed
* TWEAK - Parallax Component: optimizations and performance enhancements
* TWEAK - Gallery Component: performance optimizations
* TWEAK - Cleaned up user interface for creating Galleries in admin
* TWEAK - Audio/Video Component: waypoint filters now targets individual components
* TWEAK - Timeline Component: redesigned to perform well wihin 98% of WordPress themes
* FIXED - Better support for Aesop Lazy Loader
* FIXED - Video icon
* FIXED - Quote Component: parallax floater options fixed (could not move up or down so two options are now left and right)
* FIXED - Map Component:  warn users if no markers are set
* FIXED - Map Component:  fixed empty bubbles appearing on markers with no text set

= 1.0.9 =
* FIXED - Various generator fixes for WordPress 4.0
* FIXED - Fixed not being able to use multiple collections due to invalid cache (props @tmeister)
* FIXED - Fixed the default map zoom so its not so far zoomed out
* FIXED - Video option display error within generator
* FIXED - Spelling of the word Library in generator option descriptions
* NEW - New extended css option that loads additional CSS in an effort to be compatible with more themes out of the box (see docs for more)
* NEW - Updated Polish translation
* NEW - New Photoset gallery type
* NEW - Full compatibility for stacked gallery type. This was previously left up to themes.
* NEW - Content component enhancements for background image
* NEW - Galleries now have ID's that correspond to the gallery id and instance of gallery in the post
* NEW - Filter - stacked gallery styles (aesop_stacked_gallery_styles_ID-INSTANCE) see docs
* NEW - Filter - chapter component inline styles for the background image (aesop_chapter_img_styles_ID-INSTANCE) see docs
* TWEAK - Fixed the way unique ID's are applied to each component to aid in customizing with css
* TWEAK - Don't set a chapter image if one isn't set and add a class for this
* TWEAK - Float class added to character and quote components if component is aligned left or right. This *may* have a different effect on your design so please be aware of this.
* TWEAK - Content component now has wpautop filter applied which will make the text within the content component into proper paragraph elements. this *may* result in additional space in your design so please be aware of this tweak. We've also added a class to the parent component if an image is being used.

= 1.0.8 =
* NEW - option description tip bubbles
* NEW - Misc style refinements to the generator user interface
* NEW - Updated icon for Galleries tab
* NEW - Image caption is now displayed in the lightbox if set
* NEW - changed lightbox to close if background is clicked
* NEW - parallax floater option added to Content Component
* NEW - polish translation - props trzyem
* NEW - four additional hooks added to collections component - props @tmeister
* FIXED - Bug with responsive images when px width is set
* FIXED - Audio player from looping when set to off
* UPDATED - translation file with new strings
* UPDATED - Lightbox script
* CHANGED - Floater can now be parallax even with parallax bg set to off in parallax component
* CHANGED - Changed the “Upload” label to “Select Media”


= 1.0.7 =
* NEW - Parallax floater options added to Content Component
* NEW - Ability to have text positioned anywhere in Content Component
* NEW - Wistia support added to Video Component
* NEW - Option added to Audio Component to make it invisible
* NEW - Looping options enabled in Audio Component
* NEW - Serbian Translation
* FIXED - Bug with Image Component centering classes (props @mauryaratan)

= 1.0.6 =
* NEW - New function aesop_component_exists
* NEW - Added ability for Character Component to set a width
* NEW - Added ability for Audio Component to have an optional title
* NEW - Added ability for Quote Component to have a cite
* FIXED - is_ipad notice on Android and select Windows devices
* FIXED - Better checks for galleries and maps in posts
* FIXED - If Image Component is floated keep it from breaking out of .aesop-content
* FIXED - Missing viewstart and viewend options in Component Editor
* UPDATED - Metabox library

= 1.0.5 =
* NEW - Added new filter to adjust map meta locations in admin aesop_map_meta_location
* NEW - Added new option to audio and video components viewend="on" which stops from playing once out of view
* NEW - Added new filter to change the scroll container class for Chapter Component aesop_chapter_scroll_container
* NEW - Added new filter to change the scroll nav class for Chapter Component aesop_chapter_nav_container
* NEW - Added new filter to change the scroll container class for Timeline Component aesop_timeline_scroll_container
* NEW - Mark markers can now do some HTML
* FIXED - Bug with failing function on Android
* CHANGED - The action name for inserting the Timeline component has changed from aesop_inside_body_top to ase_theme_body_inside_top. We’ve included a deprecation notice.

= 1.0.4 =
* FIXED - insecure assets if SSL enabled in wp-admin
* FIXED - wrong audio icon
* NEW - Added option for video player to start automatically once in view
* NEW - Added framewidth and frameheight options to video player to preserve aspect ratio
* NEW - Added option to set columns in Collections Component
* NEW - Added option to set stories shown in Collections Component
* NEW - Added new “splash mode” option for Collections Component that displays collection parents
* NEW - Added theme helper shortcode [aesop_center] to be used in aesop themes where items fall outside the “content” width - props @mauryaratan
* NEW - Two new filters to control the offset scroll distance for both Timeline and Chapter components (aesop_timeline_scroll_offset) and (aesop_chapter_scroll_offset)
* NEW - Added filter to control component generator button (aesop_generator_button)
* NEW - Added filter to control gallery grid spacing (aesop_grid_gallery_spacing)
* NEW - Added filter to add custom css classes to the parent container of all components (aesop_COMPONENTNAME_component_classes)
* CHANGED - The scroll offset integers for Timeline and Chapter components were completely arbitrary. These have been set to 0, from 80, and 36.

= 1.0.3 =
* FIXED - parallax image bug in Firefox
* FIXED - added the missing “title” option for Timeline component
* FIXED - image now aligns to center if center alignment and width are set on image component
* NEW - Added option area to Theme Customizer to allow custom map tiles from Mapbox
* NEW - Added option to set the default zoom level in the Map component
* misc bug fixes

= 1.0.2 =
* FIXED - Better value saving
* FIXED - Hosted video not obeying width set
* FIXED - Stopped parallax from running on mobile
* NEW - Added option for audio player to automatically start once in view
* NEW - Added autoplay option to self hosted video component
* NEW - Added loop option to self hosted video component
* NEW - Added controls option to show/hide controls on self hosted video component
* NEW - Options panel for thumbnail galleries type that includes options to control transition, thumbnails, and autostart
* NEW - Added ability for timeline component to have a different title than what the scroll-to navigation holds
* NEW - Added ability to center align caption on image component
* NEW - Refreshed user interface with icons instead of images

= 1.0.1 =
* MOVED - We removed the “automatic remembering of page position.” It’s quite possible nobody has even noticed this feature, as it wasn’t marketed, documented, nor mentioned. We’ve moved it to an upcoming “essentials” plugin. The main reason; this is an unexpected behavior to happen on pages without story components.
* UPDATED - FitVids script with the latest fix for the “white text on Chromium 32” issue
* MOVED - The “content” width class that’s applied to the Content component, was moved from the parent div (.aesop-content-component), to the child div (.aesop-content-comp-inner), so if background images are used in Content the component still stretches 100%.
* FIXED - Fixed the width passing to content box if “content” is passed as attribute
* FIXED - Fixed map component not centering
* FIXED - Fixed incorrect quote size values
* FIXED - Removed ability to set negative Quote size values
* FIXED - Default color picker values passing to generator
* FIXED - Bug with images not working in Document viewer
* FIXED - Audio player from not recognizing audio
* NEW - Sizes “3” and “4” to the Quote component font size
* NEW - Message to empty generator settings
* NEW - Support for Instagram and Vine within Video Component

= 1.0 =
* Initial Release
