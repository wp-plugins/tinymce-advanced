<?php
/*
 * Helper functions for changing the visual editor appearance.
 *
 * version 0.1
 *
 * To enable: add this directory to your theme's folder,
 * edit editor-styles.css then add the user selectable class names
 * to the $classes array below and include this file in your theme's
 * functions.php file by adding 

	if ( is_admin() ) {
		include('tinymce-kit/tinymce-kit.php');
	}

 * If your theme has a settings page, you can also add an option so the user
 * can enable or disable this: if ( is_admin() && get_option('style_the_editor') ) ...
 *
 * @package TinyMCE Kit
 *
 * Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */


// Apply styles to the visual editor

add_filter('mce_css', 'mcekit_editor_style');
function mcekit_editor_style($url) {

	if ( !empty($url) )
		$url .= ',';

	// Change the path here if using different directories
	$url .= trailingslashit( get_stylesheet_directory_uri() ) . 'tinymce-kit/editor-style.css';

	return $url;
}


// Change TinyMCE's settings

/**
 * Add the "Styles" drop-down in the editor.
 * This filter will add it to the beginning of the second row of buttons.
 * To add to another place see function wp_tiny_mce() in wp-admin/includes/post.php
 */
add_filter('mce_buttons_2', 'mcekit_editor_buttons');
function mcekit_editor_buttons($buttons) {

	array_unshift($buttons, 'styleselect');

	return $buttons;
}

/**
 * Set the CSS classes in the Styles drop-down in the editor.
 * These classes can be added by the users and should be defined in your main style.css file too.
 * This usually works well with "inline" type of styles like color, font, text-decoration, etc.
 */
add_filter('tiny_mce_before_init', 'mcekit_editor_settings');
function mcekit_editor_settings($settings) {

	if ( !empty($settings['theme_advanced_styles']) )
		$settings['theme_advanced_styles'] .= ';';
	else
		$settings['theme_advanced_styles'] = '';

	/**
	 * The format for this setting is "Name to display=class-name;".
	 * More info: http://wiki.moxiecode.com/index.php/TinyMCE:Configuration/theme_advanced_styles
	 *
	 * To be able to translate the class names they can be set in a PHP array (to keep them readable)
	 * and then converted to TinyMCE's format. You will need to replace 'tinymce-kit' with your theme's textdomain.
	 */
	$classes = array(
		__('Attention', 'tinymce-kit') => 'attention',
		__('Bigger', 'tinymce-kit') => 'bigger',
		__('Smaller', 'tinymce-kit') => 'smaller'
	);

	$class_settings = '';
	foreach ( $classes as $name => $value ) {
		$class_settings .= "{$name}={$value};";
	}

	$settings['theme_advanced_styles'] .= trim($class_settings, '; ');

	return $settings;
}
