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
 

var Info = {
	
	search : function(text,type)
	{
		if (!type) { type = 'All'; }
		
		Search.selectOption(type);
		Search.executeText(text);
	},
	
	getActivity : function()
	{
		$.getJSON('./ss/info.php',{ action : "activity" },function(data)
		{
			var container = Dom.get('community');
			var el,className,action;
			
			container.innerHTML = '';
			
			for (var i=0; i<data.results.length; i++)
			{
				if (i%2 == 0) { className = 'odd'; }
				else { className = 'even'; }
				el = document.createElement('div');
				el.className = 'item '+className;
				
				if (data.results[i].action == 'play') { action = 'listened to'; }
				if (data.results[i].action == 'add') { action = 'added'; }
				
				el.innerHTML = '<span class="text"><span class="username">'+data.results[i].username+'</span> '+action+' <a onclick="Info.search(this.innerHTML);">'+data.results[i].title+'</a> by <a onclick="Info.search(this.innerHTML);">'+data.results[i].artist+'</a></span>';
				el.innerHTML += '<span class="time">'+data.results[i].time+'</span>';
				
				container.appendChild(el);
			}
			
			Scroll.updateScroll();
		});
	},
	
	getRecommend : function(artist)
	{
		$.getJSON('./ss/info.php',{ action : "recommend", artist : artist },function(data)
		{
			var container = Dom.get('infoList');
			var conTitle = Dom.get('infoTitle');
			var el,className,action;
			
			container.innerHTML = '';
			conTitle.innerHTML = 'Also on Music Cloud Player';
			
			el = document.createElement('div');
			el.className = 'infoListTitle';
			el.innerHTML = 'Other songs by '+artist;
			container.appendChild(el);
			
			for (var i=0; i<data.tracks.length;i++)
			{
				if (i%2 == 0) { className = 'odd'; }
				else { className = 'even'; }
				
				el = document.createElement('div');
				el.className = 'item '+className;
				el.innerHTML = '<span class="title">'+data.tracks[i].title+'<span>';
				container.appendChild(el);
			}
			
			el = document.createElement('div');
			el.className = 'infoListTitle';
			el.innerHTML = 'Other albums by '+artist;
			container.appendChild(el);
			
			for (var i=0; i<data.albums.length;i++)
			{
				if (i%2 == 0) { className = 'odd'; }
				else { className = 'even'; }
				
				el = document.createElement('div');
				el.className = 'item '+className;
				el.innerHTML = '<span class="title">'+data.albums[i].album+'<span>';
				container.appendChild(el);
			}
			
			Scroll.updateScroll();
		});
	},
	
	getArtist : function(query)
	{
		if (query.indexOf(' & ') > 0) { var query = query.split(" & ")[0]; }
		else if (query.indexOf('&amp;') > 0) { var query = query.split("&amp;")[0]; }
		else if (query.indexOf('-') > 0) { var query = query.split("-")[0]; }
		else if (query.indexOf(',') > 0) { var query = query.split(",")[0]; }
		else if (query.indexOf(' Ft. ') > 0) { var query = query.split(" Ft. ")[0]; }
		else if (query.indexOf(' ft. ') > 0) { var query = query.split(" ft. ")[0]; }
		
		tempQuery = query;
		
		Dom.get('infoTitle').innerHTML = '';
		Dom.get('infoBioContent').innerHTML = '';
		Dom.get('infoTopTracks').innerHTML = '';
		Dom.get('infoTopAlbums').innerHTML = '';
		Dom.get('infoSimilarArtists').innerHTML = '';
		Dom.get('albumArtContainer').innerHTML = '';
		
		Main.hide('infoTopArtists');
		
		var container = Dom.get('infoList');
		
		$.getJSON('http://ws.audioscrobbler.com/2.0/?callback=?',
		{

			method: "artist.getinfo",
			artist: query,
			autocorrect: "1",
			api_key: "0f71612337a413d797fa4b6877cdfd30",
			format: "json"

		},
		function(data)
		{
			if (data.artist)
			{
				Dom.get('infoTitle').innerHTML = data.artist.name;
				
				if (data.artist.image.length > 0)
				{
					if (data.artist.image[2]) { var img = data.artist.image[2]; }
					else if (data.artist.image[1]) { var img = data.artist.image[1]; }
					else if (data.artist.image[0]) { var img = data.artist.image[0]; }
					
					var tempImg = new Image();
					tempImg.src = img['#text'];
					tempImg.onload = function()
					{
						//Dom.get('albumArtContainer').style.minHeight = (this.height)+'px';
					};
					
					Dom.get('albumArtContainer').innerHTML = '<img id="albumArt" src="'+img['#text']+'" border="0">';
					Dom.get('infoBioContent').innerHTML = '<span>'+data.artist.bio.content+'</span>';
					
					Scroll.updateScroll();
				}
				
				// Get Similar Artists
				if (data.artist.similar)
				{
					if (data.artist.similar.artist.length > 0)
					{
						data.artist.similar.artist.sort(function() {return 0.5 - Math.random()});
						
						var container = document.getElementById('infoSimilarArtists');
						container.innerHTML = '';
						
						el = document.createElement('div');
						el.className = 'infoListTitle';
						el.innerHTML = 'Similar Artists ';
						container.appendChild(el);
						
						c1 = document.createElement('div');
						c1.className = 'thumbContainer';
						container.appendChild(c1);
						
						if (data.artist.similar.artist.length >= 4) { var limit = 4; }
						else { var limit = data.artist.similar.artist.length; }
						
						for (var i=0; i<limit;i++)
						{
							
							el = document.createElement('div');
							el.className = 'thumb ';
							el.innerHTML = '<span class="title">'+data.artist.similar.artist[i].name+'</span>';
							el.innerHTML += '<img src="'+data.artist.similar.artist[i].image[2]['#text']+'" />';
							
							c1.appendChild(el);
							
							Event.on(el,'click',function()
							{
								Info.search(this.getElementsByClassName('title')[0].innerHTML,'All');
							});
						}
					}
				}
			
				// Get Top Album
				$.getJSON('http://ws.audioscrobbler.com/2.0/?callback=?',
				{
		
					method: "artist.getTopAlbums",
					artist: query,
					autocorrect: "1",
					limit : 8,
					api_key: "0f71612337a413d797fa4b6877cdfd30",
					format: "json"
		
				},
				function(data)
				{
					if (data.topalbums.album)
					{
						if (data.topalbums.album.length > 0)
						{
							var container = document.getElementById('infoTopAlbums');
							container.innerHTML = '';
							
							el = document.createElement('div');
							el.className = 'infoListTitle';
							el.innerHTML = 'Top Albums ';
							container.appendChild(el);
						
							c1 = document.createElement('div');
							c1.className = 'thumbContainer';
							container.appendChild(c1);
							
							if (data.topalbums.album.length < 8) { var limit = 4; }
							else { var limit = data.topalbums.album.length; }
							
							for (var i=0; i<limit;i++)
							{
								if (i%2 == 0) { className = 'odd'; }
								else { className = 'even'; }
								
								el = document.createElement('div');
								el.className = 'thumb';
								el.innerHTML = '<span class="title" style="display:none;">'+data.topalbums.album[i].name+'</span>';
								el.innerHTML += '<img src="'+data.topalbums.album[i].image[2]['#text']+'" />';
								c1.appendChild(el);
								
								Event.on(el,'click',function()
								{
									Info.search(this.getElementsByClassName('title')[0].innerHTML+' '+tempQuery,'Album');
								});
							}
						}
					}
					
					Scroll.updateScroll();
				});
				
				// Get Top Tracks
				$.getJSON('http://ws.audioscrobbler.com/2.0/?callback=?',
				{
		
					method: "artist.getTopTracks",
					artist: query,
					autocorrect: "1",
					limit: 10,
					api_key: "0f71612337a413d797fa4b6877cdfd30",
					format: "json"
		
				},
				function(data)
				{
					if (data.toptracks.track)
					{
						if (data.toptracks.track.length > 0)
						{
							var container = document.getElementById('infoTopTracks');
							container.innerHTML = '';
							
							el = document.createElement('div');
							el.className = 'infoListTitle';
							el.innerHTML = 'Top Tracks ';
							container.appendChild(el);
							
							for (var i=0; i<data.toptracks.track.length;i++)
							{
								if (i%2 == 0) { className = 'odd'; }
								else { className = 'even'; }
								
								el = document.createElement('div');
								el.className = 'item '+className;
								el.innerHTML = '<span class="title">'+data.toptracks.track[i].name+'</span>';
								el.innerHTML += '<span class="artist">'+data.toptracks.track[i].artist.name+'</span>';
								container.appendChild(el);
								
								Event.on(el,'click',function()
								{
									Info.search(this.getElementsByClassName('title')[0].innerHTML+' '+this.getElementsByClassName('artist')[0].innerHTML,'All');
								});
							}
						}
					}
				
					Scroll.updateScroll();
					
				});
			
			}
			
			Scroll.updateScroll();
		});
		
	},
	
	getTopChart : function()
	{
		Dom.get('infoTitle').innerHTML = 'What\'s Hot';
		Dom.get('infoBioContent').innerHTML = '';
		Dom.get('infoTopTracks').innerHTML = '';
		Dom.get('infoTopAlbums').innerHTML = '';
		Dom.get('infoSimilarArtists').innerHTML = '';
		Dom.get('albumArtContainer').innerHTML = '';
		
		Main.show('infoTopArtists');
		
		var container = Dom.get('infoList');
		
		// Get Top Tracks
		$.getJSON('http://ws.audioscrobbler.com/2.0/?callback=?',
		{

			method: "chart.getTopTracks",
			limit : 40,
			api_key: "0f71612337a413d797fa4b6877cdfd30",
			format: "json"

		},
		function(data)
		{
			if (data.tracks.track)
			{
				var container = document.getElementById('infoTopTracks');
				container.innerHTML = '';
				
				el = document.createElement('div');
				el.className = 'infoListTitle';
				el.innerHTML = 'Top Tracks ';
				container.appendChild(el);
				
				for (var i=0; i<data.tracks.track.length;i++)
				{
					if (i%2 == 0) { className = 'odd'; }
					else { className = 'even'; }
					
					el = document.createElement('div');
					el.className = 'item '+className;
					el.innerHTML = '<span class="title">'+data.tracks.track[i].name+'</span>';
					el.innerHTML += '<span class="artist">'+data.tracks.track[i].artist.name+'</span>';
					container.appendChild(el);
					
					Event.on(el,'click',function()
					{
						Info.search(this.getElementsByClassName('title')[0].innerHTML+' '+this.getElementsByClassName('artist')[0].innerHTML,'All');
					});
				}
			}
			
			Scroll.updateScroll();
		});
		
		// Get Top Artists
		
		$.getJSON('http://ws.audioscrobbler.com/2.0/?callback=?',
		{

			method: "chart.getTopArtists",
			limit : 20,
			api_key: "0f71612337a413d797fa4b6877cdfd30",
			format: "json"

		},
		function(data)
		{
			if (data.artists.artist)
			{
				data.artists.artist.sort(function() {return 0.5 - Math.random()});
				
				var container = document.getElementById('infoTopArtists');
				container.innerHTML = '';
				
				c1 = document.createElement('div');
				c1.className = 'thumbContainer';
				container.appendChild(c1);
				
				for (var i=0; i<4;i++)
				{
					
					el = document.createElement('div');
					el.className = 'thumb ';
					el.innerHTML = '<span class="title">'+data.artists.artist[i].name+'</span>';
					el.innerHTML += '<img src="'+data.artists.artist[i].image[2]['#text']+'" />';
					
					var tempImg = new Image();
					tempImg.src = data.artists.artist[i].image[2]['#text'];
					tempImg.onload = function()
					{
						if (this.height > this.width) { this.style.width = '90px'; }
						else { this.style.height = '90px'; }
					};
					
					c1.appendChild(el);
					
					Event.on(el,'click',function()
					{
						Info.search(this.getElementsByClassName('title')[0].innerHTML,'All');
					});
				}
			}
			
			Scroll.updateScroll();
		});
		
	},
	
}