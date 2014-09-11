<?php
/*
Plugin Name: COinS Metadata Exposer
Plugin URI: http://www.zotero.org
Description: Makes your blog readable by Zotero and other COinS interpreters.
Version: 0.5
Author: Sean Takats - Center for History and New Media
Author URI: http://chnm.gmu.edu

Copyright 2007 Sean Takats (email: stakats@gmu.edu)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_filter('the_content', 'coinsify_the_content');

function coinsify_the_content($content)
{
	foreach((get_the_category()) as $cat) {
		$cats = $cats . "&amp;rft.subject=" . urlencode($cat->cat_name);
	} ?>
	
	<span class="Z3988" title="ctx_ver=Z39.88-2004&amp;rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Adc&amp;rfr_id=info%3Asid%2Focoins.info%3Agenerator&amp;rft.title=<?php echo(urlencode(get_the_title())); ?>&amp;rft.aulast=<?php echo(urlencode(get_the_author_lastname())); ?>&amp;rft.aufirst=<?php echo(urlencode(get_the_author_firstname())); echo $cats; ?>&amp;rft.source=<?php echo(urlencode(get_bloginfo('name'))); ?>&amp;rft.date=<?php the_time('Y-m-d'); ?>&amp;rft.type=blogPost&amp;rft.format=text&amp;rft.identifier=<?php the_permalink(); ?>&amp;rft.language=English"></span>
<?php 
	return $content;
}
?>