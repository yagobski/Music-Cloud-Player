jQuery(function($) {
    
$('#add_song').ajaxForm({
    beforeSend: function() {
        $('#status').empty();
        var percentVal = '0%';
        $('.barer').width(percentVal)
        $('.percenter').html(percentVal);
        
    },
    uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        $('.barer').width(percentVal)
        $('.percenter').html(percentVal);
        
    },
	complete: function(xhr) {
		$('#status').html(xhr.responseText);
	},
	success:    function() { 
        alert('Your song was added!');
        loader("manage_song");
    } 
}); 

});


function logout()
	{

		window.location.href = player_root + "./?action=logout";
	};


function loader(page) {
    $.ajax({
        url: player_root + 'ss/page.php',
        type: 'GET',
        data: {
        	page:page
        },
        cache: false,
        beforeSend: function () {
            $("#loading").show();
        },
        success: function (data) {
            if (data == 'error') {
                alert('Sorry we have some problem please try again');
                return false;
            } else {
             $('#publisher').html(data);
             $('#example').dataTable({
					bAutoWidth: false,
                                       
					iDisplayLength: 10,
					
					sDom: "<'row-fluid'<'widget-header'<'span6'l><'span6'f>>>rt<'row-fluid'<'widget-footer'<'span6'><'span6'p>>"
	
			});
                $("#loading").hide();
                
            }
        }
    });
};
		
function action(object_id, type) {
    $.ajax({
        url: player_root + 'ss/action.php',
        type: 'GET',
        data: {
        	type:type,
            object_id: object_id
        },
        cache: false,
        beforeSend: function () {
            $("#loading").show();

        },
        success: function (data) {
            if (data == 'error') {
                alert('Sorry we have some problem please try again');
                return false;
            } else {
				if((type == 'delete_user') || (type == 'add_admin') || (type == 'delete_admin')){
				 	loader("manage_user");
				}else if(type == 'delete_song'){
					loader("manage_song");
				}else if(type == 'edit_song_action'){
				
				}else if(type == 'edit_song'){
				 	$("#modal-body-edit").html(data);
				 	$('#edit_song').modal('show');
				}
                $("#loading").hide();

            }
        }
    });

};