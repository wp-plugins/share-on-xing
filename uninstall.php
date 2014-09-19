<?php
/**
 * Remove data written by the plugin for WordPress when the plugin is deleted.
 *
 * @since 1.0
 *
 */

// only execute as part of an uninstall script
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option( 'xing_share' );
?>
