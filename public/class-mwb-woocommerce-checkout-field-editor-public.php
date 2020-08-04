<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Woocommerce_Checkout_Field_Editor
 * @subpackage Mwb_Woocommerce_Checkout_Field_Editor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mwb_Woocommerce_Checkout_Field_Editor
 * @subpackage Mwb_Woocommerce_Checkout_Field_Editor/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Woocommerce_Checkout_Field_Editor_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// include_once WP_CONTENT_DIR.'/plugins/woocommerce/includes/class-wc-chekout.php';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 * Function enqueue_styles.
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if( !in_array('mwb-woocommerce-chekout-field-editor-pro/mwb-woocommerce-checkout-field-editor-pro.php',get_option('active_plugins',false))){

			wp_enqueue_style( $this->plugin_name, MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL . 'public/css/mwb-woocommerce-checkout-field-editor-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 * Function 	enqueue_scripts.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if( !in_array('mwb-woocommerce-chekout-field-editor-pro/mwb-woocommerce-checkout-field-editor-pro.php',get_option('active_plugins',false))){
			
			wp_enqueue_script( $this->plugin_name, MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL . 'public/js/mwb-woocommerce-checkout-field-editor-public.js', array( 'jquery' ), $this->version, false );
		}

	}


	/**
	 * Function for overriding the checkout shipping form on front end.
	 * name 	mwb_woo_cfe_remove_shipping_form_from_chekout_page
	 * @param 	$path 				string 			Path for template.
	 * @param 	$template_name 		string 			Template Name.
	 * @since    1.0.0
	*/
	public function mwb_woo_cfe_remove_shipping_form_from_chekout_page($path, $template_name ){
		$MwbWooCfeShippingFieldDisabled = get_option('mwb_woo_cfe_shipping_field_disabled',false);

		if( $template_name == 'checkout/form-shipping.php' && (isset($MwbWooCfeShippingFieldDisabled) && $MwbWooCfeShippingFieldDisabled == 'on') )
		{
				// change path here as required
			return MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH. '/template/woocommerce/checkout/form-shipping.php';
		}
		return $path;

	}

	/**
	 * Function for chnage reset,sort,validate,required field for checkout.
	 * name mwb_cfe_woocommerce_checkout_fields_modified.
	 * @param 	$fields 	array 	all checkout default fields.
	 * @return 	$fields.
	 * @since    1.0.0
	*/
	public function mwb_cfe_woocommerce_checkout_fields_modified($fields){
		$mwb_wbsr_all_billing_option = array();
		$mwb_wbsr_all_shipping_option = array();
		$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added',false);
		$MwbWooCfeSavedConditionalFieldsData = get_option('mwb_woo_cfe_saved_conditional_fields',false);
		$MwbWooCfeSavedConditionalFieldsData = json_decode($MwbWooCfeSavedConditionalFieldsData,true);
		
		//For Shipping Form.	
		$MwbWooCfeAddedFieldsShipping = get_option('mwb_woocommerce_cfe_custom_field_added_shipping',false);
		$MwbWooCfeSavedConditionalFieldsShippingData = get_option('mwb_woo_cfe_saved_conditional_fields_Shipping',false);
		$MwbWooCfeSavedConditionalFieldsShippingData = json_decode($MwbWooCfeSavedConditionalFieldsShippingData,true);

		if(isset($fields)){
			$MwbWooCfeAccounts = $fields['account'];
			$MwbWooCfeOrder = $fields['order'];
			$mwb_woo_cfe_default_billing_field = $fields['billing'];
			$mwb_woo_cfe_default_shipping_field = $fields['shipping'];

			if( is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields) ){
				$fields = array();
				$fields['billing'] = $MwbWooCfeAddedFields;
			}
			if( is_array($MwbWooCfeAddedFieldsShipping) && !empty($MwbWooCfeAddedFieldsShipping) ){
				$fields = array();
				$fields['billing'] = $MwbWooCfeAddedFields;
				$fields['shipping'] = $MwbWooCfeAddedFieldsShipping;
			}
			// $fields['shipping'] = $MwbWooCfeAddedFieldsShipping;
			$fields['account'] = $MwbWooCfeAccounts;
			$fields['order'] = $MwbWooCfeOrder;

			if(is_array($fields['billing']) && !empty($fields['billing'])){
				$billing_counter = 1;
				$billing_counter_unset = 0;
				foreach($fields['billing'] as $billing_key => $billing_value){
					
					$fields['billing'][$billing_key]['priority'] = 10 * $billing_counter;
					if(isset($mwb_woo_cfe_default_billing_field['billing'][$billing_key])){
						$fields['billing'][$billing_key]['class'] = '';
						$fields['billing'][$billing_key]['class'] = $mwb_woo_cfe_default_billing_field[$billing_key]['class'];
					}
					else
					{
						$fields['billing'][$billing_key]['class'] = '';
						$fields['billing'][$billing_key]['class'] = array('form-row-wide');
					}

					if(isset($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_custom_option']) && is_array($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_custom_option']) && !empty($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_custom_option'])){

						$Billingkeysno = array_keys($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_custom_option']);
					}
					else{
						$Billingkeysno = array();
					}
					if(is_array($Billingkeysno) && !empty($Billingkeysno)){
						foreach($Billingkeysno as $BillingKeys => $Values_Billing){

							if(isset($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_field'][$Values_Billing]) && $MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_field'][$Values_Billing] == 'select' && $MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_name_field'] [$Values_Billing] == $billing_key)
							{
								
								$mwb_wbsr_all_billing_option =  empty( $MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_custom_option'][$Values_Billing] ) ? array() : array_map( 'wc_clean', explode( '|', trim(stripslashes($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_type_custom_option'][$Values_Billing])) ) );

								if(is_array($mwb_wbsr_all_billing_option) && !empty($mwb_wbsr_all_billing_option)){
									foreach($mwb_wbsr_all_billing_option as $custom_key => $custom_value){
										unset($mwb_wbsr_all_billing_option[$custom_key]);
										$mwb_wbsr_all_billing_option[$custom_value] = $custom_value;
									}
								}
								$fields['billing'][$billing_key]['options'] = empty($mwb_wbsr_all_billing_option) ? array() : $mwb_wbsr_all_billing_option;
							}
						}
					}
					$billing_counter++;
					
					if(is_array($MwbWooCfeSavedConditionalFieldsData) && !empty($MwbWooCfeSavedConditionalFieldsData)){

						if(array_key_exists($billing_key, $MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_disable_field'])){
							unset($fields['billing'][$billing_key]);
						}
					}
					$billing_counter_unset++;
				}
			}

			//shipping
			if(is_array($fields['shipping']) && !empty($fields['shipping'])){
				$shipping_counter = 1;
				$shipping_counter_unset = 0;
				foreach($fields['shipping'] as $shipping_key => $billing_value){
					$fields['shipping'][$shipping_key]['priority'] = 10 * $shipping_counter;
					if(isset($mwb_woo_cfe_default_shipping_field['shipping'][$shipping_key])){
						
						$fields['shipping'][$shipping_key]['class'] = '';
						$fields['shipping'][$shipping_key]['class'] = $mwb_woo_cfe_default_shipping_field[$shipping_key]['class'];
					}
					else
					{

						$fields['shipping'][$shipping_key]['class'] = '';
						$fields['shipping'][$shipping_key]['class'] = array('form-row-wide');
					}

					if(isset($MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_custom_option']) && is_array($MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_custom_option']) && !empty($MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_custom_option'])){

						$Shippingkeysno = array_keys($MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_custom_option']);
					}
					else{
						$Shippingkeysno = array();
					}
					if(is_array($Shippingkeysno) && !empty($Shippingkeysno)){
						foreach($Shippingkeysno as $ShippingKeys => $Values_Shipping){

							if(isset($MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_field'][$Values_Shipping]) && $MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_field'][$Values_Shipping] == 'select' && $MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_name_shipping_field'] [$Values_Shipping] == $shipping_key)
							{
								$mwb_wbsr_all_shipping_option = empty( $MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_custom_option'][$Values_Shipping] ) ? array() : array_map( 'wc_clean', explode( '|', trim(stripslashes($MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_type_shipping_custom_option'][$Values_Shipping])) ) );

								if(is_array($mwb_wbsr_all_shipping_option) && !empty($mwb_wbsr_all_shipping_option)){
									foreach($mwb_wbsr_all_shipping_option as $custom_shipping_key => $custom_shipping_value){
										unset($mwb_wbsr_all_shipping_option[$custom_shipping_key]);
										$mwb_wbsr_all_shipping_option[$custom_shipping_value] = $custom_shipping_value;
									}
								}

								$fields['shipping'][$shipping_key]['options'] = empty($mwb_wbsr_all_shipping_option) ? array() : $mwb_wbsr_all_shipping_option;
							}
						}
					}
					$shipping_counter++;
					if(is_array($MwbWooCfeSavedConditionalFieldsShippingData) && !empty($MwbWooCfeSavedConditionalFieldsShippingData)){

						if(array_key_exists($shipping_key, $MwbWooCfeSavedConditionalFieldsShippingData['mwb_woo_cfe_disable_shipping_field'])){
							unset($fields['shipping'][$shipping_key]);
						}
					}
					$shipping_counter_unset++;
				}

			}
		}

		return $fields;
	}


	/**
	 * Function for set shipping country for chekout.
	 * name mwb_woocommerce_cart_needs_shipping.
	 * @param 	$needs_shipping object 	Object for shipping ammount and all information.
	 * @since    1.0.0
	*/
	public function mwb_woocommerce_cart_needs_shipping($needs_shipping){
		if(isset($_POST['shipping_country'])){
			$shipping_country = sanitize_text_field($_POST['shipping_country']);
			WC()->customer->set_shipping_country($shipping_country);
			WC()->customer->save();
		}
		return $needs_shipping;
	}


	/**
	 * Function for saving custom checkout fields in order meta.
	 * name mwb_woo_cfe_save_custom_data_in_order.
	 * @param 	$order_id 	integer 	function for adding custom fields data in order meta.
	 * @since    1.0.0
	*/
	public function mwb_woo_cfe_save_custom_data_in_order($order_id){
		
		$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added',false);
		$MwbWooCfeAddedFieldsShipping = get_option('mwb_woocommerce_cfe_custom_field_added_shipping',false);
		if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields)){
			$MwbWooCfeAllBillingFieldsKey = array_keys($MwbWooCfeAddedFields);
			if(is_array($MwbWooCfeAllBillingFieldsKey) && !empty($MwbWooCfeAllBillingFieldsKey)){
				foreach($MwbWooCfeAllBillingFieldsKey as $AllKey => $AllValue){
					if(strpos($AllValue,'billing_mwb_') !== false && array_key_exists($AllValue, $_POST)){
						if($_POST[$AllValue] != '' || $_POST[$AllValue]!= null){
							$ModifiedAllValue = substr_replace($AllValue,'_',0,0);
							update_post_meta($order_id,$ModifiedAllValue,sanitize_text_field($_POST[$AllValue]));
						}
					}
				}
			}
		}
		if(is_array($MwbWooCfeAddedFieldsShipping) && !empty($MwbWooCfeAddedFieldsShipping)){
			$MwbWooCfeAllShippingFieldsKey = array_keys($MwbWooCfeAddedFieldsShipping);
			if(is_array($MwbWooCfeAllShippingFieldsKey) && !empty($MwbWooCfeAllShippingFieldsKey)){
				foreach($MwbWooCfeAllShippingFieldsKey as $AllShippingKey => $AllShippingValue){
					if(strpos($AllShippingValue,'shipping_mwb_') !== false && array_key_exists($AllShippingValue, $_POST)){
						if($_POST[$AllShippingValue] != '' || $_POST[$AllShippingValue] != null){

							$ModifiedAllShippingValue = substr_replace($AllShippingValue,'_',0,0);
							update_post_meta($order_id,$ModifiedAllShippingValue,sanitize_text_field($_POST[$AllShippingValue]));
						}
					}
				}
			}
		}
	}

}
