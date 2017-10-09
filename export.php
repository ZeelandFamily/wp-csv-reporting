<?php
/*
 * This script is to be ONLY called directly
 */

// Include Wordpress
define( 'WP_USE_THEMES', false );

// Per installation instructions, adjust this path if you have WordPress in a custom location. This works for default
require_once( '../../../wp-blog-header.php' );

if ( ! isset( $_POST['verify'] ) || ! wp_verify_nonce( $_POST['verify'], 'export' ) ) {
	die( 'Form input not verified' );
}

// Only allowed a logged in and admin user see the report
$current_user = wp_get_current_user();

if ( empty( $current_user ) ) {
	die( 'You need to be logged in to see this report' );
}

// Make sure the user has sufficient privileges
if ( ! user_can( $current_user, 'publish_posts' ) ) {
	die( 'Your user account does not have sufficient privileges to view this report' );
}

if ( ! class_exists( 'ZF_Reports' ) ) {
	die( 'Reporting plugin not activate' );
}

// Grab Report
$report_lib = new ZF_Reports();
$report = sanitize_text_field( $_POST['report'] );
if ( ! method_exists( $report_lib, $report ) ) {
	die( 'Invalid report requested' );
}
$records = $report_lib->$report();

$lines = array();
if ( empty( $records ) ) {
	die( 'Nothing found!' );
}
// Set first row of CSV to have column headers from first row of results 

foreach ( array_keys( (array) $records['data'][0] ) as $field_name ) {
	$line[] = '"' . $field_name . '"';
}
$lines[] = implode( ',', $line );

// Populate Each Line for CSV
foreach ( $records['data'] as $record ) {
	foreach ( $record as &$field ) {
		$field = '"' . $field . '"';
	}
	$lines[] = implode( ',', (array) $record );
};

// Serve CSV headers
header( 'Content-Encoding: UTF-8' );
header( 'Content-type: text/csv; charset=UTF-8' );
header( 'Content-Disposition: attachment; filename=' . $records['filename'] . '.csv' );
header( 'Pragma: no-cache' );
header( 'Expires: 0' );

foreach ( $lines as $line ) {
	echo $line . "\r\n";
}
