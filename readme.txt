=== Plugin Name ===
Contributors: ItayXD
Tags: Tooltip, tinyMCE, responsive, mobile-friendly
Requires at least: 3.0.1
Tested up to: 4.3.1
Stable tag: 1.6.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that helps you create responsive and mobile-friendly tooltip to present tiny amount of hidden content - the tip.

== Description ==

tooltips are used to present a tiny amount of hidden content (mainly explanatory, so-called tips), that pops up when user moves a cursor over or clicks (less common) on a special target.

###Key Features
*	It's responsive. It relies on a maximum width value when viewed on large screens, adopts to narrow environments and picks the best viewable position relatively to the target (top, bottom; left, center, right).
*	It's mobile-friendly. It pops up when a call-to-action button is tapped and disappears when tapped on the tooltip itself.
*	It's HTML formatting capable. Need to write some words in italic or so? No problem, this will work out.
*	It's extremely easy to use: A tooltip button in added to the default WordPress editor, all you have to do it click it and fill the pop-up dialog, the rest is taken care of automatically.

###Advance
*	The button adds a WordPress short-code, if you want the tip to be HTML formated (avoid block level elements) you can just wrap it with [tooltip tip="<your tip>"]<your text>[/tooltip] in tinyMCE.
*	You can also assign the attribute rel="tooltip" and title="Enter your tip here" to any of body tags in HTML file where you want the tooltip to pop up when called.
*	You can change the pop-up look by editing responsive-tooltip.css. change it to what ever suits your website best!

== Installation ==

1. Upload the unzipped folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Check out your new button on the WordPress editor.	

== Frequently Asked Questions ==

Non I can think of, contact for help.

== Screenshots ==
1. The tooltip in action.
2. The editor dialog.

== Changelog ==

= 1.6.5 =
* Fix admin panel.

= 1.6.5 =
* Fix arrow in down position.


= 1.6.2 =
* Fix up position.

= 1.6 =
* Both "Invert" and "Hover" attributes are deprecated.
* Hopefully all bugs fixed.
* Dialog is working again.

= 1.5.2 =
* added the ajax folder we.. lost

= 1.5.1 =
*	Allow the "hover" attribute to provide nested shortcode available in bubble content
*	"invert" attribute is still available but it's deprecated

= 1.5 =
*	Allow the "invert" attribute to provide nested shortcode available in bubble content

= 1.4 =
*	Nested Shortcodes in tooltip base content (not the bubble content)

= 1.3 =
*	Bug fix- Dialog underlay.

= 1.2 =
*	Bug fix- Fixed the blinking\flashing tip (Hopefully).

= 1.1 =
*	Bug fix - fixed problem when using '.

= 1.0 =
*	Adds CSS editor and style options (image in tooltip coming soon).

= 0.2 =
*	Bug fix - fix tip flashing when the target (the content to be hovered) is a block level element.

= 0.1 =
*	first version!

== Upgrade Notice ==

= 0.1 =
First version, check it out.
