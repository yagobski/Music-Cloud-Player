/*
 * Music Cloud Player
 *
 * Copyright 2013, Humain2
 * This app includes
 * jQuery 1.5.2
 * iScroll 1.4
 * YUI (Yahoo User Interface) 2.8
 * Copyright to their respective owners
 *
 */
 
var clickEventText = 'click';

var Main = {
	
	lastClicked : 0,
	
	clickTiming : 300,
	
	loadBasic : function()
	{
		if (Main.isMobileTablet())
		{
			clickEventText = 'touchend';
		}
		else
		{
			clickEventText = 'click';
		}

		/* BIND EVENTS */
		
		// HASH CHANGE EVENT
		
		Event.on(window,'hashchange',Main.getHash);
		
		// DETECT EVENT FOR LOGIN
		
		Event.on('loginSubmitButton',clickEventText,User.login);
		
		Event.on('username','keypress',function(event){ if (event.keyCode == 13) { User.login(); } });
		Event.on('password','keypress',function(event){ if (event.keyCode == 13) { User.login(); } });
		
		// SETUP SCROLLS
		
		Scroll.setupScroll();
		
		// DEBUG WHEN ON CHROME WHEN HIT TAB KEY SEVERAL TIMES
		// IT WILL FOCUS INTO THE ALBUM PANEL
		// WHICH SHIFTS THE INTERFACE TO THE RIGHT
		
		Search.hideSearch();
		Search.hideAlbum();
		
		// SETUP SOUND MANAGER 2
		
		Main.setupSoundManager();
		
		// DETECT LOGIN
		
		if (getCookie('u'))
		{
			User.loginSuccess(getCookie('u'));
		}
		
		Focus.set('playlistContainer');
		
	},
	
	load : function()
	{
		/* BIND EVENTS */
		
		// HOME SCREEN BUTTON EVENTS
		Event.on('homeShowPlaylist',clickEventText,Main.hideHome);
		
		Event.on('loginRegisterButton',clickEventText,User.registerForm);
		Event.on('registerSubmitButton',clickEventText,User.register);
		Event.on('registerCancelButton',clickEventText,User.loginForm);
		
		// PLAYER BUTTON EVENTS
		Event.on('playButton',clickEventText,Handlers.playButton);
		Event.on('nextButton',clickEventText,Handlers.nextButton);
		Event.on('previousButton',clickEventText,Handlers.previousButton);
		
		Event.on('repeatButton',clickEventText,Handlers.repeatButton);
		Event.on('shuffleButton',clickEventText,Handlers.shuffleButton);
		
		Event.on('progressBarContainer','click',Handlers.progressBar);
		
		// TOOLBAR BUTTON EVENTS
		Event.on('homeButton',clickEventText,Main.showHome);
		Event.on('shareButton',clickEventText,Handlers.shareButton);
		Event.on('downloadButton',clickEventText,Handlers.downloadButton);
		Event.on('emptyPlaylistButton',clickEventText,Handlers.emptyPlaylistButton);
		Event.on('facebookButton',clickEventText,Handlers.facebookButton);
		
		// FOCUS EVENTS
		Event.on('playlistContainer',clickEventText,function(){ Focus.set(this); });
		Event.on('searchContainer',clickEventText,function(){ Focus.set(this); });
		Event.on('albumPanel',clickEventText,function(){ Focus.set(this); });
		Event.on('infoContainer',clickEventText,function(){ Focus.set(this); });
		Event.on('toolbar',clickEventText,function(){ Focus.set(this); });
		Event.on('searchText','focus',function(){ Focus.set(this); });
		
		Event.on(window,'mouseout',Focus.windowBlur);
		Event.on(window,'mouseover',Focus.windowFocus);
		
		// SEARCH BAR EVENTS
		Event.on('searchText','focus',Handlers.searchFocus);
		Event.on('searchText','blur',Handlers.searchBlur);
		Event.on('searchText','keyup',Handlers.searchKeypress);
		Event.on('searchReset',clickEventText,Handlers.searchReset);
		Event.on('searchIcon',clickEventText,Handlers.searchIcon);
		Event.on('searchPlaceHolder',clickEventText,Handlers.searchPlaceHolder);
		
		
		/* EXECUTE INITIAL SCRIPTS */
		
		// SETUP PLAYLIST
		
		Playlist.prepare();
		
		// SELECT DEFAULT INTERFACE BUTTONS
		
		Search.selectOption('All');
		
		// DISABLE SELECTION
		
		disableSelection(document.getElementById('playlistContainer'));
		disableSelection(document.getElementById('searchContainer'));
		disableSelection(document.getElementById('infoContainer'));
		disableSelection(document.getElementById('albumPanel'));
		disableSelection(document.getElementById('toolbar'));
		
		// GET ACTIVITIES
		/*
		Info.getActivity();
		setInterval(Info.getActivity,10000);
		*/
		
		Info.getTopChart();
		
	},
	
	setupSoundManager : function()
	{
		soundManager.url = './lib/soundmanager2/soundmanager2.swf';
		useHTML5Audio = true;
		
		
		soundManager.createSound({  id: 'song', url: '' });
		
		soundManager.onready(function()
		{
			Main.getHash();
			Player.setVolume(100);
		});
	},
	
	getHash : function()
	{
		var hash = window.location.hash;
		
		if (hash.length > 2)
		{
			var info = hash.split('/');
			var id = info[1];
			var title = info[3]+'';
			var artist = info[2]+'';
			
			Player.playingId = id;
			Player.playingArtist = artist;
			Player.playingTitle = title;
			
			Info.getArtist(decodeURI(artist.replaceAll('-',' ')));
			
			var titleText = decodeURI(title.replaceAll('-',' '));
			var artistText = decodeURI(artist.replaceAll('-',' '));
			
			Dom.get('songInfoContainer').innerHTML = '<span id="songTitle">'+titleText+'</span> by <span id="songArtist">'+artistText+'</span>';
			
			Dom.get('homePlayingInfo').innerHTML = '<span class=title>'+titleText+'</span> by <span class=artist>'+artistText+'</span>';
			Dom.get('homePlayingContainer').className = 'visible';
			
			soundManager.destroySound('song');
			soundManager.createSound({
				
				id : 'song',
				url : './ss/stream.php?id='+id,
				autoPlay: true,
				volume : Player.volume,
				onplay : function() { Dom.get('playButton').className = 'playingState'; },
				onpause : function() { Dom.get('playButton').className = 'pauseState'; },
				onresume : function() { Dom.get('playButton').className = 'playingState'; },
				onfinish : function()
				{
					Dom.get('homePlayingContainer').className = 'hidden';
					Player.playNext();
				},
				whileplaying : function()
				{
					var sound = soundManager.getSoundById('song');
					
					var totalLapsed = Math.round(sound.position / 1000);
					var minuteLapsed = Math.floor(totalLapsed / 60);
					var secondLapsed = totalLapsed % 60;
					
					if (secondLapsed < 10) secondLapsed = '0'+secondLapsed;
					
					var totalRemain = Math.round((sound.duration - sound.position) / 1000);
					var minuteRemain = Math.floor(totalRemain / 60);
					var secondRemain = totalRemain % 60;
					
					if (secondRemain < 10) secondRemain = '0'+secondRemain;
					
					Dom.get('timeLapse').innerHTML = minuteLapsed+':'+secondLapsed;
					Dom.get('timeRemain').innerHTML = '-'+minuteRemain+':'+secondRemain;
					
					var percent = sound.position / sound.duration * 100;
					
					Dom.get('progressBar').style.width = percent + '%';
				},
				whileloading : function()
				{
					var sound = soundManager.getSoundById('song');
					
					var percent = sound.bytesLoaded / sound.bytesTotal * 100;
					
					Dom.get('progressDataBar').style.width = percent + '%';
				}
				
			});
			
			logSongId = id;
			logSong = titleText;
			logArtist = artistText;
			
			if (window.logTimeout!==undefined) { clearTimeout(logTimeout); }
			
			logTimeout = setTimeout(function()
			{
				$.getJSON('./ss/update.php', {
					action : "logSong",
					id : id,
					title:titleText,
					artist:artistText,
					username:User._username 
				});
			},30000);
		}
	},
	
	hideHome : function()
	{
		Dom.get('homePageContainer').className = 'hidden';
		Dom.get('searchBar').className = 'normal';
	},
	
	showHome : function()
	{
		Dom.get('homePageContainer').className = 'visible';
		Dom.get('searchBar').style.opacity = 0;
		setTimeout(function()
		{
			Dom.get('searchBar').className = 'home';
			Dom.get('searchBar').style.opacity = 1;
		},700);
	},
	
	toggle : function(id)
	{
		var el = document.getElementById(id);
		
		if (el.className.indexOf('visible') >= 0) { el.className = el.className.replace('visible','hidden'); }
		if (el.className.indexOf('hidden') >= 0) { el.className = el.className.replace('hidden','visible'); }
	},
	
	show : function(id)
	{
		var el = document.getElementById(id);
		
		el.className = el.className.replace('hidden','visible');
	},
	
	hide : function(id)
	{
		var el = document.getElementById(id);
		
		el.className = el.className.replace('visible','hidden');
	},
	
	translateURL : function(href)
	{
		var h = href;
		h = h.replaceAll(' ','-');
		h = remove_accents(h);
		h = h.replace(/\&.*?\;/g, "");
		
		return h;
	},
	
	isWebKit : function()
	{
		if (navigator.userAgent.indexOf('iPad') >= 0) { return true; }
		else { return false; }
	},
	
	isMobileTablet : function()
	{
		if (navigator.userAgent.indexOf('iPad') >= 0) { return true; }
		else { return false; }
	}
	
};

var Scroll = {
	
	setupScroll : function()
	{
		if (Main.isWebKit())
		{
			mySongScroll = new iScroll('playlistContent', { hScrollbar: false, vScrollbar: false });
			mySearchScroll = new iScroll('searchContent', { hScrollbar: false, vScrollbar: false });
			myInfoScroll = new iScroll('infoContent', { hScrollbar: false, vScrollbar: false });
			myAlbumScroll = new iScroll('albumContent', { hScrollbar: false, vScrollbar: false });
			myCommunityScroll = new iScroll('communityContent', { hScrollbar: false, vScrollbar: false });
			myBioScroll = new iScroll('infoBio', { hScrollbar: false, vScrollbar: false });
		}
		else
		{
			fleXenv.fleXcrollMain("playlistContent");
			fleXenv.fleXcrollMain("searchContent");
			fleXenv.fleXcrollMain("infoContent");
			fleXenv.fleXcrollMain("albumContent");
			fleXenv.fleXcrollMain("communityContent");
			fleXenv.fleXcrollMain("infoBio");
		}
	},
	
	updateScroll : function()
	{
		if (Main.isWebKit())
		{
			mySongScroll.refresh();
			mySearchScroll.refresh();
			myInfoScroll.refresh();
			myAlbumScroll.refresh();
			myCommunityScroll.refresh();
			myBioScroll.refresh();
		}
		else
		{
			fleXenv.updateScrollBars();
		}
	},
	
	scrollToEl : function(el)
	{
		if (Main.isWebKit())
		{
			
		}
		else
		{
			//fleXenv.scrollTo(el);
		}
	}
	
};

var Handlers = {
	
	/* Player buttons */
	
	playButton : function(event)
	{
		soundManager.togglePause('song');
	},
	
	nextButton : function(event)
	{
		Player.playNext();
	},
	
	previousButton : function(event)
	{
		Player.playPrevious();
	},
	
	repeatButton : function(event)
	{
		var button = document.getElementById('repeatButton');
		
		if (button.className == 'none')
		{
			button.className = 'all';
		}
		else if (button.className == 'all')
		{
			button.className = 'one';
		}
		else if (button.className == 'one')
		{
			button.className = 'none';
		}
	},
	
	shuffleButton : function(event)
	{
		selection.check('shuffleButton');
	},
	
	progressBar : function(event)
	{
		var mouseX = event.pageX;
		
		/*
		cxy   :  Container X and Y
		bxy   :  Progress Bar X and Y
		
		cx    :  Container X
		bx    :  Progress Bar X
		
		cw    :  Container Width
		mx    :  Mouse X in relation with Container
		
		mp    :  Mouse position in percent in relation with Container
		*/
		
		var cxy = Dom.getXY('progressDataBar');
		var bxy = Dom.getXY('progressBar');
		
		var cx = cxy[0];
		var bx = bxy[1];
		var cw = Dom.get('progressDataBar').offsetWidth;
		var mx = event.pageX - cx;
		
		var mp = Math.round(mx / cw * 100);
		
		var sound = soundManager.getSoundById('song');
		
		var position = Math.round(sound.duration / 100 * mp);
		
		sound.setPosition(position);
	},
	
	/* Toolbar buttons */
	
	shareButton : function(event)
	{
		var el = selection.get('songList');
		
		if (el)
		{
			var href = window.location+'';
			href = href.split('#');
			href = href[0];
			href = Main.translateURL(href+'#/'+el.info.song_id+'/'+el.info.artist+'/'+el.info.title+'/');
			
			var message = "Copy the following link and send it to your friends and family.<br><br>";
			message += "<span class='dialogShareText'>"+href+"</span>";
			
			dialog.alert(message);
		}
	},
	
	downloadButton : function(event)
	{
		alert('download selected song in current playlist');
	},
	
	facebookButton : function(event)
	{
		if (selection.get('songList') == null)
		{
			dialog.alert('First select a song. Then click the Facebook button.');
		}
		else
		{
			var el = selection.get('songList');
			
			var href;
			
			var location = window.location+'';
			location = location.replace(document.location.hash,'');
			location = location.replace('#','');
			location = location.replace('index.php','');
			
			var u = new Date().getTime();
			
			href = Main.translateURL(location+'share.php?id='+el.info.song_id+'&u='+u);
			
			facebookTempLink = 'http://www.facebook.com/share.php?u='+encodeURIComponent(href)+'';
			
			dialog.confirm('Share <b>"'+el.info.title+'</b>" by <b>'+el.info.artist+'</b> on FaceBook?',function()
			{
				window.open(facebookTempLink);
			});
		}
	},
	
	emptyPlaylistButton : function(event)
	{
		Playlist.emptyList();
	},
	
	/* SEARCH BAR */
	
	searchFocus : function(event)
	{
		Focus.set('searchText');
		Dom.get('searchIcon').className = "visible";
	},
	
	searchBlur : function(event)
	{
		Dom.get('searchIcon').className = "hidden";
	},
	
	searchKeypress : function(event)
	{
		if (this.value == "")
		{
			Main.hide('searchReset');
			Search.hideSearch();
			Dom.get('searchPlaceHolder').style.display = "block";
		}
		else
		{
			Main.show('searchReset');
			Dom.get('searchPlaceHolder').style.display = "none";
		}
		
		if (event.keyCode==13)
		{
			Search.execute(0);
		}
	},
	
	searchReset : function(event)
	{
		Dom.get('searchText').value = "";
		Search.hideAlbum();
		Main.hide('searchReset');
		Search.hideSearch();
		Dom.get('searchPlaceHolder').style.display = "block";
		
	},
	
	searchIcon : function(event)
	{
		Dom.get('searchText').value = Search.keyword;
		Search.execute(1);
		
	},
	
	searchPlaceHolder : function(event)
	{
		Dom.get('searchText').focus();
	}
	
};

var Player = {
	
	volume : 100,
	
	playingEl : null,
	
	playingPlaylist : null,
	
	playingId : null,
	
	playingTitle : null,
	
	playingArtist : null,
	
	playingAlbum : null,
	
	playingHref : null,
	
	play : function(el)
	{
		if (Player.playingEl != el)
		{
			Player.playingEl = el;
			selection.select('songList',el);
			var href = '#/'+el.info.song_id+'/'+el.info.artist+'/'+el.info.title+'/';
			
			window.location = href.replaceAll(' ','-');
		}
		else
		{
			soundManager.stop('song');
			soundManager.play('song');
		}
	},
	
	playNext : function()
	{
		if (Player.playingEl != null)
		{
			var el = Player.playingEl;
			var container = el.parentNode;
			var repeat = Player.getRepeat();
			var shuffle = Player.getShuffle();
			var nextEl;
			var canPlayNext = false;
			
			if (repeat == "one")
			{
				soundManager.stop('song');
				soundManager.play('song');
			}
			else if (shuffle)
			{
				var randNum = randomNumber(0,(container.childNodes.length-1));
				var nextEl = container.childNodes[randNum];
				
				canPlayNext = true;
			}
			else
			{
				if (repeat == "all")
				{
					if (el.nextSibling) { nextEl = el.nextSibling; }
					else { nextEl = container.childNodes[0]; }
					canPlayNext = true;
				}
				if (repeat == "none")
				{
					if (el.nextSibling) { nextEl = el.nextSibling; canPlayNext = true; }
				}
			}
			
			if (canPlayNext)
			{
				Player.play(nextEl);
			}
		}
	},
	
	playPrevious : function()
	{
		if (Player.playingEl != null)
		{
			var el = Player.playingEl;
			var container = el.parentNode;
			var repeat = Player.getRepeat();
			var shuffle = Player.getShuffle();
			var nextEl;
			var canPlayNext = false;
			
			if (repeat == "one")
			{
				Player.play(el);
			}
			else if (shuffle)
			{
				var randNum = randomNumber(0,(container.childNodes.length-1));
				var nextEl = container.childNodes[randNum];
				
				canPlayNext = true;
			}
			else
			{
				if (repeat == "all")
				{
					if (el.previousSibling) { nextEl = el.previousSibling; }
					else { nextEl = container.childNodes[container.childNodes.length-1]; }
					canPlayNext = true;
				}
				if (repeat == "none")
				{
					if (el.previousSibling) { nextEl = el.previousSibling; canPlayNext = true; }
				}
			}
			
			if (canPlayNext)
			{
				Player.play(nextEl);
			}
		}
	},
	
	setVolume : function(value)
	{
		soundManager.setVolume('song',value);
		
		Player.volume = value;
		
		Dom.get('volumeBar').style.backgroundPosition = '0px -'+(value)+'px';
	},
	
	getRepeat : function()
	{
		var button = document.getElementById('repeatButton');
		
		return button.className;
	},
	
	getShuffle : function()
	{
		var button = document.getElementById('shuffleButton');
		
		if (button.className.indexOf('selected') >= 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	},
	
	getQuality : function()
	{
		var b = selection.get('quality');
	
		if (b.id=='highButton')
		{
			return 2;
		}
		else
		{
			return 1;
		}
	}

};

var Playlist = {
	
	list : ["Unnamed","Unnamed","Unnamed","Unnamed","Unnamed"], // List of all playlist name, base on index number
	
	songs : [], // List of all songs
	
	current : 0,
	
	get : function()
	{
		if (User._username)
		{
			$.getJSON('./ss/playlist.php',
			{
				action : "get",
				username : User._username
			},
			function(data)
			{
				Playlist.songs = data.results.song;
				
				for (var i=0; i<Playlist.list.length;i++)
				{
					document.getElementById('playlist_'+i).innerHTML = '';
					
					for (var s=0; s<data.results.song.length;s++)
					{
						if (data.results.song[s].playlist == i)
						{
							Playlist.render(data.results.song,i,s);
						}
					}
				}
				
				Playlist.writeIndex();
			});
		}
	},
	
	prepare : function()
	{
		var container = document.getElementById('playlist');
		var selContainer = document.getElementById('playlistTabText');
		var el,selEl;
		
		for (var i=0; i<Playlist.list.length;i++)
		{
			// GENERATE PLAYLIST CONTENT CONTAINER
			el = document.createElement('div');
			el.id = 'playlist_'+i;
			
			if (i==0) el.className = 'visible';
			else el.className = 'hidden';
			
			container.appendChild(el);
		
			$(el).sortable({
				
				//containment : 'parent',
				axis : 'y',
				revert : true,
				placeholder : 'ui-placeholder',
				tolerance : 'touch',
				distance : 15,
				cursor : 'move',
				start : function(event,ui) {
					
					selection.select('songList',ui.helper.get(0));
				},
				update : function(event,ui) {
				
					var list = [];
					
					$.each($(this).children() , function(i,el) {
						
						if (i % 2 == 0) { var classN = 'even'; }
						else { var classN = 'odd'; }
						
						$(el).removeClass('odd')
						.removeClass('even')
						.addClass(classN)
						
						.children('.index').html(i+1);
						
						list.push(el.info.song_id);
					});
					
					var tracks = JSON.stringify(list);
					
					if (User._username != null) {
					
						$.post('ss/playlist.php',{action:'sort',list:tracks,username:User._username,playlist:Playlist.current}, function(data) {
							
							//console.log(data);
						});
					}
				}
			});
			
			
			// GENERATE PLAYLIST SELECTION TAB
			selEl = document.createElement('div');
			
			selEl.id = 'playlistSelection_'+i;
			selEl.className = 'playlistSelection';
			
			selContainer.appendChild(selEl);
			
			Event.on(selEl,clickEventText,function(){ Playlist.navigate(parseFloat(this.id.replace('playlistSelection_',''))); });
		}
		
		selection.select('playlists','playlistSelection_0');
	},
	
	navigate : function(playlist)
	{
		var selected = selection.get('playlists');
		
		var id = selected.id;
		
		var index = parseFloat(id.replace('playlistSelection_',''));
		
		var currentList = document.getElementById('playlist_'+index);
		var newList = document.getElementById('playlist_'+playlist);
		
		currentList.className =' hidden';
		newList.className = 'visible';
		
		selection.select('playlists','playlistSelection_'+playlist);
		
		Playlist.current = playlist;
		
		Scroll.updateScroll();
	},
	
	render : function(list,playlist,index)
	{
		var container = document.getElementById('playlist_'+playlist);
		
		var el = document.createElement('div');
		el.id = 'playlistItem_'+index;
		
		el.info = {
			index : index,
			playlist : list[index].playlist,
			song_id : list[index].song_id,
			title : list[index].title,
			artist : list[index].artist,
			album : list[index].album
		};
		el.innerHTML = '<span class="index"></span>';
		el.innerHTML += '<span class="play"></span>';
		el.innerHTML += '<span class="title">'+list[index].title+'</span>';
		el.innerHTML += '<span class="artist">'+list[index].artist+'</span>';
		el.innerHTML += '<span class="album">'+list[index].album+'</span>';
		el.innerHTML += '<span class="delete"></span>';
		el.innerHTML += '';
		
		Event.on(el,clickEventText,function()
		{
			selection.select('songList',this);
			
			// SIMULATE DOUBLE CLICK/TAP
			var now = new Date().getTime();
			if (now > Main.lastClicked + Main.clickTiming) { Main.lastClicked = now; }
			else
			{
				Player.play(this);
			}
		});
		
		Event.on(el.getElementsByClassName('play')[0],'click',function()
		{
			Player.play(this.parentNode);
		});
		
		Event.on(el.getElementsByClassName('delete')[0],'click',function()
		{
			Playlist.remove(this.parentNode);
		});
		
		container.appendChild(el);
		
		selection.select('songList',el);
		Scroll.updateScroll();
		Scroll.scrollToEl(el);
		setTimeout(fleXenv.updateScrollBars,100);
	},
	
	add : function(el)
	{
		var index = Playlist.songs.length;
		var newEl = {
			id : "",
			playlist : Playlist.current,
			song_id : el.info.song_id,
			title : el.info.title,
			artist : el.info.artist,
			album : el.info.album
		};
		
		Playlist.songs.push(newEl);
		
		Playlist.render(Playlist.songs,Playlist.current,index);
		
		Playlist.writeIndex();
		
		if (User._username)
		{
			$.getJSON('./ss/playlist.php',
			{
				action : "addSong",
				username : User._username,
				playlist : Playlist.current,
				song_id : el.info.song_id,
				title : el.info.title,
				artist : el.info.artist,
				album : el.info.album
			});
			
			$.getJSON('./ss/update.php', { 
				action : "log", 
				type : "add", 
				id : el.info.song_id,
				title:el.info.title,
				artist:el.info.artist,
				username:User._username
			});
		}
	},
	
	remove : function(el)
	{
		if (el.nextSibling)
		{
			tempEl = el.nextSibling;
		}
		else if (el.previousSibling)
		{
			tempEl = el.previousSibling;
		}
		
		setTimeout(function() { selection.select('songList',tempEl); },10);
		
		var index = parseFloat(el.info.index);
		
		Playlist.songs.splice(index,1);
		
		el.parentNode.removeChild(el);
		
		Playlist.writeIndex();
		
		if (User._username)
		{
			$.getJSON('./ss/playlist.php',
			{
				action : "removeSong",
				username : User._username,
				playlist : Playlist.current,
				song_id : el.info.song_id
			},
			function(data)
			{
				
			});
		}
	},
	
	emptyList : function()
	{
		
		var playlist = Playlist.current;
		
		dialog.confirm("Permanently remove all songs in playlist "+(playlist+1),function()
		{
			var playlist = Playlist.current;
			var container = Dom.get('playlist_'+playlist);
			selection.deselect('songList');
			container.innerHTML = '';
			
			for (var i=(Playlist.songs.length-1); i>=0; i--)
			{
				if (Playlist.songs[i].playlist == Playlist.current)
				{
					Playlist.songs.splice(i,1);
				}
			}
			
			if (User._username)
			{
				$.getJSON('./ss/playlist.php',
				{
					action : "emptyPlaylist",
					username : User._username,
					playlist : Playlist.current,
				});
			}
			
			Scroll.updateScroll();
			
		});
	},
	
	writeIndex : function()
	{
		var container;
		var className;
		
		for (var i=0; i<Playlist.list.length;i++)
		{
			container = Dom.get('playlist_'+i);
			
			for (var s=0; s<container.childNodes.length;s++)
			{
				if (s%2 == 0) { className = 'odd'; }
				else { className = 'even'; }
				
				container.childNodes[s].getElementsByClassName('index')[0].innerHTML = s+1;
				
				if (container.childNodes[s].className.indexOf('selected') >= 0)
				{
					container.childNodes[s].className = 'item ' + className + ' selected';
				}
				else
				{
					container.childNodes[s].className = 'item ' + className + '';
				}
			}
		}
	}
	
};

var Focus = {
	
	_focusElement : null,
	
	isWindowFocused : false,
	
	set : function(el)
	{
		if (typeof el == 'string') { var el = document.getElementById(el); }
		
		if (el != Focus._focusElement)
		{
			if (el.className == "") { el.className = 'focused'; }
			else { el.className = el.className + ' focused'; }
			
			if (Focus._focusElement!=null)
			{
				if (Focus._focusElement.className == "focused")
				{
					Focus._focusElement.className = '';
				}
				else
				{
					Focus._focusElement.className = Focus._focusElement.className.replace(' focused','');
				}
			}
			
			Focus._focusElement = el;
		}
	},
	
	get : function()
	{
		if (Focus._focusElement!=null) { return Focus._focusElement; }
		else return false;
	},
	
	getID : function()
	{
		if (Focus._focusElement!=null) { return Focus._focusElement.id; }
		else return false;
	},
	
	windowBlur : function()
	{
		var el = Focus.get();
		
		if (el) 
		{
			if (el.className.indexOf('focused')>=0)
			{
				el.className = el.className.replace('focused','');
			}
		}
		
		Focus.isWindowFocused = false;
	},
	
	windowFocus : function()
	{
		var el = Focus.get();
		
		if (el) 
		{
			if (el.className.indexOf('focused') == -1)
			{
				el.className = el.className + 'focused';
				el.focus();
			}
		}
		
		Focus.isWindowFocused = true;
	}
	
};

var User = {
	
	_loggedIn : false,
	
	_username : null,
	
	_key : null,
	
	_session_id: null,
	
	logout : function()
	{
		dialog.confirm('Are you sure you want to logout?',function()
		{
			setCookie('u','',-1);
			window.location.href = "./?action=logout";
		});
	},
	
	login : function()
	{
		var username = document.getElementById('username').value;
		var password = document.getElementById('password').value;
		
		if (username == "" || password == "")
		{
			dialog.alert("Username and Password can not be blank");
		}
		else
		{
			loading.show('Logging in...');
			$.ajax("./ss/user.php", {
				
				data : { action:"login", u:username, p:password },
				success : function(data)
				{
					loading.hide();
					
					var d = JSON.parse(data);
					
					if (d.result == 'success')
					{
						Dom.get('loginContainer').className = 'hidden';
						User._key = d.key;
						User.loginSuccess(d.username);
						Dom.get('searchText').focus();
					}
					else
					{
						dialog.alert('Username and Password not match. Please try again.',function()
						{
							document.getElementById('username').value = '';
							document.getElementById('password').value = '';
							//User.loginForm();
						});
					}
				}
				
			});
		}
	},
	
	loginSuccess : function(username)
	{
		User._loggedIn = true;
		User._username = username;
		
		Dom.get('homeUsernameText').innerHTML = User._username;
		Dom.get('accountInfoContainer').className = 'accountInfo user';
		Dom.get('toolbarAccountName').innerHTML = User._username;
		
		Playlist.get();
		
		Dom.get('registerContainer').className = 'hidden';
		Dom.get('loginContainer').className = 'hidden';
		
		if (!getCookie('u')) { setCookie('u',username,14); }
		
		//Main.load();
	},
	
	loginForm : function()
	{
		Event.removeListener('loginRegisterButton',clickEventText);
		Event.removeListener('loginSubmitButton',clickEventText);
		Event.removeListener('username','keypress');
		Event.removeListener('password','keypress');
		
		Event.on('loginRegisterButton',clickEventText,function(event){ User.registerForm(); });
		Event.on('loginSubmitButton',clickEventText,function(event){ User.login(); });
		Event.on('username','keypress',function(event){ if (event.keyCode == 13) { User.login(); } });
		Event.on('password','keypress',function(event){ if (event.keyCode == 13) { User.login(); } });
		
		if (Dom.get('searchBar').className == 'normal') { Main.showHome(); }
		Dom.get('registerContainer').className = 'hidden';
		Dom.get('loginContainer').className = 'visible';
		setTimeout(function(){document.getElementById('username').focus();},500);
	},
	
	register : function()
	{
		var illegalStrings = [
			"admin",
			"pwaq",
			"customer",
			"support",
			"about",
			"administrator",
			"about",
			"admin"
		];
		var username = document.getElementById('registerUsername').value;
		var password = document.getElementById('registerPassword').value;
		var passwordConfirm = document.getElementById('registerPasswordConfirm').value;
		
		if (username == "" || password == "" || passwordConfirm == "")
		{
			dialog.alert("Username and Password can not be blank");
		}
		else if (!username.match(/^[a-zA-Z0-9_]+$/))
		{
			dialog.alert("Your username may only contains alphanumeric characters and underscores. Other characters are not allowed.");
		}
		else if (password != passwordConfirm)
		{
			dialog.alert("Password does not match");
		}
		else
		{
			
			loading.show('Registering...');
			$.ajax("./ss/user.php", {
				
				data : { action:"register", u:username, p:password },
				success : function(data)
				{
					loading.hide();
						
					var d = JSON.parse(data);
					
					if (d.result == 'success')
					{
						Dom.get('registerContainer').className = 'hidden';
						User.loginSuccess(d.username);
						dialog.alert("Your username \""+d.username+"\" has been registered successful.",function(){ Dom.get('searchText').focus(); });
					}
					else
					{
						dialog.alert("Username \""+d.username+"\" is already taken, please choose another username.",function(){ User.registerForm(); });
					}
				}
				
			});
			
		}
	},
	
	registerForm : function()
	{
		Event.removeListener('registerCancelButton','click');
		Event.removeListener('registerSubmitButton','click');
		Event.removeListener('registerUsername','keypress');
		Event.removeListener('registerPassword','keypress');
		Event.removeListener('registerPasswordConfirm','keypress');
		
		Event.on('registerCancelButton','click',function(event){ User.loginForm(); });
		Event.on('registerSubmitButton','click',function(event){ User.register(); });
		Event.on('registerUsername','keypress',function(event){ if (event.keyCode == 13) { User.register(); } });
		Event.on('registerPassword','keypress',function(event){ if (event.keyCode == 13) { User.register(); } });
		Event.on('registerPasswordConfirm','keypress',function(event){ if (event.keyCode == 13) { User.register(); } });
		
		if (Dom.get('searchBar').className == 'normal') { Main.showHome(); }
		Dom.get('registerContainer').className = 'visible';
		Dom.get('loginContainer').className = 'hidden';
		setTimeout(function(){document.getElementById('registerUsername').focus();},500);
	}
	
};

var selection = {
	
	current : [],
	
	tempGroup : false,
	
	tempEl : false,
	
	select : function(group,el)
	{
		if (typeof el == 'string') { var el = document.getElementById(el); }
		
		if (selection.current[group] == null)
		{
			$(el).addClass('selected');
		}
		else if (selection.current[group] != el)
		{
			var lastEl = selection.current[group];
			
			$(el).addClass('selected');
			$(lastEl).removeClass('selected');
		}
		
		selection.current[group] = el;
	},
	
	deselect : function(group)
	{
		if (selection.get(group) != null)
		{
			el = selection.get(group);
			
			$(el).removeClass('selected');
			
			selection.current[group] = null;
		
		}
	},
	
	get : function(group)
	{
		if (selection.current[group] == null)
		{
			return null;
		}
		else
		{
			return selection.current[group];
		}
	},
	
	set : function(group,el)
	{
		selection.current[group] = el;
		return true;
	},
	
	check : function(el)
	{
		if (typeof el == 'string') { var el = document.getElementById(el); }
		
		if (el.className.indexOf(' selected') >= 0)
		{
			$(el).removeClass('selected');
		}
		else
		{
			$(el).addClass('selected');
		}
	}
	
};

var Keys = {
	SHIFT : false,
	CTRL : false,
	CMD : false,
	ALT : false,
	
	update : function(keyCode,keydown)
	{
		if (keyCode == 16) { Keys.SHIFT = keydown; }
		if (keyCode == 17) { Keys.CTRL = keydown; }
		if (keyCode == 18) { Keys.ALT = keydown; }
		if (keyCode == 91) { Keys.CMD = keydown; }
		if (keyCode == 93) { Keys.CMD = keydown; }
	}
};

document.onkeyup = function(evt)
{
	evt = evt || window.event;
	
	var keyCode = evt.keyCode;
	
	Keys.update(keyCode,false);
};

document.onkeydown = function(evt) 
{
	evt = evt || window.event;
	
	var keyCode = evt.keyCode;
	
	Keys.update(keyCode,true);
	
	// CHECK TO SEE IF THE CURSOR IS FOCUSED ON THE APPLICATION
	if (Focus.isWindowFocused)
	{
		// GET THE CURRENT FOCUSED CONTAINER
		var focusContainer = Focus.get();
		// IF THERE IS A CONTAINER FOCUSED
		if (focusContainer)
		{
			// FIND OUT THE GROUP NAME OF THE FOCUS CONTAINER
			// EITHER SONGLIST, SEARCHLIST, OR ALBUMLIST
			if (focusContainer.id == 'playlistContainer') { var selectionGroup = 'songList'; }
			if (focusContainer.id == 'searchContainer') { var selectionGroup = 'searchList'; }
			if (focusContainer.id == 'infoPanel') { var selectionGroup = 'infoList'; }
			if (focusContainer.id == 'albumPanel') { var selectionGroup = 'searchList'; }
				
			// GET THE SELECTED ELEMENT IN THE FOCUS CONTAINER
				var el = selection.get(selectionGroup);
				
				// IF THERE IS ELEMENT DEFINED
				// THEN CONTINUE WITH THE KEYSTROKE DETECTION
				if (el != null)
				{
					// DETECT UP DOWN ARROW KEYS
					if (keyCode == 40)
					{
						// DOWN ARROW KEY IS DETECTED
						if (el.nextSibling) { selection.select(selectionGroup,el.nextSibling); return false; }
					}
					else if (keyCode == 38)
					{
						// DOWN ARROW KEY IS DETECTED
						if (el.previousSibling) { selection.select(selectionGroup,el.previousSibling); return false; }
					}
					
					if (focusContainer.id == 'playlistContainer' || 'searchContainer' || 'infoContainer' || 'albumPanel' || 'toolbar')
					{
					
					// PLUS KEY DETECTED
					if (keyCode == 187) {
						
						if (Player.volume < 100)
						{
							newVolume = Player.volume+10;
							Player.setVolume(newVolume);
							
							return false;
						}
						
					}
					// MINUS KEY DETECTED
					if (keyCode == 189) {
						
						if (Player.volume > 0)
						{
							newVolume = Player.volume-10;
							Player.setVolume(newVolume);
							
							return false;
						}
						
					}
					// ESC KEY DETECTED
					if (keyCode == 27) {
						
						Main.showHome();
						
						return false;
						
					}
				}
				
				// SHORT CUT KEY STROKE INSIDE PLAYLIST
				// SPACE -> PLAY/PAUSE
				// ENTER -> PLAY SONG
				// (CMD OR CTRL) + BACKSPACE -> DELETE SONG
				if (selectionGroup == 'songList')
				{
					// ENTER KEY DETECTED
					if (keyCode == 13) {
						
						Player.play(el);
						return false;
						
					}
					// SPACE KEY DETECTED
					if (keyCode == 32) {
						
						Handlers.playButton();
						return false;
						
					}
					// RIGHT ARROW KEY DETECTED
					if (keyCode == 39) {
						
						Player.playNext();
						return false;
						
					}
					// LEFT ARROW KEY DETECTED
					if (keyCode == 37) {
						
						Player.playPrevious();
						return false;
						
					}
					// COMMAND+BACKSPACE OR CTRL+BACKSPACE
					if ((Keys.CMD || Keys.CTRL) && keyCode == 8)
					{
						Playlist.remove(el);
						return false;
					}
					// BACKSPACE KEY DETECTED
					/*
					if (keyCode == 8) {
						
						soundManager.stop('song');
						soundManager.play('song');
						return false;
						
					}
					*/
				}
				
				if (selectionGroup == 'searchList')
				{
					// ENTER KEY DETECTED
					if (keyCode == 13) {
						if (el.className.indexOf('item')>=0)
						{
							if (el.info.type == "song")
							{
								Playlist.add(el);
							}
							else if (el.info.type == "album")
							{
								Search.getAlbum(el);
							}
							return false;
						}
					}
					// ESC KEY DETECTED
					if (keyCode == 27) {
						
						Main.showHome();
						return false;
						
					}
				}
			
			}
		}
	}
};