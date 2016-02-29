<?php get_header(); ?>

        <div class='stage <?php the_field("stagetype"); ?>'>
            <?php
            while (has_sub_field("slides")) {
                $field = get_sub_field("image");
                $img = $field["sizes"]["slide"];
                $title = get_sub_field("title");
                $link = get_sub_field("link");
                ?>
                <figure class='slide' style="background-image: url('<?php echo $img; ?>');">
                    <div class='container'>
                        <?php if (get_sub_field('title') || get_sub_field('description')): ?>
                        <div class='overlaycolumn'>
                            <figcaption>
                                <?php if ($title && $link) { ?>
                                    <h3><span class="toggle"></span><?php echo $title; ?></h3>
                                <?php } else if ($title) { ?>
                                    <h3><span class="toggle"></span><?php echo $title; ?></h3>
                                <?php } ?>
                                <summary>
                                    <?php the_sub_field("description"); ?>
                                    <?php if ($link) { ?>
                                        <p><a class='more' href='<?php echo $link; ?>'><?php echo __("Read more", "quintlab"); ?></a></p>
                                    <?php } ?>
                                </summary>
                            </figcaption>
                        </div>
                        <?php endif; ?>
                    </div>
                </figure>
                <?php
            }

            // map
            if (get_field('map_position')) {
                $map_position = get_field('map_position');
                $options = array(
                    'lat' => (float)$map_position['lat'],
                    'lng' => (float)$map_position['lng'],
                    'title' => 'Quintlab - ' . $map_position['address'],
                    'zoom' => 16,
                );
                ?>
                <figure class='slide googlemap' options="<?php echo htmlspecialchars(json_encode($options)); ?>"></figure>
                <?php
            }

            ?>
            <div class='controls'>
                <div class='container'></div>
            </div>
        </div>

        <div class='container breadcrumbs'>
            <?php
            $current_id = get_the_ID();
            $breadcrumbs = array(
                "http://www.uni-halle.de/" => array("label" => "MLU", "class" => "external"),
                "http://www.natfak3.uni-halle.de/" => array("label" => "NatFak III", "class" => "external"),
                "http://www.landw.uni-halle.de/" => array("label" => __("Institute", "quintlab"), "class" => "external"),
                home_url() => array("label" => get_bloginfo('name'), "class" => "home"),
            );
            foreach (get_post_ancestors($current_id) as $ancestor) {
                $breadcrumbs[get_permalink($ancestor)] = array("label" => get_the_title($ancestor));
            }
            if (get_post_type($current_id) == 'publication') {
                $breadcrumbs[get_post_type_archive_link('publication')] = array("label" => 'Publications');
            } else {
                $breadcrumbs[get_permalink($current_id)] = array("label" => get_the_title($current_id));
            }

            echo "<ul>";
            foreach ($breadcrumbs as $url => $opts) {
                echo "<li class=\"$opts[class]\"><a href=\"$url\">$opts[label]</a></li>";
            }
            echo "</ul>";

            ?>
        </div>

        <main class='container'>
            <?php while (have_posts()) : the_post(); ?>

                <!-- main content -->
                <?php
                $style = get_field('content_style');

                $show_image = get_field('show_postthumbnail') === true;
                $title = get_the_title();
                $image = $show_image ? ql_get_post_thumbnail($post->ID, 'medium') : "";
                ob_start();
                the_content();
                $content = ob_get_clean();
                $authors = get_field('authors');

                include("partials/maincontent.php");
                ?>

                <!-- more content blocks -->
                <?php
                while (has_sub_field("content")) {
                    $title = get_sub_field("title");
                    $image = ql_get_acf_image(get_sub_field("image"));
                    $content = get_sub_field("text");
                    $authors = false;

                    include("partials/maincontent.php");
                }
                ?>

                <!-- image items -->
                <?php
                if (get_field('image_items')) {
                    ?>
                    <section>
                        <div class="imageitems <?php echo get_field("image_items_style"); ?>">
                            <?php
                            while (has_sub_field('image_items')) {
                                $image = ql_get_acf_image(get_sub_field('image_item_image'));
                                $imageclass = "";
                                $title = get_sub_field('image_item_title');
                                $text = get_sub_field('image_item_text');
                                include("partials/imageitem.php");
                            }
                            ?>
                        </div>
                    </section>
                    <?php
                }
                ?>

                <!-- teasers -->
                <?php
                $teaser_style = get_field('teaser_style');
                $teaser_imagesize = ($teaser_style == 'normal') ? 'teaser-portrait' : 'teaser-landscape';

                $subpages = array();
                //load subpages if checked
                if (get_field('show_subpage_teasers') === true) {
                    $subpages = array_merge($subpages, get_pages(array(
                        'sort_order' => 'asc',
                        'sort_column' => 'post_title',
                        //only direct children::
                        //'hierarchical' => 0,
                        //'parent' => get_the_ID(),
                        //or all children:
                        'hierarchical' => 1,
                        'child_of' => get_the_ID(),
                    )));
                }
                //load linked pages
                $linked = get_field('teaser_pages');
                if ($linked) {
                    $subpages = array_merge($subpages, $linked);
                }

                if (!empty($subpages)) {
                    ?>
                    <section>
                        <?php
                        $heading = get_field('teaser_heading');
                        if (!empty($heading)) {
                            echo "<h2>$heading</h2>";
                        }
                        ?>
                        <div class="teasers">

                            <?php
                            foreach ($subpages as $post) {
                                setup_postdata($post);
                                $permalink = get_permalink();
                                ?>
                                <article class="teaser <?php echo $teaser_style; ?>">
                                    <?php
                                    $img = has_post_thumbnail() ? ql_get_post_thumbnail($post->ID, $teaser_imagesize) : "";
                                    echo "<a class='image' href='$permalink'>$img</a>";
                                    ?>
                                    <h3><a href="<?php echo $permalink; ?>"><?php the_title(); ?></a></h3>
                                    <summary>
                                        <?php the_excerpt(); ?>
                                        <p>
                                            <a class='more' href='<?php echo $permalink; ?>'><?php echo __("Read more", "quintlab"); ?></a>
                                        </p>
                                    </summary>
                                </article>
                                <?php
                            }
                            wp_reset_postdata();
                            ?>

                        </div>
                    </section>
                    <?php
                }
                ?>

                <!-- publications -->
                <?php
                $publications = array();
                if (get_field('show_all_publications') === true) {
                    $publications = array_merge($publications, get_posts(array(
                        'post_type' => 'publication',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    )));

                }
                if (get_field('show_linked_publications') === true) {
                    $publications = array_merge($publications, get_posts(array(
                        'post_type' => 'publication',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                        'meta_query' => array(
                            array(
                                'key' => 'members', // name of custom field
                                'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
                                'compare' => 'LIKE'
                            )
                        )
                    )));
                }
                $linked = get_field('publications');
                if ($linked) {
                    $publications = array_merge($publications, $linked);
                }

                if (!empty($publications)) {

                    if (get_field('group_publications') === true) {
                        //sort by year
                        function sort_by_year_reverse($a, $b) {
                            return get_field('year', $b->ID) - get_field('year', $a->ID);
                        }
                        usort($publications, 'sort_by_year_reverse');
                        $group = true;
                        $lastyear = false;
                    } else {
                        $group = false;
                    }

                    ?>
                    <section>
                        <?php
                        $heading = get_field('publications_heading');
                        if (!empty($heading)) {
                            echo "<h2>$heading</h2>";
                        }
                        ?>
                        <div class="imageitems">
                            <?php
                            foreach ($publications as $publication) {
                                $image = ql_get_post_thumbnail($publication->ID);
                                $imageclass = "narrow";
                                $title = $publication->post_title;
                                $authors = get_field('authors', $publication->ID);
                                $year = get_field('year', $publication->ID);

                                if ($group && $year !== $lastyear) {
                                    echo "<h3 class='spaced'>$year</h3>";
                                    $lastyear = $year;
                                }

                                $text = "<p class='nospace'><strong>$authors</strong> ($year) $title.</p>" . $publication->post_content;
                                $title = false;
                                include("partials/imageitem.php");
                            }
                            ?>
                        </div>
                    </section>
                    <?php
                }
                ?>

            <?php endwhile; //have_posts() loop ?>

        </main>

<?php get_footer(); ?>
