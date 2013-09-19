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

<?php if ($ui['module-region-fields']): ?>
<?php echo awpcp_region_control_selector(); ?>
<?php endif ?>

<form class="awpcp-search-ads-form" method="post" action="<?php echo $page->url(); ?>"name="myform">
    <?php foreach($hidden as $name => $value): ?>
    <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" />
    <?php endforeach ?>

    <p class='awpcp-form-spacer'>
        <label for="query"><?php _e("Search for Ads containing this word or phrase", "AWPCP"); ?>:</label>
        <input type="text" id="query" class="inputbox" size="50" name="keywordphrase" value="<?php echo esc_attr($form['query']); ?>" />
        <?php echo awpcp_form_error('query', $errors); ?>
    </p>

    <p class='awpcp-form-spacer'>
        <label for="category"><?php _e("Search in Category", "AWPCP"); ?></label>
        <select id="category" name="searchcategory">
            <option value=""><?php _e("Select Option", "AWPCP"); ?></option>
            <?php echo get_categorynameidall($form['category']); ?>
        </select>
    </p>

    <?php if ($ui['posted-by-field']): ?>
    <p class='awpcp-form-spacer'>
        <label for="name"><?php _e("For Ads Posted By", "AWPCP"); ?></label>
        <select id="name" name="searchname">
            <option value=""><?php _e("Select Option", "AWPCP"); ?></option>
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
    $query = array(
        'country' => $form['country'],
        'state' => $form['state'],
        'city' => $form['city'],
        'county' => $form['county']
    );
    $translations = array(
        'country' => 'searchcountry',
        'state' => 'searchstate',
        'city' => 'searchcity',
        'county' => 'searchcountyvillage'
    );

    if ($ui['module-region-fields'])
        echo awpcp_region_control_form_fields($query, $translations, $errors);
    else
        echo awpcp_region_form_fields($query, $translations, 'search', $errors);
    ?>

    <?php if ($ui['module-extra-fields']): ?>
    <?php echo awpcp_extra_fields_render_form(array(), $form, 'search', $errors); ?>
    <?php endif ?>

    <input type="submit" class="button" value="<?php _ex('Start Search', 'ad search form', "AWPCP"); ?>" />
</form>
