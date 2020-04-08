jQuery('document').ready(function(){
	jQuery('#cielo_cc_type').change(function(){
		var bandeira = jQuery(this).val();
		if( bandeira == 'DC' || bandeira == 'EL' ){
			jQuery('#cielo_cc_type').removeClass('validate-cc-type-select');
			jQuery('#cielo_cc_type').removeClass('validation-failed');
			jQuery('#cielo_cc_number').removeClass('validate-cc-number');
			jQuery('#cielo_cc_number').removeClass('validate-cc-type');
			jQuery('#cielo_cc_number').removeClass('validation-failed');
			jQuery('#cielo_cc_cid').removeClass('validate-cc-cvn');
			jQuery('#cielo_cc_cid').removeClass('validation-failed');
		}else{
			jQuery('#cielo_cc_type').addClass('validate-cc-type-select');
			jQuery('#cielo_cc_number').addClass('validate-cc-number');
			jQuery('#cielo_cc_number').addClass('validate-cc-type');
			jQuery('#cielo_cc_cid').addClass('validate-cc-cvn');
		}
	});
});