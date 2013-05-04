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


// Declare shortcuts to YUI classes
var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var Lang = YAHOO.lang;
var JSON = YAHOO.lang.JSON;

//var u = new Date().getTime();
var u = 12;
var r = 1;

var App = {
	
	state : 'not ready',
	
	scripts : [
		[
			"js/all.js?u="+u
		],
		[
			"js/main.js?u="+u,
			"js/search.js?u="+u,
			"js/info.js?u="+u,
			"js/dialog.js?u="+u,
			"lib/iscroll.js?u="+u,
			"lib/yui/connection-min.js?"+u,
			
			"lib/jquery-1.5.2.js?"+u,
			"lib/jquery-ui/minified/jquery.ui.core.min.js?"+u,
			"lib/jquery-ui/minified/jquery.ui.widget.min.js?"+u,
			"lib/jquery-ui/minified/jquery.ui.mouse.min.js?"+u,
			"lib/jquery-ui/minified/jquery.ui.sortable.min.js?"+u,
			
			"lib/soundmanager2/soundmanager2-nodebug-jsmin.js?"+u
		]
	],
	
	stylesheets : [
		[
			"styles/all.css?u="+u
		],
		[
			"styles/playlist.css?u="+u,
			"styles/info.css?u="+u,
			"styles/search.css?u="+u,
			"styles/sidebar.css?u="+u,
			"styles/toolbar.css?u="+u,
			"styles/dialog.css?u="+u,
			"styles/fleXscroll.css?u="+u
		]
	]
	
};

Event.on(document,'touchmove',function(event){ event.preventDefault(); });

Event.on(window,'load', function() {
	
	setTimeout(function()
	{
		loading.show();
		
		var getScript = YAHOO.util.Get.script( App.scripts[r] , { 
			onSuccess: function() {
				
				loading.hide();
				Main.loadBasic();
				Main.load();
				
			},
			
			onFailure : function()
			{
				alert('required system file missing.');
			}
		});
		
		var getCss = YAHOO.util.Get.css( App.stylesheets[r] , { 
			onSuccess: function() {
				
			},
			
			onFailure : function()
			{
				alert('required system file missing.');
			}
		});
	
	},20);
	
});



var loading = {
	
	show : function()
	{
		Dom.get('loading').style.display = 'block';
	},
	
	hide : function()
	{
		Dom.get('loading').style.display = 'none';
	}
	
};

// Browser Check

if (getCookie('overwrite') != "1")
{
	
	if (navigator.userAgent.indexOf('iPad') >= 1)
	{
		browserCheckPass = true;
	}
	else if (navigator.userAgent.indexOf('iPhone') >= 1)
	{
		location = 'notsupport.php?browser=Mobile+Safari+for+iPhone';
	}
	else
	{
		if ((BrowserDetect.browser=='Firefox'&& BrowserDetect.version>=4) || 
		(BrowserDetect.browser=='Safari'&& BrowserDetect.version>=5) || 
		(BrowserDetect.browser=='Chrome'&& BrowserDetect.version>=10) || 
		(BrowserDetect.browser=='Internet Explorer'&& BrowserDetect.version>=9))
		{
			browserCheckPass = true;
		}
		else
		{
			location = 'notsupport.php?browser='+BrowserDetect.browser+'&version='+BrowserDetect.version+'';
		}
	}

}