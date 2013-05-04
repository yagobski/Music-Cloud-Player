<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

require_once ROOT_DIR . '/modules/functions.php';

if( !$_SESSION['member']['role'] == '1' ){
	header('Location: index.php');  
}


$type = $db->safesql( $_REQUEST['type'] );
$object_id = $db->safesql( $_REQUEST['object_id'] );


if( $type == "delete_user" ){
	 $db->super_query("DELETE FROM " . PREFIX . "_users WHERE user_id = '".$object_id."'");
	 $db->super_query("DELETE FROM " . PREFIX . "_playlist WHERE user_id = '".$object_id."'");
}elseif( $type == "add_admin" ){
	$db->super_query("UPDATE " . PREFIX . "_users  SET `role` = '1' WHERE user_id = '".$object_id."'");
}elseif( $type == "delete_admin" ){
	$db->super_query("UPDATE " . PREFIX . "_users  SET `role` = '0' WHERE user_id = '".$object_id."'");
}elseif( $type == "delete_song" ){
	$db->super_query("DELETE FROM " . PREFIX . "_songs  WHERE id = '".$object_id."'");
}elseif( $type == "add_song" ){


$song = $db->safesql( $_REQUEST['song'] );
$album = $db->safesql( $_REQUEST['album'] );
$artist = $db->safesql( $_REQUEST['artist'] );
$url = $db->safesql( $_REQUEST['url'] );

$hash = md5(sha1($url));
$hash = substr($hash , 0, 8);

$filname = getSlug($artist).'-'.getSlug($song).'-'.$hash;

if ($_FILES['uploadedfile'])
{
    if ( in_array( strtolower(strrchr($_FILES['uploadedfile']['name'], '.')), array('.mp3', '.png') ) )
    {
        if ($_FILES['uploadedfile']['size'] < 20048000) // file size inf 20Mb
        {
            //store file
            $new_file = '../uploads/'.$filname.'.mp3';
            if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$new_file)){
                $target_path = $config['siteurl'] . 'uploads/'.$filname.'.mp3'; 
                echo "File transfer succesfull in ". $new_file;
             }else {
                //errors
                echo 'Error: uploaded file invalid';
             }
        } else { echo 'Error: file size is more than 20MB: '.$_FILES['uploadedfile']['size'].' bytes'; }
    } else { echo 'Error: Wrong file extention: '.strtolower(strrchr($_FILES['uploadedfile']['name'], '.')); }
} 




if($target_path) { $url = $target_path; }

$db->super_query("INSERT INTO  " . PREFIX . "_songs (song_title, song_album, song_artist, url) VALUES ('".$song."', '".$album."', '".$artist."', '".$url."')");

}elseif( $type == "edit_song" ){

$song_id = $db->safesql( $_REQUEST['object_id'] );

$songer = $db->super_query("SELECT * FROM " . PREFIX . "_songs WHERE id = '".$song_id."'");

$song = $songer['song_title'];
$album = $songer['song_album'];
$artist = $songer['song_artist'];
$url = $songer['url'];

echo '<div class="control-group">
<label class="control-label">Song Title</label>
<div class="controls">
<input type="text" value="'.$song.'" name="song" required="" id="song" placeholder="Song name">
<input type="hidden" value="'.$song_id.'" name="song_id" id="song_id">

</div>
</div>
<div class="control-group">
<label class="control-label">Album</label>
<div class="controls">
<input type="text" value="'.$album.'"name="album" required="" id="album" placeholder="Song album">
</div>
</div>
<div class="control-group">
<label class="control-label">Artist</label>
<div class="controls">
<input type="text" value="'.$artist.'" name="artist" required="" id="artist" placeholder="Song artist">
</div>
</div>
<div class="control-group">
<label class="control-label">Url (mp3 file)</label>
<div class="controls">
<input type="text" value="'.$url.'" name="url" required="" id="url" placeholder="http://">
</div>
</div>';

}elseif( $type == "edit_song_action" ){

$song_id = $db->safesql( $_REQUEST['song_id'] );
$song = $db->safesql( $_REQUEST['song'] );
$album = $db->safesql( $_REQUEST['album'] );
$artist = $db->safesql( $_REQUEST['artist'] );
$url = $db->safesql( $_REQUEST['url'] );

	$db->super_query("UPDATE " . PREFIX . "_songs  SET `song_title` = '".$song."', `song_album` = '".$album."', `song_artist` = '".$artist."', `url` = '".$url."' WHERE id = '".$song_id."'");
}

?>