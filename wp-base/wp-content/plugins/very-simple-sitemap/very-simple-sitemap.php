<?php
/*
Plugin Name: Very Simple Sitemap
Plugin URI: http://roidayan.com
Description: Sitemap shortcode to help create a simple sitemap page
Version: 1.1
Author: Roi Dayan
Author URI: http://roidayan.com
License: GPLv2

Based the info on http://www.intenseblog.com/wordpress/wordpress-sitemap-page.html

Copyright (C) 2012  Roi Dayan  (email : roi.dayan@gmail.com)

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class WPVerySimpleSitemap {
    function WPVerySimpleSitemap() {
        /* Add pages to exclude here.
         * e.g.: $this->exclude_pages = '101,102';
         * will exclude pages with ids 101 and 102.
         */
        $this->exclude_pages = '';
    
        $this->sitemap_xml = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'sitemap.xml';
        $this->robots_txt = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'robots.txt';
        $this->cron_event = __CLASS__ . 'cronevent';
        
        register_activation_hook(__FILE__, array(&$this, 'activate'));
        register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));
        add_shortcode('sitemap', array(&$this, 'output_sitemap'));
        add_action($this->cron_event, array(&$this, 'generate_sitemap_xml'));
        
        if (!file_exists($this->robots_txt))
            $this->generate_robots_txt();
    }
    
    function activate() {
        wp_schedule_event(time(), 'hourly', $this->cron_event);
    }
    
    function deactivate() {
        wp_clear_scheduled_hook($this->cron_event);
    }
    
    function generate_robots_txt() {
        $home_xml = trailingslashit(home_url()).'sitemap.xml.gz';
        $data = "User-agent: *\n";
        $data .= "Disallow:\n";
        $data .= "Sitemap: $home_xml";
        file_put_contents($this->robots_txt, $data);
    }
    
    function generate_sitemap_xml() {
        $sitemap_data = $this->get_sitemap_xml_content();
        file_put_contents($this->sitemap_xml, $sitemap_data);
        /* gzip sitemap */
        $gzf = gzopen($this->sitemap_xml.'.gz', 'w9');
        gzwrite($gzf, $sitemap_data);
        gzclose($gzf);
    }
    
    function get_sitemap_xml_content() {
        $urls = array(home_url());
        /* collect links to pages */
        $args = array(
            'exclude' => $this->exclude_pages
        );
        $pages = get_pages($args);
        foreach ($pages as $page)
            $urls[] = get_page_link($page->ID);
        /* collect links to posts */
        $archive_query = new WP_Query('posts_per_page=1000');
        while ($archive_query->have_posts()) {
            $archive_query->the_post();
            $urls[] = get_permalink($post->ID);
        }
        /* reset post data */
        wp_reset_postdata();
        /* generate output */
        $output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $output .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls as $url) {
            $url = htmlentities($url);
            $output .= "<url><loc>$url</loc></url>\n";
        }
        $output .= '</urlset>';
        return $output;
    }
    
    function output_sitemap($atts, $content='') {
    ?>
         <h3>Pages</h3>
            <ul>
            <?php
                $args = array(
                    'title_li=' => '',
                    'exclude' => $this->exclude_pages
                );
                wp_list_pages($args);
            ?>
            </ul>
        <h3>Feeds</h3>
            <ul>
                <li><a title="Full content" href="feed:<?php bloginfo('rss2_url'); ?>">Main RSS</a></li>
                <li><a title="Comment Feed" href="feed:<?php bloginfo('comments_rss2_url'); ?>">Comment Feed</a></li>
            </ul>
        <h3>Categories</h3>
            <ul>
            <?php wp_list_categories('orderby=name&show_count=1&hierarchical=0&feed=RSS&title_li='); ?>
            </ul>
        <h3>All Blog Posts</h3>
            <ul>
            <?php
            $archive_query = new WP_Query('posts_per_page=1000');
            while ($archive_query->have_posts()):
                $archive_query->the_post();
            ?>
                <li>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a>
                    (<?php comments_number('0', '1', '%'); ?>)
                </li>
            <?php
            endwhile;
            /* reset post data */
            wp_reset_postdata();
            ?>
            </ul>
        <h3>Archives</h3>
            <ul>
                <?php wp_get_archives('type=monthly&show_post_count=true'); ?>
            </ul>
    <?php
    }
}

new WPVerySimpleSitemap();
?>