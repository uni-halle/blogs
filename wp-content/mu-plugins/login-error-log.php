<?php



add_action( 'wp_login_failed', function($user){
	$keys = array(
		'REQUEST_TIME'=>'time',
		'REMOTE_ADDR'=>'client',
		'REQUEST_METHOD'=>'method',
		'HTTPS'=>'ssl',
		'HTTP_HOST'=>'host',
		'REQUEST_URI'=>'uri'
	);
	foreach ($keys as $k=>$v) $$v=$_SERVER[$k];
	$logline=implode(' ',array(
		date('c',$time),$user,
		'FROM',$client,
		($client==$remote=gethostbyaddr($client))?'(not-resolved)':"($remote)",
		$method,'http'.($ssl?'s':'').'://'.$host.$uri
	));
	$week=date('W',$time);
	`echo "$logline" >>\~logins/wp-login-$week.err.log`;
} );

add_action( 'wp_login', function($user){
        $keys = array(
                'REQUEST_TIME'=>'time',
                'REMOTE_ADDR'=>'client',
                'REQUEST_METHOD'=>'method',
                'HTTPS'=>'ssl',
                'HTTP_HOST'=>'host',
                'REQUEST_URI'=>'uri'
        );
        foreach ($keys as $k=>$v) $$v=$_SERVER[$k];
        $logline=implode(' ',array(
                date('c',$time),$user,
                'FROM',$client,
		($client==$remote=gethostbyaddr($client))?'(not-resolved)':"($remote)",
                $method,'http'.($ssl?'s':'').'://'.$host.$uri
        ));
        $week=date('W',$time);
        `echo "$logline" >>\~logins/wp-login-$week.success.log`;
} );

