<?php 

// Route /wp-json/custom/v1/public/public
function rest_api_public() {
  $posts = get_posts( array(
  	'numberposts'	=> -1,
    'meta_key' => 'zeitraum_0_begin',
    'orderby' => 'zeitraum_0_begin',
    'order' => 'desc',
  	'meta_query' => array(
  		'relation' => 'AND',
  		array(
  			'key'	=> 'veroffentlichung',
  			'value'	=> '1',
  			'compare'	=> '='
  		),
  		array(
  			'key'	=> 'zustimmung',
  			'value'	=> '1',
  			'compare'	=> '='
  		),
      array(
        'key'	=> 'status',
        'value'	=> '4',
        'compare'	=> '='
      )
  	)
  ));
  $response = array(
    "timestamp" => time(),
    "items" => count($posts)
  );
  foreach ( $posts as $post ) {
    $fields = get_fields($post->ID);
    $response["result"][] = array(
      "post" => $post->ID,
      "titel" => trim($post->post_title),
      "kategorie" => $fields['kategorie'],
      "beginn" => date("d.m.Y", strtotime($fields['zeitraum'][0]['begin'])),
      "ende" => date("d.m.Y", strtotime($fields['zeitraum'][0]['ende']))
    );
  };
  return $response;
}
add_action( 'rest_api_init', function () {
  register_rest_route( 'custom/v1', '/public', array(
    'methods' => 'GET',
    'callback' => 'rest_api_public',
  ));
});

// Route /wp-json/custom/v1/public/<post>
function rest_api_public_post( $data ) {
  $posts = get_posts( array(
    'numberposts'	=> 1,
    'include' => $data['post'],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'	=> 'veroffentlichung',
        'value'	=> '1',
        'compare'	=> '='
      ),
      array(
        'key'	=> 'zustimmung',
        'value'	=> '1',
        'compare'	=> '='
      ),
      array(
        'key'	=> 'status',
        'value'	=> '4',
        'compare'	=> '='
      )
    )
  ));
  $response = array(
    "timestamp" => time(),
    "items" => count($posts)
  );
  if (!empty($posts)) {
    $fields = get_fields($posts[0]->ID);
    $response["result"] = array(
      "post" => $posts[0]->ID,
      "titel" => trim($posts[0]->post_title),
      "kategorie" => $fields['kategorie'],
      "beginn" => date("d.m.Y", strtotime($fields['zeitraum'][0]['begin'])),
      "ende" => date("d.m.Y", strtotime($fields['zeitraum'][0]['ende'])),
      "bericht" => trim($fields['bericht']),
      "abbildung" => ($fields['abbildung'] ? true : false)
    );
  };
  return $response;
}
add_action( 'rest_api_init', function () {
  register_rest_route( 'custom/v1', '/public/(?P<post>\d+)', array(
    'methods' => 'GET',
    'callback' => 'rest_api_public_post',
  ));
});

// Route /wp-json/custom/v1/public/<post>/image
function rest_api_public_post_image( $data ) {
  $posts = get_posts( array(
    'numberposts'	=> 1,
    'include' => $data['post'],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key'	=> 'veroffentlichung',
        'value'	=> '1',
        'compare'	=> '='
      ),
      array(
        'key'	=> 'zustimmung',
        'value'	=> '1',
        'compare'	=> '='
      ),
      array(
        'key'	=> 'status',
        'value'	=> '4',
        'compare'	=> '='
      ),
      array(
        'key'	=> 'abbildung',
        'value'	=> array(''),
        'compare'	=> 'NOT IN'
      )
    )
  ));
  $response = array(
    "timestamp" => time(),
    "items" => count($posts)
  );
  if (!empty($posts)) {
    $fields = get_fields($posts[0]->ID);
    $meta = wp_get_attachment_metadata( $fields['abbildung'] );
    $dirs = wp_get_upload_dir();
    $file = $dirs["basedir"] . '/' . $meta["file"];
    header("Content-Type: " . mime_content_type($file));
    readfile($file);
    exit();
  };
  return $response;
}
add_action( 'rest_api_init', function () {
  register_rest_route( 'custom/v1', '/public/(?P<post>\d+)/image', array(
    'methods' => 'GET',
    'callback' => 'rest_api_public_post_image',
  ));
});

?>
