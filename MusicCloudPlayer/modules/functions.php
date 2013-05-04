<?php

function set_cookie($name, $value, $expires) {
	if( $expires ) {	
		$expires = time() + ($expires * 86400);
	} else {	
		$expires = FALSE;
	}
	if( PHP_VERSION < 5.2 ) {	
		setcookie( $name, $value, $expires, "/", DOMAIN . "; HttpOnly" );
	} else {	
		setcookie( $name, $value, $expires, "/", DOMAIN, NULL, TRUE );	
	}
}

function CutContent($content, $from, $end){
	if ((($content and $from) and $end)){
		$r = explode($from, $content);
		if (isset($r[1])){
			$r = explode($end, $r[1]);
			return $r[0];
		}
		return;
	}
}

function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)
{
   //Test1: fichier correctement uploadé
     if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
   //Test2: taille limite
     if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
   //Test3: extension
     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
     if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
   //Déplacement
     return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
}

function avatar( $avatar, $username ){
	global $config;
	
	if( $avatar ){
		
		$avatar_json =	'"small": ' . json_encode( $config['siteurl'] . "uploadfiles/avatar_small_" . $username . ".jpg" ) . ', "medium": ' . json_encode( $config['siteurl'] . "/uploadfiles/avatar_medium_" . $username . ".jpg" ) . ', "original": ' . json_encode( $config['siteurl'] . "/uploadfiles/avatar_original_" . $username . ".jpg");
		
	}else{
		
		$avatar_json =	'"small": ' . json_encode( $config['siteurl'] . "uploadfiles/avatar_default_small.png" ) . ', "medium": ' . json_encode( $config['siteurl'] . "/uploadfiles/avatar_medium_default.png" );
		
	}
	
	return $avatar_json;
	
}
function songlist_images( $name ){
	global $config, $db;
	
	$hash = artisthash($name);
	
	$name = urlencode($name);
	
	$row = $db->super_query("SELECT extralarge, width, height, work FROM vass_images WHERE image_id = '" . $hash . "' LIMIT 0,1");
	
	if( $row['work'] == "0" ){
		
		$image_link = '{"small": null, 
						"large": null, 
						"medium": null
						}';
		
	}elseif( $row['work'] == "1" ){
			
			$image_link = '{
						"small": "http://userserve-ak.last.fm/serve/64/' . $row['extralarge'] . '.jpg", 
    	                "large": "http://userserve-ak.last.fm/serve/252/' . $row['extralarge'] . '.jpg", 
    	                "medium": "http://userserve-ak.last.fm/serve/126/' . $row['extralarge'] . '.jpg"
						}';
	}else{
					
					$image_link = '{
						"small": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=64", 
    	                "large": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=252", 
    	                "medium": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=126"
					}';
		
	}
	
	return $image_link;
	
}
function images( $name ){
	global $config, $db;
	
	$hash = artisthash($name);
	
	$name = urlencode($name);
	
	$row = $db->super_query("SELECT extralarge, width, height FROM vass_images WHERE image_id = '" . $hash . "' LIMIT 0,1");
	
	if( $row['extralarge'] ) {
		$image_link = '"medium": {
                    "src": "http://userserve-ak.last.fm/serve/64/' . $row['extralarge'] . '.jpg", 
                    "height": "61", 
                    "width": "64"
                }, 
                "large": {
                    "src": "http://userserve-ak.last.fm/serve/126/' . $row['extralarge'] . '.jpg", 
                    "height": "119", 
                    "width": "126"
                }, 
                "extralarge": {
                    "src": "http://userserve-ak.last.fm/serve/252/' . $row['extralarge'] . '.jpg", 
                    "height": "' . $row['height'] . '", 
                    "width": "' . $row['width'] . '"
                }, 
                "largesquare": {
                    "src": "http://userserve-ak.last.fm/serve/126s/' . $row['extralarge'] . '.jpg", 
                    "height": "126", 
                    "width": "126"
                }, 
                "small": {
                    "src": "http://userserve-ak.last.fm/serve/34/' . $row['extralarge'] . '.jpg", 
                    "height": "32", 
                    "width": "34"
                }';
			
	}else{
				
				$image_link = '"medium": {
                    "src": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=64", 
                    "height": "61", 
                    "width": "64"
                }, 
                "large": {
                    "src": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=126", 
                    "height": "119", 
                    "width": "126"
                }, 
                "extralarge": {
                    "src": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=252", 
                    "height": "200", 
                    "width": "252"
                }, 
                "largesquare": {
                    "src": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=126", 
                    "height": "126", 
                    "width": "126"
                }, 
                "small": {
                    "src": "' . $config['siteurl'] . 'gallery.php?q=' . $name . '&size=34", 
                    "height": "32", 
                    "width": "34"
                }';
		
	}
	
	$image_link = preg_replace("/[\n\r\t]/", "", $image_link);
	
	return $image_link;
	
}
function user_template ( $username, $follower_id, $name, $site, $total_loved, $total_following, $total_followers, $location, $avatar, $bio, $background_image, $background_repeat, $background_color, $background_position ){
	global $config, $db;
	
	if( $background_image ) {
		
		$use_image = "true";
		
		$is_default = "false";
		
	} else {
		
		$is_default = "true";
		
		$use_image = "false";
		
	}
	
	$buffer =  '{"status_code": 200, 
						"status_text": "OK", 
						"user": {
						"username": ' . json_encode ( $username ) . ',
						"bio": ' . json_encode ( $background_image ) . ',
						"image": {
			            	';
			        $buffer .= avatar( $avatar, $username );
					
			        $buffer .='
						}, 
						"import_feeds": [], 
						"background": {
							"repeat": ' . json_encode ( $background_repeat ) . ',
							"color": ' . json_encode ( $background_color ) . ',
							"image": ' . json_encode ( $background_image ) . ',
							"is_default": ' . $is_default . ',
							"use_image": ' . $use_image . ',
							"position": ' . json_encode ( $background_position ) . '
						},
						"name": ' . json_encode ( $name ) . ',
						"is_beta_tester": false, 
						"website": ' . json_encode ( $site ) . ',
						"total_loved": ' . json_encode ( $total_loved ) . ',
						"total_following": ' . json_encode ( $total_following ) . ',
						"total_followers": ' . json_encode ( $total_followers ) . ',';
				    
				    if( $follower_id == $_SESSION['member']['user_id'] )
				    	
					$buffer .= '"viewer_following": true,';
					
					else $buffer .= '"viewer_following": false,';
					$buffer .= ' 
					"location": ' . json_encode ( $location ) . '
					}
				},';
	$buffer = preg_replace("/[\n\r\t]/", "", $buffer);
	
	return $buffer;
}
function usersong_template ( $artist, $source, $song_id, $song_title, $last_love, $love_count, $viewer_love, $viewer_love_on, $user_love, $user_love_on ){
	global $config, $db;
	
	$VIEWER_LOVE = FALSE;
	
	$buffer =  '{"title": ' . json_encode ( $user_love . " thích " . $song_title . " trình bày bởi " . $artist ) . ', 
					"object": {
						"album": null, 
						"similar_artists": [], 
						"buy_link": null, 
						"listened": null, 
						"artist": ' . json_encode ( $artist ) . ',
						"url": ' . json_encode ( $config['siteurl'] . 'mp3.php?site=' . $source . '&id=' . $song_id . '&rand=' . md5(date("d", time())) ) . ', 
						"image": ' . songlist_images( $artist ) . ', 
						"title": ' . json_encode ( $song_title ) . ', 
						"metadata_state": "inline_complete", 
						"sources": [
						    ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . '
						],';
    
    if( $_SESSION['member']['user_id'] ){
    
    	$row = $db->super_query("SELECT `created_on` FROM vass_song_love WHERE song_id = '" . $song_id . "' AND user_id = '" . $_SESSION['member']['user_id'] . "' LIMIT 0,1");
    	
    	if( $row['created_on'] ){
		
			$buffer .= '"viewer_love": {"username": ' . json_encode ( $_SESSION['member']['username'] ) . ', 
											"comment": "", 
											"context": "", 
											"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
											"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $row['created_on'] ) ) . '", 
											"client_id": "lala_web"
										},';
			
			$VIEWER_LOVE = TRUE;
			
		} else $buffer .= '"viewer_love": null,';
		
    }else $buffer .= '"viewer_love": null,';
	
	if( $VIEWER_LOVE ) $db->query("SELECT vass_users.username, vass_song_love.created_on FROM vass_song_love LEFT JOIN vass_users ON vass_song_love.user_id = vass_users.user_id WHERE song_id = '" . $song_id . "' AND vass_song_love.user_id <> '" . $_SESSION['member']['user_id'] . "' LIMIT 0,10");
	
	else $db->query("SELECT vass_users.username, vass_song_love.created_on FROM vass_song_love LEFT JOIN vass_users ON vass_song_love.user_id = vass_users.user_id WHERE vass_song_love.song_id = '" . $song_id . "' LIMIT 0,10");
	
	while( $row = $db->get_row() ){
		if($row['username'] != $user_love){
			$recent_loves .= '{"username": ' . json_encode ( $row['username'] ) . ', 
									"comment": "", 
									"context": "", 
									"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
									"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $row['created_on'] ) ) . '",
									"client_id": "lala_web"
								},';
		}
	}
	
	$recent_loves = substr( $recent_loves, 0, ( strLen( $recent_loves ) - 1 ) );
	
	$buffer .= '"recent_loves": [' . $recent_loves . '],';
	
    $buffer .= '"user_love": {
								"username": ' . json_encode ( $user_love ) . ', 
								"comment": "",
								"context": "",
								"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ',
								"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $user_love_on ) ) . '",
								"client_id": "lala_web"
							},
							"last_loved": "' . date( 'D M d Y H:i:s O', strtotime(  $last_love ) ) . '",
							"aliases": [],
							"loved_count": ' . $love_count . ',
							"id": ' . json_encode ( $song_id ) . ',
							"tags": [],
							"trending_rank_today": null
							}, 
							"actor": {
								"url": ' . json_encode ( $config['siteurl'] . $user_love ) . ',
								"image": {
								    "url": null
								}, 
								"displayName": ' . json_encode ( $user_love ) . ',
								"id": ' . json_encode ( $user_love ) . ',
								"objectType": "person"
							},
							"verb": "love",
							"published": "' . date( 'D M d Y H:i:s O', strtotime(  $result['created_on'] ) ) . '", 
							"id": ' . json_encode ( md5 ($user_love . $song_id ) ) . '
						},';
	
	$buffer = preg_replace("/[\n\r\t]/", "", $buffer);
	
	return $buffer;
}

function song_template ( $artist, $source, $song_id, $song_title, $last_love, $love_count, $viewer_love, $viewer_love_on, $user_love, $user_love_on ){
	global $config, $db;
	
	$buffer =  '{"album": null, 
					"similar_artists": [], 
					"buy_link": null, 
					"listened": false, 
					"artist": ' . json_encode ( $artist ) . ', 
					"url": ' . json_encode ( $config['siteurl'] . 'mp3.php?site=' . $source . '&id=' . $song_id . '&rand=' . md5(date("d", time())) ) . ', 
					"image": ' . songlist_images( $artist ) . ', 
					"title": ' . json_encode ( $song_title ) . ', 
					"metadata_state": "complete", 
					"sources": [
						' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . '
					],';
	
	if( $viewer_love == $_SESSION['member']['user_id'] && $viewer_love  ){
		
		$buffer .= '"viewer_love": {
										"username": ' . json_encode ( $_SESSION['member']['username'] ) . ', 
										"comment": "", 
										"context": "", 
										"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
										"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $viewer_love_on ) ) . '", 
										"client_id": "lala_web"
									},';
		$VIEW_ID = TRUE;
		
	}else {
		
		$buffer .= '"viewer_love": null,';
		
		$VIEW_ID = FALSE;
		
	}
	
	if( $VIEW_ID ) $db->query("SELECT vass_song_love.created_on  AS loved_on, vass_users.username FROM vass_song_love LEFT JOIN vass_users ON vass_song_love.user_id = vass_users.user_id WHERE song_id = '" . $song_id . "' AND vass_song_love.user_id <> '" . $_SESSION['member']['user_id'] . "' LIMIT 0,10");
	
	else $db->query("SELECT vass_song_love.created_on AS loved_on, vass_users.username FROM vass_song_love LEFT JOIN vass_users ON vass_song_love.user_id = vass_users.user_id WHERE song_id = '" . $song_id . "' LIMIT 0,10");
	
	while( $row = $db->get_row() ){
		if($row['username'] != $user_love){
		
			$recent_loves .=     '{
									"username": ' . json_encode ( $row['username'] ) . ', 
									"comment": "", 
									"context": "", 
									"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
									"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $row['loved_on'] ) ) . '",
									"client_id": "lala_web"
								},';
		}
	}
	
	$recent_loves = substr( $recent_loves, 0, ( strLen( $recent_loves ) - 1 ) );
	
	$buffer .= '"recent_loves": [' . $recent_loves . '],';
	
	if( $user_love ){
		
		$buffer .= '"user_love": {
									"username": ' . json_encode ( $user_love ) . ', 
									"comment": "", 
									"context": "", 
									"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
									"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $user_love_on ) ) . '",
									"client_id": "lala_web"
								},';
	}else {
		
		$buffer .= '"user_love": null,';
		
	}
					            
	$buffer .= '"last_loved": "' . date( 'D M d Y H:i:s O', strtotime(  $last_love ) ) . '",
					"aliases": [], 
					"loved_count": ' . $love_count . ', 
					"id": ' . json_encode ( $song_id ) . ', 
					"tags": [], 
					"trending_rank_today": null
				},';
	
	$buffer = preg_replace("/[\n\r\t]/", "", $buffer);
	
	return $buffer;
}
function trendding_template ( $artist, $source, $song_id, $song_title, $last_love, $love_count, $viewer_love, $viewer_love_on, $user_love, $user_love_on, $trendding ){
	global $config, $db;
	
	$buffer =  '{"album": null, 
					"similar_artists": [], 
					"buy_link": null, 
					"listened": false, 
					"artist": ' . json_encode ( $artist ) . ', 
					"url": ' . json_encode ( $config['siteurl'] . 'mp3.php?site=' . $source . '&id=' . $song_id . '&rand=' .md5(date("d", time())) ) . ', 
					"image": {
						"small": null, 
						"large": null, 
						"medium": null
					}, 
					"title": ' . json_encode ( $song_title ) . ', 
					"metadata_state": "complete", 
					"sources": [
						' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . '
					],';
	
	if( $viewer_love == $_SESSION['member']['user_id'] && $viewer_love  ){
		
		$buffer .= '"viewer_love": {
										"username": ' . json_encode ( $_SESSION['member']['username'] ) . ', 
										"comment": "", 
										"context": "", 
										"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
										"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $viewer_love_on ) ) . '", 
										"client_id": "lala_web"
									},';
		$VIEW_ID = TRUE;
		
	}else {
		
		$buffer .= '"viewer_love": null,';
		
		$VIEW_ID = FALSE;
		
	}
	
	if( $VIEW_ID ) $db->query("SELECT vass_song_love.created_on  AS loved_on, vass_users.username FROM vass_song_love LEFT JOIN vass_users ON vass_song_love.user_id = vass_users.user_id WHERE song_id = '" . $song_id . "' AND vass_song_love.user_id <> '" . $_SESSION['member']['user_id'] . "' LIMIT 0,10");
	
	else $db->query("SELECT vass_song_love.created_on AS loved_on, vass_users.username FROM vass_song_love LEFT JOIN vass_users ON vass_song_love.user_id = vass_users.user_id WHERE song_id = '" . $song_id . "' LIMIT 0,10");
	
	while( $row = $db->get_row() ){
		if($row['username'] != $user_love){
		
			$recent_loves .=     '{
									"username": ' . json_encode ( $row['username'] ) . ', 
									"comment": "", 
									"context": "", 
									"source": ' . json_encode ( $config['siteurl'] . 'song/' . $song_id ) . ', 
									"created_on": "' . date( 'D M d Y H:i:s O', strtotime(  $row['loved_on'] ) ) . '",
									"client_id": "lala_web"
								},';
		}
	}
	
	$recent_loves = substr( $recent_loves, 0, ( strLen( $recent_loves ) - 1 ) );
	
	$buffer .= '"recent_loves": [' . $recent_loves . '],';
	
	$buffer .= '"user_love": null,';
					            
	$buffer .= '"last_loved": "' . date( 'D M d Y H:i:s O', strtotime(  $last_love ) ) . '",
								"aliases": [], 
									"artist_image": {
								    ' . images( $artist ) .'
								}, 
											"loved_count": ' . $love_count . ', 
											"id": ' . json_encode ( $song_id ) . ', 
								    "tags": [], 
								    "trending_rank_today": ' . $trendding . '
        	},';
	
	$buffer = preg_replace("/[\n\r\t]/", "", $buffer);
	
	return $buffer;
}
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}else{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function getRealIpAddress() {
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}		
		else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
}

function hyperlink($string){
	
	$string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
	
	$string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</a>",$string);
	
	$string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$string);
	
	return $string;
	
}

function lala_navigation( $template, $alternative_link, $link, $total, $per_pages, $compile, $attmp3ajax = FALSE, $ajaxlink, $ajaxquery, $ajaxtype) {
		global $tpl, $config; 
		if($attmp3ajax == TRUE)
		{
		if( $total < $per_pages ) return;

		if( isset( $_GET['page'] ) ) $page = intval( $_GET['page'] );
		if( !$page OR $page < 0 ) $page = 1;

		$tpl->load_template( $template );

		if( $page > 1 ) {
			$prev = $page - 1;
				$url = str_replace ("{page}", $prev, $alternative_link );
				$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a onclick=\"".$ajaxquery."('" . $ajaxlink . "&amp;page=".$prev. "','".$ajaxtype."'); return false;\" href=\"" . $url . "\">\\1</a>" );
		
		} else {
			$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a style=\"cursor:pointer\" class=\"gray12 active\">\\1</a>" );
			$no_prev = TRUE;
		}

		if( $per_pages ) {
			
			$enpages_count = @ceil( $total / $per_pages );
			$pages = "";
			
			if( $enpages_count <= 10 ) {
				
				for($j = 1; $j <= $enpages_count; $j ++) {
					
					if( $j != $page  ) {
						
							$url = str_replace ("{page}", $j, $alternative_link );
							$pages .= "<a onclick=\"".$ajaxquery."('" . $ajaxlink . "&amp;page=".$j. "','".$ajaxtype."'); return false;\" href=\"" . $url . "\">$j</a> ";
					
					} else {
						
						$pages .= "<a style=\"cursor:pointer\" class=\"gray12 active\">$j</a> ";
					}
				
				}
			
			} else {
				
				$start = 1;
				$end = 10;
				//$nav_prefix = "<a style=\"cursor:pointer\" >...</a> ";
				
				if( $page  > 0 ) {
					
					if( $page  > 6 ) {
						
						$start = $page  - 4;
						$end = $start + 8;
						
						if( $end >= $enpages_count ) {
							$start = $enpages_count - 9;
							$end = $enpages_count - 1;
							$nav_prefix = "";
						} else $nav_prefix = "";
							//$nav_prefix = "<a style=\"cursor:pointer\" >...</a> ";
					
					}
				
				}
				
				if( $start >= 2 ) {
					
						$url = str_replace ("{page}", "1", $alternative_link );
						$pages .= "<a onclick=\"".$ajaxquery."('" . $ajaxlink . "&amp;page=".$j. "','".$ajaxtype."'); return false;\" href=\"" . $url . "\">1</a>";
				
				}
				
				for($j = $start; $j <= $end; $j ++) {
					
					if( $j != $page ) {
						
							$url = str_replace ("{page}", $j, $alternative_link );
							$pages .= "<a onclick=\"".$ajaxquery."('" . $ajaxlink . "&amp;page=".$j. "','".$ajaxtype."'); return false;\" href=\"" . $url . "\">$j</a> ";
					
					} else {
						
						$pages .= "<a style=\"cursor:pointer\" class=\"gray12 active\">$j</a> ";
					}
				
				}
				
				if( $page != $enpages_count ) {
					
						$url = str_replace ("{page}", $enpages_count, $alternative_link );
						$pages .= $nav_prefix . "<a onclick=\"".$ajaxquery."('" . $ajaxlink . "&amp;page=".$enpages_count. "','".$ajaxtype."'); return false;\" href=\"" . $url . "\">{$enpages_count}</a>";

				} else
					$pages .= "<a style=\"cursor:pointer\" class=\"gray12 active\">{$enpages_count}</a> ";
			
			}
			
			$tpl->set( '{pages}', $pages );
		
		}
		if( $page < $enpages_count ) {


			$next_page = $page + 1;

				$url = str_replace ("{page}", $next_page, $alternative_link );
				$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a onclick=\"".$ajaxquery."('" . $ajaxlink . "&amp;page=".$next_page. "','".$ajaxtype."'); return false;\" href=\"" . $url . "\" class=\"btn_next\">\\1</a>" );

		} else {
			$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a style=\"cursor:pointer\" class=\"btn_next\">\\1</a>" );
			$no_next = TRUE;
		}
		
		if( ! $no_prev or ! $no_next ) {
			$tpl->compile( $compile );
		}
		
		$tpl->clear();
		}else {
		
				if( $total < $per_pages ) return;

		if( isset( $_GET['page'] ) ) $page = intval( $_GET['page'] );
		if( !$page OR $page < 0 ) $page = 1;

		$tpl->load_template( $template );

		if( $page > 1 ) {
			$prev = $page - 1;
				$url = str_replace ("{page}", $prev, $alternative_link );
				$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a  class=\"gray12 bgrep\" href=\"" . $url . "\">\\1</a>" );

		} else {
			$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a style=\"cursor:pointer\" class=\"gray12 active\">\\1</a>" );
			$no_prev = TRUE;
		}

		if( $per_pages ) {
			
			$enpages_count = @ceil( $total / $per_pages );
			$pages = "";
			
			if( $enpages_count <= 10 ) {
				
				for($j = 1; $j <= $enpages_count; $j ++) {
					
					if( $j != $page  ) {
						
							$url = str_replace ("{page}", $j, $alternative_link );
							$pages .= "<a  class=\"gray12 bgrep\" href=\"" . $url . "\">$j</a> ";

				
					} else {
						
						$pages .= "<a style=\"cursor:pointer\" class=\"gray12 active\">$j</a> ";
					}
				
				}
			
			} else {
				
				$start = 1;
				$end = 10;
				//$nav_prefix = "<a style=\"cursor:pointer\" >...</a> ";
				
				if( $page  > 0 ) {
					
					if( $page  > 6 ) {
						
						$start = $page  - 4;
						$end = $start + 8;
						
						if( $end >= $enpages_count ) {
							$start = $enpages_count - 9;
							$end = $enpages_count - 1;
							$nav_prefix = "";
						} else
							$nav_prefix = "";
					
					}
				
				}
				
				if( $start >= 2 ) {
					
						$url = str_replace ("{page}", "1", $alternative_link );
						$pages .= "<a  class=\"gray12 bgrep\" href=\"" . $url . "\">1</a>";
		
				}
				
				for($j = $start; $j <= $end; $j ++) {
					
					if( $j != $page ) {
						
							$url = str_replace ("{page}", $j, $alternative_link );
							$pages .= "<a  class=\"gray12 bgrep\" href=\"" . $url . "\">$j</a> ";
					
					} else {
						
						$pages .= "<a style=\"cursor:pointer\" class=\"gray12 active\">$j</a> ";
					}
				
				}
				
				if( $page != $enpages_count ) {
					
						$url = str_replace ("{page}", $enpages_count, $alternative_link );
						$pages .= $nav_prefix . "";
				
				} else
					//$pages .= "<a style=\"cursor:pointer\" class=\"gray12 active\">{$enpages_count}</a> ";
					$pages .= "";
			
			}
			
			$tpl->set( '{pages}', $pages );
		
		}
		if( $page < $enpages_count ) {


			$next_page = $page + 1;

				$url = str_replace ("{page}", $next_page, $alternative_link );
				$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a  class=\"gray12 bgrep\" href=\"" . $url . "\" class=\"btn_next\">\\1</a>" );
		
		} else {
			$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a style=\"cursor:pointer\" class=\"btn_next\">\\1</a>" );
			$no_next = TRUE;
		}
		
		if( ! $no_prev or ! $no_next ) {
			$tpl->compile( $compile );
		}
		
		$tpl->clear();
		
		}
	}

class Curl {
	var $callback = false;
	var $secure = false;
	var $conn = false;
	var $cookiefile =false;
	var $header = false;
	var $cookie = false;
	var $follow = true;
	

	function Curl($u = false) {
		$this->conn = curl_init();
		if (!$u) {
			$u = rand(0,100000);
		}

		$this->cookiefile= INCLUDE_DIR.'/cache/'.md5($u);
	}

	function setCallback($func_name) {
		$this->callback = $func_name;
	}

	function close() {
		curl_close($this->conn);
		if (is_file($this->cookiefile)) {
			unlink($this->cookiefile);
		}

	}

	function doRequest($method, $url, $vars) {

		$ch = $this->conn;

		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

		curl_setopt($ch, CURLOPT_URL, $url);
		if ($this->header) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
		} else {
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		}
		curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);



		if($this->secure) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		
		if ($this->cookie) 
        {
        	curl_setopt($ch, CURLOPT_COOKIE,$this->cookie);
        }

        if ($this->follow) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        } else {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        }

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);

		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: ')); // lighttpd fix
		}

		$data = curl_exec($ch);



		if ($data) {
			if ($this->callback)
			{
				$callback = $this->callback;
				$this->callback = false;
				return call_user_func($callback, $data);
			} else {
				return $data;
			}
		} else {
			return false;
		}
	}

	function get($url) {
		return $this->doRequest('GET', $url, 'NULL');
	}

	function getError()
	{
		return curl_error($ch);
	}

	function post($url, $params = false) {

		$post_data = array(
                        'login'=>urlencode('donghungx'),
                      'password'=>urlencode('anhyeuem'),
               );

		if (is_array($params)) {

			foreach($params as $var=>$val) {
				if(!empty($post_data))$post_data.='&';
				$post_data.= $var.'='.urlencode($val);
			}

		} else {
			$post_data = $params;
		}

		return $this->doRequest('POST', $url, $post_data);
	}
}

function getPage($url,$post = false,$cookie = false)
{
    $pURL = parse_url($url);    
       
    $curl = new Curl($pURL['host']);
                    
    if (strstr($url,'https://')) 
    {
        $curl->secure = true;	
    }
    
    if ($post) {
    	return $curl->post($url,$post);
    } else {
        return $curl->get($url);
    }
    
}


function hms2sec ($hms) {
     list($m, $s) = explode (":", $hms);
     $seconds = 0;
     $seconds += (intval($m) * 60);
     $seconds += (intval($s));
     return $seconds;
}

function stripUnicode($str){
        if(!$str) return false;
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'd'=>'Đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
        );
        foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
		return $str;
}

function makekeysearch($column, $data){	
	$split_stemmed = explode(" ",$data);
	while(list($key,$val)=each($split_stemmed)){
		if($val<>" " and strlen($val) > 0){
		$sql .= $column." LIKE '%".$val."%' AND ";
		}
	}	
		$sql=substr($sql,0,(strLen($sql)-4));//this will eat the last AND
		$sql .= "";
	return $sql;
}
function removetype($data) 
{	
	$data = html_entity_decode($data);
	$data=totranslit( $data , true, false );
	return $data;
}
function artisthash($data) 
{	
	$hash = makekeyword( $data );
	
	$hash = substr(md5( $hash ), 0, 8);
	
	return $hash;
}
function makekeyword($str = null)
  {
    $str = stripUnicode($str);
    
    if( null === $str ) {
      $str = $this->getTitle();
    }
    if( strlen($str) > 32 ) {
      $str = substr($str, 0, 32) . '...';
    }
    $str = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9-]+/i', '-', $str);
    $str = preg_replace('/-+/', ' ', $str);
    $str = trim($str, '-');
    if( !$str ) {
      $str = '-';
    }
    return $str;
}

function totranslit($var, $lower = true, $punkt = true) {
	$NpjLettersFrom = "àáâăäåçèêë́íîïđṇ̃óôöû³";
	$NpjLettersTo = "abvgdeziklmnoprstufcyi";
	$NpjBiLetters = array ("é" => "j", "¸" => "yo", "æ" => "zh", "ơ" => "x", "÷" => "ch", "ø" => "sh", "ù" => "shh", "ư" => "ye", "₫" => "yu", "ÿ" => "ya", "ú" => "", "ü" => "", "¿" => "yi", "º" => "ye" );
	
	$NpjCaps = "ÀÁÂĂÄÅ¨ÆÇÈÉÊË̀ÍÎÏĐÑ̉ÓÔƠÖ×ØÙÜÚÛỮß¯ª²";
	$NpjSmall = "àáâăäå¸æçèéêë́íîïđṇ̃óôơö÷øùüúûư₫ÿ¿º³";
	
	$var = str_replace( ".php", "", $var );
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/ms", "-", $var );
	$var = strtr( $var, $NpjCaps, $NpjSmall );
	$var = strtr( $var, $NpjLettersFrom, $NpjLettersTo );
	$var = strtr( $var, $NpjBiLetters );
	
	if ( $punkt ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
	else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

	$var = preg_replace( '#[\-]+#i', '-', $var );

	if ( $lower ) $var = strtolower( $var );
	
	if( strlen( $var ) > 50 ) {
		
		$var = substr( $var, 0, 50 );
		
		if( ($temp_max = strrpos( $var, '-' )) ) $var = substr( $var, 0, $temp_max );
	
	}
	
	return $var;
}

function create_keywords($story) {
	global $metatags;
	
	$keyword_count = 20;
	$newarr = array ();
	
	$quotes = array ("\x22", "\x60", "\t", "\n", "\r", ",", ".", "/", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"' );
	$fastquotes = array ("\x22", "\x60", "\t", "\n", "\r", '"', "\\", '\r', '\n', "/", "{", "}", "[", "]" );
	
	$story = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "", $story );
	$story = preg_replace( "'\[attachment=(.*?)\]'si", "", $story );
	$story = preg_replace( "'\[page=(.*?)\](.*?)\[/page\]'si", "", $story );
	$story = str_replace( "{PAGEBREAK}", "", $story );
	
	$story = str_replace( $fastquotes, '', trim( strip_tags( str_replace( '<br />', ' ', stripslashes( $story ) ) ) ) );
	
	$metatags['description'] = substr( $story, 0, 190 );
	
	$story = str_replace( $quotes, '', $story );
	
	$arr = explode( " ", $story );
	
	foreach ( $arr as $word ) {
		if( strlen( $word ) > 4 ) $newarr[] = $word;
	}
	
	$arr = array_count_values( $newarr );
	arsort( $arr );
	
	$arr = array_keys( $arr );
	
	$total = count( $arr );
	
	$offset = 0;
	
	$arr = array_slice( $arr, $offset, $keyword_count );
	
	$metatags['keywords'] = implode( ", ", $arr );
}

function clean_url($url) {
	
	if( $url == '' ) return;
	
	$url = str_replace( "http://", "", strtolower( $url ) );
	if( substr( $url, 0, 4 ) == 'www.' ) $url = substr( $url, 4 );
	$url = explode( '/', $url );
	$url = reset( $url );
	$url = explode( ':', $url );
	$url = reset( $url );
	
	return $url;
}

function convert_unicode($t, $to = 'windows-1251') {
	$to = strtolower( $to );

	if( $to == 'utf-8' ) {
		
		return urldecode( $t );
	
	} else {
		
		if( function_exists( 'iconv' ) ) $t = iconv( "UTF-8", $to . "//IGNORE", $t );
		else $t = "The library iconv is not supported by your server";
	
	}

	return urldecode( $t );
}

function getSlug($str = null)
  {
    if( null === $str ) {
      $str = $this->getTitle();
    }
    if( strlen($str) > 32 ) {
      $str = substr($str, 0, 32) . '...';
    }
    $str = preg_replace('/([a-z])([A-Z])/', '$1 $2', $str);
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9-]+/i', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    $str = trim($str, '-');
    if( !$str ) {
      $str = '-';
    }
    return $str;
  }
  
$domain_cookie = explode (".", clean_url( $_SERVER['HTTP_HOST'] ));
$domain_cookie_count = count($domain_cookie);
$domain_allow_count = -2;

if ( $domain_cookie_count > 2 ) {

	if ( in_array($domain_cookie[$domain_cookie_count-2], array('com', 'net', 'org') )) $domain_allow_count = -3;
	if ( $domain_cookie[$domain_cookie_count-1] == 'ua' ) $domain_allow_count = -3;
	$domain_cookie = array_slice($domain_cookie, $domain_allow_count);
}

$domain_cookie = "." . implode (".", $domain_cookie);

define( 'DOMAIN', $domain_cookie );


?>