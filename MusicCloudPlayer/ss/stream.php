<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

$id = $_REQUEST['id'];

$stream = $db->query("SELECT url FROM " . PREFIX . "_songs WHERE id = '".$id."'");
$row = $db->get_row($stream);
	
$MP3_URL = $row['url'];
	
header("Location: " . html_entity_decode ($MP3_URL) );

?>