<?php
/*
Plugin Name: PTB Classy Comments
Version: 1.6.1
Plugin URI: 
Description: Adapted from Specky Geek's plug-in to add additional fields in the comment form http://smartwebworker.com. Classes added v1.5
Author: @palibaacsi
*/



/*------------------------------
 * Class Autoloading
 *------------------------------*/

/**
 * Autoload class files
 */
function ptb_classy_comments_auto_load( $class ) {
	$class = str_replace( '_', '-', $class);
	$filename = strtolower( $class ) . ".php";
	$file = __DIR__ . '/classes/' . $filename;

	if ( !file_exists( $file ) ) {
		return false;
	}

	require_once( $file );
}

if ( function_exists( 'spl_autoload_register' ) ) {
	spl_autoload_register( 'ptb_classy_comments_auto_load' );
}


/*------------------------------
 * Plugin loading
 *------------------------------*/

function ptb_classy_comments_plugins_loaded() {
	new PTB_Classy_Comments_Ratings();
	new PTB_Classy_Comments_Renaming();
//	new PTB_Classy_Comments_Output();
	new PTB_Classy_Comments_Dev_Menu();
}



/*------------------------------
 * Hook into Wordpress
 *------------------------------*/

add_action( 'plugins_loaded', 'ptb_classy_comments_plugins_loaded' );


/*------------------------------
 * Activation/Deactivation hooks
 *------------------------------*/
// This cron_schedules filter is required at this level in order for the cron
// scheduling on activation and deactivation to work.
// add_filter( 'cron_schedules', array( 'PTB_Classy_Comments_Cron_Mgr', 'add_30_minute_cron_interval' ) );

function ptb_classy_comments_activate() {
//	wp_schedule_event( time(), 'minute_30', 'ptb_classy_comments_execute_cron' );
}

function ptb_classy_comments_deactivate() {
//	wp_clear_scheduled_hook( 'ptb_classy_comments_execute_cron' );
}

register_activation_hook( __FILE__, 'ptb_classy_comments_activate' );
register_deactivation_hook( __FILE__, 'ptb_classy_comments_deactivate' );
