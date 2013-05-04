<?php

// CHECK COOKIES AND GRAB INFOS ABOUT USER FROM DATABASE

if( intval( $_COOKIE['user_id'] ) && ! $_SESSION['logged'] ){
	
	$member_id = array ();
	
	$member_id = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE username = '" . $_COOKIE['login_user'] . "' AND password = '" . $_COOKIE['login_pass'] . "'");
	
	if( $member_id['user_id'] ){
		
		@session_register( 'logged' );
		
		@session_register( 'member' );
		
		$_SESSION['username'] = $member_id['username'];
		
		$_SESSION['role'] = $member_id['role'];
		
		$_SESSION['member'] = $member_id;
		
		$_SESSION['logged'] = TRUE;
		
    }
    
}

// LOGOUT FROM SITE AND ADMIN

if( $_REQUEST['action'] == 'logout' ){
	set_cookie( "user_id", "", 0 );
	set_cookie( "login_user", "", 0 );
	set_cookie( "login_pass", "", 0 );
	
	// unset cookies
	if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    	}
	}
	
	@session_destroy();	
	@session_unset();
	header("Location: " . $config['siteurl'] );
	die();
}
$db->close ();
?>