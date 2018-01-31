<div class="awpcp-classifieds-search-bar" data-breakpoints='{"tiny": [0,450]}' data-breakpoints-class-prefix="awpcp-classifieds-search-bar">
    <form action="<?php echo esc_url( $action_url ); ?>" method="GET">
        <input type="hidden" name="awpcp-step" value="dosearch" />
        <div class="awpcp-classifieds-search-bar--query-field">
            <input type="text" name="keywordphrase" />
        </div>
        <div class="awpcp-classifieds-search-bar--submit-button">
            <input class="button" type="submit" value="<?php echo esc_attr( __( 'Find Listings', 'another-wordpress-classifieds-plugin' ) ); ?>" />
        </div>
        <div class="awpcp-classifieds-search-bar--advanced-search-link"><a href="<?php echo esc_url( $action_url ); ?>"><?php echo esc_html( __( 'Advanced Search', 'another-wordpress-classifieds-plugin' ) ); ?> </a></div>
    </form>
</div>
