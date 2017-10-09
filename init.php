<?php
/*
Plugin Name: ZF Export Student lists
Description: Plugin to export student lists as CSV from courses 
Author: Zeeland Family
Version: 0.1
*/

class ZF_Reporting_Admin {
	static public function init() {
		require_once( plugin_dir_path( __FILE__ ) . 'reports.php' );
	}

	static public function add_admin_page() {
		add_management_page( 'Reporting', 'Reports', 'manage_options', 'zf-reporting', array( 'ZF_Reporting_Admin', 'report_list' ) );
	}

	static public function report_list() {
		// Enqueue jquery to use form elements like datepicker 
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_style( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		$forms = new ZF_Forms;
		$form_names = get_class_methods( $forms );

		?>
		<div class="wrap">
			<?php
			foreach ( $form_names as $form_name ) :
				$forms->$form_name(); ?>
				<?php if ( $forms->form ) : ?>
			    	<h2><?php echo esc_html( $forms->name ); ?></h2>
			    	<div class="form">
			    		<form action='<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>export.php' method="POST">
			    			<?php echo $forms->form; ?>
			    			<?php wp_nonce_field( 'export', 'verify' ); ?>
			    			<input type='submit' value="Lataa"></input>
			    		</form>
			    	</div>
			    <?php endif; ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

add_action( 'init', array( 'ZF_Reporting_Admin', 'init' ) );
add_action( 'admin_menu', array( 'ZF_Reporting_Admin', 'add_admin_page' ) );
