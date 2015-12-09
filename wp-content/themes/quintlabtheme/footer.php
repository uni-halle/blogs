        <footer>
            <div class='container'>
                <div class='logo'>
                    <a href='<?php echo home_url(); ?>/'>
                        <img alt='' src='<?php echo QL_BASE; ?>img/logo_quintlab_white.png'>
                    </a>
                </div>
            </div>
            <div class='container'>
                <div class='column'>
                    <?php the_field('footer1', 'options'); ?>
                </div>
                <div class='column'>
                    <?php the_field('footer2', 'options'); ?>
                </div>
                <div class='column'>
                    <?php the_field('footer3', 'options'); ?>
                </div>
            </div>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>
