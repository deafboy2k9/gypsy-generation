<?php
/**
* Roles/Capabilities.
*
* Copyright: © 2009-2011
* {@link http://www.websharks-inc.com/ WebSharks, Inc.}
* ( coded in the USA )
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package s2Member\Roles_Caps
* @since 110524RC
*/
if(realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
/**/
if(!class_exists("c_ws_plugin__s2member_roles_caps"))
	{
		/**
		* Roles/Capabilities.
		*
		* @package s2Member\Roles_Caps
		* @since 110524RC
		*/
		class c_ws_plugin__s2member_roles_caps
			{
				/**
				* Configures Roles/Capabilities.
				*
				* @package s2Member\Roles_Caps
				* @since 110524RC
				*
				* @return null
				*
				* @todo Finalize support for unlimited Levels/Roles.
				* @todo Finalize support for independent Capabilities.
				*/
				public static function config_roles()
					{
						do_action("ws_plugin__s2member_before_config_roles", get_defined_vars());
						/**/
						if(!apply_filters("ws_plugin__s2member_lock_roles_caps", false))
							{
								c_ws_plugin__s2member_roles_caps::unlink_roles();
								/**/
								if(function_exists("bbp_get_caps_for_role"))
									foreach(bbp_get_caps_for_role("subscriber") as $bbp_cap)
										$bbp_caps[$bbp_cap] = true;
								/**/
								if(0 === 0) /* Subscriber Role is required by s2Member. */
									{
										$caps = array("read" => true, "level_0" => true);
										$caps = array_merge($caps, array("access_s2member_level0" => true));
										$caps = (!empty($bbp_caps)) ? array_merge($caps, $bbp_caps) : $caps;
										/**/
										if(!($role = &get_role("subscriber")))
											{
												add_role("subscriber", "Subscriber");
												$role = &get_role("subscriber");
											}
										/**/
										foreach(array_keys($caps) as $cap)
											$role->add_cap($cap);
									}
								/**/
								for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
									{
										for($i = 0, $caps = array("read" => true, "level_0" => true); $i <= $n; $i++)
											$caps = array_merge($caps, array("access_s2member_level".$i => true));
										$caps = (!empty($bbp_caps)) ? array_merge($caps, $bbp_caps) : $caps;
										/**/
										if(!($role = &get_role("s2member_level".$n)))
											{
												add_role("s2member_level".$n, "s2Member Level ".$n);
												$role = &get_role("s2member_level".$n);
											}
										/**/
										foreach(array_keys($caps) as $cap)
											$role->add_cap($cap);
									}
								/**/
								foreach(array("administrator", "editor", "author", "contributor", "bbp_moderator") as $role)
									{
										if(($role = &get_role($role)))
											for($n = 0; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["levels"]; $n++)
												$role->add_cap("access_s2member_level".$n);
									}
							}
						/**/
						do_action("ws_plugin__s2member_after_config_roles", get_defined_vars());
						/**/
						return; /* Return for uniformity. */
					}
				/**
				* Unlinks Roles/Capabilities.
				*
				* @package s2Member\Roles_Caps
				* @since 110524RC
				*
				* @return null
				*/
				public static function unlink_roles()
					{
						do_action("ws_plugin__s2member_before_unlink_roles", get_defined_vars());
						/**/
						if(!apply_filters("ws_plugin__s2member_lock_roles_caps", false))
							{
								if(($role = &get_role("subscriber")))
									$role->remove_cap("access_s2member_level0");
								/**/
								for($n = 1; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["max_levels"]; $n++)
									remove_role("s2member_level".$n);
								/**/
								foreach(array("administrator", "editor", "author", "contributor", "bbp_moderator") as $role)
									{
										if(($role = &get_role($role)))
											for($n = 0; $n <= $GLOBALS["WS_PLUGIN__"]["s2member"]["c"]["max_levels"]; $n++)
												$role->remove_cap("access_s2member_level".$n);
									}
							}
						/**/
						do_action("ws_plugin__s2member_after_unlink_roles", get_defined_vars());
						/**/
						return; /* Return for uniformity. */
					}
				/**
				* Updates Roles/Capabilities via AJAX.
				*
				* @package s2Member\Roles_Caps
				* @since 110524RC
				*
				* @attaches-to ``add_action("wp_ajax_ws_plugin__s2member_update_roles_via_ajax");``
				*
				* @return null Exits script execution after output for AJAX caller.
				*/
				public static function update_roles_via_ajax()
					{
						do_action("ws_plugin__s2member_before_update_roles_via_ajax", get_defined_vars());
						/**/
						status_header(200); /* Send a 200 OK status header. */
						header("Content-Type: text/plain; charset=utf-8"); /* Content-Type with UTF-8. */
						eval('while (@ob_end_clean ());'); /* End/clean all output buffers that may exist. */
						/**/
						if(current_user_can("create_users")) /* Check priveledges. Ability to create Users? */
							/**/
							if(!empty($_POST["ws_plugin__s2member_update_roles_via_ajax"]))
								if(($nonce = $_POST["ws_plugin__s2member_update_roles_via_ajax"]))
									if(wp_verify_nonce($nonce, "ws-plugin--s2member-update-roles-via-ajax"))
										/**/
										if(!apply_filters("ws_plugin__s2member_lock_roles_caps", false))
											{
												c_ws_plugin__s2member_roles_caps::config_roles();
												$success = true; /* Roles updated. */
											}
										else /* Else flag as having been locked here. */
											$locked = true;
						/**/
						exit(apply_filters("ws_plugin__s2member_update_roles_via_ajax", /* Also handle ``$locked`` here. */
						((isset($success) && $success) ? "1" : ((isset($locked) && $locked) ? "l" : "0")), get_defined_vars()));
					}
			}
	}
?>