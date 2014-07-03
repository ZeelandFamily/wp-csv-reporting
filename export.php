<?php
/*
 * This script is to be ONLY called directly
 */

// Include Wordpress
define( 'WP_USE_THEMES', false );
// Per installation instructions, adjust this path if you have WordPress in a custom location. This works for default
require_once( '../../../wp-blog-header.php' );

// Only allowed a logged in and admin user see the report
global $current_user;
get_currentuserinfo();
if ( empty( $current_user ) ) {
	die( 'You need to be logged in to see this report' );
}

// Make sure the user has sufficient privileges
if ( ! user_can( $current_user, 'publish_posts' ) ) {
	die( 'Your user account does not have sufficient privileges to view this report' );
}

if ( ! class_exists( 'AC_Reports' ) ) {
	die( 'Reporting plugin not activate' );
}

// Grab Report
$report_lib = new AC_Reports();
$report = sanitize_text_field( $_GET['report'] );
if ( ! method_exists( $report_lib, $report ) ) {
	die( 'Invalid report requested' );
}
$records = $report_lib->$report();

$lines = array();

// Set first row of CSV to have column headers from first row of results
foreach ( array_keys( (array) $records[0] ) as $field_name ) {
	$line[] = '"' . $field_name . '"';
}
$lines[] = implode( ',', $line );

// Populate Each Line for CSV
foreach ( $records as $record ) {
	foreach ( $record as &$field ) {
		$field = '"' . $field . '"';
	}
	$lines[] = implode( ',', (array) $record );
};

// Serve CSV headers
header( 'Content-type: text/csv' );
header( 'Content-Disposition: attachment; filename=' . $report . '.csv' );
header( 'Pragma: no-cache' );
header( 'Expires: 0' );

foreach ( $lines as $line ) {
	echo $line . "\r\n";
}