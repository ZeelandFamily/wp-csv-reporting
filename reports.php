<?php
class AC_Reports {
	/**
	 * Example Report showing list of users
	 */
	function user_list() {
		global $wpdb;

		$query = "
SELECT
    u.ID AS `WP User ID`,
    u.user_login AS `User Login`,
    umf.meta_value AS `First Name`,
    uml.meta_value AS `Last Name`,
    u.user_email AS `Email Address`
FROM {$wpdb->prefix}users u
INNER JOIN {$wpdb->prefix}usermeta umf ON umf.user_id = u.ID
INNER JOIN {$wpdb->prefix}usermeta uml ON uml.user_id = u.ID
WHERE umf.meta_key = 'first_name'
AND uml.meta_key = 'last_name'";
		return $wpdb->get_results( $query );
	}
}