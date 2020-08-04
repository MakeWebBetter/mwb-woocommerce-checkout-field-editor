<?php
if(!defined('ABSPATH')){
	exit;
}
if(!class_exists('Mwb_Woocommerce_Checkout_Field_Editor_Saved_Data')){

	/**
	*  Class for saving checkout field data.
	*/
	class Mwb_Woocommerce_Checkout_Field_Editor_Saved_Data
	{
		// Construtor for class Mwb_Woocommerce_Checkout_Field_Editor_Saved_Data.
		public function __construct()
		{
			$mwb_woo_cfe_current_country = wc_get_base_location();
			$mwb_woocommerce_cfe_billing_field_obj = new WC_Countries();
			$mwb_woo_cfe_default_billlingfields = $mwb_woocommerce_cfe_billing_field_obj->get_address_fields($mwb_woo_cfe_current_country['country'],'billing_');
		}


		/**
		 * function mwb_woo_cfe_default_billlingfields_data_saved
		 * @param 	$MwbWooCfeFieldName					string 		String that defines the field type to save.
		 * @param 	$MwbWooCfeAllDataArray				array 		Array for saving the checkkout fields data.
		 * @param 	$MwbWooCfeDefaultBilllingFields		array 		Array for default checkout fields.\
		 * @return  1 or 0 value.
		 * @since   1.0.0
		*/
		public function mwb_woo_cfe_default_billlingfields_data_saved($MwbWooCfeFieldName, $MwbWooCfeAllDataArray, $MwbWooCfeDefaultBilllingFields){
			
			$mwb_woo_cfe_new_billing_array = array();
			if(isset($MwbWooCfeFieldName) && $MwbWooCfeFieldName == 'billing_mwb_'){
				if(is_array($MwbWooCfeAllDataArray) && !empty($MwbWooCfeAllDataArray)){
					foreach($MwbWooCfeAllDataArray as $CustomBillingFieldKey => $CustomerBillingFieldValue){
						if(is_array($CustomerBillingFieldValue) && !empty($CustomerBillingFieldValue)){
							foreach($CustomerBillingFieldValue as $CustomKey => $CustomValue){
								if($CustomBillingFieldKey == 'mwb_woo_cfe_name_field'){
									if(!isset($MwbWooCfeAllDataArray['mwb_woo_cfe_validate_field'][$CustomKey]) && $MwbWooCfeAllDataArray['mwb_woo_cfe_validate_field'][$CustomKey] == ''){

										$mwb_woo_cfe_new_billing_array[$CustomValue] = array(
											'label'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_label_field'][$CustomKey],
											'required'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_required_field'][$CustomKey],
											'class'	=> array('form-row-first'),
											'autocomplete' => $MwbWooCfeAllDataArray['mwb_woo_cfe_autocomplete_field'][$CustomKey], 
											'priority'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_priority_field'][$CustomKey]
											);
									}
									else{
										$mwb_woo_cfe_new_billing_array[$CustomValue] = array(
											'label'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_label_field'][$CustomKey],
											'required'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_required_field'][$CustomKey],
											'class'	=> array('form-row-first'),
											'validate'	=> array($MwbWooCfeAllDataArray['mwb_woo_cfe_validate_field'][$CustomKey]),
											 
											'priority'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_priority_field'][$CustomKey]
											);
									}
									
									if(is_array($MwbWooCfeAllDataArray['mwb_woo_cfe_type_field']) && !empty($MwbWooCfeAllDataArray['mwb_woo_cfe_type_field'])){
											

										if(array_key_exists($CustomKey, $MwbWooCfeAllDataArray['mwb_woo_cfe_type_field']) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_field'][$CustomKey] != ''){
											$mwb_woo_cfe_new_billing_array[$CustomValue]['type'] =  $MwbWooCfeAllDataArray['mwb_woo_cfe_type_field'][$CustomKey];
										}
									}

									if(is_array($MwbWooCfeAllDataArray['mwb_woo_cfe_type_class']) && !empty($MwbWooCfeAllDataArray['mwb_woo_cfe_type_class']) && isset($MwbWooCfeAllDataArray['mwb_woo_cfe_type_class'][$CustomKey]) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_class'][$CustomKey] != ''){

										$mwb_woo_cfe_new_billing_array[$CustomValue]['class'][0] =  $MwbWooCfeAllDataArray['mwb_woo_cfe_type_class'][$CustomKey];
									}
									if(is_array($MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option']) && !empty($MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option']) && isset($MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'][$CustomKey]) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'][$CustomKey] != ''){

										$mwb_woo_cfe_new_billing_array[$CustomValue]['options'] = empty( $MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'][$CustomKey] ) ? array() : array_map( 'wc_clean', explode( '|', trim(stripslashes($MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'][$CustomKey])) ) );
									}
								}
							}
						}
					}
				}
											
				if(is_array($mwb_woo_cfe_new_billing_array) && !empty($mwb_woo_cfe_new_billing_array)){
					update_option('mwb_woocommerce_cfe_custom_field_added',$mwb_woo_cfe_new_billing_array);
					return "1";
				}else{
					return "0";
				}
			}
			elseif(isset($MwbWooCfeFieldName) && $MwbWooCfeFieldName == 'shipping_mwb_'){

				if(is_array($MwbWooCfeAllDataArray) && !empty($MwbWooCfeAllDataArray)){
					foreach($MwbWooCfeAllDataArray as $CustomShippingFieldKey => $CustomerShippingFieldValue){
						if(is_array($CustomerShippingFieldValue) && !empty($CustomerShippingFieldValue)){
							foreach($CustomerShippingFieldValue as $CustomKeyShipping => $CustomValueShipping){
								if($CustomShippingFieldKey == 'mwb_woo_cfe_name_shipping_field'){
									if(!isset($MwbWooCfeAllDataArray['mwb_woo_cfe_validate_shipping_field'][$CustomKeyShipping]) && $MwbWooCfeAllDataArray['mwb_woo_cfe_validate_shipping_field'][$CustomKeyShipping] == ''){

										$mwb_woo_cfe_new_billing_array[$CustomValueShipping] = array(
											'label'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_label_shipping_field'][$CustomKeyShipping],
											'required'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_required_shipping_field'][$CustomKeyShipping],
											'class'	=> array('form-row-first'),
											'autocomplete' => $MwbWooCfeAllDataArray['mwb_woo_cfe_autocomplete_shipping_field'][$CustomKeyShipping], 
											'priority'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_priority_shipping_field'][$CustomKeyShipping]
											);
									}
									else{
										$mwb_woo_cfe_new_billing_array[$CustomValueShipping] = array(
											'label'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_label_shipping_field'][$CustomKeyShipping],
											'required'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_required_shipping_field'][$CustomKeyShipping],
											'class'	=> array('form-row-first'),
											'validate'	=> array($MwbWooCfeAllDataArray['mwb_woo_cfe_validate_shipping_field'][$CustomKeyShipping]),
											'priority'	=> $MwbWooCfeAllDataArray['mwb_woo_cfe_priority_shipping_field'][$CustomKeyShipping]
											);
									}

									if(is_array($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_field']) && !empty($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_field'])){
										
										if(array_key_exists($CustomKeyShipping, $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_field']) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_field'][$CustomKeyShipping] != ''){

											$mwb_woo_cfe_new_billing_array[$CustomValueShipping]['type'] =  $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_field'][$CustomKeyShipping];
										}
									}
									if(is_array($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_class']) && !empty($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_class']) && isset($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_class'][$CustomKeyShipping]) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_class'][$CustomKeyShipping] != ''){

										$mwb_woo_cfe_new_billing_array[$CustomValueShipping]['class'][0] =  $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_class'][$CustomKeyShipping];
									}
									if(is_array($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_custom_option']) && !empty($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_custom_option']) && isset($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_custom_option'][$CustomKeyShipping]) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_custom_option'][$CustomKeyShipping] != ''){

										$mwb_woo_cfe_new_billing_array[$CustomValueShipping]['options'] = empty( $MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_custom_option'][$CustomKeyShipping] ) ? array() : array_map( 'wc_clean', explode( '|', trim(stripslashes($MwbWooCfeAllDataArray['mwb_woo_cfe_type_shipping_custom_option'][$CustomKeyShipping])) ) );
									}
								}
							}
						}
					}
				}
				if(is_array($mwb_woo_cfe_new_billing_array) && !empty($mwb_woo_cfe_new_billing_array)){
					update_option('mwb_woocommerce_cfe_custom_field_added_shipping',$mwb_woo_cfe_new_billing_array);
					return "1";
				}else{
					return "0";
				}
			}
		}
	}
}