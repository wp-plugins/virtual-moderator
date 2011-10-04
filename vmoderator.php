<?php
/*
Plugin Name: Virtual Moderator
Plugin URI: http://eadnan.com/vmoderator
Description: A plugin for open community wordpress blogs. Moderators can now take rest and let visitors choose what they want to see. Dedicated to techtunes.
Version: 1.2.2
Author: Mohaimenul Haque Adnan
Author URI: http://eadnan.com
License: GPL2
*/
/*                       Functions                   */	

$ip=$_SERVER['REMOTE_ADDR'];
$vmpath=WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
global $vmpath;	
function vm_register(){//Action to be done on plugin activation
include(dirname(__FILE__)."/css.php");
$vmpath=WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
$defaults = array( //Default Virtual Moderator settings. Can be changed on vModerator settings.
					'flags2removePost' => '10',
					'result' => 'moderated',
					'emailAdmin' => FALSE,
					'emailAuthor' => FALSE,
					'emailAdminText' => '', //{To write}
					'emailAuthorText' => '', //{To write}
					'postFlagIcon' => $vmpath.'images/flag.gif', //{To write}
					'displayText' => '{flagged}/{total}, {to go} more to hide',
					'waitIcon' => $vmpath.'images/wait.gif', //{To write}
					'waitText' => 'Please wait...',
					'postUnflagIcon' => $vmpath.'images/waving-flag.gif', //{To write}
					'unflagText' => 'Thank you!',
					'addContentTop' => FALSE,
					'addContentBottom' => TRUE,
					'addExcerptTop' => FALSE,
					'addExcerptBottom' => TRUE,
					'traceUser' => TRUE,
					'traceIP' => FALSE,
					'traceCookie' => TRUE,
					'noFlagAdmin' => FALSE,
					'noFlagEditor' => FALSE,
					'excCats' => '',
					'canFlag' => 0,
					'canUnflag' => 7,
					'editDieText' => "Sorry, The post has been flagged by visitor as spam. Unable to publish.",
					'safeUsers' => '',
					'css' => default_css()
					);
	add_option( 'vms', $defaults);
	


   global $wpdb;

   $table_name = $wpdb->prefix . "vmdata";
$sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  post_id mediumint(9) NOT NULL,
	  user_id mediumint(9) NOT NULL,
	  ip tinytext NOT NULL,
	  UNIQUE KEY id (id)
	);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql); }
function vm_head(){global $vmpath, $vms;
    include(dirname(__FILE__)."/css.php");
	style();
	include(dirname(__FILE__)."/js.php");
	};
function content_filter($content){
	global $vms;
	if(is_single()){
	if($vms['addContentTop']){
		$content=vmoderator(array('flag-arya-content-top', 'flag-arya-top')).$content;
	}
	global $vms;
	if($vms['addContentBottom']){
		$content=$content.vmoderator(array('flag-arya-content-bottom', 'flag-arya-bottom'));
	};};
	return $content;};
	
function excerpt_filter($content){
	global $vms;
	if($vms['addExcerptTop']){
		$content=vmoderator(array('flag-arya-excerpt-top', 'flag-arya-top')).$content;
	}
	global $vms;
	if($vms['addExcerptBottom']){
		$content=$content.vmoderator(array('flag-arya-excerpt-bottom', 'flag-arya-bottom'));
	}
	return $content;
	};
	
	
function chk_duplicate($pid, $type, $data){
	global $wpdb;
	if($type=="userID"){
			if($wpdb->get_var("SELECT id FROM ".$wpdb->prefix."vmdata WHERE user_id ='".$data."' AND post_id='".$pid."'")){return TRUE;} else {return FALSE;};
	}
	elseif($type=="ip"){
			if($wpdb->get_var("SELECT id FROM ".$wpdb->prefix."vmdata WHERE ip='".$data."' AND post_id='".$pid."'")){return TRUE;} else {return FALSE;};
			}
	elseif($type=="id"){
			if($wpdb->get_var("SELECT ip FROM ".$wpdb->prefix."vmdata WHERE id='".$data."' AND post_id='".$pid."'")){return TRUE;} else {return FALSE;};};};

function vms() {// Adding the settings page
global $vmpath;
	add_menu_page( 'vModerator Flag Settings', 'vModerator', 'administrator', 'vm_settings', 'vms_flag', $vmpath."images/vmicon.gif" );
	add_submenu_page( 'vm_settings', 'vModerator Flag Settings', 'Settings', 'administrator', 'vm_settings', 'vms_flag' );
	add_submenu_page( 'vm_settings', 'vModerator Moderation Settings', 'Moderation', 'administrator', 'vm_moderation', 'vms_moderation' );
	add_submenu_page( 'vm_settings', 'Virtual Moderator Documantation', 'Documentation', 'administrator', 'vm_documentation', 'vm_documentation' );
	add_submenu_page( 'vm_settings', 'Virtual Moderator Uninstallation', 'Uninstall', 'administrator', 'vm_uninstall', 'vm_uninstall' );	
	};
	
function vms_flag() {//Locate setting page
	$display='flag';
	include(dirname(__FILE__)."/vms.php");};
	
function vms_moderation() {//Locate setting page
	$display='moderation';
	include(dirname(__FILE__)."/vms.php");};

function vm_documentation() {//Locate setting page
	$display='documentation';
	include(dirname(__FILE__)."/vms.php");};

function vm_uninstall() {//Locate setting page
	$display='uninstallation';
	include(dirname(__FILE__)."/vms.php");};

function anti_publish(){//Prevent republishing of a spam post
	global $post, $vms;
	if(wp_get_current_user()->data->user_level < $vms['canUnflag']&&$post->post_status=='moderated'){
		wp_die($vms['editDieText'], 'Failed', array('back_link'=>1));
	};
};

function on_publish($postid){
	global $wpdb, $culevel, $vms;
	if($culevel>=$vms['canUnflag']){
	delete_post_meta($postid, '_flags');
		$wpdb->query( $wpdb->prepare( 
	"DELETE FROM ".$wpdb->prefix."vmdata
	WHERE post_id='".$postid."'") );
	};
};

function post_wp_loaded(){//Tasks list after wp is loaded
	global $vmpath;
	include(dirname(__FILE__)."/loaded.php");
};

function flag(){ global $post, $vms;//Displays the flag option

$flags=get_post_meta($post->ID, '_flags', TRUE);
if(!$flags)$flags=0;
	$key=crc32(wp_get_current_user()->data->user_pass);
	$content="<button class='flag flag-".$post->ID."' id='flag-".$post->ID."' onclick='loadXMLDoc(".$post->ID.", ".$key.", \"flag\")'><span class='flag-button-text' >Flag</span></button>";
		if($vms['displayText']!=''){
		$content.='
		<div class="displayText flagText">
		<div class="flagText-left"></div>
		<div class="flagText-text">'.str_replace(array('{flagged}', '{to go}', '{total}'), array($flags, $vms['flags2removePost']-$flags, $vms['flags2removePost']),  $vms['displayText']).'</div>
		<div class="flagText-right"></div>
		</div>';};
		return $content;
};

function flagged(){ global $post, $vms;//Displays the unflag option
$flags=get_post_meta($post->ID, '_flags', TRUE);
	$key=crc32(wp_get_current_user()->data->user_pass);
	$content="<button class='unflag unflag-".$post->ID."' id='unflag-".$post->ID."' onclick='loadXMLDoc(".$post->ID.", ".$key.", \"unflag\")'><span class='unflag-button-text' >Unflag</span></button>";
	if($vms['unflagText']!=''){
		$content.= '
		<div class="unflagText flagText">
		<div class="unflagText-left"></div>
		<div class="unflagText-text">'.str_replace(array('{flagged}', '{to go}', '{total}'), array($flags, $vms['flags2removePost']-$flags, $vms['flags2removePost']),  $vms['unflagText']).'</div>
		<div class="unflagText-right"></div>
		</div>';
		};
		return $content;
};

function flag_arya(){ global $post, $vms, $user;//Figures out what to show in flag arya.
			if(($vms['traceCookie']&&chk_duplicate($post->ID, 'id', $_COOKIE['flagged-post-'.$post->ID]))||($vms['traceIP']&&chk_duplicate($post->ID, 'ip', $ip))||($user!=0&&$vms['traceUser']&&chk_duplicate($post->ID, 'userID', $user))){return flagged();}else{return flag();};		
};

function vmoderator($classes=NULL) {//The function to display flag arya.
	global $vms, $user, $post, $culevel, $ip;
	if(is_array($classes)){
		$classes=implode(' ', $classes);
	};
	$alevel=get_userdata($post->post_author)->user_level;
	if(in_category(explode(",", str_replace(' ', '', $vms['excCats']))))$die=TRUE;
	if(in_array($post->post_author,  explode(',' , str_replace(' ', '', $vms['safeUsers']))))$die=TRUE;
	if($vms['noFlagAdmin']&&$alevel>7)$die=TRUE;
	if($vms['noFlagEditor']&&$alevel>2&&$alevel<8)$die=TRUE;
if(get_post_meta($post->ID,'_flags')==0)$die=FALSE;
	if(get_post_type()!='post')$die=TRUE;
	if(!$vms['flags2removePost']>0)$die=TRUE;
	if($vms['canFlag']>$culevel)$die=TRUE;
	if(!$die){return "<div id='flag-arya-".$post->ID."' class='".$classes." flag-arya flag-arya-".$post->ID."'>".flag_arya()."</div>";
	}
}//End function vmoderator

function post_classes($classes){//Sets the post class considering flags
	global $post;
	$flags=get_post_meta($post->ID, '_flags', true);
	if($flags=='')$flags="no-flag";
	elseif($flags==1)$flags="1-flag";
	else $flags=$flags."-flags";
	$classes[]=$flags;
	return $classes;
};

function image_uploader() {global $vmpath;
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('image-upload', $vmpath.'/upload.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('image-upload');
}

function image_uploader_styles() {
wp_enqueue_style('thickbox');
}
 
function vm_add_post_column($columns) {
	$columns['vm_flag'] = 'Flags';
	return $columns;
}

function vm_render_post_column($column_name, $id) {
$flags=get_post_meta( $id, '_flags', TRUE);
if($flags=='')$flags="0";
echo $flags;
	};
		
/*                      End Functions                   */	

				

/*                       Actions                        */		
			
register_activation_hook( __FILE__, 'vm_register');

add_action('admin_menu', 'vms');

add_action('publish_post', 'on_publish');

add_action('pre_post_update', 'anti_publish');

add_action('wp_loaded', 'post_wp_loaded');

add_action('wp_head', 'vm_head');

if (isset($_GET['page']) && $_GET['page'] == 'vm_settings') {
add_action('admin_print_scripts', 'image_uploader');
add_action('admin_print_styles', 'image_uploader_styles');
};


add_filter('manage_post_posts_columns', 'vm_add_post_column');

add_action('manage_posts_custom_column', 'vm_render_post_column', 10, 2);

/*                       End Actions                   */
/*                       Filters                       */
add_filter( 'the_excerpt', 'excerpt_filter' );

add_filter( 'the_content', 'content_filter' );

add_filter('post_class','post_classes');

/*                       End Filters                   */

$vms=get_option('vms');
global $vms;
