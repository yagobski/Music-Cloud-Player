<?php

@session_start ();

@ob_start ();

@ob_implicit_flush ( 0 );

@error_reporting ( E_ALL ^ E_NOTICE );

@ini_set ( 'display_errors', true );

@ini_set ( 'html_errors', false );

@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );

define ( 'ROOT_DIR', dirname ( __FILE__ ) );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

@include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

require_once ROOT_DIR . '/modules/functions.php';

require_once INCLUDE_DIR . '/login.php';


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link type="text/css" rel="stylesheet" href="./styles/home.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta name="viewport" content = "width = device-width, initial-scale = 1, maximum-scale = 1, minimum-scale = 1, user-scalable = no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-icon-precomposed" href="styles/images/appIcon.png"/>
<meta name="description" content="Music Cloud Player is a totally free, no subscription required online mp3 player with millions of songs catalogued coupled with extremely fast and intuitive user interface. It works like a native app on iPad, PC, Mac and soon will be available on the Android platform. Sign up for free to save your playlists and share songs with friends on Facebook. Find all your favorite music on Pwaq!" />
<meta name="keywords" content="Cloud Player,online music player,online,music,player,free mp3 download,free music online,free music,download music free,music download,music download free,ajax music player,js music player,javascript music player,javascript player,music webapp,music player webapp,apple webapp,ipad web aoo, music for ipad,music for iphone,ipad online player,ipad online music player,lady gaga,backstreet boys,britney spears,justin timberlake,jennifer lopez" />
<link rel="canonical" href="http://www.pwaq.com/" />
<link rel="shortcut icon" href="images/favicon.ico"/>
<script src="lib/yui/yahoo-dom-event.js" ></script>
<script src="lib/yui/json-min.js" ></script>
<script src="lib/yui/get-min.js" ></script>
<script src="lib/flexcroll.js" ></script>
<script src="lib/browserDetect.js" ></script>
<script src="js/lib.js" ></script>
<script src="js/init.js" ></script>


<title>Music Cloud Player</title>
	
</head>
<body style="overflow:hidden;">
<div id="mainContainer" class="hidden">
	<div id="searchBar" class="home">
		<input type="text" id="searchText" />
		<div id="searchPlaceHolder">Search for Song / Artist / Album</div>
		<div id="searchIcon" class="hidden"></div>
		<div id="searchReset" class="hidden"></div>
		<div id="searchLoading" class="hidden"></div>
	</div>
	<div id="homePageContainer" class="visible">
		<div id="homePage">
			<div id="homeTopbar"></div>
			<div id="homeLogo"></div>
			<div id="homeCredit"><strong>Music Cloud Player</strong> by Humain2</div>
			<div id="homeAccount">Hi, <span id="homeUsernameText" style="font-size:2em;">Guest</span></div>
			<div id="homeShowPlaylist">My <span id="homeUsernameText" style="font-size:2em;">Playlist</span></div>
			<div id="homePlayingContainer" class="hidden">
				<div id="homePlayingImgContainer"></div>
				<div id="homePlayingText"></div>
				<div id="homePlayingInfo"></div>
			</div>
			<div id="homeBottom">
				<div id="loginContainer">
					<div id="loginNotice">To be able to use Music Cloud Player, please login with your account</div>
					<div id="loginUsernameText" class="inputTitle">Username:</div>
					<div id="loginPasswordText" class="inputTitle">Password:</div>
					<input type="text" id="username" autocorrect="off" autocapitalize="off" class="input login" />
					<input type="password" id="password" autocorrect="off" autocapitalize="off" class="input login" />
					<div id="loginButtonContainer"> <a id="loginSubmitButton" href="Javascript:void(0)" class="loginButton">Login</a> <a id="loginRegisterButton" href="Javascript:void(0)" class="registerButton">Register</a> </div>
				</div>
				<div id="registerContainer" class="hidden">
					<div id="loginNotice">Register a free account</div>
					<div id="registerUsernameText" class="inputTitle">Username:</div>
					<div id="registerPasswordText" class="inputTitle">Password:</div>
					<div id="registerPasswordConfirmText" class="inputTitle">Confirm:</div>
					<input type="text" id="registerUsername" autocorrect="off" autocapitalize="off" class="input register" maxlength="40" />
					<input type="password" id="registerPassword" autocorrect="off" autocapitalize="off" class="input register" maxlength="40" />
					<input type="password" id="registerPasswordConfirm" autocorrect="off" autocapitalize="off" class="input register" maxlength="40" />
					<div id="registerButtonContainer"> <a id="registerSubmitButton" href="Javascript:void(0)" class="registerButton">Register</a><br />
						<a id="registerCancelButton" href="Javascript:void(0)" class="">Cancel</a> </div>
				</div>
			</div>
			<div id="homeSlogan"></div>
			<div id="homeSlogan2"></div>
		</div>
	</div>
	<div id="toolbar">
		<div id="accountInfoContainer" class="accountInfo guest">
			<div class="register" onclick="User.registerForm();">Register</div>
			<div id="toolbarAccountName" class="name"></div>
			<div class="login" onclick="User.loginForm();">Login</div>
			<div class="logout" onclick="User.logout();">Logout</div>
		</div>
		<div id="toolbarLogo" class="logo"></div>
		<div id="controlButtonContainer">
			<div id="previousButton"></div>
			<div id="playButton" class="pauseState"></div>
			<div id="nextButton"></div>
		</div>
		<div class="toolbarDivider first"></div>
		<div class="toolbarDivider second"></div>
		<div class="toolbarDivider third"></div>
		<div class="toolbarDivider fourth"></div>
		<div class="toolbarHorizonalDivider"></div>
		<div id="repeatButtonContainer">
			<div id="repeatButton" class="none"></div>
			<div id="shuffleButton"></div>
		</div>
		<div id="hudContainer">
			<div id="toolbarMusicIcon"></div>
			<div id="songInfoContainer"><span id="songTitle">Music Cloud Player&trade;</span><span id="songArtist">'s Ready to Roll</span></div>
			<div id="progressBarContainer">
				<div id="progressDataBar">
					<div id="progressBar"></div>
				</div>
			</div>
			<div id="timeLapse">0:00</div>
			<div id="timeRemain">0:00</div>
		</div>
		<div id="topbarButtonContainer"> <a id="homeButton" class="toolbarButton"><span class="icon"></span><span class="title">Home</span></a> <a id="shareButton" class="toolbarButton"><span class="icon"></span><span class="title">Share</span></a> 
			<!--<a id="downloadButton" class="toolbarButton"><span class="icon"></span><span class="title">Download</span></a>-->
			<a id="emptyPlaylistButton" class="toolbarButton"><span class="icon"></span><span class="title">Empty</span></a> <a id="facebookButton" class="toolbarButton"><span class="icon"></span><span class="title">Facebook</span></a> </div>
		<div id="volumeContainer">
			<div id="volumeIcon"></div>
			<div id="volumeBar" title='"-" : Volume Down ; "+" : Volume Up'> <span class="volumeSelect" onclick="Player.setVolume(10);"></span> <span class="volumeSelect" onclick="Player.setVolume(20);"></span> <span class="volumeSelect" onclick="Player.setVolume(30);"></span> <span class="volumeSelect" onclick="Player.setVolume(40);"></span> <span class="volumeSelect" onclick="Player.setVolume(50);"></span> <span class="volumeSelect" onclick="Player.setVolume(60);"></span> <span class="volumeSelect" onclick="Player.setVolume(70);"></span> <span class="volumeSelect" onclick="Player.setVolume(80);"></span> <span class="volumeSelect" onclick="Player.setVolume(90);"></span> <span class="volumeSelect" onclick="Player.setVolume(100);"></span> </div>
		</div>
	</div>
	<div id="searchContainer" class="hidden">
		<div id="searchOptionContainer"> <a id="searchOption_All" class="searchOption" onclick="Search.selectOption('All');">All</a> <a id="searchOption_Song" class="searchOption" onclick="Search.selectOption('Song');">Song</a> 
			<a id="searchOption_Artist" class="searchOption" onclick="Search.selectOption('Artist');">Artist</a>
			<a id="searchOption_Album" class="searchOption" onclick="Search.selectOption('Album');">Album</a> </div>
		<div id="searchContent">
			<div id="searchScroller">
				<div id="searchResults"></div>
			</div>
		</div>
	</div>
	<div id="playlistTab">
		<div id="playlistTabText"></div>
	</div>
	<div id="playlistTableHeader"> <span class="index">#</span> <span class="title">Title</span> <span class="artist">Artist</span> </div>
	<div id="playlistContainer">
		<div id="playlistContent">
			<div id="playlist"></div>
		</div>
	</div>
	<div id="infoContainer">
		<div id="infoTitle"></div>
		<div id="infoBioContainer">
			<div id="albumArtContainer"></div>
			<div id="infoBio">
				<div id="infoBioContent"></div>
			</div>
		</div>
		<div id="infoTopArtists" class="hidden"></div>
		<div id="infoContent">
			<div id="infoScroller">
				<div id="infoList">
					<div id="infoTopAlbums"></div>
					<div id="infoTopTracks"></div>
					<div id="infoSimilarArtists"></div>
				</div>
			</div>
		</div>
	</div>
	<div id="communityContainer" class="full">
		<div id="communityTitle">Community</div>
		<div id="communityContent">
			<div id="communityScroller">
				<div id="community"></div>
			</div>
		</div>
	</div>
	<div id="albumPanel" class="hidden">
		<div id="albumTitle">Album Name</div>
		<div id="albumArtist">Album Artist</div>
		<div id="albumPanelClose"></div>
		<div id="albumContent">
			<div id="albumScroller">
				<div id="albumResults"></div>
			</div>
		</div>
	</div>
	<div id="bottomBar">
		<div id="bottomBarIcon"></div>
		<div id="statusText"></div>
		<div id="creditbar">Music Cloud Player • by <a href="http://themeforest.net/user/humain2" target="_blank">Humain2</a></div>
	</div>
</div>
<div id="message" class="dialog hidden"></div>
<div id="loading">Loading...</div>
</body>
</html></div>

    
    <div id="bottomBar">
        
        <div id="bottomBarIcon"></div>
        <div id="topbarQualityContainer">
        </div>
        
        <div id="statusText"></div>
		<div id="creditbar">Music Cloud Player • by <a href="http://themeforest.net/user/humain2" target="_blank">Humain2</a></div>

    </div>

</div>

<div id="message" class="dialog hidden"></div>

<div id="loading">Loading...</div>

</body>
</html>