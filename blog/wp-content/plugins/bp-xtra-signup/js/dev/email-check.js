jQuery(document).ready(function(){
	jQuery(signup_mail_field).wrap("<div id='email_checker'></div> ");
	jQuery("#email_checker").append("<span class='loading' style='display:none'></span>")
	jQuery("#email_checker").append("<span id='email-info'></span> ");
	
	jQuery(signup_mail_field).bind("blur",function(){
		jQuery("#email_checker #email-info").css({display:'none'});
		jQuery("#email_checker span.loading").css({display:'block'});
	
		var email = jQuery(signup_mail_field).val();
		
		jQuery.post( ajaxurl, {
			action: 'check_email',
			'cookie': encodeURIComponent(document.cookie),
			'email': email
		},
		function(response){
			var resp = jQuery.parseJSON(response);
			
			if(resp.code == 'success')
				show_email_message(resp.message,0);
			else
				show_email_message(resp.message,1);
		});
	});
	
	function show_email_message(msg,is_error)	{
		jQuery("#email_checker #email-info").removeClass().css({display:'block'});
		jQuery("#email_checker span.loading").css({display:'none'});
		jQuery("#email_checker #email-info").empty().html(msg);
		
		if(is_error)
			jQuery("#email_checker #email-info").addClass("error");
		else
			jQuery("#email_checker #email-info").addClass("available");
	}
});