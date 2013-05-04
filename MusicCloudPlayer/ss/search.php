<?php


@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

$type = $_GET['type'];

$page = $_GET['page'];

$qtxt0 = $_GET['query'];

$qtxt = urlencode($qtxt0);

$c = 0;
$i = 0;

if ($type == "All") {

$SQL = $db->query("SELECT id, song_title, song_album, song_artist, url FROM " . PREFIX . "_songs WHERE MATCH(song_title, song_artist, song_album) AGAINST ('" . $qtxt0 . "')");

}elseif($type == "Song"){

$SQL = $db->query("SELECT id, song_title, song_album, song_artist, url FROM " . PREFIX . "_songs WHERE MATCH(song_title) AGAINST ('" . $qtxt0 . "')");

}elseif($type == "Artist"){

$SQL = $db->query("SELECT id, song_title, song_album, song_artist, url FROM " . PREFIX . "_songs WHERE MATCH(song_artist) AGAINST ('" . $qtxt0 . "')");

}elseif($type == "Album"){

$SQL = $db->query("SELECT id, song_title, song_album, song_artist, url FROM " . PREFIX . "_songs WHERE MATCH(song_album) AGAINST ('" . $qtxt0 . "')");

}else{
			
$SQL = $db->query("SELECT id, song_title, song_album, song_artist, url FROM " . PREFIX . "_songs");

}
		while ($row = $db->get_row($SQL)){
		
				$id = $row['id'];

				$title = $row['song_title'];
								
				$url = $row['url'];
				
				$artist = $row['song_artist'];
				
				$album = $row['song_album'];
				
				if ($title && $artist && $url){
					
					$buffer .= '{
								        "id": "' . $id . '",
								        "title": ' . json_encode ($title) . ',
								        "artist": ' . json_encode ($artist) . ',
								        "album": ' . json_encode ($album) . '
								},';
					$c++;
				}
				++$i;
		}



$buffer = substr( $buffer, 0, ( strLen( $buffer ) - 1 ) );

echo '{
    "query": "Queen",
    "type": "All",
    "page": 1,
    "results": {
        "artist": [],
        "album": [],"song": [' . $buffer . ']}}';

?>