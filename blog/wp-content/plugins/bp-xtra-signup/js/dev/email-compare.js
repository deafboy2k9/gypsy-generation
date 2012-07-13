jQuery(document).ready(function(){
	jQuery("input#signup_email").bind("blur",function(){
		var first_value = jQuery("input#signup_email_first").val();
		var second_value = jQuery("input#signup_email").val();
		
		if( first_value != second_value )
			jQuery('input#signup_email').after( '<span id="compare-info" class="error">'+ compare_error +'</span>' );
	});
});