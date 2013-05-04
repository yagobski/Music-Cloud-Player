<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

require_once ROOT_DIR . '/modules/functions.php';

require_once INCLUDE_DIR . '/login.php';

$action = $db->safesql( $_REQUEST['action'] );

$username = $db->safesql( $_REQUEST['username'] );

$username = preg_replace("/[^a-zA-Z0-9\s]/", "", $username);

$playlist = $db->safesql( $_REQUEST['playlist'] );


if( $action == "get" ){
	
	
	if( ! $_SESSION['logged'] ){
	
		header('HTTP/1.0 404 Not Found');
		
		$buffer =  'error';
		
	}else{
		
		$db->query("SELECT * FROM vass_playlist WHERE user_id = '" . $_SESSION['member']['user_id'] . "'");
		
		while ($row = $db->get_row()){
			
			$buffer .= '{"id":' . json_encode( $row['id'] ) . ',"playlist":' . json_encode( $row['playlist'] ) . ',"song_id":' . json_encode( $row['song_id'] ) . ',"title":' . json_encode( $row['song_name'] ) . ',"artist":' . json_encode( $row['song_artist'] ) . ',"album":null},';
			
		}
		
		$buffer = substr( $buffer, 0, ( strLen( $buffer ) - 1 ) );
		
		$buffer =  '{"username":' . json_encode( $_SESSION['member']['username'] ) . ',"results": { "song" : [ ' . $buffer . ' ] } }';
		
	}
	
}elseif( $action == "login" ){
	
	if( ! $_SESSION['logged'] && $username ){
		
		$login_user = $db->safesql( $username );
		
		$login_pass = $db->safesql( $password );
		
		$member_id = array ();
		
		$login_pass	= md5($login_pass);
		
		$member_id = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE username = '" . $login_user . "' AND password ='" . $login_pass . "'");
		
		if( $member_id['user_id'] ){
		
			@session_register( 'logged' );
			
			@session_register( 'member' );
	    	
			$_SESSION['logged'] = TRUE;
			
			$_SESSION['member'] = $member_id;
			
			set_cookie( "user_id", $member_id['user_id'], 365 );
			
			set_cookie( "login_user", $login_user, 365 );
			
			set_cookie( "login_pass", $login_pass, 365 );
	    	
			$db->query("UPDATE vass_users SET last_date = '" . date( "Y-m-d H:i:s", time() ) . "' WHERE user_id = '" . $member_id['user_id'] . "'");
			
			$buffer =  '{"result":"success","username":"' . $username . '","key":"' . md5( $username ) . '"}';
			
	    }else{
			
			$buffer =  '{"result":"failed","username":"","key":""}';
			
		}
	}
	
}elseif( $action == "addSong" ){
	
	$songid = $db->safesql( $_REQUEST['song_id'] );
	
	$song_title = $db->safesql( $_REQUEST['title'] );
	
	$song_artist = $db->safesql( $_REQUEST['artist'] );
	
	$song_album = $db->safesql( $_REQUEST['album'] );
	
	$db->query("INSERT IGNORE INTO " . PREFIX . "_playlist SET playlist = '" . $playlist . "', song_id = '" . $songid . "', user_id= '" . $_SESSION['member']['user_id'] . "', song_name = '" . $song_title . "', song_artist = '" . $song_artist . "', created_on = '" . date( "Y-m-d H:i:s", time() ) . "'");
	
	$buffer =  '{ "status":"success" }';
	
}elseif( $action == "removeSong" ){
	
	$songid = $db->safesql( $_REQUEST['song_id'] );
	
	$db->query("DELETE FROM " . PREFIX . "_playlist WHERE playlist = '" . $playlist . "' AND song_id = '" . $songid . "' AND user_id= '" . $_SESSION['member']['user_id'] . "'");
	
	$buffer =  '{ "status":"success" }';
	
}

print $buffer;

?>