<?php
/*
   Plugin Name: Responsive Mobile-Friendly Tooltip
   Plugin URI: https://github.com/ItayXD/responsive-tooltip
   Description: Responsive and mobile-friendly tooltip to present tiny amount of hidden content - the tip.
   Version: 1.6.6
   Author: ItayXD
   Author URI: itayxd.com
   License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/*-------------------- Adds the shortcode --------------------*/
function RMFtooltip_shortcode_function($atts, $content = null) {
	extract(shortcode_atts(array(
		'tip' => null,
		'hover' => null,   // deprecated
		'invert' => false,// deprecated
	), $atts));

	// deprecated
	if ($invert) {
		list ($tip, $hover) = array ($hover, $tip);
	}
	//deprecated
	if ($content && ($tip || $hover)) {
		$content = do_shortcode( $content );
		
		if ($hover) { // swap tip/content
			list ($tip, $content) = array ($content, $hover);
		}

		$return = "<abbr title='".esc_attr( $tip )."' rel='tooltip'>".$content."</abbr>";
		return $return;
	}
}
function RMFtooltip_register_shortcode(){
   add_shortcode('tooltip', 'RMFtooltip_shortcode_function');
}
add_action( 'init', 'RMFtooltip_register_shortcode');


/*-------------------- Adds the TinyMCE plugin --------------------*/

function RMFtooltip_register_button( $buttons ) {
   array_push( $buttons, "|", "RMFtooltip" );
   return $buttons;
}
function RMFtooltip_add_plugin( $plugin_array ) {
   $plugin_array['RMFtooltip'] = plugins_url( 'responsive-tooltip-tinyMCE.js' , __FILE__ );
   return $plugin_array;
}
function RMFtooltip_button() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'RMFtooltip_add_plugin' );
      add_filter( 'mce_buttons', 'RMFtooltip_register_button' );
   }

}
add_action('init', 'RMFtooltip_button');

/*-------------------- Adds admin options page  --------------------*/
include_once 'responsive-tooltip-admin-page.php';

/*-------------------- Adds needed stylesheets and Js  --------------------*/
//For the front-site
add_action( 'wp_enqueue_scripts', 'RMFtooltip_stylesheet_js' );

function RMFtooltip_stylesheet_js() {
	wp_register_style( 'RMFtooltip-css', plugins_url('responsive-tooltip.css', __FILE__) );
	wp_enqueue_style( 'RMFtooltip-css' );
	wp_register_script( 'RMFtooltip-js', plugins_url('responsive-tooltip.js', __FILE__), 'jquery', null, true );
	wp_enqueue_script( 'RMFtooltip-js' );
}
//For admin panel
add_action( 'admin_enqueue_scripts', 'RMFtooltip_admin__stylesheet' );

function RMFtooltip_admin__stylesheet() {
   wp_register_style( 'RMFtooltip-admin-css', plugins_url('responsive-tooltip-dialog.css', __FILE__) );
   wp_enqueue_style( 'RMFtooltip-admin-css' );
}
/*-------------------- Version Handling --------------------*/
$c_version = 1.66; // Current version
$o_version = get_option('RMFtooltip_version'); //Gets old version
if ( $c_version > $o_version ) {
   update_option('RMFtooltip_version', $c_version);
   $RMFtooltip_style_settings = get_option('RMFtooltip_style_settings'); //Genrate user's custom CSS file
   if ($RMFtooltip_style_settings['chkbx_use_custom_css'] == 'on') {
      if ($RMFtooltip_style_settings['chkbx_replace_css'] == 'on') {
         $css_file = $RMFtooltip_style_settings['textarea_css']; //Writes only the new changes
       } else {
         $css_file = file_get_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.org.css');
          $css_file .= "\n{$RMFtooltip_style_settings['textarea_css']}"; //Adds the entered code at the end of the original code
       }
   } else { //Else writes the original file
      $css_file = file_get_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.org.css'); //Writes only the original file
   }
   file_put_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.css', $css_file, LOCK_EX);
}

/*-------------------------------TinyMCE Dialog-------------------------------*/

function rmf_tooltip_dialog() { //Creates the dialog (HTML)
      ?>
      <div id="rmf-tooltip-backdrop" style="display: none"></div>
      <div id="rmf-tooltip-wrap" class="wp-core-ui" style="display: none">
      <form id="rmf-tooltip" tabindex="-1">
      <div id="tooltip-modal-title">
         <?php _e( 'Tooltip wizard' ) ?>
         <button type="button" id="rmf-tooltip-close"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></button>
      </div>
      <div id="tip-creator">
         <div id="tip-options">
            <p class="howto"><?php _e( 'The tip will pop up when the text is hovered' ); ?></p>
            <div>
               <label><span><?php _e( 'The tip' ); ?></span><input id="rmf-tooltip-tip" type="text" /></label>
            </div>
            <div class="rmf-tooltip-text-field">
               <label><span><?php _e( 'The base text' ); ?></span><input id="rmf-tooltip-text" type="text" /></label>
            </div>
         </div>
      </div>
      <div class="submitbox">
         <div id="rmf-tooltip-cancel">
            <a class="submitdelete deletion" href="#"><?php _e( 'Cancel' ); ?></a>
         </div>
         <div id="rmf-tooltip-update">
            <input type="submit" value="<?php esc_attr_e( 'Add Tip' ); ?>" class="button button-primary" id="rmf-tooltip-submit" name="rmf-tooltip-submit">
         </div>
      </div>
      </form>
      </div>
      <?php
}
add_action( 'after_wp_tiny_mce', 'rmf_tooltip_dialog' ); //Runs after tiny mce loads
