<?php

if ( ! current_user_can('edit_themes') )
	wp_die('Cheatin&#8217; uh?');

if ( isset( $_POST['tadv_uninstall'] ) ) {
    check_admin_referer( 'tadv-uninstall' );

	delete_option('tadv_options');
    delete_option('tadv_toolbars');
    delete_option('tadv_plugins');
    delete_option('tadv_btns1');
    delete_option('tadv_btns2');
    delete_option('tadv_btns3');
    delete_option('tadv_btns4');
    delete_option('tadv_allbtns');

echo '<script type="text/javascript">window.location="plugins.php?action=deactivate&plugin=tinymce-advanced/tinymce-advanced.php&_wpnonce=' . wp_create_nonce('deactivate-plugin_tinymce-advanced/tinymce-advanced.php') . '";</script>';
exit;
}

$update_tadv_options = false;
$imgpath = get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/images/';

$tadv_toolbars = get_option('tadv_toolbars');
if ( ! is_array($tadv_toolbars) )
	@include_once( dirname(__FILE__) . '/tadv_defaults.php');
else $tadv_options = get_option('tadv_options');

if ( isset( $_POST['save'] ) ) {
    check_admin_referer( 'tadv-save-buttons-order' );
	$tb1 = $tb2 = $tb3 = $tb4 = $btns = array();
	parse_str( $_POST['toolbar_1order'], $tb1 );
	parse_str( $_POST['toolbar_2order'], $tb2 );
	parse_str( $_POST['toolbar_3order'], $tb3 );
	parse_str( $_POST['toolbar_4order'], $tb4 );
	
	$tadv_toolbars['toolbar_1'] = (array) $tb1['toolbar_1'];
	$tadv_toolbars['toolbar_2'] = (array) $tb2['toolbar_2'];
	$tadv_toolbars['toolbar_3'] = (array) $tb3['toolbar_3'];
	$tadv_toolbars['toolbar_4'] = (array) $tb4['toolbar_4'];

	update_option( 'tadv_toolbars', $tadv_toolbars );

    $tadv_options['advlink'] = $_POST['advlink'] ? 1 : 0;
    $tadv_options['advimage'] = $_POST['advimage'] ? 1 : 0;
    $tadv_options['contextmenu'] = $_POST['contextmenu'] ? 1 : 0;
    $tadv_options['importcss'] = $_POST['importcss'] ? 1 : 0;
    $tadv_options['fix_autop'] = $_POST['fix_autop'] ? 1 : 0;
    $update_tadv_options = true;
}

$hidden_row = 0;
$i = 0;
foreach ( $tadv_toolbars as $toolbar ) {
	$vv = $l = false;
    $i++;

	if ( empty($toolbar) ) {
		$btns["toolbar_$i"] = array();
		continue;
	}

    foreach( $toolbar as $k => $v ) {
        if ( strpos($v, 'separator') !== false ) $toolbar[$k] = 'separator';
        if ( 'layer' == $v ) $l = $k;
        if ( 'vipersvideoquicktags' == $v ) $vv = $k;
        if ( 'wp_adv' == $v ) $hidden_row = ($i + 1);
        if ( empty($v) ) unset($toolbar[$k]);
    }
    if ( $l ) array_splice( $toolbar, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );

    $btns["toolbar_$i"] = $toolbar;
}
extract($btns);

if ( $hidden_row > 0 && $hidden_row < 4 ) $tadv_options['hidden_row'] = $hidden_row;
else $tadv_options['hidden_row'] = false;

if ( empty($toolbar_1) && empty($toolbar_2) && empty($toolbar_3) && empty($toolbar_4) ) {
    $allbtns = array();
    ?><div class="error" id="message"><p>All toolbars are empty!</p></div><?php
} else {
    $allbtns = array_merge( $toolbar_1, $toolbar_2, $toolbar_3, $toolbar_4 );

	if ( in_array('advhr', $allbtns) ) $plugins[] = 'advhr';
	if ( in_array('insertlayer', $allbtns) ) $plugins[] = 'layer';
	if ( in_array('visualchars', $allbtns) ) $plugins[] = 'visualchars';

	if ( in_array('nonbreaking', $allbtns) ) $plugins[] = 'nonbreaking';
	if ( in_array('styleprops', $allbtns) ) $plugins[] = 'style';
	if ( in_array('emotions', $allbtns) ) $plugins[] = 'emotions';
	if ( in_array('insertdate', $allbtns) ||
    	in_array('inserttime', $allbtns) ) $plugins[] = 'insertdatetime';

	if ( in_array('tablecontrols', $allbtns) ) $plugins[] = 'table';
	if ( in_array('print', $allbtns) ) $plugins[] = 'print';
	if ( in_array('search', $allbtns) ||
    	in_array('replace', $allbtns) ) $plugins[] = 'searchreplace';

	if ( in_array('cite', $allbtns) || 
    	in_array('ins', $allbtns) ||
    	in_array('del', $allbtns) ||
    	in_array('abbr', $allbtns) ||
    	in_array('acronym', $allbtns) ||
    	in_array('attribs', $allbtns) ) $plugins[] = 'xhtmlxtras';

	if ( $tadv_options['advlink'] == '1' ) $plugins[] = 'advlink';
	if ( $tadv_options['advimage'] == '1' ) $plugins[] = 'advimage';
	if ( $tadv_options['contextmenu'] == '1' ) $plugins[] = 'contextmenu';
}

$buttons = array( 'Kitchen Sink' => 'wp_adv', 'Bold' => 'bold', 'Italic' => 'italic', 'Strikethrough' => 'strikethrough', 'Underline' => 'underline', 'Bullet List' => 'bullist', 'Numbered List' => 'numlist', 'Outdent' => 'outdent', 'Indent' => 'indent', 'Allign Left' => 'justifyleft', 'Center' => 'justifycenter', 'Alligh Right' => 'justifyright', 'Justify' => 'justifyfull', 'Cut' => 'cut', 'Copy' => 'copy', 'Paste' => 'paste', 'Link' => 'link', 'Remove Link' => 'unlink', 'Insert Image' => 'image', 'More Tag' => 'wp_more', 'Split Page' => 'wp_page', 'Search' => 'search', 'Replace' => 'replace', '<!--fontselect-->' => 'fontselect', '<!--fontsizeselect-->' => 'fontsizeselect', 'Help' => 'wp_help', 'Full Screen' => 'fullscreen', '<!--styleselect-->' => 'styleselect', '<!--formatselect-->' => 'formatselect', 'Text Color' => 'forecolor', 'Paste as Text' => 'pastetext', 'Paste from Word' => 'pasteword', 'Remove Format' => 'removeformat', 'Clean Code' => 'cleanup', 'Check Spelling' => 'spellchecker', 'Character Map' => 'charmap', 'Print' => 'print', 'Undo' => 'undo', 'Redo' => 'redo', 'Table' => 'tablecontrols', 'Citation' => 'cite', 'Inserted Text' => 'ins', 'Deleted Text' => 'del', 'Abbreviation' => 'abbr', 'Acronym' => 'acronym', 'XHTML Attribs' => 'attribs', 'Layer' => 'layer', 'Advanced HR' => 'advhr', 'View HTML' => 'code', 'Hidden Chars' => 'visualchars', 'NB Space' => 'nonbreaking', 'Sub' => 'sub', 'Sup' => 'sup', 'Visual Aids' => 'visualaid', 'Insert Date' => 'insertdate', 'Insert Time' => 'inserttime', 'Anchor' => 'anchor', 'Style' => 'styleprops', 'Smilies' => 'emotions', 'Insert Movie' => 'media', 'Quote' => 'blockquote' );

$tadv_allbtns = array_values($buttons);
$tadv_allbtns[] = 'separator';
$tadv_allbtns[] = '|';

if ( get_option('tadv_plugins') != $plugins ) update_option( 'tadv_plugins', $plugins ); 
if ( get_option('tadv_btns1') != $toolbar_1 ) update_option( 'tadv_btns1', $toolbar_1 );
if ( get_option('tadv_btns2') != $toolbar_2 ) update_option( 'tadv_btns2', $toolbar_2 );
if ( get_option('tadv_btns3') != $toolbar_3 ) update_option( 'tadv_btns3', $toolbar_3 );
if ( get_option('tadv_btns4') != $toolbar_4 ) update_option( 'tadv_btns4', $toolbar_4 );
if ( get_option('tadv_allbtns') != $tadv_allbtns ) update_option( 'tadv_allbtns', $tadv_allbtns );

for ( $i = 1; $i < 21; $i++ ) 
	$buttons["s$i"] = "separator$i";

if ( isset($_POST['tadv']) && isset($_POST['save']) ) {	?>
	<div class="updated" id="message"><p>Options saved</p></div>
<?php } ?>

<div class="wrap">

	<h2>TinyMCE Buttons Arrangement</h2>

	<form id="tadvadmin" method="post" action="" onsubmit="tadvSerializeAll();">
	<p>Drag and drop buttons onto the toolbars below.</p>

	<div id="tadvzones">
		<input id="toolbar_1order" name="toolbar_1order" value="" type="hidden" />
		<input id="toolbar_2order" name="toolbar_2order" value="" type="hidden" />
		<input id="toolbar_3order" name="toolbar_3order" value="" type="hidden" />
		<input id="toolbar_4order" name="toolbar_4order" value="" type="hidden" />
        <input name="tadv" value="1" type="hidden" />		

		<div class="tadvdropzone">
        <ul style="position: relative;" id="toolbar_1">
<?php
if ( is_array($tadv_toolbars['toolbar_1']) ) {
    $tb1 = array();
    foreach( $tadv_toolbars['toolbar_1'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb1 += $t;
    }

    foreach( $tb1 as $name => $btn ) { 
        if ( strpos( $btn, 'separator' ) !== false ) { ?>

<li class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"> </div></li>
<?php	} else { ?>

<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb1 );
} ?>
        </ul></div>
        <br class="clear" />

        <div class="tadvdropzone">
		<ul style="position: relative;" id="toolbar_2">
<?php
if ( is_array($tadv_toolbars['toolbar_2']) ) {
    $tb2 = array();
    foreach( $tadv_toolbars['toolbar_2'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb2 = $tb2 + $t;
    }
    foreach( $tb2 as $name => $btn ) { 
        if ( strpos( $btn, 'separator' ) !== false ) { ?>

<li class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"> </div></li>
<?php	} else { ?>

<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb2 );
} ?>
        </ul></div>
        <br class="clear" />

        <div class="tadvdropzone">
		<ul style="position: relative;" id="toolbar_3">
<?php   
if ( is_array($tadv_toolbars['toolbar_3']) ) {
    $tb3 = array();
    foreach( $tadv_toolbars['toolbar_3'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb3 += $t;
    }
    foreach( $tb3 as $name => $btn ) { 
        if ( strpos( $btn, 'separator' ) !== false ) { ?>

<li class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"> </div></li>
<?php	} else { ?>

<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb3 );
} ?>
        </ul></div>
        <br class="clear" />

        <div class="tadvdropzone">
		<ul style="position: relative;" id="toolbar_4">
<?php   
if ( is_array($tadv_toolbars['toolbar_4']) ) {
    $tb4 = array();
    foreach( $tadv_toolbars['toolbar_4'] as $k ) {
        $t = array_intersect( $buttons, (array) $k );
        $tb4 += $t;
    }
    foreach( $tb4 as $name => $btn ) { 
        if ( strpos( $btn, 'separator' ) !== false ) { ?>

<li class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"> </div></li>
<?php	} else { ?>

<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb4 );
} ?>
        </ul></div>
        <br class="clear" />
    </div>

	<div id="tadvWarnmsg" ></div>

    <div id="tadvpalettediv">
        <ul style="position: relative;" id="tadvpalette">
<?php
if ( is_array($buttons) ) {
    foreach( $buttons as $name => $btn ) { 
        if ( strpos( $btn, 'separator' ) !== false ) { ?>

<li class="separator" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"> </div></li>
<?php	} else { ?>

<li class="tadvmodule" id="pre_<?php echo $btn; ?>">
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" title="<?php echo $name; ?>" />
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
        
        <p><label for="importcss" class="tadv-box">Import the current theme's CSS classes &nbsp;
        <input type="checkbox" class="tadv-chk"  name="importcss" id="importcss" <?php if ( $tadv_options['importcss'] == '1' ) echo ' checked="checked"'; ?> /></label></p>
        <p style="font-size:10px;">Custom CSS styles can be added in /wp-content/plugins/tinymce-advanced/css/tadv-mce.css. They will be imported and used in TinyMCE. The file has to be downloaded with FTP, edited and uploaded, overwriting the original. Only CSS classes can be added, also <strong>div.my-class</strong> would not work, but <strong>.my-class</strong> will.</p>
        <p><label for="fix_autop" class="tadv-box">Stop removing the &lt;p&gt; and &lt;br /&gt; tags when saving and show them in the HTML editor &nbsp;
        <input type="checkbox" class="tadv-chk"  name="fix_autop" id="fix_autop" <?php if ( $tadv_options['fix_autop'] == '1' ) echo ' checked="checked"'; ?> /></label></p>
        <p style="font-size:10px;">This will make it possible to use more advanced HTML without the back-end filtering affecting it much. It also adds two new buttons to the HTML editor: &quot;autop&quot; that allows wpautop to be run on demand and &quot;undo&quot; that can undo the last changes.</p>
		</td></tr>
<?php
    $mce_locale = ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) );
    if ( $mce_locale != 'en' ) {
        if ( ! file_exists(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/' . $mce_locale . '_dlg.js') ) { ?>
        <tr><td style="padding:2px 12px 8px;">
        <p style="font-weight:bold;">Language Settings</p>
        <p>Your WordPress language is set to <strong><?php echo get_locale(); ?></strong>. However there is no matching language installed for TinyMCE's plugins. This plugin includes several translations: German, French, Italian, Spanish, Portuguese, Russian, Japanese and Chinese. More translations are available at <a href="http://services.moxiecode.com/i18n/">TinyMCE's web site</a>.</p>
		</td></tr>
<?php	}
    } // end mce_locale
?>
	</table>

<p class="submit">
	<?php wp_nonce_field( 'tadv-save-buttons-order' ); ?>
	<input type="submit" name="save" value="Save Changes" />
	<input type="button" name="uninstall" class="tadv_btn" value="Uninstall" onclick="document.getElementById('tadv_uninst_div').style.display = 'block';" />
</p>
</form>
<br class="clear" />

<div id="tadv_uninst_div" style="">
<form method="post" action="">
<?php wp_nonce_field('tadv-uninstall'); ?>
<div>Uninstalling will remove all saved settings and buttons arrangement from the database.
<input class="button tadv_btn" type="button" name="cancel" value="Cancel" onclick="document.getElementById('tadv_uninst_div').style.display = 'none';" style="margin-left:20px" />
<input class="button tadv_btn" type="submit" name="tadv_uninstall" value="Continue" /></div>
</form>
</div>
</div>

<script type="text/javascript">
// <![CDATA[
Sortable.create("toolbar_1", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar_1","toolbar_2","toolbar_3","toolbar_4"],
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("toolbar_2", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar_1","toolbar_2","toolbar_3","toolbar_4"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("toolbar_3", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar_1","toolbar_2","toolbar_3","toolbar_4"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("toolbar_4", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar_1","toolbar_2","toolbar_3","toolbar_4"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
Sortable.create("tadvpalette", {
  dropOnEmpty: true, 
  containment: ["tadvpalette","toolbar_1","toolbar_2","toolbar_3","toolbar_4"], 
  starteffect: function(element){new Effect.Opacity(element, {duration:0, from:1.0, to:0.7}); },
  endeffect: function(element){new Effect.Opacity(element, {duration:0, from:0.7, to:1.0}); },
  overlap: 'horizontal', 
  constraint: false, onUpdate: tadvUpdateAll, 
  format: /^pre_(.*)$/
});
// ]]>
</script>
		
<?php 
    if ( $update_tadv_options )
        update_option( 'tadv_options', $tadv_options );
?>