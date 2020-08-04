<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Mwb_Woocommerce_Checkout_Field_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       MWB Woocommerce Checkout Field Editor
 * Plugin URI:        https://makewebbetter.com/product/mwb-woocommerce-checkout-field-editor/
 * Description:       This plugin helps to customize woocommerce default checkout fields.
 * Version:           1.0.4
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/mwb-woocommerce-checkout-field-editor
 * Text Domain:       mwb-woocommerce-checkout-field-editor
 * Domain Path:       /languages
 *
 * Requires at least:        4.6
 * Tested up to: 	         5.0.0
 * WC requires at least:     3.2
 * WC tested up to:          3.3.4
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// To Activate plugin only when WooCommerce is active.
$activated = true;
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

	$activated = false;
}

if ( $activated ) {

	// Define plugin constants.
	function define_mwb_woocommerce_checkout_field_editor_constants() {

		mwb_woocommerce_checkout_field_editor_constants( 'MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_VERSION', '1.0.1' );
		mwb_woocommerce_checkout_field_editor_constants( 'MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH', plugin_dir_path( __FILE__ ) );
		mwb_woocommerce_checkout_field_editor_constants( 'MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL', plugin_dir_url( __FILE__ ) );
	}

	// Callable function for defining plugin constants.
	function mwb_woocommerce_checkout_field_editor_constants( $key, $value ) {

		if( ! defined( $key ) ) {
			
			define( $key, $value );
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-mwb-woocommerce-checkout-field-editor-activator.php
	 */
	function activate_mwb_woocommerce_checkout_field_editor() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-checkout-field-editor-activator.php';
		Mwb_Woocommerce_Checkout_Field_Editor_Activator::activate();

		// Create transient data.
    	set_transient( 'mwb_woocommerce_checkout_field_editor_transient_user_exp_notice', true, 5 );
	}

	// Add admin notice only on plugin activation.
	add_action( 'admin_notices', 'mwb_woocommerce_checkout_field_editor_user_exp_notice' );	

	// Facebook setup notice on plugin activation.
	function mwb_woocommerce_checkout_field_editor_user_exp_notice() {

		/**
		 * Check transient.
		 * If transient available display notice.
		 */
		if( get_transient( 'mwb_woocommerce_checkout_field_editor_transient_user_exp_notice' ) ):
			update_option('mwb_wbl_actived_plugin','1');
			?>
			<div class="notice notice-info is-dismissible">
				<p><strong><?php _e( 'Welcome to MWB Woocommerce Checkout Field Editor', 'mwb-woocommerce-checkout-field-editor' ); ?></strong><?php _e( ' – < Here try to explain the Next Process after plugin activation. >', 'mwb-woocommerce-checkout-field-editor' ); ?></p>
				<p class="submit"><a href="<?php echo admin_url( 'admin.php?page=mwb_woocommerce_checkout_field_editor_menu' ); ?>" class="button-primary"><?php _e( '< Next Process Link >', 'mwb-woocommerce-checkout-field-editor' ); ?></a></p>
			</div>

			<?php

			delete_transient( 'mwb_woocommerce_checkout_field_editor_transient_user_exp_notice' );

		endif;
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-mwb-woocommerce-checkout-field-editor-deactivator.php
	 */
	function deactivate_mwb_woocommerce_checkout_field_editor() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-checkout-field-editor-deactivator.php';
		Mwb_Woocommerce_Checkout_Field_Editor_Deactivator::deactivate();
		delete_option('mwb_wbl_actived_plugin');
	}

	register_activation_hook( __FILE__, 'activate_mwb_woocommerce_checkout_field_editor' );
	register_deactivation_hook( __FILE__, 'deactivate_mwb_woocommerce_checkout_field_editor' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-checkout-field-editor.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_mwb_woocommerce_checkout_field_editor() {

		define_mwb_woocommerce_checkout_field_editor_constants();

		$plugin = new Mwb_Woocommerce_Checkout_Field_Editor();
		$plugin->run();

	}
	run_mwb_woocommerce_checkout_field_editor();

	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'mwb_woocommerce_checkout_field_editor_settings_link' );

	// Settings link.
	function mwb_woocommerce_checkout_field_editor_settings_link( $links ) {

	    $my_link = array(
	    		'<a href="' . admin_url( 'admin.php?page=mwb_woocommerce_checkout_field_editor_menu' ) . '">' . __( 'Settings', 'mwb-woocommerce-checkout-field-editor' ) . '</a>',
	    	);
	    return array_merge( $my_link, $links );
	}
}

else {

	// WooCommerce is not active so deactivate this plugin.
	add_action( 'admin_init', 'mwb_woocommerce_checkout_field_editor_activation_failure' );

	// Deactivate this plugin.
	function mwb_woocommerce_checkout_field_editor_activation_failure() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	add_action( 'admin_notices', 'mwb_woocommerce_checkout_field_editor_activation_failure_admin_notice' );

	// This function is used to display admin error notice when WooCommerce is not active.
	function mwb_woocommerce_checkout_field_editor_activation_failure_admin_notice() {

		// to hide Plugin activated notice.
		unset( $_GET['activate'] );

	    ?>

	    <div class="notice notice-error is-dismissible">
	        <p><?php _e( 'WooCommerce is not activated, Please activate WooCommerce first to activate MWB Woocommerce Checkout Field Editor.','mwb-woocommerce-checkout-field-editor' ); ?></p>
	    </div>

	    <?php
	}
}




