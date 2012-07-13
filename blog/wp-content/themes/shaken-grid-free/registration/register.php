<?php get_header( 'buddypress' ) ?>
	<?php print_r($_POST); ?>
	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_register_page' ) ?>

		<div class="page" id="register-page">

			<form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

			<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<?php do_action( 'template_notices' ) ?>
				<?php do_action( 'bp_before_registration_disabled' ) ?>

					<p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>

				<?php do_action( 'bp_after_registration_disabled' ); ?>
			<?php endif; // registration-disabled signup setp ?>

			<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

								

				<div class="register-section" id="basic-details-section">

					<h2>Why Join?</h2>
					<p>Et risus. Lorem vel odio mattis. Cum! Augue, vel a, ac lundium ac vut? Platea ac nisi magna! Nec elit urna! In tincidunt scelerisque! Non nascetur odio, diam dapibus ut a in magna sagittis sociis et urna purus vel quis placerat quis urna odio, pid. Habitasse nisi aliquam. Magnis! Mus.</p>

<p>Magnis nunc ut? Parturient natoque magna et tempor pulvinar! Rhoncus proin turpis, et, placerat, arcu? Et mauris nascetur tortor, magna risus, vel, et facilisis dictumst integer natoque amet auctor tincidunt dapibus nisi amet platea, mus. Vel. Dictumst, a elementum, ac etiam. Sed tincidunt urna mattis! Porta porttitor. Vel pulvinar.</p>

<p>Montes etiam dictumst porttitor! Enim et facilisis dis ridiculus sed ut, pulvinar ut. Ultrices enim elementum nisi nunc natoque, hac adipiscing quis nec lectus placerat, aenean porta eu nisi et cursus, sit, nec, augue, ac, porttitor auctor? Aliquet vut! Dis eros, urna duis, mauris, rhoncus sed tincidunt? A vut.</p>

<p>Mattis, arcu, pid ac dolor eu scelerisque et risus in a, augue porta non a adipiscing enim? Scelerisque sociis. Placerat? Eros adipiscing aliquet! Adipiscing. Amet augue turpis! Phasellus urna? Porttitor diam! Aenean urna amet mus duis, mattis nunc vut. Elit pulvinar dolor diam turpis duis rhoncus nunc! Penatibus dignissim.</p>

				</div><!-- #basic-details-section -->

				<?php /***** Extra Profile Details ******/ ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>

					<?php do_action( 'bp_before_signup_profile_fields' ) ?>

					<div class="register-section" id="profile-details-section">

						<div>
            	    		<h2>Join Us</h2>
                	    
                	        <?php do_action( 'template_notices' ) ?>
                    		
                    		<p>Complete this form to request your invite into our community.</p>
                    		
                    		<p>* = Required field</p>
        	    
    					</div>
					<fieldset>
    					
                	    <?php /***** Basic Account Details ******/ ?>
    					<?php do_action( 'bp_before_account_details_fields' ) ?>
    					<?php $bp_profile_field_ids = array(); ?>
    					<h4><?php _e( 'Account Details', 'buddypress' ) ?></h4>
    
    					<label for="signup_username"><?php _e( 'Username', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<?php do_action( 'bp_signup_username_errors' ) ?>
    					<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value() ?>" />
    
    					<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<?php do_action( 'bp_signup_email_errors' ) ?>
    					<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value() ?>" />
    
    					<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<?php do_action( 'bp_signup_password_errors' ) ?>
    					<input type="password" name="signup_password" id="signup_password" value="" />
    
    					<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ) ?> <?php _e( '(required)', 'buddypress' ) ?></label>
    					<?php do_action( 'bp_signup_password_confirm_errors' ) ?>
    					<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />
					
					</fieldset>
					<?php do_action( 'bp_after_account_details_fields' ) ?>

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


				<?php endif; ?>

				<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?> 
				
				<?php endif; ?>

				<?php do_action( 'bp_before_registration_submit_buttons' ) ?>

				<div class="submit">
					<input type="submit" name="signup_submit" id="signup_submit" value="<?php _e( 'Complete Sign Up', 'buddypress' ) ?>" />
				</div>

				<?php do_action( 'bp_after_registration_submit_buttons' ) ?>

				<?php wp_nonce_field( 'bp_new_signup' ) ?>

			<?php endif; // request-details signup step ?>

			<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

				<h2><?php _e( 'Sign Up Complete!', 'buddypress' ) ?></h2>

				<?php do_action( 'template_notices' ) ?>
				<?php do_action( 'bp_before_registration_confirmed' ) ?>

				<?php if ( bp_registration_needs_activation() ) : ?>
					<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'buddypress' ) ?></p>
				<?php else : ?>
					<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ) ?></p>
				<?php endif; ?>
				
				<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
				
				<fieldset class="columns five alpha omega row">
					<h4><?php _e( 'Your Current Avatar', 'buddypress' ) ?></h4> 
				 	<p><?php _e( "We've fetched an avatar for your new account. If you'd like to change this, why not upload a new one?", 'buddypress' ) ?></p>
				 	
				 	<div id="signup-avatar"> 
                            <?php bp_signup_avatar() ?> 
                    </div> 

                    <p> 
                            <input type="file" name="file" id="file" /> 
                            <input type="submit" name="upload" id="upload" value="<?php _e( 'Upload Image', 'buddypress' ) ?>" /> 
                            <input type="hidden" name="action" id="action" value="bp_avatar_upload" /> 
                            <input type="hidden" name="signup_email" id="signup_email" value="<?php bp_signup_email_value() ?>" /> 
                            <input type="hidden" name="signup_username" id="signup_username" value="<?php bp_signup_username_value() ?>" /> 
                    </p> 

                    <?php wp_nonce_field( 'bp_avatar_upload' ) ?> 
                    
				</fieldset>
				<?php endif; ?>
				
				<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?> 
	 	 		<fieldset class="columns five alpha omega row">
                        <h3><?php _e( 'Crop Your New Avatar', 'buddypress' ) ?></h3> 

                        <img src="<?php bp_avatar_to_crop() ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ) ?>" /> 

                        <div id="avatar-crop-pane"> 
                                <img src="<?php bp_avatar_to_crop() ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ) ?>" /> 
                        </div> 

                        <input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e( 'Crop Image', 'buddypress' ) ?>" /> 

                        <input type="hidden" name="signup_email" id="signup_email" value="<?php bp_signup_email_value() ?>" /> 
                        <input type="hidden" name="signup_username" id="signup_username" value="<?php bp_signup_username_value() ?>" /> 
                        <input type="hidden" name="signup_avatar_dir" id="signup_avatar_dir" value="<?php bp_signup_avatar_dir_value() ?>" /> 

                        <input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src() ?>" /> 
                        <input type="hidden" id="x" name="x" /> 
                        <input type="hidden" id="y" name="y" /> 
                        <input type="hidden" id="w" name="w" /> 
                        <input type="hidden" id="h" name="h" /> 

                        <?php wp_nonce_field( 'bp_avatar_cropstore' ) ?> 
				</fieldset>
                <?php endif; ?>

				<?php do_action( 'bp_after_registration_confirmed' ) ?>

			<?php endif; // completed-confirmation signup step ?>

			<?php do_action( 'bp_custom_signup_steps' ) ?>

			</form>

		</div>

		<?php do_action( 'bp_after_register_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->
	<script type="text/javascript">
		jQuery(document).ready( function() {
			if ( jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show') )
				jQuery('div#blog-details').toggle();

			jQuery( 'input#signup_with_blog' ).click( function() {
				jQuery('div#blog-details').fadeOut().toggle();
			});
		});
	</script>

<?php get_footer( 'buddypress' ) ?>