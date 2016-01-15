<div class="imageitem">
    <div class="image <?php echo $imageclass; ?>"><?php echo $image ?></div>
    <article>
        <?php
        if ($title) {
            echo "<h3>$title</h3>";
        }
        ?>
        <summary>
            <?php echo $text; ?>
        </summary>
    </article>
</div>
