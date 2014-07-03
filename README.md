WordPress Custom Reporting to CSV
=============

*Creating Reports*

Once the plugin is active you can add your reports to the reports.php file. Simply add a method to the AC_Reports class, for example

```
function all_wp_options() {
	global $wpdb;
	return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options" );
}
```

Then add a link to your report on the reports page by adding another link in the report_list method of AC_Reporting_Admin in init.php. Make sure the ?report variable matches the name of the method containing the report. Continuing with our example this would be:

```
<p><a target="_blank" href="<?php echo plugin_dir_url( __FILE__ ); ?>export.php?report=all_wp_options">All WordPress Options</a></p>
```

*NOTE:* If your WordPress installation uses a custom path to the wp-content folder (a.k.a you defined your own WP_CONTENT_DIR in wp-config.php) you will need to modify the export.php file at the very top where it includes wp-blog-header.php.
