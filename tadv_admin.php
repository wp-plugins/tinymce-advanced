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

?>
<h3 style="margin:60px auto auto;text-align:center;"><a href="plugins.php?action=deactivate&amp;plugin=tinymce-advanced%2Ftinymce-advanced.php&amp;_wpnonce=<?php echo wp_create_nonce('deactivate-plugin_tinymce-advanced/tinymce-advanced.php'); ?>">Deactivate TinyMCE Advanced</a></h3>

<?php
include('admin-footer.php');
exit;
}

$update_tadv_options = false;
$imgpath = get_bloginfo('wpurl') . '/wp-content/plugins/tinymce-advanced/images/';

$tadv_toolbars = get_option('tadv_toolbars');
if ( ! is_array($tadv_toolbars) ) {
	$tadv_toolbars = tadv_defaults();
    update_option( 'tadv_toolbars', $tadv_toolbars );
}

$tadv_options = get_option('tadv_options');
if ( ! is_array($tadv_options) ) {
	$tadv_options = array( 'advlink' => '1', 'advimage' => '1' );
    $update_tadv_options = true;
}

if ( isset( $_POST['save'] ) ) {
    check_admin_referer( 'tadv-save-buttons-order' );
	$tb1 = $tb2 = $tb3 = $tb4 = array();
	parse_str( $_POST['toolbar_1order'], $tb1 );
	parse_str( $_POST['toolbar_2order'], $tb2 );
	parse_str( $_POST['toolbar_3order'], $tb3 );
	parse_str( $_POST['toolbar_4order'], $tb4 );
	$tadv_toolbars = $tb1 + $tb2 + $tb3 + $tb4;
	update_option( 'tadv_toolbars', $tadv_toolbars );

    $tadv_options['advlink'] = $_POST['advlink'] ? 1 : 0;
    $tadv_options['advimage'] = $_POST['advimage'] ? 1 : 0;
    $tadv_options['contextmenu'] = $_POST['contextmenu'] ? 1 : 0;
    $tadv_options['importcss'] = $_POST['importcss'] ? 1 : 0;
    $update_tadv_options = true;
}

$btns1 = $btns2 = $btns3 = $btns4 = array();
$hidden_row = false;

if ( is_array($tadv_toolbars['toolbar_1']) ) {
    $btns1 = $tadv_toolbars['toolbar_1'];
    
    if ( ! empty($btns1) ) {
    	foreach( $btns1 as $k => $v ) {
        	if ( strpos($v, 'separator') !== false ) $btns1[$k] = 'separator';
        	if ( 'layer' == $v ) $l = $k;
        	if ( 'wp_adv' == $v ) $hidden_row = 2;
        	if ( empty($v) ) unset($btns1[$k]);
    	}
    	if ( $l ) array_splice( $btns1, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
    }
}

if ( is_array($tadv_toolbars['toolbar_2']) ) {
    $btns2 = $tadv_toolbars['toolbar_2'];
    
    if ( ! empty($btns2) ) {

		foreach( $btns2 as $k => $v ) {
        	if ( strpos($v, 'separator') !== false ) $btns2[$k] = 'separator';
        	if ( 'layer' == $v ) $l = $k;
        	if ( 'wp_adv' == $v ) $hidden_row = 3;
        	if ( empty($v) ) unset($btns2[$k]);
    	}

    	if ( $l ) array_splice( $btns2, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
	}
}

if ( is_array($tadv_toolbars['toolbar_3']) ) {
    $btns3 = $tadv_toolbars['toolbar_3'];
    
    if ( ! empty($btns3) ) {
    
		foreach( $btns3 as $k => $v ) {
        	if ( strpos($v, 'separator') !== false ) $btns3[$k] = 'separator';
        	if ( 'layer' == $v ) $l = $k;
        	if ( 'wp_adv' == $v ) $hidden_row = 4;
        	if ( empty($v) ) unset($btns3[$k]);
    	}
    	
		if ( $l ) array_splice( $btns3, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
    }
}

if ( $hidden_row ) $tadv_options['hidden_row'] = $hidden_row;
$update_tadv_options = true;

if ( is_array($tadv_toolbars['toolbar_4']) ) {
    $btns4 = $tadv_toolbars['toolbar_4'];
    
	if ( ! empty($btns4) ) {
	
		foreach( $btns4 as $k => $v ) {
        	if ( strpos($v, 'separator') !== false ) $btns4[$k] = 'separator';
        	if ( 'layer' == $v ) $l = $k;
        	if ( empty($v) ) unset($btns4[$k]);
    	}
    	
		if ( $l ) array_splice( $btns4, $l, 1, array('insertlayer', 'moveforward', 'movebackward', 'absolute') );
	}
}

if ( empty($btns1) && empty($btns2) && empty($btns3) && empty($btns4) ) {
    $allbtns = array();
    ?><div class="error" id="message"><p>All toolbars are empty!</p></div><?php
} else {
    $allbtns = array_merge( $btns1, $btns2, $btns3, $btns4 );

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

if ( get_option('tadv_plugins') != $plugins ) update_option( 'tadv_plugins', $plugins ); 
if ( get_option('tadv_btns1') != $btns1 ) update_option( 'tadv_btns1', $btns1 );
if ( get_option('tadv_btns2') != $btns2 ) update_option( 'tadv_btns2', $btns2 );
if ( get_option('tadv_btns3') != $btns3 ) update_option( 'tadv_btns3', $btns3 );
if ( get_option('tadv_btns4') != $btns4 ) update_option( 'tadv_btns4', $btns4 ); 

$buttons = array( 'Kitchen Sink' => 'wp_adv', 'Bold' => 'bold', 'Italic' => 'italic', 'Strikethrough' => 'strikethrough', 'Underline' => 'underline', 'Bullet List' => 'bullist', 'Numbered List' => 'numlist', 'Outdent' => 'outdent', 'Indent' => 'indent', 'Allign Left' => 'justifyleft', 'Center' => 'justifycenter', 'Alligh Right' => 'justifyright', 'Justify' => 'justifyfull', 'Cut' => 'cut', 'Copy' => 'copy', 'Paste' => 'paste', 'Link' => 'link', 'Remove Link' => 'unlink', 'Insert Image' => 'image', 'More Tag' => 'wp_more', 'Split Page' => 'wp_page', 'Search' => 'search', 'Replace' => 'replace', '<!--fontselect-->' => 'fontselect', '<!--fontsizeselect-->' => 'fontsizeselect', 'Help' => 'wp_help', 'Full Screen' => 'fullscreen', '<!--styleselect-->' => 'styleselect', '<!--formatselect-->' => 'formatselect', 'Text Color' => 'forecolor', 'Paste as Text' => 'pastetext', 'Paste from Word' => 'pasteword', 'Remove Format' => 'removeformat', 'Clean Code' => 'cleanup', 'Check Spelling' => 'spellchecker', 'Character Map' => 'charmap', 'Print' => 'print', 'Undo' => 'undo', 'Redo' => 'redo', 'Table' => 'tablecontrols', 'Citation' => 'cite', 'Inserted Text' => 'ins', 'Deleted Text' => 'del', 'Abbreviation' => 'abbr', 'Acronym' => 'acronym', 'XHTML Attribs' => 'attribs', 'Layer' => 'layer', 'Advanced HR' => 'advhr', 'View HTML' => 'code', 'Hidden Chars' => 'visualchars', 'NB Space' => 'nonbreaking', 'Sub' => 'sub', 'Sup' => 'sup', 'Visual Aids' => 'visualaid', 'Insert Date' => 'insertdate', 'Insert Time' => 'inserttime', 'Anchor' => 'anchor', 'Style' => 'styleprops', 'Smilies' => 'emotions', 'Insert Movie' => 'media' );  

$active_plugins = get_settings('active_plugins');
$add = array();
foreach( $active_plugins as $plug ) {
    if ( strpos( $plug, 'wpg2' ) !== false ) $add['Gallery 2'] = 'g2image';
    if ( strpos( $plug, 'nextgen-gallery' ) !== false ) $add['Nextgen Gallery'] = 'NextGEN';
    if ( strpos( $plug, 'vipers-video' ) !== false ) $add["Viper's Video"] = 'vipersvideoquicktags';
    if ( strpos( $plug, 'embedded-video' ) !== false ) $add['EmbeddedVideo'] = 'embeddedvideo';
    if ( strpos( $plug, 'imagemanager' ) !== false ) $add['Image Manager'] = 'ps_imagemanager_tinymceplugin';
}

if ( ! empty($add) ) $buttons += $add;

$separators = array( 's1' => 'separator1', 's2' => 'separator2', 's3' => 'separator3', 's4' => 'separator4', 's5' => 'separator5', 's6' => 'separator6', 's7' => 'separator7', 's8' => 'separator8', 's9' => 'separator9', 's10' => 'separator10', 's11' => 'separator11', 's12' => 'separator12', 's13' => 'separator13', 's14' => 'separator14', 's15' => 'separator15', 's16' => 'separator16', 's17' => 'separator17', 's18' => 'separator18', 's19' => 'separator19', 's20' => 'separator20' );

$buttons += $separators;

if ( isset( $_POST['tadv'] ) ) { 
    if ( isset($_POST['save']) ) { ?><div class="updated" id="message"><p>Options saved</p></div><?php }
    if ( isset($_POST['reset']) ) { ?><div class="updated" id="message"><p>Defaults loaded</p></div><?php }
} ?>

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
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
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
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
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
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
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
<div class="tadvitem"><img src="<?php echo $imgpath . $btn . '.gif'; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" />
<span class="descr"> <?php echo $name; ?></span></div></li>
<?php   }
    }
    $buttons = array_diff( $buttons, $tb4 );
}

$tadv_btns_left = is_array($buttons) ? array_values($buttons) : array();
if ( get_option('tadv_btns_left') != $tadv_btns_left ) update_option( 'tadv_btns_left', $tadv_btns_left ); ?>

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
        <p>Custom CSS styles can be added in /wp-content/plugins/tinymce-advanced/css/tadv-mce.css. They will be imported in the Style Select menu. The file has to be downloaded with FTP, edited and uploaded, overwriting the original.</p>
        </td></tr>
<?php
    $mce_locale = ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) );
    if ( $mce_locale != 'en' ) {
        
        if ( ! file_exists(ABSPATH . PLUGINDIR . '/tinymce-advanced/mce/advlink/langs/' . $mce_locale . '_dlg.js') ) {
?>
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