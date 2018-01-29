<?php

//	function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
/*		$post_id = get_the_ID();
		$is_not_builder_mode = ! is_singular() || ! User::is_current_user_can_edit( $post_id ) || 'builder' !== Plugin::$instance->db->get_edit_mode( $post_id );
*//*		if ( !current_user_can('administrator') ) {
			return;
		}
		$wp_admin_bar->add_node( [
			'id' => 'elementor_library',
			'title' => __( 'Templates', 'eletheme' ),
			'href' => '/wp-admin/edit.php?post_type=elementor_library',
		] );
	}
	add_action( 'admin_bar_menu', 'add_menu_in_admin_bar', 300 );*/