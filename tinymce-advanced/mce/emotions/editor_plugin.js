/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 * 
 * Modified to use the build-in smilies in WordPress.
 */

// UK lang variables
tadvmce = realTinyMCE;
tadvmce.addToLang('emotions',{
title : ':-)'
});

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('emotions');

// Plugin static class
var TinyMCE_EmotionsPlugin = {
	getInfo : function() {
		return {
			longname : 'Emotions',
			author : 'Moxiecode Systems AB',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/emotions',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	/**
	 * Returns the HTML contents of the emotions control.
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "emotions":
				return tinyMCE.getButtonHTML(cn, 'lang_emotions_desc', '{$pluginurl}/images/emotions.gif', 'mceEmotion');
		}

		return "";
	},

	/**
	 * Executes the mceEmotion command.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mceEmotion":
				var template = new Array();

				template['file'] = tinyMCE.baseURL + '/../../../wp-content/plugins/tinymce-advanced/mce/emotions/emotions.htm'; // Relative to theme
				template['width'] = 230;
				template['height'] = 140;

				// Language specific width and height addons
				template['width'] += tinyMCE.getLang('lang_emotions_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_emotions_delta_height', 0);

				tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});

				return true;
		}

		// Pass to next handler in chain
		return false;
	}
};

// Register plugin
tinyMCE.addPlugin('emotions', TinyMCE_EmotionsPlugin);
