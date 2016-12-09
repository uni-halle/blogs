<div id="fgj2wp_help_options">
<h1>FG Joomla to WordPress Options</h1>

<h2>Empty WordPress content</h2>
<p>Before running the import or if you want to rerun the import from scratch, you can empty the WordPress content.</p>
<p><strong>Remove only the new imported posts:</strong> Only the new imported posts will be removed when you click on the "Empty WordPress content" button.</p>
<p><strong>Remove all WordPress content:</strong> All the WordPress content (posts, pages, attachments, categories, tags, navigation menus, custom post types) will be removed when you click on the "Empty WordPress content" button.</p>
<p><strong>Automatic removal:</strong> If you check this option, all the WordPress content will be deleted when you click on the Import button.</p>


<h2>Joomla web site parameters</h2>

<p><strong>URL:</strong> In this field, you fill in the Joomla home page URL.</p>


<h2>Joomla database parameters</h2>

<p>You can find the following informations in the Joomla file <strong>configuration.php</strong></p>

<p><strong>Hostname:</strong> $host</p>
<p><strong>Port:</strong> By default, it is 3306.</p>
<p><strong>Database:</strong> $db</p>
<p><strong>Username:</strong> $user</p>
<p><strong>Password:</strong> $password</p>
<p><strong>Joomla Table Prefix:</strong> $dbprefix</p>


<h2>Behavior</h2>

<p><strong>Import introtext:</strong> The text before the «More» split can be imported to the post excerpt or to the post content or to both.</p>

<p><strong>Archived posts:</strong> The archived posts can be imported as drafts, as published posts or not imported.</p>

<p><strong>Medias:</strong><br />
<ul>
<li><strong>Skip media:</strong> You can import or skip the medias (images, attached files).</li>
<li><strong>Import first image:</strong> You can import the first image contained in the article as the WordPress post featured image or just keep it in the content (as is), or to both.</li>
<li><strong>Import external media:</strong> If you want to import the medias that are not on your site, check the "External media" option. Be aware that it can reduce the speed of the import or even hang the import.</li>
<li><strong>Import media with duplicate names:</strong> If you have several images with the exact same filename in different directories, you need to check the "media with duplicate names" option. In this case, all the filenames will be named with the directory as a prefix.</li>
<li><strong>Force media import:</strong> If you already imported some images and these images are corrupted on WordPress (images with a size of 0Kb for instance), you can force the media import. It will overwrite the already imported images. In a normal use, you should keep this option unchecked.</li>
<li><strong>Timeout for each media:</strong> The default timeout to copy a media is 5 seconds. You can change it if you have many errors like "Can't copy xxx. Operation timeout".</li>
</ul>
</p>

<p><strong>Meta keywords:</strong> You can import the Joomla meta keywords as WordPress tags linked to the posts.</p>

<p><strong>Create pages:</strong> You have the choice to import the Joomla articles as WordPress posts or pages.</p>

<p><strong>Create submenus with subcategories for categories menus:</strong> <span class="fgj2wp_premium_feature">(Premium feature)</span>
This option will create automatically subcategories menus for each category menu.

<p><strong>SEO <span class="fgj2wp_premium_feature">(Premium feature)</span>:</strong>
<ul>
<li><strong>Import the meta description and the meta keywords to WordPress SEO by Yoast:</strong> If you are using the WordPress SEO by Yoast plugin, this option will import the articles meta data into the WordPress posts.</li>
<li><strong>Set the meta data from menus instead of articles:</strong> If you stored the meta data (description and keywords) in the Joomla menus instead of in the articles, you must check this option.</li>
<li><strong>Set the post slugs from menus instead of aliases:</strong> Normally the WordPress post slugs are the same as the Joomla article aliases, but it you prefer that the slugs are defined from the Joomla menus slugs, you can check this option.</li>
<li><strong>Keep the Joomla articles IDs:</strong> With this option checked, the WordPress post IDs will be the same as the Joomla ones. If you choose this option, you need to empty all the WordPress content before the import.</li>
<li><strong>Redirect the Joomla URLs:</strong> With this option checked, the old Joomla article links will be automatically redirected to the new WordPress URLs. It uses "301 redirect". By this way, the SEO will be kept. The plugin must remain active to redirect the URLs.</li>
</ul></p>

<p><strong>Partial import <span class="fgj2wp_premium_feature">(Premium feature)</span>:</strong> If you don't want to import all the Joomla data, you can use this option. Please note that even if you don't use this option and if you rerun the import, the already imported content won't be imported twice.</p>

<?php do_action('fgj2wp_help_options'); ?>

</div>
