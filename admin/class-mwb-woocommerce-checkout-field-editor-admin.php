<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Woocommerce_Checkout_Field_Editor
 * @subpackage Mwb_Woocommerce_Checkout_Field_Editor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Woocommerce_Checkout_Field_Editor
 * @subpackage Mwb_Woocommerce_Checkout_Field_Editor/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Woocommerce_Checkout_Field_Editor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	public $all_billing_fields;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		include_once WP_CONTENT_DIR.'/plugins/woocommerce/includes/class-wc-countries.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		// Enqueue styles only on this plugin's menu page.
		if( $hook != 'toplevel_page_mwb_woocommerce_checkout_field_editor_menu' ){

			return;
		}

		wp_enqueue_style( $this->plugin_name, MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL . 'admin/css/mwb-woocommerce-checkout-field-editor-admin.css', array(), '2.1', 'all' );

		// Enqueue style for using WooCommerce Tooltip.
		wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style('select2');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		// Enqueue scripts only on this plugin's menu page.
		if( $hook != 'toplevel_page_mwb_woocommerce_checkout_field_editor_menu' ){

			return;
		}
		if(!isset($_GET['tab'])){
			$_GET['tab'] = 'general';
		}
		if( !in_array('mwb-woocommerce-chekout-field-editor-pro/mwb-woocommerce-checkout-field-editor-pro.php',get_option('active_plugins',false))){
			if((isset($_GET['page']) && isset($_GET['tab'])) && ($_GET['page'] == 'mwb_woocommerce_checkout_field_editor_menu' && ($_GET['tab'] == 'general' || $_GET['tab'] == 'checkout-license') )){
		

				wp_enqueue_script( 'mwb-woo-cfe-billing-admin-js', MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL . 'admin/js/mwb-woocommerce-checkout-field-editor-admin.js', array( 'jquery','select2','jquery-tiptip','jquery-ui-dialog','jquery-ui-sortable' ), $this->version, false );

				wp_localize_script( 'mwb-woo-cfe-billing-admin-js', 'license_ajax_object_free', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=mwb_woocommerce_checkout_field_editor_menu' ),
					'license_nonce' => wp_create_nonce( 'mwb-woocommerce-checkout-field-editor-license-nonce-action' ),
					'check_image' => MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/checked.png',
					'cross_image' => MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/cancel.png',
					'edit_image' => MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/edit.png',
					'option_text'	=> __('Enter Options','mwb-woocommerce-checkout-field-editor'),
					'option_placeholder'	=> __('Enter Values Seperated By | Symbol','mwb-woocommerce-checkout-field-editor'),
					'name_placeholder'	=> __('Please Fill This Field','mwb-woocommerce-checkout-field-editor'),
					'edit_name'	=> __('Name','mwb-woocommerce-checkout-field-editor'),
					'edit_label'	=> __('Label','mwb-woocommerce-checkout-field-editor'),
					'edit_required'	=> __('Required','mwb-woocommerce-checkout-field-editor'),
					'edit_validation'	=> __('Validation','mwb-woocommerce-checkout-field-editor'),
					'edit_save_button'	=> __('Save','mwb-woocommerce-checkout-field-editor'),
					'delete_billin_record'	=> __('Are You Sure To Reset The Field ?','mwb-woocommerce-checkout-field-editor'),
					) 
				);
			}
			if((isset($_GET['page']) && isset($_GET['tab'])) && ($_GET['page'] == 'mwb_woocommerce_checkout_field_editor_menu' && $_GET['tab'] == 'form_shipping') ){

				wp_enqueue_script('mwb-woo-cfe-shipping-admin-js', MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL . 'admin/js/mwb-woocommerce-checkout-shipping-field-editor.js', array( 'jquery','select2','jquery-tiptip','jquery-ui-dialog','jquery-ui-sortable' ), $this->version, false );
				wp_localize_script( 'mwb-woo-cfe-shipping-admin-js', 'license_ajax_object_free', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=mwb_woocommerce_checkout_field_editor_menu' ),
					'license_nonce' => wp_create_nonce( 'mwb-woocommerce-checkout-field-editor-license-nonce-action' ),
					'check_image' => MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/checked.png',
					'cross_image' => MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/cancel.png',
					'edit_image' => MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/edit.png',
					'option_text'	=> __('Enter Options','mwb-woocommerce-checkout-field-editor'),
					'option_placeholder'	=> __('Enter Values Seperated By | Symbol','mwb-woocommerce-checkout-field-editor'),
					'name_placeholder'	=> __('Please Fill This Field','mwb-woocommerce-checkout-field-editor'),
					'edit_name'	=> __('Name','mwb-woocommerce-checkout-field-editor'),
					'edit_label'	=> __('Label','mwb-woocommerce-checkout-field-editor'),
					'edit_required'	=> __('Required','mwb-woocommerce-checkout-field-editor'),
					'edit_validation'	=> __('Validation','mwb-woocommerce-checkout-field-editor'),
					'edit_save_button'	=> __('Save','mwb-woocommerce-checkout-field-editor'),
					'delete_record'	=> __('Are You Sure To Reset The Field ?','mwb-woocommerce-checkout-field-editor'),
					) 
				);
			}
		}

		do_action('mwb_wcfe_addon_extra_scripts');
		// Enqueue and Localize script for using WooCommerce Tooltip.

		wp_enqueue_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select' ), WC_VERSION );

		$params = array(
			'strings' => '',
			'urls' => '',
			);

		wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
	}

	/**
	 * Adding settings menu for MWB Woocommerce Checkout Field Editor.
	 *
	 * @since    1.0.0
	 */
	public function add_options_page() {

		add_menu_page(
			__( 'MWB Woocommerce Checkout Field Editor', 'mwb-woocommerce-checkout-field-editor' ),
			__( 'MWB WOO Checkout Field Editor', 'mwb-woocommerce-checkout-field-editor' ),
			'manage_options',
			'mwb_woocommerce_checkout_field_editor_menu',
			array( $this, 'options_menu_html' ),
			'',
			85
			);
	}

	/**
	 * MWB Woocommerce Checkout Field Editor admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function options_menu_html() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {

			return;
		}
		?>

		<div class="mwb-woocommerce-checkout-field-editor-wrap">
			<h2><?php _e('MWB Woocommerce Checkout Field Editor', 'mwb-woocommerce-checkout-field-editor' ); ?></h2>

			<?php

			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

			$mwb_wcfe_tabs = array(
				'general'       => array(
					'label'     => __('Checkout Billing Form Field Editor', 'mwb-woocommerce-checkout-field-editor' ),
					'page'  	=> 'mwb_woocommerce_checkout_field_editor_menu',
					),
				'form_shipping' => array(
					'label'    	=> __('Checkout Shipping Form Field Editor', 'mwb-woocommerce-checkout-field-editor' ),
					'page'  	=> 'mwb_woocommerce_checkout_field_editor_menu',
					)
				);

			$mwb_wcfe_tabs = apply_filters( 'mwb_wcfe_pro_addon_nav_menu_link', $mwb_wcfe_tabs);
			?>
			<h2 class="nav-tab-wrapper">
				<?php

				do_action('mwb_wcfe_add_notice_for_license_from_addon');

				if(is_array($mwb_wcfe_tabs) && !empty($mwb_wcfe_tabs)){
					foreach($mwb_wcfe_tabs as $mwb_wcfe_tabs_key => $mwb_wcfe_tabs_value){
						?>
						<a href="?page=<?php echo $mwb_wcfe_tabs_value['page']; ?>&tab=<?php echo $mwb_wcfe_tabs_key; ?>" class="nav-tab <?php echo $active_tab == $mwb_wcfe_tabs_key ? 'nav-tab-active' : ''; ?>"><?php echo $mwb_wcfe_tabs_value['label']; ?></a>
						<?php
					}
				}
				?>

				
			</h2>
			<?php

			if( $active_tab == 'general' ) {
				$mwb_woo_cfe_current_country = wc_get_base_location();
				$mwb_woocommerce_cfe_billing_field_obj = new WC_Countries();
				$mwb_woo_cfe_default_billlingfields = $mwb_woocommerce_cfe_billing_field_obj->get_address_fields($mwb_woo_cfe_current_country['country'],'billing_');

				include_once MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH.'admin/partials/mwb-woocommerce-checkout-field-editor-admin-display.php';

				}// endif General Options tab.
				elseif($active_tab == 'form_shipping'){
					$mwb_woo_cfe_current_country = wc_get_base_location();
					$mwb_woocommerce_cfe_shipping_field_obj = new WC_Countries();
					$mwb_woo_cfe_default_shippingfields = $mwb_woocommerce_cfe_shipping_field_obj->get_address_fields($mwb_woo_cfe_current_country['country'],'shipping_');
					include_once MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH.'admin/partials/mwb-woocommerce-checkout-field-editor-shipping-form.php';

				}
				

				unset($mwb_wcfe_tabs['general']);
				unset($mwb_wcfe_tabs['form_shipping']);
				
				if(is_array($mwb_wcfe_tabs) && !empty($mwb_wcfe_tabs)){
					foreach($mwb_wcfe_tabs as $tabs_key => $tabs_value){
						if($active_tab == $tabs_key){
							$mwb_wcfe_template_name = $tabs_key;
							do_action('mwb_wcfe_add_addon_page_template',$mwb_wcfe_template_name);
						}
					}
				}


				?>
			</div> <!-- mwb-woocommerce-checkout-field-editor-wrap -->
			<?php
		}

	/**
	 * Reset the Billing fields data.
	 * name 	 mwb_woo_cfe_reset_field_data
	 * @since    1.0.0
	*/
	public function mwb_woo_cfe_reset_field_data(){
		
		$mwb_nonce_security = check_ajax_referer( 'mwb-woocommerce-checkout-field-editor-license-nonce-action', 'mwb_reset_nonce' );
		if($mwb_nonce_security){

			$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added',false);

			$mwb_woo_cfe_current_country = wc_get_base_location();
			$mwb_woocommerce_cfe_billing_field_obj = new WC_Countries();
			$mwb_woo_cfe_default_billlingfields = $mwb_woocommerce_cfe_billing_field_obj->get_address_fields($mwb_woo_cfe_current_country['country'],'billing_');

			if(is_array($mwb_woo_cfe_default_billlingfields) && !empty($mwb_woo_cfe_default_billlingfields)){
				update_option('mwb_woocommerce_cfe_custom_field_added',$mwb_woo_cfe_default_billlingfields);
			}
			else{
				echo "failed";
			}
			delete_option('mwb_woo_cfe_saved_conditional_fields');
			echo "success";
		}
		else{
			echo "failed";
		}
		wp_die();
	}


	/**
	 * Reset the Shipping fields data.
	 * name 	 mwb_woo_cfe_reset_field_data_shipping
	 * @since    1.0.0
	 */
	public function mwb_woo_cfe_reset_field_data_shipping(){
		$mwb_nonce_security = check_ajax_referer( 'mwb-woocommerce-checkout-field-editor-license-nonce-action', 'mwb_reset_nonce' );

		if($mwb_nonce_security){

			$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added_shipping',false);

			$mwb_woo_cfe_current_country = wc_get_base_location();
			$mwb_woocommerce_cfe_billing_field_obj = new WC_Countries();
			$mwb_woo_cfe_default_billlingfields = $mwb_woocommerce_cfe_billing_field_obj->get_address_fields($mwb_woo_cfe_current_country['country'],'shipping_');

			if(is_array($mwb_woo_cfe_default_billlingfields) && !empty($mwb_woo_cfe_default_billlingfields)){
				update_option('mwb_woocommerce_cfe_custom_field_added_shipping',$mwb_woo_cfe_default_billlingfields);
			}
			else{
				echo "failed";
			}
			delete_option('mwb_woo_cfe_saved_conditional_fields_Shipping');
			echo "success";
		}
		else{
			echo "failed";
		}
		wp_die();
	}


	/**
	 * Update the Billing fields data.
	 *
	 * @since    1.0.0
	*/
	public function mwb_woo_cfe_edit_field_values(){
		$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added',false);
		$MwbWooCfeEditFiledsData = isset($_POST['mwb_woo_cfe_field_values']) ? sanitize_text_field($_POST['mwb_woo_cfe_field_values']) : '';
		if(is_array($MwbWooCfeEditFiledsData) && !empty($MwbWooCfeEditFiledsData)){
			if($MwbWooCfeEditFiledsData['mwb_woo_cfe_key'] == 'billing_'){
				if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields)){
					foreach($MwbWooCfeAddedFields as $UpdatedKey => $UpdatedValues){
						if($UpdatedKey == $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']){
							$MwbWooCfeAddedFields[$UpdatedKey]['label'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_label'];
							$MwbWooCfeAddedFields[$UpdatedKey]['required'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_rquired'];
							if(isset($MwbWooCfeAddedFields[$UpdatedKey]['validate'])){
								$MwbWooCfeAddedFields[$UpdatedKey]['validated'][0] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_validate'];
							}
						}
					}
					update_option('mwb_woocommerce_cfe_custom_field_added',$MwbWooCfeAddedFields);
					echo "success";
				}
			}	
			else{
				if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields)){
					$MwbWooCfeNewArray = array();
					foreach($MwbWooCfeAddedFields as $UpdatedKey => $UpdatedValues){
						if($UpdatedKey == $MwbWooCfeEditFiledsData['mwb_woo_cfe_previous_name']){

							$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']] = $UpdatedValues;

							$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['label'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_label'];
							$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['required'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_rquired'];
							if(isset($MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['validate'])){
								$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['validate'][0] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_validate'];
							}
						}
						else{
							$MwbWooCfeNewArray[$UpdatedKey] = $UpdatedValues;
						}
					}
					update_option('mwb_woocommerce_cfe_custom_field_added',$MwbWooCfeNewArray);
					echo "success";
				}
			}
		}
		wp_die();
	}

	/**
	 * Update the Shipping fields data.
	 *
	 * @since    1.0.0
	*/
	public function mwb_woo_cfe_edit_field_values_shipping(){
		$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added_shipping',false);
		$MwbWooCfeEditFiledsData = isset($_POST['mwb_woo_cfe_field_values']) ? sanitize_text_field($_POST['mwb_woo_cfe_field_values']) : '';
		if(is_array($MwbWooCfeEditFiledsData) && !empty($MwbWooCfeEditFiledsData)){
			if($MwbWooCfeEditFiledsData['mwb_woo_cfe_key'] == 'shipping_'){

				if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields)){
					foreach($MwbWooCfeAddedFields as $UpdatedKey => $UpdatedValues){

						if($UpdatedKey == $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']){

							$MwbWooCfeAddedFields[$UpdatedKey]['label'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_label'];
							$MwbWooCfeAddedFields[$UpdatedKey]['required'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_rquired'];
							if(isset($MwbWooCfeAddedFields[$UpdatedKey]['validate'])){
								$MwbWooCfeAddedFields[$UpdatedKey]['validated'][0] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_validate'];
							}
						}
					}

					update_option('mwb_woocommerce_cfe_custom_field_added_shipping',$MwbWooCfeAddedFields);
					echo "success";
				}
			}	
			else{
				if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields)){
					$MwbWooCfeNewArray = array();
					foreach($MwbWooCfeAddedFields as $UpdatedKey => $UpdatedValues){
						if($UpdatedKey == $MwbWooCfeEditFiledsData['mwb_woo_cfe_previous_name']){

							$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']] = $UpdatedValues;

							$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['label'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_label'];
							$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['required'] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_rquired'];
							if(isset($MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['validate'])){
								$MwbWooCfeNewArray[$MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_name']]['validate'][0] = $MwbWooCfeEditFiledsData['mwb_woo_cfe_edit_field_validate'];
							}
						}
						else{
							$MwbWooCfeNewArray[$UpdatedKey] = $UpdatedValues;
						}
					}
					update_option('mwb_woocommerce_cfe_custom_field_added_shipping',$MwbWooCfeNewArray);
					echo "success";
				}
			}
		}
		wp_die();
	}
}
