<section>
    <?php

    if ($title) {
        echo "<h2>$title</h2>";
    }

    if ($style === 'image_text') {

        echo "<figure class='column third'>$image</figure>";
        echo "<article class='column twothirds'>$content</article>";

    } else if ($style === 'text_image') {

        echo "<article class='column twothirds'>$content</article>";
        echo "<figure class='column third'>$image</figure>";

    } else if ($style === 'text_form') {

        echo "<article class='column'>$content</article>";
        echo "<div class='column'>";
        do_shortcode("[wp_simpleform]");
        echo "</div>";

    } else {

        switch ($style) {
            case 'text_text':
                $articleclass = 'double';
                break;
            case 'text_text_text':
                $articleclass = 'triple';
                break;
            default:
                $articleclass = '';
                break;
        }
        echo "<article class=\"textcolumns $articleclass\">";
        if ($image) {
            echo "<div class=\"image\">$image</div>";
        }
        if ($authors) {
            echo "<p class='nospace'><strong>$authors</strong></p>";
        }
        echo $content;
        echo "</article>";

    }

    ?>

</section>
