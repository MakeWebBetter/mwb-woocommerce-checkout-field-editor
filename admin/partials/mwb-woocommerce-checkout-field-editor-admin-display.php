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
if(isset($_POST['mwb_woo_cfe_submit_data'])){
	$MwbWooCfeAllDataArray = array();
	
	if(wp_verify_nonce($_REQUEST['mwb_wcfe_nonce_verify'],'mwb-wcfe-nonce')){

		$MwbWooCfeAllFieldName = isset($_POST['mwb_woo_cfe_billing_name']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_name']) : '';
		$MwbWooCfeAllFieldLabel = isset($_POST['mwb_woo_cfe_billing_label']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_label']) : '';
		$MwbWooCfeAllFieldRequired = isset($_POST['mwb_woo_cfe_billing_required']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_required']) : '';
		$MwbWooCfeAllFieldValidate = isset($_POST['mwb_woo_cfe_billing_validate']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_validate']) : '';
		$MwbWooCfeAllFieldDisable = isset($_POST['mwb_woo_cfe_switch_enable']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_switch_enable']) : array();
		$MwbWooCfeAllFieldPrority = isset($_POST['mwb_woo_cfe_billing_priority']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_priority']) : '';
		$MwbWooCfeAllFieldAutocomplete = isset($_POST['mwb_woo_cfe_billing_autocomplete']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_autocomplete']) : '';
		$MwbWooCfeAllFieldType = isset($_POST['mwb_woo_cfe_billing_type']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_type']) : '';
		$MwbWooCfeAllFieldClass = isset($_POST['mwb_woo_cfe_billing_filed_class']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_filed_class']) : '';
		$MwbWooCfeAllFieldCustomSelectOption = isset($_POST['mwb_woo_cfe_billing_filed_selected_option']) ? mwb_sanitize_array($_POST['mwb_woo_cfe_billing_filed_selected_option']) : '';
		
		$MwbWooCfeAllDataArray = array(
			'mwb_woo_cfe_name_field' => $MwbWooCfeAllFieldName,
			'mwb_woo_cfe_label_field' => $MwbWooCfeAllFieldLabel,
			'mwb_woo_cfe_required_field' => $MwbWooCfeAllFieldRequired,
			'mwb_woo_cfe_validate_field' => $MwbWooCfeAllFieldValidate,
			'mwb_woo_cfe_disable_field' => $MwbWooCfeAllFieldDisable,
			'mwb_woo_cfe_priority_field' => $MwbWooCfeAllFieldPrority,
			'mwb_woo_cfe_autocomplete_field' => $MwbWooCfeAllFieldAutocomplete,
			'mwb_woo_cfe_type_field' => $MwbWooCfeAllFieldType,
			'mwb_woo_cfe_type_class' => $MwbWooCfeAllFieldClass,
			'mwb_woo_cfe_type_custom_option' => $MwbWooCfeAllFieldCustomSelectOption
			);
		

		if(isset($MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option']) && $MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'] != ''){
			foreach($MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'] as $billing_fields => $billing_fields_value){
				if($billing_fields_value == 'undefined'){
					$MwbWooCfeAllDataArray['mwb_woo_cfe_type_custom_option'][$billing_fields] = '';
				}
			}
		}
		
		$MwbWooCfeAllDataArrayEncoded = json_encode($MwbWooCfeAllDataArray);
		$MwbWooCfeSavedConditionalFieldsDataBeforeSave = get_option('mwb_woo_cfe_saved_conditional_fields',false);
		$MwbWooCfeSavedConditionalFieldsDataBeforeSave = json_decode($MwbWooCfeSavedConditionalFieldsDataBeforeSave,true);	
		if(is_array($MwbWooCfeSavedConditionalFieldsDataBeforeSave) && !empty($MwbWooCfeSavedConditionalFieldsDataBeforeSave)){
			delete_option('mwb_woo_cfe_saved_conditional_fields');
		}
		update_option('mwb_woo_cfe_saved_conditional_fields',$MwbWooCfeAllDataArrayEncoded);

		require_once MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH.'admin/saved_fields/class-mwb-woocommerce-checkout-field-editor-saved-data.php';
		$mwb_woo_cfe_billing_field_saved_obj = new Mwb_Woocommerce_Checkout_Field_Editor_Saved_Data();
		$mwb_woo_cfe_billing_field_saved = $mwb_woo_cfe_billing_field_saved_obj->mwb_woo_cfe_default_billlingfields_data_saved('billing_mwb_',$MwbWooCfeAllDataArray,$mwb_woo_cfe_default_billlingfields);

		if(isset($mwb_woo_cfe_billing_field_saved) && $mwb_woo_cfe_billing_field_saved == '1'){
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php _e('Your settings has been saved','mwb-woocommerce-checkout-field-editor');?></p>
			</div>
			<?php
		}
	}
}
$MwbWooCfeAddedFields = get_option('mwb_woocommerce_cfe_custom_field_added',false);

if(is_array($MwbWooCfeAddedFields) && !empty($MwbWooCfeAddedFields) ){

	$mwb_woo_cfe_default_billlingfields = array();
	$mwb_woo_cfe_default_billlingfields = $MwbWooCfeAddedFields;
}
?>
<div class="notice notice-success is-dismissible" id="mwb_wcfe_custom_field_notice">
	<p><?php _e('Custom Billing Field has been added','mwb-woocommerce-checkout-field-editor');?></p>
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="mwb-woo-cfe-wrapper">
	<div class="mwb-woo-cfe-wrapper__table-section">
		<div class="mwb-woo-cfe-wrapper__table">
			<form action="" method="POST" id="mwb_woo_cfe_save_data_for_billing">
				<table class="mwb_woo_cfe_save_data_for_billing_table" id="mwb_woo_cfe_save_data_billing_table">
					<thead>
						<tr>
							<th><?php _e('Action','mwb-woocommerce-checkout-field-editor');?></th>
							<th><?php _e('Name','mwb-woocommerce-checkout-field-editor');?></th>
							<th><?php _e('Label','mwb-woocommerce-checkout-field-editor');?></th>
							<th><?php _e('Required','mwb-woocommerce-checkout-field-editor');?></th>
							<th><?php _e('Validation','mwb-woocommerce-checkout-field-editor');?></th>
							<th><?php _e('Sort','mwb-woocommerce-checkout-field-editor');?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$MwbWooCfeSavedConditionalFieldsData = get_option('mwb_woo_cfe_saved_conditional_fields',false);
						$MwbWooCfeSavedConditionalFieldsData = json_decode($MwbWooCfeSavedConditionalFieldsData,true);
						
						$i=0;
						if(is_array($mwb_woo_cfe_default_billlingfields) && !empty($mwb_woo_cfe_default_billlingfields)){
							foreach($mwb_woo_cfe_default_billlingfields as $billing_field_key => $billing_field_value){
								// if($billing_field_key != 'billing_email'){
								?>
								<tr id="mwb_woo_cfe_row_<?php echo $i; ?>">
									<td data-th_attr="<?php _e('Action','mwb-woocommerce-checkout-field-editor');?>">
										<?php do_action('mwb_wcfe_pro_edit_fileds'); ?>
										<span class="delete-field">
											<?php
											if($billing_field_key != 'billing_email'){
												?>
												<input type="checkbox" class="mwb_woo_cfe_switch_class" id="mwb_woo_cfe_switch_<?php echo $i;?>" name="mwb_woo_cfe_switch_enable[<?php echo $billing_field_key; ?>]" <?php if(isset($MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_disable_field']) && array_key_exists($billing_field_key,$MwbWooCfeSavedConditionalFieldsData['mwb_woo_cfe_disable_field'])){ echo "checked='checked'"; }?> /><label class="mwb_woo_cfe_switch_label_class" for="mwb_woo_cfe_switch_<?php echo $i;?>"></label>
												<?php
											}
											?>
										</td>
										<td data-th_attr="<?php _e('Name','mwb-woocommerce-checkout-field-editor');?>"><?php echo $billing_field_key; ?></td>

										<td data-th_attr="<?php _e('label','mwb-woocommerce-checkout-field-editor');?>"><?php if(isset($billing_field_value['label'])){ echo $billing_field_value['label']; }else{ _e('--');}?></td>

										<td data-th_attr="<?php _e('Required','mwb-woocommerce-checkout-field-editor');?>"><img src="<?php if(isset($billing_field_value['required']) && $billing_field_value['required'] == 1){ echo MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/checked.png'; } else{ echo MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL.'admin/images/cancel.png'; }?>"></td>

										<td data-th_attr="<?php _e('Validation','mwb-woocommerce-checkout-field-editor');?>"><?php if(isset($billing_field_value['validate'][0]) && $billing_field_value['validate'][0] != ''){ echo $billing_field_value['validate'][0];}else{ _e('--');}?></td>

										<td width="1%" class="sort ui-sortable-handle"></td>

										<td style="display: none;" class="mwb_woo_cfe_form_data_none" >
											<input type="hidden" name="mwb_woo_cfe_billing_name[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_name" value="<?php echo $billing_field_key; ?>">
											<input type="hidden" name="mwb_woo_cfe_billing_label[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_label" value="<?php if(isset($billing_field_value['label'])){ echo $billing_field_value['label']; }?>">
											<input type="hidden" name="mwb_woo_cfe_billing_required[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_required" value="<?php if(isset($billing_field_value['required'])){ echo $billing_field_value['required']; }else{ echo 0 ;} ?>">
											<input type="hidden" name="mwb_woo_cfe_billing_validate[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_validate" value="<?php if(isset($billing_field_value['validate'][0]) && $billing_field_value['validate'][0] != ''){ echo $billing_field_value['validate'][0]; }?>">
											<input type="hidden" name="mwb_woo_cfe_billing_priority[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_priority" value="<?php if(isset($billing_field_value['priority'])){ echo $billing_field_value['priority']; }?>">

											<input type="hidden" name="mwb_woo_cfe_billing_autocomplete[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_autocomplete" value="<?php if(isset($billing_field_value['autocomplete'])){ echo $billing_field_value['autocomplete']; }?>">
											<?php
											if(isset($billing_field_value['type'])){
												?>
												<input type="hidden" name="mwb_woo_cfe_billing_type[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_type" value="<?php if(isset($billing_field_value['type'])){ echo $billing_field_value['type']; }?>">
												<?php
											}
											if(isset($billing_field_value['options'])){
												
												?>
												<input type="hidden" name="mwb_woo_cfe_billing_filed_selected_option[<?php echo $i; ?>]" id="mwb_woo_cfe_billing_filed_selected_option" value="<?php if(isset($billing_field_value['options'])){ echo implode('|',$billing_field_value['options']); }?>">
												<?php
											}
											?>

										</td>
									</tr>
									<tr class="mwb_woo_cfe_toggle_row" id="child_mwb_woo_cfe_row_<?php echo $i; ?>" data-row_priority="<?php if(isset($billing_field_value['priority'])){ echo $billing_field_value['priority']; }?>" data-index="<?php echo $i; ?>"></tr>
									<?php
									$i++;
									// }
								}
							}
							?>
						</tbody>
						<input type="hidden" name="mwb_wcfe_nonce_verify" id="mwb_wcfe_nonce_verify" value="<?php echo wp_create_nonce('mwb-wcfe-nonce');?>">
						<tfoot>
							<tr>
								<td colspan="6" style="text-align: right; background-color: #fff;">
									<input type="submit" id="mwb_woo_cfe_submit_data" name="mwb_woo_cfe_submit_data" class="button-primary" value="<?php _e('Save','mwb-woocommerce-checkout-field-editor'); ?>" disabled>
									<input type="button" id="mwb_woo_cfe_reset_data" name="mwb_woo_cfe_reset_data" class="button" value="<?php _e('Reset Data','mwb-woocommerce-checkout-field-editor'); ?>">
								</td>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
		</div>
		<?php do_action('mwb_wcfe_add_checkout_billing_field_form'); ?>
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