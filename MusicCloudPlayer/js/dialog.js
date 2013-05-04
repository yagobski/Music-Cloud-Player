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


var dialog = {
	
	current : null,
	
	show : function(id)
	{
		var d = document.getElementById(id);
		
		dialog.current = d;
		
		Dom.setStyle(d,'display','block');
		
		setTimeout(function()
		{
			dialog.current.className = 'dialog scaled';
		},100);
		setTimeout(function()
		{
			dialog.current.className = 'dialog visible';
		},300);
	},
	
	hide : function()
	{
		setTimeout(function()
		{
			dialog.current.className = 'dialog hidden';
		},100);
		setTimeout(function()
		{
			Dom.setStyle(dialog.current,'display','none');
		},500);
	},
	
	alert : function(text,callback)
	{
		var el = document.getElementById('message');
		var html = '';
		
		html += '<div class="text">'+text+'</div>';
		html += '<div class="buttonContainer"><a id="messageDoneButton" class="button">Ok</a></div>';
		el.innerHTML = html;
		
		if (callback)
		{
			dialog.confirmCallback = callback;
			Event.on('messageDoneButton','click',function(){ dialog.confirmCallback(); });
		}
		
		Event.on('messageDoneButton','click',function(){ dialog.hide(); });
		dialog.show('message');
		document.getElementById('messageDoneButton').focus();
	},
	
	confirmCallback : null,
	
	confirm : function(text,callback)
	{
		var el = document.getElementById('message');
		var html = '';
		
		html += '<div class="text">'+text+'</div>';
		html += '<div class="buttonContainer"><a id="messageCancelButton" class="button">No</a><a id="messageDoneButton" class="button">Yes</a></div>';
		el.innerHTML = html;
		
		dialog.confirmCallback = callback;
		
		Event.on('messageCancelButton','click',function(){ dialog.hide('message'); });
		Event.on('messageDoneButton','click',function(){ dialog.hide('message'); dialog.confirmCallback(); });
		dialog.show('message');
		document.getElementById('messageDoneButton').focus();
	}
};