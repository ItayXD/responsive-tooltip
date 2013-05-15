<?php
/*
   Plugin Name: Responsive Mobile-Friendly Tooltip
   Plugin URI: https://github.com/ItayXD/responsive-tooltip
   Description: Responsive and mobile-friendly tooltip to present tiny amount of hidden content - the tip.
   Version: 1.0
   Author: ItayXD; Osvaldas;
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
		$return = "<abbr title='".$tip."' rel='tooltip'>".$content."</abbr>";
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

add_action( 'wp_enqueue_scripts', 'RMFtooltip_stylesheet_js' );

function RMFtooltip_stylesheet_js() {
	wp_register_style( 'RMFtooltip-css', plugins_url('responsive-tooltip.css', __FILE__) );
	wp_enqueue_style( 'RMFtooltip-css' );
	wp_register_script( 'RMFtooltip-js', plugins_url('responsive-tooltip.js', __FILE__), 'jquery', null, true );
	wp_enqueue_script( 'RMFtooltip-js' );
}


