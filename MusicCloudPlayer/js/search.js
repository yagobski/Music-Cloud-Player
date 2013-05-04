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

var Search = {
	
	keyword : "",
	
	type : "Song",
	
	page : 0,
	
	resultsPerPage : 10,
	
	running : false,
	
	selectOption : function(type)
	{
		selection.select('searchOption','searchOption_'+type);
		Search.type = type;
		Search.execute(0);
	},
	
	showSearch : function(id)
	{
		Dom.get('searchContainer').style.display = "block";
		setTimeout(function() {
		Dom.get('searchContainer').className = Dom.get('searchContainer').className.replace('hidden','visible');
		},100);
	},
	
	hideSearch : function(id)
	{
		Dom.get('searchContainer').className = Dom.get('searchContainer').className.replace('visible','hidden');
		setTimeout(function(){ Dom.get('searchContainer').style.display = "none"; },500);
	},
	
	showAlbum : function(id)
	{
		Dom.get('albumPanel').style.display = "block";
		setTimeout(function() {
		Dom.get('albumPanel').className = Dom.get('albumPanel').className.replace('hidden','visible');
		},100);
	},
	
	hideAlbum : function(id)
	{
		Dom.get('albumPanel').className = Dom.get('albumPanel').className.replace('visible','hidden');
		setTimeout(function(){ Dom.get('albumPanel').style.display = "none"; },500);
	},
	
	executeText : function(keyword)
	{
		Dom.get('searchText').value = keyword;
		Search.execute(1);
	},
	
	execute : function(page)
	{
		if (Search.running == false)
		{
			if (!page) { page = 1; }
			Search.page = page;
			Search.keyword = Dom.get('searchText').value;
			
			if (Search.keyword != "")
			{
				Search.running = true;
				
				Main.show('searchReset');
				Main.show('searchLoading');
				Search.hideAlbum();
				Dom.get('searchPlaceHolder').style.display = "none";
				
				Main.hideHome();
				
				var container = Dom.get('searchResults');
				
				if (page == 1)
				{
					selection.deselect('searchList');
					container.innerHTML = '';
				}
				
				var url = './ss/search.php';
				
				
				$.getJSON(url,
				{
					type : Search.type,
					query : remove_accents(Search.keyword),
					page : Search.page,
					resultsPerPage : Search.resultsPerPage,
					username : User._username,
				},
				function(data)
				{
					Main.hide('searchLoading');
					Search.showSearch();
					
					if (data != null)
					{
						if (Search.type == 'Song' || Search.type == 'Artist')
						{
							Search.render.songList(data);
						}
						else if (Search.type == 'Album')
						{
							Search.render.albumList(data);
						}
						else if (Search.type == 'All')
						{
							Search.render.all(data);
						}
					}
					
					Search.running = false;
			
					Scroll.updateScroll();
					setTimeout(fleXenv.updateScrollBars,1000);
				}).error(function(XMLHttpRequest, textStatus, errorThrown)
				{
					Search.running = false;
					Main.hide('searchLoading');
					Handlers.searchReset();
				});
				
			}
		
		}
		
	},
	
	getAlbum : function(el)
	{
		loading.show();
		var id = el.info.album_id;
		var title = el.info.title;
		var artist = el.info.artist;
		var url = './ss/search.php?type=SongByAlbum&id='+id;
		
		Dom.get('albumTitle').innerHTML = title;
		Dom.get('albumArtist').innerHTML = artist;
		
		$.getJSON(url,function(data)
		{
			Search.showAlbum();
			Focus.set('albumPanel');
			Dom.get('albumResults').innerHTML = '';
			
			Event.removeListener('albumPanelClose','click');
			Event.on('albumPanelClose','click',function()
			{
				Search.hideAlbum();
				Focus.set('searchContainer');
			});
			
			if (data != null)
			{
				Search.render.albumSongList(data);
			}
	
			Scroll.updateScroll();
			setTimeout(Scroll.updateScroll,1000);
			
			loading.hide();
		});
		
	},
	
	render : {
		
		albumList : function(data)
		{
			for (var i=0; i<data.results.album.length;i++)
			{
				Search.render.albumSingle(data.results.album,i);
			}
			
			if (data.results.album.length == Search.resultsPerPage)
			{
				var el = document.createElement('div');
				el.id = 'searchMore';
				el.innerHTML = 'More';
				
				Event.on(el,'click',function()
				{
					this.parentNode.removeChild(this);
					
					Search.execute(Search.page+1);
				});
				
				Dom.get('searchResults').appendChild(el);
			}
			else
			{
				var el = document.createElement('div');
				el.className = 'noResult';
				
				if (data.results.album.length == 0) { el.innerHTML = 'NO RESULT FOUND'; }
				else { el.innerHTML = 'NO MORE RESULTS'; }
				
				Dom.get('searchResults').appendChild(el);
			}
		},
		
		songList : function(data)
		{
			if (Search.page==1000)
			{
				for (var i=0; i<data.results.album.length;i++)
				{
					Search.render.albumSingle(data.results.album,i);
				}
			}
			for (var i=0; i<data.results.song.length;i++)
			{
				Search.render.single(data.results.song,i);
			}
			
			if (data.results.song.length == Search.resultsPerPage)
			{
				var el = document.createElement('div');
				el.id = 'searchMore';
				el.innerHTML = 'More';
				
				Event.on(el,'click',function()
				{
					this.parentNode.removeChild(this);
					
					Search.execute(Search.page+1);
				});
				
				Dom.get('searchResults').appendChild(el);
			}
			else
			{
				var el = document.createElement('div');
				el.className = 'noResult';
				
				if (data.results.song.length == 0) { el.innerHTML = 'NO RESULT FOUND'; }
				else { el.innerHTML = 'No more result'; }
				
				Dom.get('searchResults').appendChild(el);
			}
		},
		
		all : function(data)
		{
			
			/* ############# */
			if (data.results.artist.length > 0)
			{
				var el = document.createElement('div');
				el.className = 'searchResultHeader';
				el.innerHTML = 'Artist found';
				Dom.get('searchResults').appendChild(el);
				
				for (var i=0; i<data.results.artist.length;i++)
				{
					Search.render.artistSingle(data.results.artist,i,'searchResults',i+1);
				}
			}
			
			/* ############# */
			if (data.results.song.length > 0)
			{
				
				var el = document.createElement('div');
				el.className = 'searchResultHeader';
				el.innerHTML = 'Songs found for "'+ Search.keyword+'"';
				Dom.get('searchResults').appendChild(el);
				
				for (var i=0; i<data.results.song.length;i++)
				{
					Search.render.single(data.results.song,i);
				}
				
				if (data.results.song.length == 5)
				{
					var el = document.createElement('div');
					el.id = 'searchMore';
					el.innerHTML = 'SHOW ALL';
					
					Event.on(el,'click',function()
					{
						Search.selectOption('Song');
					});
					
					Dom.get('searchResults').appendChild(el);
				}
			}
			
			/* ############# */
			if (data.results.album.length > 0)
			{
				var el = document.createElement('div');
				el.className = 'searchResultHeader';
				el.innerHTML = 'Albums found for "'+ Search.keyword+'"';
				Dom.get('searchResults').appendChild(el);
				
				for (var i=0; i<data.results.album.length;i++)
				{
					Search.render.albumSingle(data.results.album,i);
				}
				
				if (data.results.album.length == 5)
				{
					var el = document.createElement('div');
					el.id = 'searchMore';
					el.innerHTML = 'SHOW ALL';
					
					Event.on(el,'click',function()
					{
						Search.selectOption('Album');
					});
					
					Dom.get('searchResults').appendChild(el);
				}
			}
			
			if (data.results.album.length == 0 && data.results.song.length == 0) {
				
				var el = document.createElement('div');
				el.className = 'noResult';
				
				el.innerHTML = 'NO RESULT FOUND';
				
				Dom.get('searchResults').appendChild(el);
			}
		},
		
		albumSongList : function(data)
		{
			
			for (var i=0; i<data.results.song.length;i++)
			{
				Search.render.single(data.results.song,i,'albumResults',i+1);
			}
		},
		
		single : function(list,index,container,indexText)
		{
			var className;
			if (!container) { var container = Dom.get('searchResults'); }
			else { var container = Dom.get(container); }
			
			if (index%2==0) { className = 'odd'; }
			else { className = 'even'; }
			
			var el = document.createElement('div');
			el.className = 'item '+className;
			el.info = {
				type : "song",
				song_id : list[index].id,
				title : list[index].title,
				artist : list[index].artist,
				album : list[index].album,
				bitrate : list[index].bitrate,
				playcount : list[index].playcount
			};
			
			if (!indexText) { var indexText = ((Search.page-1) * 10) + (index+1); }
			
			el.innerHTML = '<span class="index">'+indexText+'</span>';
			el.innerHTML += '<span class="add"></span>';
			el.innerHTML += '<span class="title">'+list[index].title+'</span>';
			el.innerHTML += '<span class="artist">'+list[index].artist+'</span>';
			el.innerHTML += '<span class="bitrate">'+list[index].bitrate+' Kpbs</span>';
			el.innerHTML += '<span class="playcount">'+list[index].playcount+' Listens</span>';
			el.innerHTML += '<span class="songIcon"></span>';
			el.innerHTML += '';
			
			Event.on(el,'click',function()
			{
				selection.select('searchList',this);
				
				// SIMULATE DOUBLE CLICK/TAP
				var now = new Date().getTime();
				if (now > Main.lastClicked + Main.clickTiming) { Main.lastClicked = now; }
				else
				{
					Playlist.add(this);
				}
			});
			
			Event.on(el.getElementsByClassName('add')[0],'click',function()
			{
				
			});
			
			container.appendChild(el);
			
		},
		
		artistSingle : function(list,index,container,indexText)
		{
			var className;
			if (!container) { var container = Dom.get('searchResults'); }
			else { var container = Dom.get(container); }
			
			if (index%2==0) { className = 'odd'; }
			else { className = 'even'; }
			
			var el = document.createElement('div');
			el.className = 'item artist '+className;
			el.info = {
				type : "artist",
				title : list[index].artist,
				id : list[index].id
			};
			
			Event.on(el,'click',function()
			{
				selection.select('searchList',this);
				
				// SIMULATE DOUBLE CLICK/TAP
				var now = new Date().getTime();
				if (now > Main.lastClicked + Main.clickTiming) { Main.lastClicked = now; }
				else
				{
					var text = this.getElementsByClassName('title')[0].innerHTML;
					Dom.get('searchText').value = text;
					Search.selectOption('All');
				}
			});
			
			if (!indexText) { var indexText = ((Search.page-1) * 10) + (index+1); }
			
			el.innerHTML = '<span class="index">'+indexText+'</span>';
			el.innerHTML += '<span class="title">'+list[index].artist+'</span>';
			el.innerHTML += '<span class="artistIcon"></span>';
			el.innerHTML += '';
			
			container.appendChild(el);
			
		},
		
		albumSingle : function(list,index)
		{
			var className;
			var container = Dom.get('searchResults');
			
			if (index%2==0) { className = 'odd'; }
			else { className = 'even'; }
			
			var el = document.createElement('div');
			el.className = 'item '+className;
			el.info = {
				type : "album",
				album_id : list[index].id,
				title : list[index].title,
				artist : list[index].artist,
				album : list[index].album,
				bitrate : list[index].bitrate,
				playcount : list[index].playcount
			};
			el.innerHTML = '<span class="index">'+(((Search.page-1) * 10) + (index+1))+'</span>';
			el.innerHTML += '<span class="add"></span>';
			el.innerHTML += '<span class="title">'+list[index].title+'</span>';
			el.innerHTML += '<span class="artist">'+list[index].artist+'</span>';
			el.innerHTML += '<span class="albumIcon"></span>';
			el.innerHTML += '';
			
			Event.on(el,'click',function()
			{
				selection.select('searchList',this);
				
				// SIMULATE DOUBLE CLICK/TAP
				var now = new Date().getTime();
				if (now > Main.lastClicked + Main.clickTiming) { Main.lastClicked = now; }
				else
				{
					Search.getAlbum(this);
				}
			});
			
			container.appendChild(el);
			
		}
		
	}
	
}