<?php
/*
Plugin Name: TinyMCE Advanced
Plugin URI: http://www.laptoptips.ca/projects/tinymce-advanced/
Description: Enables advanced features and plugins in TinyMCE.
Version: 2.1
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Some code and ideas from WordPress(http://www.wordpress.org/). The options page for this plugin uses Prototype.js by Sam Stephenson(http://prototype.conio.net/) and Scriptaculous by Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us). The Javascript files have been compressed and concatenated for faster loading.

Released under the GPL, http://www.gnu.org/copyleft/gpl.html

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

if( ! function_exists(tadv_admin_head) ) {
function tadv_admin_head() {
    global $is_winIE;
?>
<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/tinymce-advanced/js/tadv-all.js?ver=1.7.0-2.1"></script>
<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/tinymce-advanced/css/tadv-styles.css?ver=2.1" type="text/css" />
<?php
} } // end tadv_admin_head

if( ! function_exists(tadv_page) ) {
function tadv_page() {
    global $is_winIE;

if ( ! current_user_can('edit_themes') )
	wp_die( __( 'Cheatin&#8217; uh?' ));

$tadv_toolbars = (array) get_option('tadv_toolbars');
$tadv_options = (array) get_option('tadv_options');
$imgpath = get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/images/';

if( isset( $_POST['save'] ) ) {
    check_admin_referer( 'tadv-save-buttons-order' );
	parse_str( $_POST['toolbar-1order'], $tb1 );
	parse_str( $_POST['toolbar-2order'], $tb2 );
	parse_str( $_POST['toolbar-3order'], $tb3 );
	$tadv_toolbars = $tb1 + $tb2 + $tb3;
	update_option( 'tadv_toolbars', $tadv_toolbars );

    $tadv_options['advlink'] = $_POST['advlink'] ? '1' : '';
    $tadv_options['advimage'] = $_POST['advimage'] ? '1' : '';
    $tadv_options['contextmenu'] = $_POST['contextmenu'] ? '1' : '';
    $tadv_options['importcss'] = $_POST['importcss'] ? '1' : '';
    $tadv_options['fixcss'] = $_POST['fixcss'] ? '1' : '';
    $update_tadv_options = true;
}
	
if( isset( $_POST['reset'] ) ) {
    check_admin_referer( 'tadv-save-buttons-order' );
    $tadv_toolbars = '';
    $tadv_options = '';
}

if( empty($tadv_toolbars) ) {
    $tb1 = array( 'bold', 'italic', 'strikethrough', 'underline', 'separator1', 'bullist', 'numlist', 'outdent',  'indent', 'separator2', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'separator3', 'link', 'unlink', 'separator4', 'image', 'styleprops', 'separator12', 'wp_more', 'wp_page', 'separator5', 'spellchecker', 'search', 'separator6', 'wp_help', 'fullscreen' );

    $tb2 = '';

    $tb3 = array( 'styleselect', 'formatselect', 'paste', 'pastetext', 'pasteword', 'separator7', 'cleanup', 'separator8', 'charmap', 'print', 'separator9', 'emotions', 'separator10', 'sup', 'sub', 'separator11', 'undo', 'redo', 'attribs' );
    
    $tadv_toolbars = array( 'toolbar-1' => $tb1, 'toolbar-2' => $tb2, 'toolbar-3' => $tb3 );
    $tadv_options = array( 'advlink' => '1', 'advimage' => '1', 'importcss' => '1', 'refresh' => '1' );
    
    update_option( 'tadv_toolbars', $tadv_toolbars );
    $update_tadv_options = true;
}

if( is_array($tadv_toolbars['toolbar-1']) ) {
    $btns1 = $tadv_toolbars['toolbar-1'];
    
    foreach( $btns1 as $k => $v ) {
        if( strpos($v, 'separator') !== false ) $btns1[$k] = 'separator';
        if( $v == 'layer' ) $l = $k;
        if( empty($v) ) unset($btns1[$k]);
    }
    if( $l ) array_splice( $btns1, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
}

if( is_array($tadv_toolbars['toolbar-2']) ) {
    $btns2 = $tadv_toolbars['toolbar-2'];
    
    foreach( $btns2 as $k => $v ) {
        if( strpos($v, 'separator') !== false ) $btns2[$k] = 'separator';
        if( $v == 'layer' ) $l = $k;
        if( empty($v) ) unset($btns2[$k]);
    }
    if( $l ) array_splice( $btns2, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
    if( empty($btns1) ) {
        $btns1 = $btns2;
        $btns2 = array();
    }    
}

if( is_array($tadv_toolbars['toolbar-3']) ) {
    $btns3 = $tadv_toolbars['toolbar-3'];
    foreach( $btns3 as $k => $v ) {
        if( strpos($v, 'separator') !== false ) $btns3[$k] = 'separator';
        if( $v == 'layer' ) $l = $k;
    }
    if( $l ) array_splice( $btns3, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
    if( is_array($btns2) && ! empty($btns2) ) {
        $btns2[] = 'wp_adv';
        $btns2[] = 'wp_adv_start';
        $btns3[] = 'wp_adv_end';
        $btns2 = array_merge( $btns2, $btns3 );
    } elseif ( is_array($btns1) && ! empty($btns1) ) {
        $btns1[] = 'wp_adv';
        $btns1[] = 'wp_adv_start';
        $btns3[] = 'wp_adv_end';
        $btns1 = array_merge( $btns1, $btns3 );
    } else {
        $btns1 = $btns3;
        $btns3 = '';
    }
}

if( empty($btns1) && empty($btns2) ) {
    $allbtns = array();
    ?><div class="error" id="message"><p>All toolbars are empty!</p></div><?php
} else {
    $allbtns = array_merge( (array) $btns1, (array) $btns2 );
}

if( in_array('advhr', $allbtns) ) $plugins[] = 'advhr';
if( in_array('fullscreen', $allbtns) ) $plugins[] = 'fullscreen';
if( in_array('insertlayer', $allbtns) ) $plugins[] = 'layer';
if( in_array('visualchars', $allbtns) ) $plugins[] = 'visualchars';

if( in_array('iespell', $allbtns) ) $plugins[] = 'iespell';
if( in_array('nonbreaking', $allbtns) ) $plugins[] = 'nonbreaking';
if( in_array('styleprops', $allbtns) ) $plugins[] = 'style';
if( in_array('emotions', $allbtns) ) $plugins[] = 'emotions';

if( in_array('print', $allbtns) ) $plugins[] = 'print';
if( in_array('search', $allbtns) ||
    in_array('replace', $allbtns) ) $plugins[] = 'searchreplace';

if( in_array('cite', $allbtns) || 
    in_array('ins', $allbtns) ||
    in_array('del', $allbtns) ||
    in_array('abbr', $allbtns) ||
    in_array('acronym', $allbtns) ||
    in_array('attribs', $allbtns) ) $plugins[] = 'xhtmlxtras';

if ( $tadv_options['advlink'] == '1' ) $plugins[] = 'advlink';
if ( $tadv_options['advimage'] == '1' ) $plugins[] = 'advimage';
if ( $tadv_options['contextmenu'] == '1' ) $plugins[] = 'contextmenu';
$plugins = array_merge( array( 'table', 'media' ), (array) $plugins );

if( get_option('tadv_plugins') != $plugins ) update_option( 'tadv_plugins', $plugins ); 
if( get_option('tadv_btns1') != $btns1 ) update_option( 'tadv_btns1', $btns1 );
if( get_option('tadv_btns2') != $btns2 ) update_option( 'tadv_btns2', $btns2 ); 

$buttons = array( 'Bold' => 'bold', 'Italic' => 'italic', 'Strikethrough' => 'strikethrough', 'Underline' => 'underline', 'Bullet List' => 'bullist', 'Numbered List' => 'numlist', 'Outdent' => 'outdent', 'Indent' => 'indent', 'Allign Left' => 'justifyleft', 'Center' => 'justifycenter', 'Alligh Right' => 'justifyright', 'Justify' => 'justifyfull', 'Cut' => 'cut', 'Copy' => 'copy', 'Paste' => 'paste', 'Link' => 'link', 'Remove Link' => 'unlink', 'Insert Image' => 'image', 'More Tag' => 'wp_more', 'Split Page' => 'wp_page', 'Search' => 'search', 'Replace' => 'replace', 'Select Font' => 'fontselect', 'Help' => 'wp_help', 'Full Screen' => 'fullscreen', 'CSS Styles' => 'styleselect', 'Format' => 'formatselect', 'Text Color' => 'forecolor', 'Paste as Text' => 'pastetext', 'Paste from Word' => 'pasteword', 'Remove Format' => 'removeformat', 'Clean Code' => 'cleanup', 'Check Spelling' => 'spellchecker', 'IE Spell' => 'iespell', 'Character Map' => 'charmap', 'Print' => 'print', 'Undo' => 'undo', 'Redo' => 'redo', 'Table' => 'tablecontrols', 'Citation' => 'cite', 'Inserted Text' => 'ins', 'Deleted Text' => 'del', 'Abbreviation' => 'abbr', 'Acronym' => 'acronym', 'XHTML Attribs' => 'attribs', 'Layer' => 'layer', 'Advanced HR' => 'advhr', 'View HTML' => 'code', 'Hidden Chars' => 'visualchars', 'NB Space' => 'nonbreaking', 'Sub' => 'sub', 'Sup' => 'sup', 'Visual Aids' => 'visualaid', 'Anchor' => 'anchor', 'Style' => 'styleprops', 'Smilies' => 'emotions' );

if( ! $is_winIE ) $buttons['Insert Movie'] = 'media';

$active_plugins = get_settings('active_plugins');
$add = array();
foreach( $active_plugins as $plug ) {
    if( strpos( $plug, 'wpg2' ) !== false ) $add['Gallery 2'] = 'g2image';
    if( strpos( $plug, 'nextgen-gallery' ) !== false ) $add['Nextgen Gallery'] = 'NextGEN';
    if( strpos( $plug, 'vipers-video' ) !== false ) $add["Viper's Video"] = 'vipersvideoquicktags';
    if( strpos( $plug, 'embedded-video' ) !== false ) $add['EmbeddedVideo'] = 'embeddedvideo';
    if( strpos( $plug, 'imagemanager' ) !== false ) $add['Image Manager'] = 'ps_imagemanager_tinymceplugin';
}

if( ! empty($add) ) $buttons += $add;

$separators = array( 's1' => 'separator1', 's2' => 'separator2', 's3' => 'separator3', 's4' => 'separator4', 's5' => 'separator5', 's6' => 'separator6', 's7' => 'separator7', 's8' => 'separator8', 's9' => 'separator9', 's10' => 'separator10', 's11' => 'separator11', 's12' => 'separator12', 's13' => 'separator13', 's14' => 'separator14', 's15' => 'separator15', 's16' => 'separator16', 's17' => 'separator17', 's18' => 'separator18', 's19' => 'separator19', 's20' => 'separator20' );

$buttons += $separators;

if ( isset( $_POST['tadv'] ) ) { 
    if( isset($_POST['save']) ) { ?><div class="updated" id="message"><p>Options saved</p></div><?php }
    if( isset($_POST['reset']) ) { ?><div class="updated" id="message"><p>Defaults loaded</p></div><?php }
    
    $tadv_options['refresh'] = '1';
    $update_tadv_options = true;
} ?>

<div class="wrap">
	<h2>TinyMCE Buttons Arrangement</h2>

	<form id="tadvadmin" method="post" onsubmit="tadvSerializeAll();">
	<p>Drag and drop buttons onto the toolbars below.</p>
	
		<input id="toolbar-1order" name="toolbar-1order" value="" type="hidden" />
		<input id="toolbar-2order" name="toolbar-2order" value="" type="hidden" />
		<input id="toolbar-3order" name="toolbar-3order" value="" type="hidden" />
        <input name="tadv" value="1" type="hidden" />		
        <div id="tadvzones">
        
		<div class="tadvdropzone">
        <ul style="position: relative;" id="toolbar-1">
<?php
if( is_array($tadv_toolbars['toolbar-1']) ) {
    $tb1 = array();
    foreach( $tadv_toolbars['toolbar-1'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb1 = $tb1 + $t;
    }

    foreach( $tb1 as $name => $btn ) { 
        if( strpos( $btn, 'eparator' ) ) { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . 'separator.gif'; ?>" alt="Separator" title="Separator" /></div></li>
<?php
        } else { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb1 );
} ?>

        </ul></div>
        <br class="clear" />
        
        <div class="tadvdropzone">
		<ul style="position: relative;" id="toolbar-2">
<?php
if( is_array($tadv_toolbars['toolbar-2']) ) {
    $tb2 = array();
    foreach( $tadv_toolbars['toolbar-2'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb2 = $tb2 + $t;
    }
    foreach( $tb2 as $name => $btn ) { 
        if( strpos( $btn, 'eparator' ) ) { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . 'separator.gif'; ?>" alt="Separator" title="Separator" /></div></li>
<?php
        } else { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb2 );
} ?>
        
        </ul></div>
        <br class="clear" />
        
        <div class="tadvdropzone">
		<ul style="position: relative;" id="toolbar-3">
<?php   
if( is_array($tadv_toolbars['toolbar-3']) ) {
    $tb3 = array();
    foreach( $tadv_toolbars['toolbar-3'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb3 = $tb3 + $t;
    }
    foreach( $tb3 as $name => $btn ) { 
        if( strpos( $btn, 'eparator' ) ) { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . 'separator.gif'; ?>" alt="Separator" title="Separator" /></div></li>
<?php
        } else { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb3 );
}

$tadv_btns4 = is_array($buttons) ? array_values($buttons) : array();
if( get_option('tadv_btns4') != $tadv_btns4 ) update_option( 'tadv_btns4', $tadv_btns4 ); ?>

        </ul></div>
        <br class="clear" />
        </div>
		
		<div id="tadvWarnmsg" ></div>
		
        <div id="tadvpalettediv">
        <ul style="position: relative;" id="tadvpalette">
<?php
if( is_array($buttons) ) {
    foreach( $buttons as $name => $btn ) { 
        if( strpos( $btn, 'eparator' ) ) { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . 'separator.gif'; ?>" alt="Separator" title="Separator" /></div></li>
<?php
        } else { ?>

<li style="position: relative; top: 0px; left: 0px; z-index: 0; opacity: 0.999999;" class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    } 
} ?>
        </ul>
	   </div>

        <table class="clear" style="margin:10px 0"><tr><td style="padding:2px 12px 8px;">
		Also enable: 
        <label for="advlink" class="tadv-box">Advanced Link &nbsp;
        <input type="checkbox" class="tadv-chk"  name="advlink" id="advlink" <?php if ( $tadv_options['advlink'] == '1' ) echo ' checked="checked"'; ?> /></label> &bull;

        <label for="advimage" class="tadv-box">Advanced Image &nbsp;
        <input type="checkbox" class="tadv-chk"  name="advimage" id="advimage" <?php if ( $tadv_options['advimage'] == '1' ) echo ' checked="checked"'; ?> /></label> &bull;

        <label for="contextmenu" class="tadv-box">Context Menu &nbsp;
        <input type="checkbox" class="tadv-chk"  name="contextmenu" id="contextmenu" <?php if ( $tadv_options['contextmenu'] == '1' ) echo ' checked="checked"'; ?> /></label>
        (to show the browser's context menu in Firefox and use the spellchecker, hold down the &quot;Ctrl&quot; key).
        </td></tr>

        <tr><td style="border:1px solid #CD0000;padding:2px 12px 8px;">
        <p style="font-weight:bold;color:#CD0000;">Advanced</p> 
        
        <p>&middot; Import the current theme's 
        <label for="importcss" class="tadv-box">CSS classes &nbsp;
        <input type="checkbox" class="tadv-chk"  name="importcss" id="importcss" <?php if ( $tadv_options['importcss'] == '1' ) echo ' checked="checked"'; ?> /></label></p>
        
         <p>&middot; If the editor doesn't look right, 
        <label for="fixcss" class="tadv-box">reset some of the CSS styles &nbsp;
        <input type="checkbox" class="tadv-chk"  name="fixcss" id="fixcss" <?php if ( $tadv_options['fixcss'] == '1' ) echo ' checked="checked"'; ?> /></label></p> 
        
        <p>You can also disable the importing of your theme's css and <a href="templates.php?file=wp-content/plugins/tinymce-advanced/css/tadv-tinymce.css" >add the names of CSS classes</a> that are defined in your theme and you want them to appear in the &quot;Styles&quot; drop-down menu. You don't need to copy the whole CSS classes, just add their names, like: <code>.my-class{}</code>, <code>.my-other-class{}</code>, etc. (add each name on a new line).</p>
        </td></tr>
<?php
    $mce_locale = ( '' == get_locale() ) ? 'en' : strtolower(get_locale());
    if ( $mce_locale != 'en' ) {
        
        if( ! file_exists(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/' . $mce_locale . '.js') ) {
            $mce_locale_lang = substr($mce_locale, 0, 2);
                
            if( strlen($mce_locale) == 2 ) {
                if( file_exists(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/' . $mce_locale . '_' . $mce_locale . '.js') ) {
                    $lang = $mce_locale . '_' . $mce_locale;
                }
            } elseif( file_exists(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/' . $mce_locale_lang . '.js') ) {
                $lang = $mce_locale_lang;
            }
            
            if( isset($lang) ) {
                if( $tadv_options['tadv_lang'] != $lang ) {
                    $tadv_options['tadv_lang'] = $lang;
                    $update_tadv_options = true;
                }
            } else {
            
                $open = opendir(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/');
                $tadv_langs = array();
                while( false !== ($file = readdir($open)) ) {
                    if( '.' == $file || '..' == $file ) continue;
                    $tadv_langs[] = substr($file, 0, -3);
                    if( substr($file, 0, 2) == $mce_locale_lang )
                        $closest_lang = substr($file, 0, -3);
                }
            
                if( isset( $_POST['tadv_lang'] ) ) {
                    check_admin_referer( 'tadv-save-buttons-order' );
                    if( in_array( $_POST['tadv_lang'], (array) $tadv_langs ) ) {
                        if( $tadv_options['tadv_lang'] != $_POST['tadv_lang'] ) {
                            $tadv_options['tadv_lang'] = $_POST['tadv_lang'];
                            $update_tadv_options = true;
                        }
                    }
                }
?>
        <tr><td style="padding:2px 12px 8px;">
        <p style="font-weight:bold;">Language Settings</p>
        <p>Your WordPress language is set to <strong><?php echo get_locale(); ?></strong>. However there is no matching language installed for TinyMCE's plugins. <?php if( isset($closest_lang) ) { ?>The closest match seem to be <strong><?php echo $closest_lang . '</strong>.'; } ?></p>
        <p>Please select one of the installed languages 

        <select name="tadv_lang" id="tadv_lang" style="width:100px;padding:0;">
        <option value="en">en(default)</option>
            <?php if( ! empty($tadv_langs) ) { ?>
                <?php foreach( $tadv_langs as $la ) { 
                    if( $la == 'en' ) continue; ?>
        <option value="<?php echo $la; if( $tadv_options['tadv_lang'] == $la ) echo '" selected="selected' ?>"><?php echo $la; ?></option>
                <?php } ?>
            <?php } ?>
        </select>

        </p></td></tr>
<?php       }
        }
    } // end mce_locale
?>      </table>
		
<script type="text/javascript">
// <![CDATA[
Sortable.create("toolbar-1", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar-1","toolbar-2","toolbar-3"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("toolbar-2", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar-1","toolbar-2","toolbar-3"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("toolbar-3", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar-1","toolbar-2","toolbar-3"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("tadvpalette", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar-1","toolbar-2","toolbar-3"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
// ]]>
</script>
		
<p class="submit">
	<?php wp_nonce_field( 'tadv-save-buttons-order' ); ?>
	<input type="submit" name="reset" id="reset" value="<?php _e( 'Load Defaults' ); ?>" />
	<input type="submit" name="save" id="save" value="<?php _e( 'Save Changes' ); ?>" />
</p>
</form>
		
<br class="clear" />
</div>
<?php 
    if( $update_tadv_options )
        update_option( 'tadv_options', $tadv_options );

} } // end tadv_page

if( ! class_exists(tadv_mceClass) ) {
class tadv_mceClass {
    var $extra_btns = array();

    function tadv_mceClass() {
    
    if( 'plugins.php' == basename($_SERVER['SCRIPT_FILENAME']) && $_GET['action'] == 'deactivate' && $_GET['plugin'] == 'tinymce-advanced/tinymce-advanced.php') $this->tadv_deactivate();
    }
    
    function tadv_mce_opt() { 
?>
        valid_child_elements : "table[thead|tbody|tfoot|tr|td|th],object[param|embed|%itrans|#text]",
        extended_valid_elements : "object[*],param[name|value|valuetype|type|id],embed[*]",
        fix_table_elements : true,
        convert_fonts_to_spans : true,
        paste_auto_cleanup_on_paste : true,
        cleanup_on_startup : false,
<?php 
        $tadv_options = (array) get_option('tadv_options');
        $mce_locale = ( '' == get_locale() ) ? 'en' : strtolower(get_locale());

        if ( $mce_locale != 'en' ) {
            if( ! file_exists(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/' . $mce_locale . '.js') ) {
                if( isset($tadv_options['tadv_lang']) )  echo 'language : "' . $tadv_options['tadv_lang'] . '",' . "\n";
                else echo 'language : "en",' . "\n";
            }
        }
    }

    function tdav_css($wp) {
        $tadv_options = (array) get_option('tadv_options');
        
        if( $tadv_options['importcss'] == '1' ) $add = get_bloginfo('stylesheet_url') . ',';
        else $add = '';

        $add .=  get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/css/tadv-tinymce.css?r=' . $tadv_options['refresh'];
        
        if( $tadv_options['fixcss'] == '1' )
            $add .= ',' . get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/css/tadv-fixstyle.css';
        
        return $add;
    }

    function tadv_mce_plugins($plug) {

        $tadv_plugins = (array) get_option('tadv_plugins');
        return array_merge($plug, $tadv_plugins);
    }

    function tadv_mce_btns($orig) {
        global $is_winIE;
    
        $tadv_btns1 = (array) get_option('tadv_btns1');

        $this->extra_btns = array_merge( (array) $this->extra_btns, (array) $orig );
        $this->extra_btns = array_diff( $this->extra_btns, $tadv_btns1 );

        if( $is_winIE ) $tadv_btns1 = array_diff( $tadv_btns1, array('media') );
    
        return $tadv_btns1;
    }

    function tadv_mce_btns2($orig) {
        global $is_winIE;
    
        $tadv_btns2 = (array) get_option('tadv_btns2');
        $tadv_btns4 = (array) get_option('tadv_btns4');
    
        $orig = array_merge( (array) $this->extra_btns, (array) $orig );
        $orig = array_diff( $orig, $tadv_btns2, $tadv_btns4, array('wp_adv_start', 'wp_adv', 'wp_adv_end', 'separator') );
        
        if( ! empty($orig) )
            $tadv_btns2 = array_merge($orig, $tadv_btns2);

        if( $is_winIE ) $tadv_btns2 = array_diff( $tadv_btns2, array('media') );
    
        return $tadv_btns2;
    }

    function tadv_mce_btns3($orig) {

        if( is_array($orig) && ! empty($orig) ) 
            $this->extra_btns = $orig;

        return array();
    }
    
    function tadv_load_plugins() { 
        $tadv_plugins = (array) get_option('tadv_plugins');
        $plugpath = get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/mce/';
        
        foreach( $tadv_plugins as $plug )
            echo 'tinyMCE.loadPlugin("' . $plug . '","' . $plugpath . $plug . '");' . "\n";
    }
    
    function tadv_refresh_mceconfig($loc) {
        
        $tadv_options = (array) get_option('tadv_options');
        if( $tadv_options['refresh'] == '1' ) {
            $tadv_options['refresh'] = rand(1000, 9999);
            update_option( 'tadv_options', $tadv_options );
        } 
        return $loc . '?r=' . $tadv_options['refresh'];
    }
    
    function tadv_activate() {
    
        $tb1 = array( 'bold', 'italic', 'strikethrough', 'underline', 'separator1', 'bullist', 'numlist', 'outdent',  'indent', 'separator2', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'separator3', 'link', 'unlink', 'separator4', 'image', 'styleprops', 'separator12', 'wp_more', 'wp_page', 'separator5', 'spellchecker', 'search', 'separator6', 'wp_help', 'fullscreen' );

        $tb2 = '';

        $tb3 = array( 'styleselect', 'formatselect', 'paste', 'pastetext', 'pasteword', 'separator7', 'cleanup', 'separator8', 'charmap', 'print', 'separator9', 'emotions', 'separator10', 'sup', 'sub', 'separator11', 'undo', 'redo', 'attribs' );
    
        $tadv_toolbars = array( 'toolbar-1' => $tb1, 'toolbar-2' => $tb2, 'toolbar-3' => $tb3 );
        $tadv_options = array( 'advlink' => '1', 'advimage' => '1', 'importcss' => '1', 'refresh' => '1' );
        $tadv_plugins = array( 'table', 'media', 'fullscreen', 'style', 'emotions', 'print', 'searchreplace', 'xhtmlxtras', 'advlink', 'advimage' );
        $btns1 = array( 'bold', 'italic', 'strikethrough', 'underline', 'separator', 'bullist', 'numlist', 'outdent', 'indent', 'separator', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'separator', 'link', 'unlink', 'separator', 'image', 'styleprops', 'separator', 'wp_more', 'wp_page', 'separator', 'spellchecker', 'search', 'separator', 'wp_help', 'fullscreen', 'wp_adv', 'wp_adv_start', 'styleselect', 'formatselect', 'paste', 'pastetext', 'pasteword', 'separator', 'cleanup', 'separator', 'charmap', 'print', 'separator', 'emotions', 'separator', 'sup', 'sub', 'separator', 'undo', 'redo', 'attribs', 'wp_adv_end' );

        add_option( 'tadv_toolbars', $tadv_toolbars, 'TinyMCE Advanced', 'no' );
        add_option( 'tadv_options', $tadv_options, 'TinyMCE Advanced', 'no' );
        add_option( 'tadv_plugins', $tadv_plugins, 'TinyMCE Advanced', 'no' );
        add_option( 'tadv_btns1', $btns1, 'TinyMCE Advanced', 'no' );
        add_option( 'tadv_btns2', array(), 'TinyMCE Advanced', 'no' );
        add_option( 'tadv_btns4', array( 'forecolor', 'removeformat' ), 'TinyMCE Advanced', 'no' );
    }
    
    function tadv_deactivate() {
        
        switch ($_GET['tadv_remove']) {
			case 'all':
				delete_option('tadv_options');
                delete_option('tadv_toolbars');
                delete_option('tadv_plugins');
                delete_option('tadv_btns1');
                delete_option('tadv_btns2');
                delete_option('tadv_btns4');
				break;
			case 'none':
				break;
			default: 
?>
<script language="JavaScript" type="text/javascript">
// <![CDATA[
var remove_options = confirm('Remove the TinyMCE Advanced toolbar buttons arrangement and options from the database?');
if (remove_options) {
	window.location = "plugins.php?action=deactivate&plugin=tinymce-advanced/tinymce-advanced.php&tadv_remove=all&_wpnonce=<?php echo $_GET['_wpnonce']; ?>";
} else if (!remove_options) {
    window.location = "plugins.php?action=deactivate&plugin=tinymce-advanced/tinymce-advanced.php&tadv_remove=none&_wpnonce=<?php echo $_GET['_wpnonce']; ?>";
}
// ]]>
</script>
<?php
				exit;
		}
    }
    
} } //end tadv_mceClass

function tadv_menu() {
    if( function_exists('add_management_page') ) 
	   $page = add_management_page( 'TinyMCE Advanced', 'TinyMCE Advanced', 9, __FILE__, 'tadv_page' );
	   add_action("admin_print_scripts-$page", 'tadv_admin_head');
}

if ( class_exists("tadv_mceClass") ) {
	$tadv_mce = new tadv_mceClass();

    add_action( 'tinymce_before_init', array(&$tadv_mce, 'tadv_load_plugins') );
    add_filter( 'mce_css', array(&$tadv_mce, 'tdav_css') );
    add_filter( 'tiny_mce_config_url', array(&$tadv_mce, 'tadv_refresh_mceconfig') );
    add_action( 'mce_options', array(&$tadv_mce, 'tadv_mce_opt') );
    add_filter( 'mce_plugins', array(&$tadv_mce, 'tadv_mce_plugins'), 99 );
    add_filter( 'mce_buttons_3', array(&$tadv_mce, 'tadv_mce_btns3'), 98 );
    add_filter( 'mce_buttons', array(&$tadv_mce, 'tadv_mce_btns'), 99 );
    add_filter( 'mce_buttons_2', array(&$tadv_mce, 'tadv_mce_btns2'), 99 );
    add_action( 'activate_tinymce-advanced/tinymce-advanced.php', array(&$tadv_mce, 'tadv_activate') );
}
add_action( 'admin_menu', 'tadv_menu' );
?>
