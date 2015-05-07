=== Plugin Name ===
Contributors: danlester
Tags: doc, docx, pdf, office, powerpoint, google, document, embed, intranet
Requires at least: 3.3
Tested up to: 4.2
Stable tag: 2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Embed PDFs straight into your posts and pages, with intelligent resizing of width and height. No third-party services or iframes required. 

== Description ==

Upload PDFs and embed them straight into your site - just like adding images! PDFs will be automatically sized to their natural size and shape (or just fill the width available if they are too big). Optionally, you can specify a width and the correct height will be calculated automatically. The shape will be recalculated whenever the page is resized by the user.

The plugin has a unique method for embedding PDFs, using Javascript only, and _not_ using iframes or third-party services. This gives a lot of flexibility over the appearance of your document.

The viewer currently has Next/Prev buttons to page through the document, and zoom buttons. There is no button to download the entire PDF (but of course a savvy user will be able to obtain the content since it is displayed to them).

It uses a customized version of Mozilla's PDF.js project, but embeds it within your existing WordPress pages so we have full control over appearance. Other plugins may use similar technology, but they will insert the PDF itself into an 'iframe' which means they do not get the flexibility over sizing.

= Usage =

Once installed and Activated, click Add Media from any page or post, just like adding an image, but drag and drop a PDF file instead.

When you insert into your post, it will appear in the editor as a 'shortcode' as follows:

[pdf-embedder url="https://mysite.com/wp-content/uploads/2015/01/Plan-Summary.pdf"]

See [Frequently Asked Questions](https://wordpress.org/plugins/pdf-embedder/faq/) for information about sizing options plus other ways to customize the shortcodes.

= Mobile-friendly embedding using PDF Embedder Premium =

The free version of the plugin should work on most mobile browsers, but it will be cumbersome for users with small screens - it is difficult to position the document entirely within the screen, and your users' fingers may catch the entire browser page when they're trying only to move about the document...

Our **PDF Embedder Premium** plugin solves this problem with an intelligent 'full screen' mode. 
When the document is smaller than a certain width, the document displays only as a 'thumbnail' with a large 'View in Full Screen' button for the user to click when they want to study your document. 
This opens up the document so it has the full focus of the mobile browser, and the user can move about the document without hitting other parts of the web page by mistake. Click Exit to return to the regular web page.

See our website [wp-pdf.com](http://wp-pdf.com/premium/?utm_source=PDF%20Readme%20Premium&utm_medium=freemium&utm_campaign=Freemium) for more details and purchase options.

= Protect your PDFs with our secure premium version =

Our **PDF Embedder Premium Secure** plugin provides the same simple but elegant viewer as the premium version, with the added protection that it is difficult for users to 
download or print the original PDF document.

This means that your PDF is unlikely to be shared outside your site where you have no control over who views, prints, or shares it.

See our website [wp-pdf.com](http://wp-pdf.com/secure/?utm_source=PDF%20Readme%20Secure&utm_medium=freemium&utm_campaign=Freemium) for more details and purchase options.


== Screenshots ==

1. Uploaded PDF is displayed within your page/post at the correct size to fit. 
2. User hovers over document to see Next/Prev page butons.

== Frequently Asked Questions ==

= How can I obtain support for this product? =

Please feel free to email [contact@wp-pdf.com](mailto:contact@wp-pdf.com) with any questions.

Always include your full shortcode, plus links to the relevant pages, and screenshots if they would be helpful too. 

We may occasionally be able to respond to support queries posted on the 'Support' forum here on the wordpress.org
plugin page, but we recommend sending us an email instead if possible.

= How can I change the Size? =

You can optionally override width and height as follows:

[pdf-embedder url="https://mysite.com/wp-content/uploads/2015/01/Plan-Summary.pdf" **width="500"**]

Note the default value for width is 'max'.

Resizing works as follows:

* If width='max' the width will take as much space as possible within its parent container (e.g. column within your page).
* If width='auto' the width will be equal to the 'natural' width of the PDF document contents (i.e. whatever width the PDF says it should be by default).
* If width is a number (e.g. width='500') then it will display at that number of pixels wide.

*In all cases, if the parent container is narrower than the width calculated above, then the document width will be reduced to the size of the container.*

The height will be calculated so that the document fits naturally, given the width already calculated.

It is possible to specify a fixed height (e.g. height="200"), in which case the document may be cut off vertically. 
The height will be reduced to fit if it is larger than needed to display the document correctly.

= Can I customize the Toolbar? =

_Toolbar Location_

Add toolbar="[top|bottom|both]" to the shortcode to change the location of the Next/Prev toolbar (default is 'bottom').

E.g. [pdf-embedder url="https://mysite.com/wp-content/uploads/2015/01/Plan-Summary.pdf" **toolbar="top"**]

_Toolbar Fixed/Hover_

Add toolbarfixed="on" (default is 'off') to keep the toolbar open at all times rather than only when the user hovers over the document. 
Note this of course extends the length of your embedded area.

E.g. [pdf-embedder url="https://mysite.com/wp-content/uploads/2015/01/Plan-Summary.pdf" **toolbarfixed="on"**]

= Can I improve the viewing experience for mobile users? =

Yes, our **PDF Embedder Premium** plugin has an intelligent 'full screen' mode. 
When the document is smaller than a certain width, the document displays only as a 'thumbnail' with a large 'View in Full Screen' button for the user to click when they want to study your document. 
This opens up the document so it has the full focus of the mobile browser, and the user can move about the document without hitting other parts of the web page by mistake. 
Click Exit to return to the regular web page.

See our website [wp-pdf.com](http://wp-pdf.com/premium/?utm_source=PDF%20Readme%20FAQ%20Premium&utm_medium=freemium&utm_campaign=Freemium) for more details and purchase options.

= Can I protect my PDFs so they are difficult for viewers to download directly? =

Not with the free or (regular) premium versions - it is relatively easy to find the link to download the file directly.

A **secure premium** version is available that encrypts the PDF during transmission, so it is difficult for a casual user to save or print the file for use outside your site.

See our website [wp-pdf.com](http://wp-pdf.com/secure/?utm_source=PDF%20Readme%20FAQ%20Secure&utm_medium=freemium&utm_campaign=Freemium) for more details and purchase options.


== Installation ==

Easiest way:

1. Go to your WordPress admin control panel's plugin page
1. Search for 'PDF Embedder'
1. Click Install
1. Click Activate on the plugin

If you cannot install from the WordPress plugins directory for any reason, and need to install from ZIP file:

1. Upload directory and contents to the `/wp-content/plugins/` directory, or upload the ZIP file directly in
the Plugins section of your Wordpress admin
1. Follow the instructions from step 4 above

== Changelog ==

= 2.0 =

Added zoom feature. Toolbars can be fixed instead of appearing on hover.

= 1.2.1 =

Fixed 'scrollbars' in IE.

= 1.2 =

Fixed 'scrollbar' issues.

Displays page number on toolbar ("Page 1/10").

Added 'Loading...' indicator.

Improved display of many PDFs (Added 'cmaps' to the distribution).

= 1.0.4 =

Added compatibility.js to support some minor browsers, e.g. Safari which did not allow ranged downloads

= 1.0.2 =

Minified Javascript code. Default width/height (now "max") expands to fill parent container width regardless of the natural size of the document. Use width="auto" to obtain the old behavior.

= 1.0.1 =

Added usage instructions within the settings page.

= 1.0 =
First version
