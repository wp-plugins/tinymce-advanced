<?php
/*
Plugin Name: TinyMCE Advanced
Plugin URI: http://www.laptoptips.ca/projects/tinymce-advanced/
Description: Enables advanced features and plugins in TinyMCE.
Version: 3.0.1
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Some code and ideas from WordPress(http://wordpress.org/). The options page for this plugin uses Prototype.js by Sam Stephenson(http://prototype.conio.net/) and Scriptaculous by Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us). The Javascript files have been compressed and concatenated for faster loading.

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

if ( ! function_exists('tadv_admin_head') ) {
	function tadv_admin_head() { ?>

<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/tinymce-advanced/js/tadv-all.js?ver=1.7.0-3.0"></script>
<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/tinymce-advanced/css/tadv-styles.css?ver=3.0" type="text/css" />

<script type="text/javascript">
// <![CDATA[
var mceToolbars = ['toolbar_1', 'toolbar_2', 'toolbar_3', 'toolbar_4'];
function initTadv() {
  $A(Draggables.drags).map(function(o){o.startDrag(null);o.finishDrag(null);});
  tadvResetHeight();
}
function tadvResetHeight() {
  var pd = $('tadvpalette');
  if( pd.childNodes.length > 6 ) { 
    var last = pd.lastChild.previousSibling; 
    pd.style.height = last.offsetTop + last.offsetHeight + 30 + "px";
  } else { pd.style.height = "60px"; }
}
function tadvSerializeAll() {
  $('toolbar_1order').value = Sortable.serialize('toolbar_1');
  $('toolbar_2order').value = Sortable.serialize('toolbar_2');
  $('toolbar_3order').value = Sortable.serialize('toolbar_3');
  $('toolbar_4order').value = Sortable.serialize('toolbar_4');
}
function tadvUpdateAll() {
  tadvResetHeight();
  $('tadvWarnmsg').innerHTML = '';

  mceToolbars.map(function(o){
    var kids = $(o).childNodes, tbwidth = $(o).clientWidth, kidswidth = 0;

    for( i=0; i<kids.length; i++ ) 
      kidswidth += kids[i].clientWidth;

    if( (kidswidth+8) > tbwidth )
      $('tadvWarnmsg').innerHTML = 'Adding too many buttons will make the toolbar too long and will not display correctly in TinyMCE!';
  });

  var wp_adv = $('pre_wp_adv'), t1 = $('toolbar_1'), t2 = $('toolbar_2'), t3 = $('toolbar_3'), t4 = $('toolbar_4'), tp = $('tadvpalette');
  var t2l = (t2.childNodes.length > 0 && t2.childNodes[0] != wp_adv), t3l = (t3.childNodes.length > 0 && t3.childNodes[0] != wp_adv), t4l = (t4.childNodes.length > 0 && t4.childNodes[0] != wp_adv);
  
  switch (wp_adv.parentNode.id) {
    case 'toolbar_4' :
      if (t4l) {
	    t3.appendChild(wp_adv);
	    break;
      }
    case 'toolbar_3' :
      if (t4l) break;
      else if (t3l) {
	    t2.appendChild(wp_adv);
	    break;
	  }
	case 'toolbar_2' :
	  if (t3l) break;
      else if (t2l) {
	    t1.appendChild(wp_adv);
	    break;
	  }
	case 'toolbar_1' :
	  if (t2l) break;
	case 'tadvpalette' :
	  break;
	default :
	  tp.appendChild(wp_adv);
  }
};
Event.observe(window, 'load', function() {
initTadv();
tadvUpdateAll();
});
Event.observe(window, 'resize', tadvUpdateAll);
// ]]>
</script>

<?php
	}
} // end tadv_admin_head

if ( ! function_exists('tadv_activate') ) {
	function tadv_activate() {
	        
	    if ( empty($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.5', '<') ) // if less than 2.5
	    exit('<h2>This plugin requires WordPress version 2.5 or newer. Please upgrade your WordPress installation or remove the plugin.</h2>');
	
		@include_once( dirname(__FILE__) . '/tadv_defaults.php');
		
		if ( isset($tadv_toolbars) ) {
			add_option( 'tadv_toolbars', $tadv_toolbars, '', 'no' );
		    add_option( 'tadv_options', $tadv_options, '', 'no' );
		    add_option( 'tadv_plugins', $tadv_plugins, '', 'no' );
		    add_option( 'tadv_btns1', $tadv_btns1, '', 'no' );
		    add_option( 'tadv_btns2', $tadv_btns2, '', 'no' );
		    add_option( 'tadv_btns3', $tadv_btns3, '', 'no' );
		    add_option( 'tadv_btns4', $tadv_btns4, '', 'no' );
		    add_option( 'tadv_allbtns', $tadv_allbtns, '', 'no' );
	    }
	}
}
add_action( 'activate_tinymce-advanced/tinymce-advanced.php', 'tadv_activate' );

if ( ! function_exists('tdav_css') ) {
	function tdav_css($wp) {
		$tadv_options = (array) get_option('tadv_options');
	        
	    if ( $tadv_options['importcss'] == '1' )
			$wp .= ',' . get_bloginfo('stylesheet_url');
	
	    return $wp .= ',' . get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/css/tadv-mce.css';
	}
}
add_filter( 'mce_css', 'tdav_css' );

$tadv_allbtns = array();
$tadv_hidden_row = 0;

if ( ! function_exists('tadv_mce_btns') ) {
	function tadv_mce_btns($orig) {
	    global $tadv_allbtns, $tadv_hidden_row;
		$tadv_btns1 = (array) get_option('tadv_btns1');
		$tadv_allbtns = (array) get_option('tadv_allbtns');

		if ( in_array( 'wp_adv', $tadv_btns1 ) )
			$tadv_hidden_row = 2;

	    if ( is_array($orig) && ! empty($orig) ) {
	    	$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns1 = array_merge( $tadv_btns1, $orig );
		}
	    return $tadv_btns1;
	}
}
add_filter( 'mce_buttons', 'tadv_mce_btns', 999 );

if ( ! function_exists('tadv_mce_btns2') ) {
	function tadv_mce_btns2($orig) {
		global $tadv_allbtns, $tadv_hidden_row;
		$tadv_btns2 = (array) get_option('tadv_btns2');

		if ( in_array( 'wp_adv', $tadv_btns2 ) )
			$tadv_hidden_row = 3;

	    if ( is_array($orig) && ! empty($orig) ) {
	    	$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns2 = array_merge( $tadv_btns2, $orig );
		}
	    return $tadv_btns2;
	}
}
add_filter( 'mce_buttons_2', 'tadv_mce_btns2', 999 );

if ( ! function_exists('tadv_mce_btns3') ) {
	function tadv_mce_btns3($orig) {
	    global $tadv_allbtns, $tadv_hidden_row;
		$tadv_btns3 = (array) get_option('tadv_btns3');

		if ( in_array( 'wp_adv', $tadv_btns3 ) )
			$tadv_hidden_row = 4;

	    if ( is_array($orig) && ! empty($orig) ) {
	    	$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns3 = array_merge( $tadv_btns3, $orig );
		}
	    return $tadv_btns3;
	}
}
add_filter( 'mce_buttons_3', 'tadv_mce_btns3', 999 );

if ( ! function_exists('tadv_mce_btns4') ) {
	function tadv_mce_btns4($orig) {
	    global $tadv_allbtns;
	    $tadv_btns4 = (array) get_option('tadv_btns4');

	    if ( is_array($orig) && ! empty($orig) ) {
	    	$orig = array_diff( $orig, $tadv_allbtns );
			$tadv_btns4 = array_merge( $tadv_btns4, $orig );
		}
	    return $tadv_btns4;
	}
}
add_filter( 'mce_buttons_4', 'tadv_mce_btns4', 999 );

if ( ! function_exists('tadv_mce_options') ) {
	function tadv_mce_options($init) {
		global $tadv_hidden_row;
		$tadv_options = get_option('tadv_options');

		if ( $tadv_hidden_row > 0 )
			$init['wordpress_adv_toolbar'] = 'toolbar' . $tadv_hidden_row;
		else $init['wordpress_adv_hidden'] = false;

		if ( isset($tadv_options['fix_autop']) && $tadv_options['fix_autop'] == 1 ) {
			$init['apply_source_formatting'] = true;
		}
		return $init;
	}
}
add_filter( 'tiny_mce_before_init', 'tadv_mce_options' );

if ( ! function_exists('tadv_htmledit') ) {
	function tadv_htmledit($c) {
		$tadv_options = get_option('tadv_options');
		
		if ( isset($tadv_options['fix_autop']) && $tadv_options['fix_autop'] == 1 ) {
			$c = preg_replace( array('/&amp;/','/&lt;/','/&gt;/'), array('&','<','>'), $c );
			$c = wpautop($c);
			$c = htmlspecialchars($c, ENT_NOQUOTES);
		}
		return $c;
	}
}
add_filter('htmledit_pre', 'tadv_htmledit', 999);

if ( ! function_exists('tmce_init') ) {
    function tmce_init() {
    	global $wp_scripts;
    	$tadv_options = get_option('tadv_options');
    	
    	if ( ! isset($tadv_options['fix_autop']) || $tadv_options['fix_autop'] != 1 ) return;
    	
    	$queue = $wp_scripts->queue;
    	if ( is_array($queue) && in_array( 'autosave', $queue ) )
    		wp_enqueue_script( 'tadv_replace', get_option('siteurl') . '/wp-content/plugins/tinymce-advanced/js/tadv_replace.js', array('editor_functions'), '20080425' );
    }
}
add_action( 'admin_print_scripts', 'tmce_init' );

if ( ! function_exists('tadv_load_plugins') ) {
	function tadv_load_plugins($plug) { 
	    $tadv_plugins = (array) get_option('tadv_plugins');
	    $plugpath = get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/mce/';
		
		$plug = (array) $plug;
	    foreach( $tadv_plugins as $plugin )
	        $plug["$plugin"] = $plugpath . $plugin . '/editor_plugin.js';
		
		return $plug;
	}
}
add_action( 'mce_external_plugins', 'tadv_load_plugins', 999 );

if ( ! function_exists('tadv_load_langs') ) {
	function tadv_load_langs($langs) {
		$tadv_plugins = (array) get_option('tadv_plugins');
	    $langpath = ABSPATH . '/' . PLUGINDIR . '/tinymce-advanced/mce/';
		$nolangs = array( 'bbcode', 'contextmenu', 'insertdatetime', 'layer', 'nonbreaking', 'print', 'visualchars', 'emotions', 'tadvreplace' );

		$langs = (array) $langs;
		foreach( $tadv_plugins as $plugin ) {
			if ( in_array( $plugin, $nolangs ) ) continue;
			$langs["$plugin"] = $langpath . $plugin . '/langs/langs.php';
		}
		return $langs;
	}
}
add_filter( 'mce_external_languages', 'tadv_load_langs' );

if ( ! function_exists('tadv_page') ) {
	function tadv_page() {
		include_once( dirname(__FILE__) . '/tadv_admin.php');
	}
}

if ( ! function_exists('tadv_menu') ) {
	function tadv_menu() {
	    if ( function_exists('add_management_page') ) {
		   $page = add_management_page( 'TinyMCE Advanced', 'TinyMCE Advanced', 9, __FILE__, 'tadv_page' );
		   add_action( "admin_print_scripts-$page", 'tadv_admin_head' );
		}
	}
}
add_action( 'admin_menu', 'tadv_menu' );

?>