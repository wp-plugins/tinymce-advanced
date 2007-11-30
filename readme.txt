=== TinyMCE Advanced ===
Contributors: Andrew Ozz
Donate link: 
Tags: wysiwyg, formatting, tinymce, write, edit, post
Requires at least: 2.2.1
Tested up to: 2.3
Stable tag: 2.1

Enables most of the advanced features of TinyMCE, the WordPress WYSIWYG editor. 

== Description ==

This plugin adds 16 plugins to TinyMCE: Advanced hr, Advanced Image, Advanced Link, Context Menu, Emotions (Smilies), Full Screen, IESpell, Layer, Media, Nonbreaking, Print, Search and Replace, Style, Table, Visual Characters and XHTML Extras. 

Version 2.0 includes an admin page for arranging the TinyMCE toolbar buttons, easy installation, a lot of bugfixes, customized "Smilies" plugin that uses the built-in WordPress smilies, etc. The admin page uses Scriptaculous and Prototype.js (similar to the "Widgets" admin page) that lets you "drag and drop" the TinyMCE buttons to arrange your own toolbars and enables/disables the corresponding plugins depending on the used buttons.

New in version 2.1: Improved language selection, improved compatibility with WordPress 2.3 and TinyMCE 2.1.1.1, option to override some of the imported css classes and other small improvements and bugfixes.

**Language Support:** The plugin interface in only in English, but the TinyMCE plugins include several translations: German, French, Italian, Spanish, Portuguese, Russian and Chinese. Another 36 translations are available as a [separate download](http://svn.wp-plugins.org/tinymce-advanced/branches/tinymce-advanced_extra-languages.zip).


= Some of the new features added by this plugin =

* Imports all CSS classes from the main theme’s stylesheet and add them to a drop-down list.
* Full screen mode.
* Support for making and editing basic tables.
* In-line css styles.
* Much better (advanced) link and image dialogs that offer a lot of options.
* Search and Replace while editing.
* Support for XHTML specific tags and for (div based) layers.


== Installation ==

1. Download.
2. Unzip.
3. Upload to the plugins directory (wp-content/plugins).
4. Activate the plugin.
5. Set your preferences at "Manage - TinyMCE Advanced".
6. Clear your browser cache.


= Upgrading from TinyMCE Advanced 2.0-beta  =

1. Deactivate the beta version.
2. Delete the "tinymce-advanced" folder from WordPress plugins directory.
3. Follow the above steps to install the new version.


= Upgrading from TinyMCE Advanced 1.0 =

This version of TinyMCE Advanced is self-contained. It does not require separate installation of TinyMCE plugins. If you have one of the previous versions (1.0 or 1.0.1) installed, please follow these steps:

1. Deactivate the old TinyMCE Advanced.
2. Backup the TinyMCE plugins folder, located at wp-includes/js/tinymce/plugins.
3. Delete the following TinyMCE plugins that were added when installing the previous version (delete the directories with these names from wp-includes/js/tinymce/plugins):
    
    * advhr
    * contextmenu
    * print
    * visualchars
    * advimage
    * advlink
    * table
    * xhtmlxtras
    * nonbreaking
    * layer
    * searchreplace
    * fullscreen

4. After deleting the above plugins, you should have the 7 default plugins that came with WordPress: autosave, directionality, inlinepopups, paste, spellchecker, wordpress, wphelp. Or if you prefer, delete the whole tinymce plugins directory (wp-includes/js/tinymce/plugins) and upload a fresh copy from the WordPress installation package.
5. Delete the tinymce-advanced folder from WordPress plugins directory (wp-includes/plugins).
6. Follow the installation instructions above to install the new version.


== Frequently Asked Questions ==

= After installing the plugin, the editor background is black/dark or the font is too small =

This is due to TinyMCE importing the styles from your theme and trying to make the editor look as close to your site as possible. However that does not work well in some themes. To fix it either check "reset some of the css styles" checkbox in the advanced settings or uncheck the "import the css classes" checkbox.

= No styles are imported in the Styles drop-down menu. =

These styles (just the classes) are imported from your current theme style.css file. However some themes use @import to load the actual css file(s). Tiny does not follow these links. To make the classes appear, add their names to tinymce.css file located in "tinymce-advanced/css". You do not need to copy the whole classes, just add the names, like that:

    .my-class{}
    .my-other-class{}

= I just added my css classes to tinymce.css but they are still missing from the editor. =

Click on "Save Changes" on the admin page of the plugin, even if you did not change any buttons. This will force TinyMCE to reload the css files.

= I have just installed this plugin, but it does not do anything. =

Log out of WordPress, clear your browser cache, quit and restart the browser and try again. If that does not work, there may be a caching proxy or network cache somewhere between you and your host. You may need to wait for a few hours until this cache expires.

= When I add "Smilies", they do not show in the editor. =

The "Emotions" button in TinyMCE adds the codes for the smilies. The actual images are added by WordPress when viewing the Post/Page. Make sure the checkbox "Convert emoticons to graphics on display" in "Options - Writing" is checked.

= The Media plugin is missing. =

Yes, the Media plugin is disabled in IE. It seems that it conflicts with some of the other js loaded when editing posts in WordPress. However it works nicely in both Firefox and Opera.

= Some of the window shows through in full screen mode. =

Click on "Save and continue editing" to refresh it.

= The plugin does not add any buttons. =

Make sure the "Use the visual editor when writing" checkbox under "Users - Your Profile" is checked.

== Screenshots ==

= Other questions? Screenshots? =

Please visit the homepage for [TinyMCE Advanced](http://www.laptoptips.ca/projects/tinymce-advanced/). 
