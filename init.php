<?php
/*
Plugin Name: CSV Reporting
Plugin URI: https://github.com/andrej1c/wp-csv-reporting
Description: This plugin allows developers to quickly create custom reports that export to Excel-friendly CSV by passing queries to $wpdb->get_results()
Author: Andrej Ciho
Version: 0.1
*/

class AC_Reporting_Admin {
	static public function init() {
		require_once( plugin_dir_path( __FILE__ ) . 'reports.php' );
	}

	static public function add_admin_page() {
		add_management_page( 'Reporting', 'Reports', 'manage_options', 'ac-reporting', array( 'AC_Reporting_Admin', 'report_list' ) );
	}
	
	static public function report_list() {
		?>
<h2>Reports</h2>
<p><a target="_blank" href="<?php echo plugin_dir_url( __FILE__ ); ?>export.php?report=user_list">List of Users</a></p>
		<?php
	}
}

add_action( 'init', array( 'AC_Reporting_Admin', 'init' ) );
add_action( 'admin_menu', array( 'AC_Reporting_Admin', 'add_admin_page' ) );
