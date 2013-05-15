<?php
//-------------- Register the menu page ------------------------
function RMFtooltip_admin_menu() {  
    add_options_page("Responsive Mobile-Friendly Tooltip Settings", "Responsive Mobile-Friendly Tooltip", "manage_options", "RMFtooltip-options", "RMFtooltip_admin_page");  
}
add_action('admin_menu', 'RMFtooltip_admin_menu');
//--Creating the page
function RMFtooltip_admin_page () { ?>
	<script type="text/javascript">
		function chkchng(){ //Disable input fields as needed
			if(jQuery('#chkbx_use_custom_css').is(":checked")) {
				jQuery('#chkbx_replace_css').prop('disabled', false);
				jQuery('#textarea_css').prop('disabled', false);
			} else if (!jQuery('#chkbx_use_custom_css').is(":checked")) {
				jQuery('#chkbx_replace_css').prop('disabled', true);
				jQuery('#textarea_css').prop('disabled', true);
			}
		}
	</script>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Responsive Mobile-Friendly Tooltip Settings</h2>
		<form method="post" action="options.php">
			<table class="form-table">
				<tbody>
					<?php settings_fields( 'RMFtooltip_style_settings' );
					do_settings_fields( 'RMFtooltip-options', 'RMFtooltip_style_settings' ); //Output the setting section ?>
				</tbody>
			</table>
			<?php submit_button(); //Output the submit button ?>
		</form>
	</div>
<?php }

//-------------------Creates the hooked fields (outputted above)------------------------
function RMFtooltip_admin_init(){
	add_settings_section('RMFtooltip_style_settings', 'Tooltip Style Settings', 'style_settings_title', 'RMFtooltip-options'); //Register the setting section
	global $RMFtooltip_style_settings;
	$RMFtooltip_style_settings = get_option('RMFtooltip_style_settings'); //Gets old options
	$RMFtooltip_style_settings[chkbx_use_custom_css] = $RMFtooltip_style_settings[chkbx_use_custom_css] ? 'on' : 'off';
	register_setting( 'RMFtooltip_style_settings', 'RMFtooltip_style_settings', 'RMFtooltip_input_processor' ); //Does all the saving =D
		function RMFtooltip_input_processor ($input) { //Process user data
			global $RMFtooltip_style_settings;
			if ( $input[chkbx_use_custom_css] == 'on' && empty($input[textarea_css])) { //Makes sure the textarea isn't empty if it suppose to be full 
				add_settings_error( 'textarea_css', 'textarea_css_empty', "You haven't entered any CSS code, either enter some code or uncheck the use custom CSS box. Your settings will NOT be saved." ); //Output an error
				return $RMFtooltip_style_settings; //Save the old settings again since invalid settings has been entred
			}
			/*----------Save css to file------------*/
			if ($input[chkbx_use_custom_css] == 'on') {
			   if ($input[chkbx_replace_css] == 'on') {
			    	$css_file = $input[textarea_css]; //Writes only the new changes
			    } else {
			  		$css_file = file_get_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.org.css');
				    $css_file .= "\n{$input[textarea_css]}"; //Adds the entered code at the end of the original code
			    }
			} else { //Else writes the original file
				$css_file = file_get_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.org.css'); //Writes only the original file
			}
			$rtnval = file_put_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.css', $css_file, LOCK_EX); //Writes and put the return value to $rtnval for error check

			/*--------- Error handling ----------------*/
			$rtnval = var_export($rtnval, true);
			if ($rtnval > 0) { //Check for the result of the writing process
				add_settings_error( 'textarea_css', 'css_file_success', "CSS file successfully updated ($rtnval bytes were written)", 'updated' );
				return $input; //If written sucessfully sends the data for save in the db
			} elseif ( $rtnval === false) {
				if (!is_dir( dirname(__FILE__) ) or !is_writable( dirname(__FILE__) )) {
				    add_settings_error( 'textarea_css', 'css_file_fail_dir_perm', "It seems the directory doesn't exist or you don't have write permission. Your settings will NOT be saved." );
				    return $RMFtooltip_style_settings; //Save old settings since writing to file failed
				} elseif (is_file(__FILE__) and !is_writable( __FILE__ )) {
				    add_settings_error( 'textarea_css', 'css_file_fail_file_perm', "You don't have permission to write to this file. Your settings will NOT be saved." );
				    return $RMFtooltip_style_settings; //Save old settings since writing to file failed
				} else {
					add_settings_error( 'textarea_css', 'css_file_fail_unkn', "Can't write to the file. Your settings will NOT be saved." );
					return $RMFtooltip_style_settings; //Save old settings since writing to file failed
				}
			}
		}
		/*------------------Adding the input fields--------------------*/
		add_settings_field('chkbx_use_custom_css', '', 'chkbx_use_custom_css_func', 'RMFtooltip-options', 'RMFtooltip_style_settings'); //Create first checbox
			function chkbx_use_custom_css_func () {
				global $RMFtooltip_style_settings; ?>
				<tr valign="top">
					<th scope="row">
						<label for="chkbx_use_custom_css">Use custom CSS?</label>
					</th>
					<td>
						<input type="checkbox" id="chkbx_use_custom_css" onclick="chkchng()" name="RMFtooltip_style_settings[chkbx_use_custom_css]" <?php checked( $RMFtooltip_style_settings[chkbx_use_custom_css], 'on' ); ?> />
						<p class="description">Check if you want to use your own custom stylesheet for the tooltip.</p>
					</td>
				</tr>
				<?php
			}
		add_settings_field('chkbx_replace_css', '', 'chkbx_replace_css_func', 'RMFtooltip-options', 'RMFtooltip_style_settings'); //Create second checkbox
			function chkbx_replace_css_func () {
				global $RMFtooltip_style_settings; ?>
				<tr valign="top">
					<th scope="row">
						<label for="chkbx_replace_css">Replace original CSS?</label>
					</th>
					<td>
						<input type="checkbox" id="chkbx_replace_css" name="RMFtooltip_style_settings[chkbx_replace_css]" <?php checked( $RMFtooltip_style_settings[chkbx_replace_css], 'on' ); ?> <?php disabled( $RMFtooltip_style_settings[chkbx_use_custom_css], 'off'); ?> />
						<p class="description">Check if want to replace the default CSS rules rather than add to them</p>
					</td>
				</tr>
				<?php
			}
		add_settings_field('textarea_css', '', 'textarea_css_func', 'RMFtooltip-options', 'RMFtooltip_style_settings'); //Creates the textarea 
			function textarea_css_func () {
				global $RMFtooltip_style_settings; ?>
				<tr valign="top">
					<th scope="row">
						<label for="textarea_css">Your CSS code</label>
					</th>
					<td>
						<textarea id="textarea_css" class="large-text" name="RMFtooltip_style_settings[textarea_css]" cols="50" rows="15" placeholder="Enter your css code here" <?php disabled( $RMFtooltip_style_settings[chkbx_use_custom_css], 'off'); ?> ><?php echo esc_textarea( $RMFtooltip_style_settings[textarea_css] ); ?></textarea>
					</td>
				</tr>
				<?php
			}
}
add_action('admin_init', 'RMFtooltip_admin_init');