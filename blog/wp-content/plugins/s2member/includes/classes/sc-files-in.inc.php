<?php
/**
* Shortcode `[s2File /]` ( inner processing routines ).
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
* @package s2Member\s2File
* @since 110926
*/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");
/**/
if (!class_exists ("c_ws_plugin__s2member_sc_files_in"))
	{
		/**
		* Shortcode `[s2File /]` ( inner processing routines ).
		*
		* @package s2Member\s2File
		* @since 110926
		*/
		class c_ws_plugin__s2member_sc_files_in
			{
				/**
				* Handles the Shortcode for: `[s2File /]`.
				*
				* @package s2Member\s2File
				* @since 110926
				*
				* @attaches-to ``add_shortcode("s2File");``
				*
				* @param array $attr An array of Attributes.
				* @param str $content Content inside the Shortcode.
				* @param str $shortcode The actual Shortcode name itself.
				* @return str Value of requested File Download URL, streamer array element; or null on failure.
				*/
				public static function sc_get_file ($attr = FALSE, $content = FALSE, $shortcode = FALSE)
					{
						eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__s2member_before_sc_get_file", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
						/**/
						$attr = c_ws_plugin__s2member_utils_strings::trim_qts_deep ((array)$attr); /* Force array; trim quote entities. */
						/**/
						$attr = shortcode_atts (array ("download" => "", "download_key" => "", "stream" => "", "inline" => "", "storage" => "", "remote" => "", "ssl" => "", "rewrite" => "", "rewrite_base" => "", "skip_confirmation" => "", "url_to_storage_source" => "", "count_against_user" => "", "check_user" => "", /* Shortcode-specifics » */ "get_streamer_json" => "", "get_streamer_array" => ""), $attr);
						/**/
						eval ('foreach(array_keys(get_defined_vars())as$__v)$__refs[$__v]=&$$__v;');
						do_action ("ws_plugin__s2member_before_sc_get_file_after_shortcode_atts", get_defined_vars ());
						unset ($__refs, $__v); /* Unset defined __refs, __v. */
						/**/
						$get_streamer_json = filter_var ($attr["get_streamer_json"], FILTER_VALIDATE_BOOLEAN); /* Getting streamer? */
						$get_streamer_array = filter_var ($attr["get_streamer_array"], FILTER_VALIDATE_BOOLEAN); /* Streamer? */
						$get_streamer_json = $get_streamer_array = ($get_streamer_array || $get_streamer_json) ? true : false;
						/**/
						foreach ($attr as $key => $value) /* Now we need to go through and a `file_` prefix  to certain Attribute keys, for compatibility. */
							if (strlen ($value) && in_array ($key, array ("download", "download_key", "stream", "inline", "storage", "remote", "ssl", "rewrite", "rewrite_base")))
								$config["file_" . $key] = $value; /* Set prefixed config parameter here so we can pass properly in ``$config`` array. */
							else if (strlen ($value) && !in_array ($key, array ("get_streamer_json", "get_streamer_array"))) /* Else, exclude? */
								$config[$key] = $value;
						/**/
						unset ($key, $value); /* Ditch these now. We don't want these bleeding into Hooks/Filters anyway. */
						/**/
						if (!empty ($config) && isset ($config["file_download"])) /* Looking for a File Download URL? */
							{
								$_get = c_ws_plugin__s2member_files::create_file_download_url ($config, $get_streamer_array);
								/**/
								if ($get_streamer_array && $get_streamer_json && is_array ($_get))
									$get = json_encode ($_get);
								/**/
								else if ($get_streamer_array && $get_streamer_json)
									$get = "null"; /* Null object value. */
								/**/
								else if (!empty ($_get)) /* Else ``$get``. */
									$get = $_get; /* Default return. */
							}
						/**/
						return apply_filters ("ws_plugin__s2member_sc_get_file", ((isset ($get)) ? $get : null), get_defined_vars ());
					}
			}
	}
?>