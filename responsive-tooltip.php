<?php
/*
   Plugin Name: Responsive Mobile-Friendly Tooltip
   Plugin URI: https://github.com/ItayXD/responsive-tooltip
   Description: Responsive and mobile-friendly tooltip to present tiny amount of hidden content - the tip.
   Version: 1.3
   Author: ItayXD;
   Author URI: itayxd.com
   License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/*-------------------- Adds the shortcode --------------------*/

function RMFtooltip_shortcode_function($atts, $content = null) {
	extract(shortcode_atts(array(
	      'tip' => null,
	 ), $atts));
	if ($content && $tip) {
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

 /*  if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {*/
      add_filter( 'mce_external_plugins', 'RMFtooltip_add_plugin' );
      add_filter( 'mce_buttons', 'RMFtooltip_register_button' );
//   }

}
add_action('init', 'RMFtooltip_button');

/*-------------------- Adds admin options page  --------------------*/
include_once 'responsive-tooltip-admin-page.php';

/*-------------------- Adds needed stylesheets and Js  --------------------*/

add_action( 'wp_enqueue_scripts', 'RMFtooltip_stylesheet_js' );

function RMFtooltip_stylesheet_js() {
	wp_register_style( 'RMFtooltip-css', plugins_url('responsive-tooltip.css', __FILE__) );
	wp_enqueue_style( 'RMFtooltip-css' );
	wp_register_script( 'RMFtooltip-js', plugins_url('responsive-tooltip.js', __FILE__), 'jquery', null, true );
	wp_enqueue_script( 'RMFtooltip-js' );
}
/*-------------------- Version Handling --------------------*/
$c_version = 1.2; // Current version
$o_version = get_option(RMFtooltip_version); //Gets old version
if ( $c_version > $o_version ) {
   update_option('RMFtooltip_version', $c_version);
   $RMFtooltip_style_settings = get_option('RMFtooltip_style_settings'); //Genrate user's custom CSS file
   if ($RMFtooltip_style_settings[chkbx_use_custom_css] == 'on') {
      if ($RMFtooltip_style_settings[chkbx_replace_css] == 'on') {
         $css_file = $RMFtooltip_style_settings[textarea_css]; //Writes only the new changes
       } else {
         $css_file = file_get_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.org.css');
          $css_file .= "\n{$RMFtooltip_style_settings[textarea_css]}"; //Adds the entered code at the end of the original code
       }
   } else { //Else writes the original file
      $css_file = file_get_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.org.css'); //Writes only the original file
   }
   file_put_contents(plugin_dir_path(__FILE__) . 'responsive-tooltip.css', $css_file, LOCK_EX);
}