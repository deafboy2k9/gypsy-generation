<?php get_header() ?>
<div id="registration-content" class="clearfix row">
    <div class="columns six">
        <h2>Why?</h2>
        <p>
        Nascetur parturient scelerisque adipiscing elementum! Odio enim sociis. Lectus? 
        Magnis a ac, pulvinar elit cursus hac mattis et turpis duis mattis dignissim lorem 
        tristique sociis, vel platea dolor pulvinar penatibus nisi nunc, 
        nascetur augue rhoncus aliquam tortor, porta a? Vut phasellus habitasse, 
        dignissim magna cursus egestas eros urna in, mus.
        </p>
    </div>
    <div class="columns ten left-column-dashed alpha omega">
    
    	<?php do_action( 'bp_before_register_page' ) ?>
	    
    	<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
	        
	        <?php do_action( 'template_notices' ) ?>
	        
	        <?php do_action( 'bp_before_registration_disabled' ) ?>
			
			<p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>
			
	        <?php do_action( 'bp_after_registration_disabled' ); ?>
	        
	    <?php endif; //end if disabled ?>
	    
	    <?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>
	    
	    	<div>
    	    	<h2>Join Us</h2>
        	    
    	        <?php do_action( 'template_notices' ) ?>
        		
        		<p>Complete this form to request your invite into our community.</p>
    	    
			</div>
    	    
            <form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">
            
            	<?php /***** Basic Account Details ******/ ?>
            	<?php do_action( 'bp_before_account_details_fields' ) ?>
    			<?php $bp_profile_field_ids = array(); ?>
            	<fieldset class="columns five alpha omega row">
            		
            		<h4><?php _e( 'Account Details', 'buddypress' ) ?></h4>
            		<div class="row clearfix">	
                		<label for="signup_username"><?php _e( 'Username', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value() ?>" />
    					<?php do_action( 'bp_signup_username_errors' ) ?>
					</div>
					<div class="row clearfix">
    					<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value() ?>" />
    					<?php do_action( 'bp_signup_email_errors' ) ?>
					</div>
					<div class="row clearfix">
    					<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<input type="password" name="signup_password" id="signup_password" value="" />
    					<?php do_action( 'bp_signup_password_errors' ) ?>
					</div>
					<div class="row clearfix">
    					<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />
    					<?php do_action( 'bp_signup_password_confirm_errors' ) ?>
					</div>    	
            	</fieldset>
            	<?php do_action( 'bp_after_account_details_fields' ) ?>
            	<?php /***** End Basic Account Details ******/ ?>
            	
            	<?php /***** Extra Profile Details ******/ ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>
				
					
				    <?php do_action( 'bp_before_signup_profile_fields' ) ?>
					
						<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile() ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
						<fieldset class="columns five alpha omega row">
						<h4><?php bp_the_profile_group_name(); ?></h4>
						
						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
							<?php 
							if ( bp_get_the_profile_field_is_required() ) :
                            	$bp_profile_field_ids[] = bp_get_the_profile_field_id();
                            endif;
                           	?>
							<div class="editfield row">

								
								<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></label>
									<input type="text" name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>" value="<?php bp_the_profile_field_edit_value() ?>" />
									<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
									
								<?php endif; ?>

								<?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></label>
									<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_edit_value() ?></textarea>
									<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
									
								<?php endif; ?>

								<?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></label>
									<select name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>">
										<?php bp_the_profile_field_options() ?>
									</select>
									<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
									
								<?php endif; ?>

								<?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>

									<label for="<?php bp_the_profile_field_input_name() ?>"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></label>
									<select name="<?php bp_the_profile_field_input_name() ?>" id="<?php bp_the_profile_field_input_name() ?>" multiple="multiple">
										<?php bp_the_profile_field_options() ?>
									</select>
									<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
									
								<?php endif; ?>

								<?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>

									<div class="radio">
										<span class="label"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></span>

										<?php bp_the_profile_field_options() ?>

										<?php if ( !bp_get_the_profile_field_is_required() ) : ?>
											<a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name() ?>' );"><?php _e( 'Clear', 'buddypress' ) ?></a>
										<?php endif; ?>
										<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
										
									</div>

								<?php endif; ?>

								<?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>

									<div class="checkbox">
										<span class="label"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></span>
										<?php bp_the_profile_field_options() ?>
										<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
										
									</div>

								<?php endif; ?>

								<?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>

									<div class="datebox">
									
										<label for="<?php bp_the_profile_field_input_name() ?>_day"><?php bp_the_profile_field_name() ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ) ?><?php endif; ?></label>
										
										<select name="<?php bp_the_profile_field_input_name() ?>_day" id="<?php bp_the_profile_field_input_name() ?>_day">
											<?php bp_the_profile_field_options( 'type=day' ) ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name() ?>_month" id="<?php bp_the_profile_field_input_name() ?>_month">
											<?php bp_the_profile_field_options( 'type=month' ) ?>
										</select>

										<select name="<?php bp_the_profile_field_input_name() ?>_year" id="<?php bp_the_profile_field_input_name() ?>_year">
											<?php bp_the_profile_field_options( 'type=year' ) ?>
										</select>
										<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ) ?>
										
									</div>

								<?php endif; ?>

								<?php do_action( 'bp_custom_profile_edit_fields' ) ?>

								<p class="description"><?php bp_the_profile_field_description() ?></p>
								
							</div>
							
						<?php endwhile; ?>
						</fieldset>
						<br class="clearfix" />
						<?php endwhile; endif; endif; ?>
					
				    <?php do_action( 'bp_after_signup_profile_fields' ) ?>
					<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php $asdf = implode(",", $bp_profile_field_ids); echo $asdf; ?>" />
						
				    <?php /***** End Extra Profile Details ******/ ?>

					<?php do_action( 'bp_custom_signup_steps' ) ?>
					

				<?php endif; //end if xprofile ?>
				
				<?php do_action( 'bp_before_registration_submit_buttons' ) ?>

				<div class="submit columns nine">
					<input type="submit" name="signup_submit" id="signup_submit" value="<?php _e( 'Complete Sign Up', 'buddypress' ) ?>" />
				</div>

				<?php do_action( 'bp_after_registration_submit_buttons' ) ?>

				<?php wp_nonce_field( 'bp_new_signup' ) ?>
				
            </form>
            
        <?php endif;  //end if request-details ?>
        
        <?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

			<h2><?php _e( 'Sign Up Complete!', 'buddypress' ) ?></h2>

	        <?php do_action( 'template_notices' ) ?>
	        <?php do_action( 'bp_before_registration_confirmed' ) ?>

			<?php if ( bp_registration_needs_activation() ) : ?>
					
				<p>Your request to join has successfully been sent out to the community! Now, all you'll have to do is wait for one of our members to invite you in.</p>

				<p>We look forward to you joining us! </p>
			        
			<?php else : ?>
					
				<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ) ?></p>
					
		    <?php endif; ?>

        <?php do_action( 'bp_after_registration_confirmed' ) ?>

		<?php endif; // completed-confirmation signup step ?>
        
        
        
        <?php do_action( 'bp_after_register_page' ) ?>
    </div>
</div>
<?php get_footer() ?>