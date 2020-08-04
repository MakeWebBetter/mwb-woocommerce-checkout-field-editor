(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $(document).ready(function() {
	 	$('#mwb_wcfe_custom_field_notice').hide();
	 	
	 	$('.mwb_woo_cfe_save_data_for_billing_table tbody').sortable({
	 		items : 'tr',
	 		cursor : 'move',
	 		axis : 'y',
	 		handle : 'td.sort',
	 		scrollSensitivity:40,
	 		start : function(e,ui){

	 			ui.item.startPos = ui.item.index();
	 		}, 
	 		helper : function(e,ui){
	 			ui.children().each(function() {
	 				$(this).width($(this).width());
	 			});
	 			return ui;
	 		},
	 		update : function(e,ui){
	 			var child = '';
	 			if(ui.item.startPos < ui.item.index()){
	 				child = $(document).find('#child_'+ui.item.context.id).remove().clone();
	 				$(document).find('#'+ui.item.context.id).after(child);
	 				var classname = $(document).find('#child_' + ui.item.context.id).next().attr('id');
	 				child = $(document).find('#'+classname).remove().clone();
					// console.log(child);
					$(document).find('#'+ui.item.context.id).before(child);  
				}
				else{
					child = $(document).find('#child_'+ ui.item.context.id).remove().clone();
					$(document).find('#'+ui.item.context.id).after(child);
				}
				$(document).find('#mwb_woo_cfe_submit_data').prop('disabled',false);
			}
		}); 

	 	$(".mwb_woo_cfe_save_data_for_billing_table tbody").on("sortstart", function( event, ui ){
	 		ui.item.css('background-color','#dadada');										
	 	});

	 	$('.mwb_woo_cfe_switch_class').on('change',function(){
	 		$(document).find('#mwb_woo_cfe_submit_data').prop('disabled',false);
	 	});
	 });

	 //JS for reset billing fields.
	 $(document).on('click','#mwb_woo_cfe_reset_data',function(){
	 	var mwb_user_conform_msg = window.confirm(license_ajax_object_free.delete_billin_record);
	 	if(mwb_user_conform_msg){
	 		$.ajax({
	 			url : license_ajax_object_free.ajaxurl,
	 			type : 'POST',
	 			cache : false,
	 			data : {
	 				action : 'mwb_woo_cfe_reset_field_data',
	 				mwb_reset_nonce : license_ajax_object_free.license_nonce
	 			},success : function(response){
	 				if(response == 'success'){
	 					window.location.reload();
	 				}
	 			}
	 		});
	 	}
	 });

})( jQuery );
