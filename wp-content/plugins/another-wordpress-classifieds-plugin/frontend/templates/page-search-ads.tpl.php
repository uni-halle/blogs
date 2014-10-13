<?php
    foreach ($messages as $message) {
        echo awpcp_print_message($message);
    }

    foreach ($errors as $index => $error) {
        if (is_numeric($index)) {
            echo awpcp_print_message($error, array('error'));
        } else {
            echo awpcp_print_message($error, array('error', 'ghost'));
        }
    }
?>

<form class="awpcp-search-ads-form" method="post" action="<?php echo esc_attr( $page->url() ); ?>"name="myform">
    <?php foreach($hidden as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" />
    <?php endforeach ?>

    <p class='awpcp-form-spacer'>
        <label for="query"><?php _e("Search for Ads containing this word or phrase", "AWPCP"); ?>:</label>
        <input type="text" id="query" class="inputbox" size="50" name="keywordphrase" value="<?php echo esc_attr($form['query']); ?>" />
        <?php echo awpcp_form_error('query', $errors); ?>
    </p>

    <p class="awpcp-form-spacer">
        <?php $dropdown = new AWPCP_CategoriesDropdown(); ?>
        <?php echo $dropdown->render( array(
                'context' => 'search',
                'selected' => awpcp_array_data('category', '', $form),
                'name' => 'searchcategory',
                'required' => false,
              ) ); ?>
    </p>

    <?php if ($ui['posted-by-field']): ?>
    <p class='awpcp-form-spacer'>
        <label for="name"><?php _e("For Ads Posted By", "AWPCP"); ?></label>
        <select id="name" name="searchname">
            <option value=""><?php _e("All Users", "AWPCP"); ?></option>
            <?php echo create_ad_postedby_list($form['name']); ?>
        </select>
    </p>
    <?php endif ?>

    <?php if ($ui['price-field']): ?>
    <p class="awpcp-form-spacer">
        <label for="min-price"><?php _e( 'Price', 'AWPCP' ); ?></label>
        <span class="awpcp-range-search">
            <label for="min-price"><?php _e( "Min", "AWPCP" ); ?></label>
            <input id="min-price" class="inputbox money" type="text" name="searchpricemin" value="<?php echo esc_attr( $form['min_price'] ); ?>">
            <label for="max-price"><?php _e( "Max", "AWPCP" ); ?></label>
            <input id="max-price" class="inputbox money" type="text" name="searchpricemax" value="<?php echo esc_attr( $form['max_price'] ); ?>">
        </label>
        <?php echo awpcp_form_error('min_price', $errors); ?>
        <?php echo awpcp_form_error('max_price', $errors); ?>
    </p>
    <?php endif ?>

    <?php
    $options = array(
        'showTextField' => true,
        'maxRegions' => ($ui['allow-user-to-search-in-multiple-regions'] ? 10 : 1),
    );

    $selector = new AWPCP_MultipleRegionSelector( $form['regions'], $options );
    echo $selector->render( 'search', array(), $errors );
    ?>

    <?php if ($ui['module-extra-fields']): ?>
    <?php echo awpcp_extra_fields_render_form(array(), $form, 'search', $errors); ?>
    <?php endif ?>

    <input type="submit" class="button" value="<?php echo esc_attr( _x( 'Start Search', 'ad search form', "AWPCP" ) ); ?>" />
</form>
