/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

// UK lang variables
tadvmce = realTinyMCE;
tadvmce.addToLang('advlink',{
general_tab : 'General',
popup_tab : 'Popup',
events_tab : 'Events',
advanced_tab : 'Advanced',
general_props : 'General properties',
popup_props : 'Popup properties',
event_props : 'Events',
advanced_props : 'Advanced properties',
popup_opts : 'Options',
anchor_names : 'Anchors',
target_same : 'Open in this window / frame',
target_parent : 'Open in parent window / frame',
target_top : 'Open in top frame (replaces all frames)',
target_blank : 'Open in new window',
popup : 'Javascript popup',
popup_url : 'Popup URL',
popup_name : 'Window name',
popup_return : 'Insert \'return false\'',
popup_scrollbars : 'Show scrollbars',
popup_statusbar : 'Show status bar',
popup_toolbar : 'Show toolbars',
popup_menubar : 'Show menu bar',
popup_location : 'Show location bar',
popup_resizable : 'Make window resizable',
popup_dependent : 'Dependent (Mozilla/Firefox only)',
popup_size : 'Size',
popup_position : 'Position (X/Y)',
id : 'Id',
style: 'Style',
classes : 'Classes',
target_name : 'Target name',
langdir : 'Language direction',
target_langcode : 'Target language',
langcode : 'Language code',
encoding : 'Target character encoding',
mime : 'Target MIME type',
rel : 'Relationship page to target',
rev : 'Relationship target to page',
tabindex : 'Tabindex',
accesskey : 'Accesskey',
ltr : 'Left to right',
rtl : 'Right to left'
});

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('advlink');

var TinyMCE_AdvancedLinkPlugin = {
	getInfo : function() {
		return {
			longname : 'Advanced link',
			author : 'Moxiecode Systems AB',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/advlink',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
		inst.addShortcut('ctrl', 'k', 'lang_advlink_desc', 'mceAdvLink');
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "link":
				return tinyMCE.getButtonHTML(cn, 'lang_link_desc', '{$themeurl}/images/link.gif', 'mceAdvLink');
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceAdvLink":
				var anySelection = false;
				var inst = tinyMCE.getInstanceById(editor_id);
				var focusElm = inst.getFocusElement();
				var selectedText = inst.selection.getSelectedText();

				if (tinyMCE.selectedElement)
					anySelection = (tinyMCE.selectedElement.nodeName.toLowerCase() == "img") || (selectedText && selectedText.length > 0);

				if (anySelection || (focusElm != null && focusElm.nodeName == "A")) {
					var template = new Array();

					template['file']   = tinyMCE.baseURL + '/../../../wp-content/plugins/tinymce-advanced/mce/advlink/link.htm';
					template['width']  = 480;
					template['height'] = 400;

					// Language specific width and height addons
					template['width']  += tinyMCE.getLang('lang_advlink_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_advlink_delta_height', 0);

					tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});
				}

				return true;
		}

		return false;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		if (node == null)
			return;

		do {
			if (node.nodeName == "A" && tinyMCE.getAttrib(node, 'href') != "") {
				tinyMCE.switchClass(editor_id + '_advlink', 'mceButtonSelected');
				return true;
			}
		} while ((node = node.parentNode));

		if (any_selection) {
			tinyMCE.switchClass(editor_id + '_advlink', 'mceButtonNormal');
			return true;
		}

		tinyMCE.switchClass(editor_id + '_advlink', 'mceButtonDisabled');

		return true;
	}
};

tinyMCE.addPlugin("advlink", TinyMCE_AdvancedLinkPlugin);
