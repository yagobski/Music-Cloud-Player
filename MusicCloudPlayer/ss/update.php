<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

require_once ROOT_DIR . '/modules/functions.php';

$action = $db->safesql( $_REQUEST['action'] );

$username = $db->safesql( $_REQUEST['u'] );

$username = preg_replace("/[^a-zA-Z0-9\s]/", "", $username);

$songid = $db->safesql( $_REQUEST['id'] );

$song_title = $db->safesql( $_REQUEST['title'] );

$song_artist = $db->safesql( $_REQUEST['artist'] );

if( ! $_SESSION['logged'] ){
	
	header('HTTP/1.0 404 Not Found');
	
	$buffer =  'error';
	
}else{
	
	if( $action == "log" && $username ){
		
	}
	
}
print $buffer;

?>