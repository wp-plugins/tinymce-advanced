<?php
/*
Plugin Name: TinyMCE Advanced
Plugin URI: http://www.laptoptips.ca/projects/tinymce-advanced/
Description: Enables advanced features and plugins in TinyMCE.
Version: 1.0.1
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Released under the GPL, http://www.gnu.org/copyleft/gpl.html

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

function tadv_mce_opt() {
?>
    inline_styles : true,
    paste_create_paragraphs : false,
    paste_create_linebreaks: true,
    paste_auto_cleanup_on_paste : true,
<?php
}

function tdav_css($de) {
$de = get_bloginfo( 'stylesheet_url' ) . ', ' . get_bloginfo( 'wpurl' ) . '/wp-content/plugins/tinymce-advanced/tinymce.css, ' . $de;
return $de;
}

function tadv_mce_plugins($plug) {
    global $is_IE6;
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false ) 
	   $is_IE6 = true;

    $tadv_plugins = array( 'advhr', 'contextmenu', 'print', 'visualchars', 'advimage', 'advlink', 'table', 'xhtmlxtras', 'nonbreaking', 'layer', 'searchreplace', 'fullscreen' );
    
    if( ! $is_IE6 ) $tadv_plugins[] = 'media';
    return array_merge($plug, $tadv_plugins);
}

function tadv_mce_btns($orig) {
    global $extra_btns;
    
    $tadv_btns1 = array( 'bold', 'italic', 'strikethrough', 'underline', 'separator', 'bullist', 'numlist', 'outdent', 'indent', 'separator', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'separator', 'link', 'unlink', 'separator', 'image', 'wp_more', 'separator', 'spellchecker', 'separator', 'search', 'wp_help', 'print', 'separator', 'fullscreen' );
    
    if( is_array($orig) && ! empty($orig) ) {
        $extra_btns = array_diff( $orig, $tadv_btns1 );
    }
    return $tadv_btns1;
}

function tadv_mce_btns2() {
    global $extra_btns, $is_IE6;
    
    $tadv_btns2 = array('styleselect', 'formatselect', 'forecolor', 'separator', 'pastetext', 'pasteword', 'separator', 'removeformat', 'cleanup', 'separator', 'charmap', 'separator', 'undo', 'redo', 'separator', 'wp_adv', 'wp_adv_start', 'tablecontrols', 'separator', 'cite', 'ins', 'del', 'abbr', 'acronym', 'attribs', 'separator', 'insertlayer', 'moveforward', 'movebackward', 'absolute', 'separator', 'advhr', 'wp_adv_end');
    
    if( is_array($extra_btns) && ! empty($extra_btns) ) {
        $extra_btns = array_diff( $extra_btns, $tadv_btns2 );
        if( ! empty($extra_btns) )
            $tadv_btns2 = $extra_btns + $tadv_btns2;
    }
    
    if( ! $is_IE6 ) array_unshift($tadv_btns2, 'media');
    return $tadv_btns2;
}

add_filter('mce_css', 'tdav_css');
add_action('mce_options', 'tadv_mce_opt');
add_filter('mce_plugins', 'tadv_mce_plugins');
add_filter('mce_buttons', 'tadv_mce_btns');
add_filter('mce_buttons_2', 'tadv_mce_btns2');
?>
