<!-- Container -->
<div id="p-tl-cont" class="container p-tl-cont">
<style type="text/css">
    .p-tl-cont .col-md-12 li {
        font-family: sans-serif;
        font-style: oblique;
    }
</style>
	<div class="row ptl-inner-cont">
        <div class="col-md-12">
            <h3 class="alert alert-info head-1">How to use Post Timeline?</h3>
            <ol>
                <li>Add New Tags for Tag based Navigation [post-timeline ptl-type="tag"], it is must to <a href="edit-tags.php?taxonomy=post_tag&post_type=post-timeline">Add Tags</a> and Assign to your Timeline Posts. No need to create tags for Date based Timeline, <br>use shortcode [post-timeline ptl-type="date"].</li>
                <li>Add Timeline Posts through <a href="post-new.php?post_type=post-timeline">Add New</a> Timeline Posts.</li>
                <li><a href="edit-tags.php?taxonomy=category&post_type=post-timeline">Add New Category</a> and assign Categories to your Timeline Posts and add category_id attribute in the shortcode to show Timeline of that category only,<br> shortcode [post-timeline category_id="xxx"], where xxx is the Category ID.</li>
                <li>Add the Shortcode on your <a href="post-new.php?post_type=page">WordPress Page</a> or <a href="post-new.php">WordPress Post</a>. Make sure container width is set to 100% width.</li>
            </ol>
        </div>
        <div class="col-md-12">
        <h3 class="alert alert-info head-1">23 Templates of Post Timeline (<a target="_blank" href="https://posttimeline.com/">Pro Version</a>)</h3>
        </div>
        <div class="col-md-12 ptl-admin-tmpls">
            <div class="row text-center">
                <?php 
                foreach ($ptl_templates as $ptl_template):
                ?>
                <div class="template-box col-md-3">
                    <div class="template-cover">
                        <div class="template-over-lay"></div>
                        <div class="template-image">
                            <div class="template-image" style="background-image:url('<?php echo $ptl_template['image']; ?>" class="img-responsive');">
                            </div>
                        </div>
                    </div>
                    <div class="template-details">
                        <h3 class="timeline-title"><?php echo $ptl_template['template_name']; ?></h3>
                    </div>
                    <div class="template-view">
                        <a class="btn btn-link" target="_blank" href="https://posttimeline.com/<?php echo $ptl_template['href']; ?>?v=lite" data-id="<?php echo $ptl_template['id']; ?>">Pro Demo</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
	</div>
</div>