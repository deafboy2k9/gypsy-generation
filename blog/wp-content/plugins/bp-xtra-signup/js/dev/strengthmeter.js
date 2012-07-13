jQuery(document).ready(function(){
	// add the password strength meter	
	jQuery("#signup_password_confirm").after('<div id="pass-strength-result">' + pwsL10n['title'] + '</div><p>' + pwsL10n['desc'] + '</p>');
	// do the deed
	jQuery('#signup_password').val('').keyup( check_pass_strength );
	jQuery('#signup_password_confirm').val('').keyup( check_pass_strength );

	function check_pass_strength() {
		var pass1 = jQuery('#signup_password').val(), user = jQuery('#signup_username').val(), pass2 = jQuery('#signup_password_confirm').val(), strength;

		jQuery('#pass-strength-result').removeClass('short bad good strong');
		if ( ! pass1 ) {
			jQuery('#pass-strength-result').html( pwsL10n.empty );
			return;
		}

		strength = passwordStrength(pass1, user, pass2);

		switch ( strength ) {
			case 2:
				jQuery('#pass-strength-result').addClass('bad').html( pwsL10n['bad'] );
				break;
			case 3:
				jQuery('#pass-strength-result').addClass('good').html( pwsL10n['good'] );
				break;
			case 4:
				jQuery('#pass-strength-result').addClass('strong').html( pwsL10n['strong'] );
				break;
			case 5:
				jQuery('#pass-strength-result').addClass('short').html( pwsL10n['mismatch'] );
				break;
			default:
				jQuery('#pass-strength-result').addClass('short').html( pwsL10n['short'] );
		}
	}

	function passwordStrength(password1, username, password2) {
		var shortPass = 1, badPass = 2, goodPass = 3, strongPass = 4, mismatch = 5, symbolSize = 0, natLog, score;
	
		// password 1 != password 2
		if ( (password1 != password2) && password2.length > 0)
			return mismatch
	
		//password < 4
		if ( password1.length < 4 )
			return shortPass
	
		//password1 == username
		if ( password1.toLowerCase() == username.toLowerCase() )
			return badPass;
	
		if ( password1.match(/[0-9]/) )
			symbolSize +=10;
		if ( password1.match(/[a-z]/) )
			symbolSize +=26;
		if ( password1.match(/[A-Z]/) )
			symbolSize +=26;
		if ( password1.match(/[^a-zA-Z0-9]/) )
			symbolSize +=31;
	
		natLog = Math.log( Math.pow(symbolSize, password1.length) );
		score = natLog / Math.LN2;
	
		if (score < 40 )
			return badPass
	
		if (score < 56 )
			return goodPass
	
		return strongPass;
	}
});