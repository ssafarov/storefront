<?php

// Likes
function likeThis($post_id,$action = 'get') 
{
	if( ! is_numeric($post_id)) {
		error_log("Error: Value submitted for post_id was not numeric");
		return;
	}

	switch($action) 
	{
		case 'get':
			$data = get_post_meta($post_id, '_likes');
		
			if( ! is_numeric($data[0])) 
			{
				$data[0] = 0;
				add_post_meta($post_id, '_likes', '0', true);
			}
			return $data[0];
		break;
	
		case 'update':
			if(isset($_COOKIE["like_" . $post_id])) 
			{
				return;
			}
		
			$currentValue = get_post_meta($post_id, '_likes');
		
			if( ! is_numeric($currentValue[0])) 
			{
				$currentValue[0] = 0;
				add_post_meta($post_id, '_likes', '1', true);
			}
			
			$currentValue[0]++;
			update_post_meta($post_id, '_likes', $currentValue[0]);
		
			setcookie("like_" . $post_id, $post_id,time()+(60*60*24*365));
		break;
	}

}

function printLikes($post_id) 
{
	$likes = likeThis($post_id);
	
	$who = '';
	
	if(isset($_COOKIE["like_" . $post_id])) 
	{
		print '<a href="#" class="likeThis done" id="like-'.$post_id.'"><i class="icon-heart"></i> '.$likes.'</a>';
		return;
	}

	print '<a href="#" class="likeThis" id="like-'.$post_id.'"><i class="icon-heart"></i> '.$likes.'</a>';
}

function setUpPostLikes($post_id) 
{
	if(!is_numeric($post_id)) 
	{
		error_log("Error: Value submitted for post_id was not numeric");
		return;
	}
	add_post_meta($post_id, '_likes', '0', true);

}

function checkHeaders() 
{
	if(isset($_POST["likepost"])) 
	{
		likeThis($_POST["likepost"],'update');
	}

}

add_action ('publish_post', 'setUpPostLikes');
add_action ('init', 'checkHeaders');