<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WPCBN_LITE' ) ? WPCBN_LITE : WPCBN_FILE, 'wpcbn_activate' );
register_deactivation_hook( defined( 'WPCBN_LITE' ) ? WPCBN_LITE : WPCBN_FILE, 'wpcbn_deactivate' );
add_action( 'admin_init', 'wpcbn_check_version' );

function wpcbn_check_version() {
	if ( ! empty( get_option( 'wpcbn_version' ) ) && ( get_option( 'wpcbn_version' ) < WPCBN_VERSION ) ) {
		wpc_log( 'wpcbn', 'upgraded' );
		update_option( 'wpcbn_version', WPCBN_VERSION, false );
	}
}

function wpcbn_activate() {
	wpc_log( 'wpcbn', 'installed' );
	update_option( 'wpcbn_version', WPCBN_VERSION, false );
}

function wpcbn_deactivate() {
	wpc_log( 'wpcbn', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}