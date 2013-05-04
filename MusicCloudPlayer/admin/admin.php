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

$SQL = $db->query("SELECT count( user_id ) AS counter, reg_date FROM " . PREFIX . "_users GROUP BY DATE_FORMAT(reg_date, '%Y%m%d') ORDER BY reg_date DESC LIMIT 10");
		while ($row = $db->get_row($SQL)){
			    $counter = $row['counter'];			
				$reg_date =  date("d M Y",strtotime($row['reg_date'])); ;
				$userdate .= '<tr><th>'.$reg_date.'</th><td>'.$counter.'</td> </tr>';
		}

$count_songs = $db->super_query("SELECT count(id) AS counter_songs FROM " . PREFIX . "_songs");
$count_users = $db->super_query("SELECT count(user_id) AS counter_user FROM " . PREFIX . "_users");
$count_admin = $db->super_query("SELECT count(user_id) AS counter_admin FROM " . PREFIX . "_users WHERE role = '1'");

?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow">
	<meta name="description" content="Pwaq Admin Panel: Controle music player">
	
	<title>Pwaq Admin Panel</title>

	<!-- CSS --> 
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	
	<!-- Jquery 1.8.2 --> 
	<script src="js/jquery-1.8.2.min.js"></script>
	
	<!-- Twitter Bootstrap --> 
	<script src="js/bootstrap.min.js"></script>
	<script src="js/core.js"></script>
	
	<!-- Plugins Tables --> 
	<script src="js/jquery.dataTables.js"></script> 
	<script src="js/jquery.dataTables.columnFilter.js"></script> 
	<script src="js/jqueryForm.js"></script> 

	<!-- Custom Functions for Admin --> 
    <script src="js/functions.js"></script> 
    
    
    <!-- Define Player root for javascript from config.inc.php --> 
<?php $player_root = <<<HTML
<script>var player_root = "{$config['siteurl']}";</script>
HTML;
echo $player_root;
?>

</head>
<body data-target=".bs-docs-sidebar" data-spy="scroll" data-twttr-rendered="true">

<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="index.php" class="brand">Admin Panel</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
				<li><a href="index.php"><i class="icon-home icon-white"></i> Dashboard</a></li>
				<li><a onclick="loader('manage_song');" href="#"><i class="icon-file icon-white"></i> Manage Songs</a></li>
				<li><a onclick="loader('manage_user');" href="#"><i class="icon-user icon-white"></i> Manage Users</a></li>
			</ul>
			 <ul class="nav pull-right">
				<li><a onclick="logout();" href="#"><i class="icon-off icon-white"></i> Logout</a></li>
				
			</ul>
          </div>
        </div>
      </div>
    </div>
    <div id="publisher">
		 <header id="overview" class="jumbotron subhead">
  				<div class="container">
    				<h1>Dashboard</h1>
    					<p class="lead">You can manage songs, users and more.</p>
 				 </div>
			</header>
			<div class="container">
			<div class="row">
			<div class="span12">
					 
<div class="row">
<div class="span6"><table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Number of</th>
                                                <th>Stats</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                         <tr><th>Users</th><td> <? echo $count_users['counter_user']; ?> </td></tr>
                                         <tr><th>Admins</th><td> <? echo $count_admin['counter_admin']; ?></td></tr>
                                         <tr><th>Songs</th><td> <? echo $count_songs['counter_songs']; ?></td></tr>
                                          </tbody>
                                         </table></div>
<div class="span6"><div class="graph">
        	 <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Number of registration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         <? echo $userdate; ?>
                                          </tbody>
                                         </table>
					</div></div>
</div>
             </div></div>
                                       
             
              </div>
              <!--Statistics Graph END-->
	</div>
	
	<footer class="footer">
      <div class="container">
        <p>Designed and built with all the love in the world by Humain2.</p>
      </div>
    </footer>
<div id="edit_song" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Edit song</h3>
</div>
<form data-async data-target="#edit-song" action="<? echo $config['siteurl']; ?>ss/action.php?type=edit_song_action" method="POST">

<div id="modal-body-edit" class="modal-body">

</div>
<div class="modal-footer">
 <button type="submit" class="btn">Submit</button>
</div>
</form>
</div>   
<div id="addsongs" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Add new song</h3>
</div>
<form id="add_song" action="<? echo $config['siteurl']; ?>ss/action.php?type=add_song" enctype="multipart/form-data" method="POST">

 <div class="modal-body">

<!-- The async form to send and replace the modals content with its response -->
<div class="control-group">
<label class="control-label">Song Title</label>
<div class="controls">
<input type="text" name="song" required="" id="song" placeholder="Song name">
</div>
</div>
<div class="control-group">
<label class="control-label">Album</label>
<div class="controls">
<input type="text" name="album" required="" id="album" placeholder="Song album">
</div>
</div>
<div class="control-group">
<label class="control-label">Artist</label>
<div class="controls">
<input type="text" name="artist" required="" id="artist" placeholder="Song artist">
</div>
</div>
<div class="control-group">
<label class="control-label">Url (mp3 file)</label>
<div class="controls">
<input type="text" name="url" id="url" placeholder="http://">
</div>
</div>

<div class="control-group">
<label class="control-label">Or upload MP3 file</label>
<div class="controls">
 <input name="uploadedfile" id="uploadedfile" type="file" >
 <div class="progress">
        <div class="barer"></div >
        <div class="percenter">0%</div >
</div>
		<div id="status"></div>
</div>
</div> 
</div>
<div class="modal-footer">

 <button type="submit" class="btn">Submit</button>

</div>
</form>
</div>

<div id="loading">
<img width="32" height="32" src="img/ajax-loader.gif" style="padding: 7px;">
</div>
</body>
</html>