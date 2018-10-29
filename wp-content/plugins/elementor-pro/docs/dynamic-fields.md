#Elementor Pro - Dynamic Fields

##Text Fields
---

##  Post Title
Print the current post title.

## Post Excerpt
Print the current post excerpt.

###### Settings
* **Length** - The Maximum number of words to show. Default: 55.
* **More** - The "Read More" Indicator if the Excerpt is too long. Default: [&hellip;].

## Post Content
Print the current post content. (Available only in Templates).

## Post Date
Print the current post date.

###### Settings
* **Format** - The date format. Default: Wordpress Default.
	* *F j, Y*: Example: September 27, 2017
	* *Y-m-d*: Example: 2017-09-27
	* *m/d/Y*: Example: 09/27/2017
	* *d/m/Y*: Example: 27/09/2017
	* *Human Readable*: 2 Days Ago
	* *custom*: Custom format
* **Custom Format** - A custom format string according to the [Documentation](https://codex.wordpress.org/Formatting_Date_and_Time)

## Post Time
Print the current post time.

###### Settings
* **Format** - The time format Default: Wordpress Default.
	* *g:i a*: Example: 2:27 pm
	* *g:i A*: Example: 2:27 PM
	* *H:i*: Example: 14:24
	* *custom*: Custom format
* **Custom Format** - A custom format string according to the [Documentation](https://codex.wordpress.org/Formatting_Date_and_Time)
	
## Post Meta
Print a post meta value.

###### Settings
* **Key** - The meta key to show. (meta keys that start's with an underscore are hidden).

## Post Terms
Print a post meta value.

###### Settings
* **Taxonomy** - The taxonomy to show. Default: "Tags".
* **Prefix** - A prefix that printed before the terms - only if has terms. Default: Empty.
* **Suffix** - A suffix that printed after the terms - only if has terms. Default: Empty.
* **Separator** - The separator to use. Default: ", ".

## Post Thumbnail Details
Print a post thumbnail detail.

###### Settings
* **Name** - The meta field to show. Default: Title.
	* *Title*: Example: Coffee
	* *Caption*:  Example: Coffee is the engine behind a Developer. (Photo: SWang/Flickr)
	* *Description*: Example: I can't develop without a coffee cup near me.

## Post ID
Print the current post ID.

## Post URL
Print the current post url.

## Author Name
Print the current post author.

###### Settings
* **Link To** - Whether to show the author name as a link to one of the following options. Default: None.
	* *None*: Without a link
	* *Author Archive*: Link to the author's posts archive
	* *Author URL*: Link to the URL from the author WordPress profile - if exist.

## Author Info
Print info from current post author profile.

###### Settings
* **Field** - Which field to show: Email / Website / Bio

## Author Meta
Print current post author meta.

###### Settings
* **Key** - The meta key.

## Comment Count
Print the current post comments count.

###### Settings
* **No Comments Format** - What to show if no comments yet. Default: "No Responses".
* **One Comment Format** - What to show for one comments. Default: "One Response".
* **Many Comment Format** - What to show for many comments. It possible to use the number with a placeholder "{number}". Default: "{number} Responses".
* **Link To** - Whether to show the comment number as a link to the comments anchor. Default: None.
	* *None*: Without a link
	* *Comments Link*: Link to the comments anchor, for example http://domain.com/post-name/#comments.

## Post Facebook Shares Count
Print the current post shares on facebook.

## Site URL
Print the site url.

## Site Name
Print the site name.
 
## Site Tagline
Print the site tagline.

##Images Fields
---

## Author Gravatar
Print the current post author gravatar.

## Post Thumbnail
Print the current post thumbnail gravatar.