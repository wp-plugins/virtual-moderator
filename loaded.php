<?php
	global $culevel, $user, $alevel, $vms;
	$user=wp_get_current_user()->ID;
	$alevel=the_author_meta('user_level');
    if (is_user_logged_in()) {
		$culevel = wp_get_current_user()->data->wp_user_level;
		$options = array(
		'label'       => _x( 'Moderated', 'post' ),
		'public'      => false,
		'_builtin'    => true, /* internal use only. */
		'label_count' => _n_noop( 'Moderated <span class="count">(%s)</span>', 'Moderated <span class="count">(%s)</span>' ),
		'protected' => true,
		'private' => true,
		'show_in_admin_all' => true,
		'publicly_queryable' => false,
		'show_in_admin_status_list' => true,
		'show_in_admin_all_list' => true
	);
	register_post_status( 'moderated', $options );
	}else{$culevel=-1;};
	