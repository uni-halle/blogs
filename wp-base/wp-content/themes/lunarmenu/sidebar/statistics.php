<li><h1><?php _e('Blog Statistics','avenue'); ?></h1><ul>
<li><?php _e('Number of Entries: (','avenue'); ?><?php global $numposts; echo $numposts; ?>)</li>
<li><?php _e('Number of Comments: (','avenue'); ?><?php global $numcmnts; echo $numcmnts;?>)</li>
<li><?php _e('Number of Words: (','avenue'); ?><?php mdv_post_word_count_edit(); ?>)</li>
</ul></li>