<div id="fgj2wp_help_instructions">

<h1>FG Joomla to WordPress Instructions</h1>
<img src="https://ps.w.org/fg-joomla-to-wordpress/assets/fg-joomla-to-wordpress.png" alt="FG Joomla to WordPress screenshot" />

<h2>Step 0:</h2>
Before using the plugin, you must:
<ul>
<li>Define the WordPress permalinks on <a href="<?php echo admin_url('options-permalink.php'); ?>" target="_blank">the permalink screen</a><br />
If you want to use the URL redirect (Premium version), you must choose a permalink other than the default one. "Post name" is a good choice.</li>
<li>Define the media sizes on <a href="<?php echo admin_url('options-media.php'); ?>" target="_blank">the media settings screen</a><br />
The plugin will move your Joomla images to the WordPress media library and will resize them to all the sizes defined here.</li>
</ul>

<h2>Step 1:</h2>
<h3>Empty the WordPress content</h3>
<p>This action is not mandatory the first time you run the import. But it is required if you have already ran an import and if you want to restart if from scratch. It will delete all the WordPress content (posts, pages, attachments, categories, tags, navigation menus, custom post types).</p>

<h2>Step 2:</h2>
<h3>Test the connection</h3>
<p>After having filled in the database parameters, you can test the connection to the Joomla database. It will tell you how many articles the plugin has found in the Joomla database.</p>

<h2>Step 3:</h2>
<h3>Run the import</h3>
<p>After having chosen the different import options (see the options help tab), you click on this button to run the import. It can take a long time depending on the number of articles and images in Joomla.</p>
<p>Once the process is finished, it will display the import results.</p>
<p>If the process stops before having imported all the content, you can run it again and it will continue where it left off. This may happen if you have a timeout on your server or if the memory becomes low. In this case, ensure that the automatic removal checkbox is not checked.</p>

<h2>Step 4:</h2>
<h3>Modify the internal links</h3>
<p>Click on this button to modify the links inside each post or page to make them point to the new WordPress URLs.</p>

<?php do_action('fgj2wp_help_instructions'); ?>

</div>
