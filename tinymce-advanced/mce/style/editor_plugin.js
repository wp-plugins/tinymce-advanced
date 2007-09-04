/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

// UK lang variables
tadvmce = realTinyMCE;
tadvmce.addToLang('style',{
title : 'Edit CSS Style',
styleinfo_desc : 'Edit CSS Style',
apply : 'Apply',
text_tab : 'Text',
background_tab : 'Background',
block_tab : 'Block',
box_tab : 'Box',
border_tab : 'Border',
list_tab : 'List',
positioning_tab : 'Positioning',
text_props : 'Text',
text_font : 'Font',
text_size : 'Size',
text_weight : 'Weight',
text_style : 'Style',
text_variant : 'Variant',
text_lineheight : 'Line height',
text_case : 'Case',
text_color : 'Color',
text_decoration : 'Decoration',
text_overline : 'overline',
text_underline : 'underline',
text_striketrough : 'strikethrough',
text_blink : 'blink',
text_none : 'none',
background_color : 'Background color',
background_image : 'Background image',
background_repeat : 'Repeat',
background_attachment : 'Attachment',
background_hpos : 'Horizontal position',
background_vpos : 'Vertical position',
block_wordspacing : 'Word spacing',
block_letterspacing : 'Letter spacing',
block_vertical_alignment : 'Vertical alignment',
block_text_align : 'Text align',
block_text_indent : 'Text indent',
block_whitespace : 'Whitespace',
block_display : 'Display',
box_width : 'Width',
box_height : 'Height',
box_float : 'Float',
box_clear : 'Clear',
padding : 'Padding',
same : 'Same for all',
top : 'Top',
right : 'Right',
bottom : 'Bottom',
left : 'Left',
margin : 'Margin',
style : 'Style',
width : 'Width',
height : 'Height',
color : 'Color',
list_type : 'Type',
bullet_image : 'Bullet image',
position : 'Position',
positioning_type : 'Type',
visibility : 'Visibility',
zindex : 'Z-index',
overflow : 'Overflow',
placement : 'Placement',
clip : 'Clip'
});

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('style');

var TinyMCE_StylePlugin = {
	getInfo : function() {
		return {
			longname : 'Style',
			author : 'Moxiecode Systems AB',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/style',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "styleprops":
				return tinyMCE.getButtonHTML(cn, 'lang_style_styleinfo_desc', '{$pluginurl}/images/styleprops.gif', 'mceStyleProps', true);
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		var e, inst;

		// Handle commands
		switch (command) {
			case "mceStyleProps":
				TinyMCE_StylePlugin._styleProps();
				return true;

			case "mceSetElementStyle":
				inst = tinyMCE.getInstanceById(editor_id);
				e = inst.selection.getFocusElement();

				if (e) {
					e.style.cssText = value;
					inst.repaint();
				}

				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		if (node.nodeName == 'BODY')
			tinyMCE.switchClass(editor_id + '_styleprops', 'mceButtonDisabled');
		else
			tinyMCE.switchClass(editor_id + '_styleprops', 'mceButtonNormal');
	},

	// Private plugin specific methods

	_styleProps : function() {
		var e = tinyMCE.selectedInstance.selection.getFocusElement();

		if (!e || e.nodeName == 'BODY')
			return;

		tinyMCE.openWindow({
			file : tinyMCE.baseURL + '/../../../wp-content/plugins/tinymce-advanced/mce/style/props.htm',
			width : 480 + tinyMCE.getLang('lang_style_props_delta_width', 0),
			height : 320 + tinyMCE.getLang('lang_style_props_delta_height', 0)
		}, {
			editor_id : tinyMCE.selectedInstance.editorId,
			inline : "yes",
			style_text : e.style.cssText
		});
	}
};

tinyMCE.addPlugin("style", TinyMCE_StylePlugin);
