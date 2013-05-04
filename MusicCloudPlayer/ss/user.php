<?php

@session_start ();

define ( 'ROOT_DIR' , '..' );

define ( 'INCLUDE_DIR', ROOT_DIR . '/includes' );

include (INCLUDE_DIR . '/config.inc.php');

require_once INCLUDE_DIR . '/class/_class_mysql.php';

require_once INCLUDE_DIR . '/db.php';

require_once ROOT_DIR . '/modules/functions.php';

$action = $db->safesql( $_REQUEST['action'] );

$username = $db->safesql( $_REQUEST['u'] );

$username = preg_replace("/[^a-zA-Z0-9\s]/", "", $username);

$password = $db->safesql( $_REQUEST['p'] );

$page = $db->safesql( $_REQUEST['page'] );

$REGISTER = TRUE;

if( $action == "register" ){
	
	if( $username && $password ){
		
		if( preg_match('/[^0-9A-Za-z]/', $username)){
			
			$buffer =  '{"result":"failed","message":"failed"}';
			
			$REGISTER = FALSE;
			
		}elseif( strlen( $password ) < 4 ){
			
			$buffer =  '{"result":"failed","message":"failed"}';
			
			$REGISTER = FALSE;
			
		}else{
			
			$row = $db->super_query("SELECT user_id FROM vass_users WHERE username = '" . $username . "' LIMIT 0,1");
			
			if( $row['user_id'] ){
			
				$buffer =  '{"result":"failed","message":"existed","username":"' . $username . '"}';
				
				$REGISTER = FALSE;
				
			}else{
			
				$row = $db->super_query("SELECT user_id FROM vass_users WHERE username = '" . $username . "' LIMIT 0,1");
				
				if( $row['user_id'] ){
				
					$buffer =  '{"result":"failed","message":"existed","username":"' . $username . '"}';
					
					$REGISTER = FALSE;
					
				}
				
			}
			
			if( $REGISTER ) {
				
				$db->query("INSERT INTO vass_users SET username = '" . $username . "', password = '" . md5( $password ) . "', reg_date = '" . date( "Y-m-d H:i:s", time() ) . "'");
				
				$login_user = $username;
				
				$login_pass = md5( $password );
				
				$member_id = array ();
				
				$member_id = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE username = '" . $login_user . "' AND password ='" . $login_pass . "'");
				
				@session_register( 'logged' );
				
				@session_register( 'member' );
				
				$_SESSION['logged'] = TRUE;
				
				$_SESSION['member'] = $member_id;
				
				set_cookie( "user_id", $member_id['user_id'], 365 );
				
				set_cookie( "login_user", $login_user, 365 );
				
				set_cookie( "login_pass", $login_pass, 365 );
				
				$buffer =  '{"result":"success","username":"' . $username . '","key":"' . md5( $username ) . '"}';
				
			}
			
		}
		
	}
	
}elseif( $action == "login" ){
	
	if( ! $_SESSION['logged'] && $username ){
		
		$login_user = $db->safesql( $username );
		
		$login_pass = $db->safesql( $password );
		
		$member_id = array ();
		
		$login_pass	= md5($login_pass);
		
		$member_id = $db->super_query("SELECT * FROM " . PREFIX . "_users WHERE username = '" . $login_user . "' AND password ='" . $login_pass . "'");
		
		if( $member_id['user_id'] ){
		
			@session_register( 'logged' );
			
			@session_register( 'member' );
	    	
			$_SESSION['logged'] = TRUE;
			
			$_SESSION['member'] = $member_id;
			
			set_cookie( "user_id", $member_id['user_id'], 365 );
			
			set_cookie( "login_user", $login_user, 365 );
			
			set_cookie( "login_pass", $login_pass, 365 );
	    	
			$db->query("UPDATE vass_users SET last_date = '" . date( "Y-m-d H:i:s", time() ) . "' WHERE user_id = '" . $member_id['user_id'] . "'");
			
			$buffer =  '{"result":"success","username":"' . $username . '","key":"' . md5( $username ) . '"}';
			
			if( $page == 'admin' ){
			  header('Location: '.$config["siteurl"].'admin/admin.php');
			}
	    }else{
	    
			$buffer =  '{"result":"failed","username":"","key":""}';
			if( $page == 'admin' ){
			  header('Location: '.$config["siteurl"].'admin/index.php?error=failed');
			}
			
		}
	}
	
}

print $buffer;

?>