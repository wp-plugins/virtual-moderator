<?php $homepath = str_replace(stristr( __FILE__, 'wp-content' ),'', __FILE__);
include_once($homepath."wp-load.php"); //Loads wordpress.
$v=$_GET;
$flags=get_post_meta($v['post_id'], '_flags', TRUE);
$post=get_post($v['post_id']);
$pid=$v['post_id'];
$author=$post->post_author;


function vm_notify(){
	global $post, $vms;
	$excerpt=$post->post_excerpt;
	if(!$excerpt){
	$words=explode(' ', wp_get_single_post($post->ID)->post_content);
	if (count($words)>50){array_splice($words, 50);
	$excerpt=implode(" ", $words);
	$excerpt.="...";}else{$excerpt=implode(" ", $words);};};
	
	if($vms['emailAdmin']){
		$to=get_option('admin_email');
		$subject="vModerator: a post is flagged by visitors";
		$headers='From: "Virtual Moderator" <vmoderator@'.str_replace(array('http://','https://', 'www.'), '', get_option('siteurl')).'>';
		$url=get_option('siteurl').'/wp-admin/post.php?action=edit&post='.$post->ID;
		$message=str_replace(array('{title}', '{excerpt}', '{id}', '{url}'), array($post->post_title, $excerpt, $post->ID, $url), $vms['emailAdminText']);
		wp_mail( $to, $subject, $message, $headers );
	};
	
	if($vms['emailAuthor']){
		$to=get_userdata($post->post_author)->user_email;
		$subject="Your post at ".get_bloginfo( 'name' )." is flagged by visitors";
		$headers='From: "'.get_bloginfo( 'name' ).'" <vmoderator@'.str_replace(array('http://','https://', 'www.'), '', get_option('siteurl')).'>';
		$url=get_option('siteurl').'/wp-admin/post.php?action=edit&post='.$post->ID;
		$message=str_replace(array('{title}', '{excerpt}', '{id}', '{url}'), array($post->post_title, $excerpt, $post->ID, $url), $vms['emailAuthorText']);
		wp_mail( $to, $subject, $message, $headers );
	};
}
if(get_post_type($v['post_id'])!='post')$die=TRUE;
if($vms['flags2removePost']==0)$die=TRUE;
if($culevel<$vms['canFlag'])$die=TRUE;
if($user!=-1&&$v['key']!=crc32(wp_get_current_user()->data->user_pass))$die=TRUE;
if($v['action']=='flag'){
if($vms['noFlagAdmin']&&get_userdata($author)->user_level>7)$die=TRUE;
if($vms['noFlagEditor']&&get_userdata($author)->user_level>2&&get_userdata($author)->user_level<8)$die=TRUE;
if(in_array($author, explode(',', str_replace(" ", '', $vms['safeUsers']))))$die=TRUE;
if(in_category(explode(",", str_replace(" ", "", $vms['excCats'])), $v['post_id']))$die=TRUE;
if(get_post_meta($post->ID,'_flags')==0)$die=FALSE;
if($vms['traceCookie']&&chk_duplicate($post->ID, 'id', $_COOKIE['flagged-post-'.$v['post_id']]))$die=TRUE;
if($vms['traceIP']&&chk_duplicate($v['post_id'], 'ip', $ip))$die=TRUE;
if($user!=0&&$vms['traceUser']&&chk_duplicate($v['post_id'], 'userID', $user))$die=TRUE;
if(!$die){
	$flags+=1;
	update_post_meta($v['post_id'], '_flags', $flags);
	if($vms['flags2removePost']<=$flags){//Do the action preferd.
		switch ($vms['result']){
			case 'draft':
			wp_update_post(array('ID' => $v['post_id'], 'post_status'=>'draft'));
			break;

			case 'pending':
			wp_update_post(array('ID' => $v['post_id'], 'post_status'=>'pending'));
			break;

			case 'moderated':
			wp_update_post(array('ID' => $v['post_id'], 'post_status'=>'moderated'));
			break;

			case 'trash':
			wp_update_post(array('ID' => $v['post_id'], 'post_status'=>'trash'));
			break;

			case 'remove':
			wp_delete_post( $v['post_id'], TRUE );
			break;
		};
		vm_notify();
	};
$wpdb->query( $wpdb->prepare( 
	"
		INSERT INTO ".$wpdb->prefix."vmdata
		( post_id, user_id, ip )
		VALUES ( %d, %s, %s )
	", 
        $pid, 
	$user, 
	$ip 
) );
$id=$wpdb->get_var("SELECT id FROM ".$wpdb->prefix."vmdata WHERE post_id = '".$pid."' AND user_id = '".$user."' AND  ip = '".$ip."'");
setcookie("flagged-post-".$v['post_id'], $id, time()+3600*24*365, '/');
echo flagged();
}else{flag_arya();};
}elseif($v['action']=='unflag'){
	if(!chk_duplicate($post->ID, 'id', $_COOKIE['flagged-post-'.$v['post_id']]))$die=TRUE;
	if(!$die){
		$flags-=1;
		update_post_meta($v['post_id'], '_flags', $flags);
		setcookie("flaged-post-".$v['post_id'], FALSE, time()-3601);
		echo flag();

if($user!=0){
$wpdb->query( $wpdb->prepare( 
	"DELETE FROM ".$wpdb->prefix."vmdata
	WHERE post_id='".$pid."' AND user_id='".$user."'") );
}else{$wpdb->query( $wpdb->prepare( 
	"
		DELETE FROM ".$wpdb->prefix."vmdata
		WHERE post_id='".$pid."' AND ip='".$ip."'", 
        $pid, 
	$user, 
	$ip 
) );

};};};
