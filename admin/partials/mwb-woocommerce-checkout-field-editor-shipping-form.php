<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Woocommerce_Checkout_Field_Editor
 * @subpackage Mwb_Woocommerce_Checkout_Field_Editor/admin/partials
 */
if( !defined('ABSPATH') ){
	exit();
}

if(isset($_POST['mwb_woo_cfe_submit_shipping_data'])){
	$MwbWooCfeAllShippingDataArray = array();
	if(wp_verify_nonce($_REQUEST['mwb_wcfe_nonce_verify'],'mwb-wcfe-nonce')){
		
		$mwb_woo_cfe_disable_all_shipping_fields = isset($_POST['mwb_woo_cfe_shipping_switch_disable']) ? sanitize_text_field($_POST['mwb_woo_cfe_shipping_switch_disable']) : 'off';

		update_option('mwb_woo_cfe_shipping_field_disabled',$mwb_woo_cfe_disable_all_shipping_fields);
		
		$MwbWooCfeAllFieldNameShipping = isset($_POST['mwb_woo_cfe_shipping_name']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_name']) : '';

		$MwbWooCfeAllFieldLabelShipping = isset($_POST['mwb_woo_cfe_shipping_label']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_label']) : '';
		$MwbWooCfeAllFieldRequiredShipping = isset($_POST['mwb_woo_cfe_shipping_required']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_required']) : '';
		$MwbWooCfeAllFieldValidateShipping = isset($_POST['mwb_woo_cfe_shipping_validate']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_validate']) : '';
		$MwbWooCfeAllFieldClassShipping = isset($_POST['mwb_woo_cfe_shippinging_filed_class']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shippinging_filed_class']) : '';
		$MwbWooCfeAllFieldProrityShipping = isset($_POST['mwb_woo_cfe_shipping_priority']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_priority']) : '';
		$MwbWooCfeAllFieldTypeShipping = isset($_POST['mwb_woo_cfe_shipping_type']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_type']) : '';
		$MwbWooCfeAllFieldDisableShipping = isset($_POST['mwb_woo_cfe_shipping_switch_enable']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_switch_enable']) : array();
		$MwbWooCfeAllFieldAutocompleteShipping = isset($_POST['mwb_woo_cfe_shipping_autocomplete']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_autocomplete']) : '';
		$MwbWooCfeAllFieldShippingOptions = isset($_POST['mwb_woo_cfe_shipping_filed_selected_option']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_shipping_filed_selected_option']) : '';


		$MwbWooCfeAllShippingDataArray = array(
			'mwb_woo_cfe_name_shipping_field'	 	 => $MwbWooCfeAllFieldNameShipping,
			'mwb_woo_cfe_label_shipping_field' 	 	 => $MwbWooCfeAllFieldLabelShipping,
			'mwb_woo_cfe_required_shipping_field' 	 => $MwbWooCfeAllFieldRequiredShipping,
			'mwb_woo_cfe_validate_shipping_field' 	 => $MwbWooCfeAllFieldValidateShipping,
			'mwb_woo_cfe_disable_shipping_field'  	 => $MwbWooCfeAllFieldDisableShipping,
			'mwb_woo_cfe_priority_shipping_field' 	 => $MwbWooCfeAllFieldProrityShipping,
			'mwb_woo_cfe_autocomplete_shipping_field' => $MwbWooCfeAllFieldAutocompleteShipping,
			'mwb_woo_cfe_type_shipping_field' 		 => $MwbWooCfeAllFieldTypeShipping,
			'mwb_woo_cfe_type_shipping_class' 		 => $MwbWooCfeAllFieldClassShipping,
			'mwb_woo_cfe_type_shipping_custom_option' => $MwbWooCfeAllFieldShippingOptions
			);

		if(isset($MwbWooCfeAllShippingDataArray['mwb_woo_cfe_type_shipping_custom_option']) && $MwbWooCfeAllShippingDataArray['mwb_woo_cfe_type_shipping_custom_option'] != ''){
			foreach($MwbWooCfeAllShippingDataArray['mwb_woo_cfe_type_shipping_custom_option'] as $shipping_fields => $shipping_fields_value){
				if($shipping_fields_value == 'undefined'){
					$MwbWooCfeAllShippingDataArray['mwb_woo_cfe_type_shipping_custom_option'][$shipping_fields] = '';
				}
			}
		}

		$MwbWooCfeAllShippingDataArrayEncoded = json_encode($MwbWooCfeAllShippingDataArray);
		update_option('mwb_woo_cfe_saved_conditional_fields_Shipping',$MwbWooCfeAllShippingDataArrayEncoded);

		require_once MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH.'admin/saved_fields/class-mwb-woocommerce-checkout-field-editor-saved-data.php';
		$mwb_woo_cfe_shipping_field_saved_obj = new Mwb_Woocommerce_Checkout_Field_Editor_Saved_Data();

		$mwb_woo_cfe_shipping_field_saved = $mwb_woo_cfe_shipping_field_saved_obj->mwb_woo_cfe_default_billlingfields_data_saved('shipping_mwb_',$MwbWooCfeAllShippingDataArray,$mwb_woo_cfe_default_shippingfields);

		if(isset($mwb_woo_cfe_shipping_field_saved) && $mwb_woo_cfe_shipping_field_saved == '1'){
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php _e('Your settings has been saved','mwb-woocommerce-checkout-field-editor');?></p>
			</div>
			<?php
		}
	}
}
$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added_shipping',false);

if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields) ){

	$mwb_woo_cfe_default_shippingfields = array();
	$mwb_woo_cfe_default_shippingfields = $MwbWooCfeAddedFields;
}
?>

<div class="notice notice-success is-dismissible" id="mwb_wcfe_shipping_field_added_notice">
	<p><?php _e('Custom Shipping Field has been added','mwb-woocommerce-checkout-field-editor');?></p>
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-woo-cfe-wrapper">
	<div class="mwb-woo-cfe-wrapper__table-section">
		<div class="mwb-woo-cfe-wrapper__table">
			<form action="" method="POST" id="mwb_woo_cfe_save_data_for_billing">
				<table class="mwb_woo_cfe_save_data_for_shipping_table" id="mwb_woo_cfe_save_data_shipping_table">
					<thead>
						<tr>
							<th style="width : 100px"><?php _e('Action','mwb-woocommerce-checkout-field-editor');?></th>
							<th style="width : 20%"><?php _e('Name','mwb-woocommerce-checkout-field-editor');?></th>
							<th style="width : 20%"><?php _e('Label','mwb-woocommerce-checkout-field-editor');?></th>
							<th style="width : 15%"><?php _e('Required','mwb-woocommerce-checkout-field-editor');?></th>
							<th style="width : 15%"><?php _e('Validation','mwb-woocommerce-checkout-field-editor');?></th>
							<th style="width : 10%"><?php _e('Sort','mwb-woocommerce-checkout-field-editor'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$MwbWooCfeSavedConditionalFieldsDataShipping = get_option('mwb_woo_cfe_saved_conditional_fields_Shipping',false);
						$MwbWooCfeSavedConditionalFieldsDataShipping = json_decode($MwbWooCfeSavedConditionalFieldsDataShipping,true);
						
						$i=0;
						if(is_array($mwb_woo_cfe_default_shippingfields) && !empty($mwb_woo_cfe_default_shippingfields)){
							foreach($mwb_woo_cfe_default_shippingfields as $shipping_field_key => $shipping_field_value){

								?>
								<tr id="mwb_woo_cfe_row_shipping_<?php echo $i; ?>">
									<td data-th_attr="<?php _e('Action','mwb-woocommerce-checkout-field-editor');?>">
										<?php do_action('mwb_wcfe_pro_edit_fileds_shipping'); ?>
										<span class="delete-field">	
											<input type="checkbox" class="mwb_woo_cfe_switch_class" id="mwb_woo_cfe_switch_<?php echo $i;?>" name="mwb_woo_cfe_shipping_switch_enable[<?php echo $shipping_field_key; ?>]" <?php if(isset($MwbWooCfeSavedConditionalFieldsDataShipping['mwb_woo_cfe_disable_shipping_field']) && array_key_exists($shipping_field_key,$MwbWooCfeSavedConditionalFieldsDataShipping['mwb_woo_cfe_disable_shipping_field'])){ echo "checked='checked'"; }?> /><label class="mwb_woo_cfe_switch_label_class" for="mwb_woo_cfe_switch_<?php echo $i;?>"></label>
										</span>
									</td>
									<td data-th_attr="<?php _e('Name','mwb-woocommerce-checkout-field-editor');?>"><?php echo $shipping_field_key; ?></td>

									<td data-th_attr="<?php _e('label','mwb-woocommerce-checkout-field-editor');?>"><?php if(isset($shipping_field_value['label'])){ echo $shipping_field_value['label']; }else{ _e('--');}?></td>

									<td data-th_attr="<?php _e('Required','mwb-woocommerce-checkout-field-editor');?>"><img src="<?php if(isset($shipping_field_value['required']) && $shipping_field_value['required'] == 1){ echo MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/checked.png'; } else{ echo MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/cancel.png'; }?>"></td>

									<td data-th_attr="<?php _e('Validation','mwb-woocommerce-checkout-field-editor');?>"><?php if(isset($shipping_field_value['validate'][0]) && $shipping_field_value['validate'][0] != ''){ echo $shipping_field_value['validate'][0];}else{ _e('--');}?></td>

									<td width="1%" class="sort ui-sortable-handle"></td>

									<td style="display: none;" class="mwb_woo_cfe_form_data_none" >
										<input type="hidden" name="mwb_woo_cfe_shipping_name[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_name" value="<?php echo $shipping_field_key; ?>">
										<input type="hidden" name="mwb_woo_cfe_shipping_label[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_label" value="<?php if(isset($shipping_field_value['label'])){ echo $shipping_field_value['label']; }?>">
										<input type="hidden" name="mwb_woo_cfe_shipping_required[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_required" value="<?php if(isset($shipping_field_value['required']) && $shipping_field_value['required'] == 1){ echo $shipping_field_value['required']; }else{ echo 0 ;} ?>">
										<input type="hidden" name="mwb_woo_cfe_shipping_validate[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_validate" value="<?php if(isset($shipping_field_value['validate']) && $shipping_field_value['validate'][0] != 'default'){ echo $shipping_field_value['validate'][0]; }?>">
										<input type="hidden" name="mwb_woo_cfe_shipping_priority[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_priority" value="<?php if(isset($shipping_field_value['priority'])){ echo $shipping_field_value['priority']; }?>">

										<input type="hidden" name="mwb_woo_cfe_shipping_autocomplete[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_autocomplete" value="<?php if(isset($shipping_field_value['autocomplete'])){ echo $shipping_field_value['autocomplete']; }?>">
										<?php
										if(isset($shipping_field_value['type'])){
											?>
											<input type="hidden" name="mwb_woo_cfe_shipping_type[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_type" value="<?php if(isset($shipping_field_value['type'])){ echo $shipping_field_value['type']; }?>">
											<?php
										}
										if(isset($shipping_field_value['options'])){
											?>
											<input type="hidden" name="mwb_woo_cfe_shipping_filed_selected_option[<?php echo $i; ?>]" id="mwb_woo_cfe_shipping_filed_selected_option" value="<?php if(isset($shipping_field_value['options'])){ echo implode('|',$shipping_field_value['options']); }?>">
											<?php
										}
										?>

									</td>
								</tr>
								<tr class="mwb_woo_cfe_toggle_row" id="child_mwb_woo_cfe_row_shipping_<?php echo $i; ?>" data-row_priority="<?php if(isset($shipping_field_value['priority'])){ echo $shipping_field_value['priority']; }?>" data-index="<?php echo $i; ?>"></tr>
								<?php
								$i++;
							}
						}
						?>
						<input type="hidden" name="mwb_wcfe_nonce_verify" id="mwb_wcfe_nonce_verify" value="<?php echo wp_create_nonce('mwb-wcfe-nonce');?>">
					</tbody>
					<tfoot>
						<?php 
						$MwbWooCfeShippingFieldDisabled = get_option('mwb_woo_cfe_shipping_field_disabled',false);
						?>
						<tr class="mwb_woo_cfe_footer_class">
							<td style="background-color: #fff;">
								<span class="edit-field mwb_woo_cfe_shipping_edit_field"></span>
								<span class="delete-field">
									<input type="checkbox" class="mwb_woo_cfe_disable_shipping_fields_class" id="mwb_woo_cfe_shipping_switch_disable" name="mwb_woo_cfe_shipping_switch_disable" <?php if(isset($MwbWooCfeShippingFieldDisabled) && $MwbWooCfeShippingFieldDisabled == 'on'){ echo "checked='checked'"; }?> />
									<label class="mwb_woo_cfe_switch_shipping_label_class" for="mwb_woo_cfe_shipping_switch_disable"></label>
								</span>
							</td>
							<td colspan="2" style="background-color: #fff;"><strong><?php _e('Enable Checkbox to hide Shipping Fields','mwb-woocommerce-checkout-field-editor'); ?></strong></td>
							<td colspan="2" style="text-align: right; background-color: #fff;">
								<input type="submit" id="mwb_woo_cfe_submit_shipping_data" name="mwb_woo_cfe_submit_shipping_data" class="button-primary" value="<?php _e('Save','mwb-woocommerce-checkout-field-editor'); ?>" disabled>
								<input type="button" id="mwb_woo_cfe_reset_data_shipping" name="mwb_woo_cfe_reset_data_shipping" class="button" value="<?php _e('Reset Data','mwb-woocommerce-checkout-field-editor'); ?>">
							</td>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>
	<?php do_action('mwb_wcfe_add_checkout_shipping_field_form'); ?>
</div>
<?php
function mwb_sanitize_array($mwb_post_array){
	$mwb_wcfe_filed_sanitize = array();
	if(is_array($mwb_post_array) && !empty($mwb_post_array)){
		foreach($mwb_post_array as $field_key => $field_value){
			$mwb_wcfe_filed_sanitize[$field_key] = sanitize_text_field($field_value);
		}
	}
	return $mwb_wcfe_filed_sanitize;
}
?>