<?php
	global $culevel, $user, $alevel, $vms;
	$user=wp_get_current_user()->ID;
	$alevel=the_author_meta('user_level');
    if (is_user_logged_in()) {
		$culevel = wp_get_current_user()->data->user_level;}else{$culevel=-1;};
	
