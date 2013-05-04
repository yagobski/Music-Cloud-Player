<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

require_once ROOT_DIR . '/modules/functions.php';


$page = $db->safesql( $_REQUEST['page'] );


if( $page == "manage_user" ){
	
		$SQL = $db->query("select  username, user_id, role, reg_date, last_date from " . PREFIX . "_users");
		while ($row = $db->get_row($SQL)){
		
			    $username = $row['username'];
				
				$user_id =  $row['user_id'];

				$last_date = $row['last_date'];
				
				$count_playlist = $db->super_query("SELECT count(playlist) AS counter FROM " . PREFIX . "_playlist WHERE user_id = '".$user_id."'");

				
				if( $row['role']=='1' ){ $role='<span id="label_'.$user_id.'" class="label label-warning">Admin</span>'; $admin='<button class="btn btn-mini" onclick="action(\''.$user_id.'\', \'delete_admin\');">Delete admin role</button>'; }else{
				$role='<span id="label_'.$user_id.'" class="label">Member</span>'; $admin='<button class="btn btn-mini" onclick="action(\''.$user_id.'\', \'add_admin\');">Add Admin role</button>';}
															
				$userlist .= '<tr id="user_'.$user_id.'" class="even_gradeC">
								<td>'.$user_id.'</td>
								<td>'.$username.'</td>
								<td>'.$last_date.'</td>
								<td>'.$count_playlist['counter'].'</td>
								<td>'.$role.'</td>
								<td>'.$admin.'<button class="btn btn-mini btn-danger" onclick="action(\''.$user_id.'\', \'delete_user\');">Delete</button></td>
							  </tr>';
				}

$buffer = '<header id="overview" class="jumbotron subhead">
  				<div class="container">
    				<h1>Manage users</h1>
    					<p class="lead">You can search, delete and set admin role for users.</p>
 				 </div>
			</header>
			<div class="container">
			<div class="row">
				<div class="span12">
			
					
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
	<thead>
		<tr>
			<th>User id</th>
			<th>User name</th>
			<th>Last date</th>
			<th>Number of playlists</th>
			<th>Role</th>
			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>User id</th>
			<th>User name</th>
			<th>Last date</th>
			<th>Number of playlists</th>
			<th>Role</th>
			<th>Action</th>
		</tr>
	</tfoot><tbody>'.$userlist.'</tbody></table></div></div></div>';
	
}elseif( $page == "manage_song" ){
	
$SQL = $db->query("select id, song_title, song_album, song_artist, url from " . PREFIX . "_songs");
		while ($row = $db->get_row($SQL)){
		
			    $id = $row['id'];
			    
				$song_title =  $row['song_title'];
				$song_album =  $row['song_album'];
				$song_artist =  $row['song_artist'];
				$url =  $row['url'];
				
	
				$songlist .= '<tr id="song_'.$id.'" class="even_gradeC">
								<td>'.$id.'</td>
								<td>'.$song_title.'</td>
								<td>'.$song_album.'</td>
								<td>'.$song_artist.'</td>
								<td>'.$url.'</td>

								<td><button class="btn btn-mini" onclick="action(\''.$id.'\', \'edit_song\');">Edit</button><button class="btn btn-mini btn-danger" onclick="action(\''.$id.'\', \'delete_song\');">Delete</button></td>
							  </tr>';
				}

$buffer = '<header id="overview" class="jumbotron subhead">
  				<div class="container">
    				<h1>Manage Songs</h1>
    					<p class="lead">You can search, delete and add new songs.</p>
 				 </div>
			</header>
			<div id="noticer" class="span12"></div>
			<div class="container">
			<div class="row">
			<div class="span12">
	<div class="btn-group-top">
		<a class="btn btn-success pull-right" data-toggle="modal" href="#addsongs"><i class="icon-plus icon-white"></i> Add new song</a>
	</div>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
	<thead>
		<tr>
			<th>Song id</th>
			<th>Title</th>
			<th>Album</th>
			<th>Artist</th>
			<th>URL</th>
			<th>Action</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Song id</th>
			<th>Title</th>
			<th>Album</th>
			<th>Artist</th>
			<th>URL</th>
			<th>Action</th>
		</tr>
	</tfoot><tbody>'.$songlist.'</tbody></table></div></div></div>';	
	
}elseif( $page == "dashboard" ){
	
	
}
echo $buffer;

?>