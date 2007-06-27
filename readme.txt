=== TinyMCE Advanced ===
Contributors: Andrew Ozz
Donate link: 
Tags: wysiwyg, formatting, tinymce, write, edit, post
Requires at least: 2.2
Tested up to: 2.2
Stable tag: trunk

Enables most of the advanced features of TinyMCE, the WordPress WYSIWYG editor.

== Description ==

This plugin adds 12 plugins to TinyMCE: Advanced hr, Advanced Image, Advanced Link, Context Menu, Full Screen, Layer, Media, Print, Search and Replace, Table, Visual Characters and XHTML Extras. Together these plugins add over 30 new buttons to the toolbar, which is now two rows plus one hidden row.

= Some of the new features added by this plugin =

* Imports all CSS classes from the main theme’s stylesheet and add them to a drop-down list.

* Fullscreen mode.

* Support for making and editing tables.

* Much better (advanced) link and image dialogs that offer a lot of options.

* Search and Replace while editing.

* Some support for XHTML specific tags and for layers.


== Installation ==

1. Download.

2. Unzip.

3. Upload the *tinymce-advanced* folder to the plugins directory and *themes* and *plugins* folders to the TinyMCE’s directory at wp-includes/js/tinymce/.

4. Activate the plugin.

5. Try your new and improved wysiwyg editor (after clearing your browser cache).


== Frequently Asked Questions ==

= No styles are imported in the Styles drop-down menu. =

These styles (just the classes) are imported from your current themes style.css file. However some themes use @import to load the actual css file(s). Tiny does not follow these links for now. To make the classes appear, add their names to tinymce.css file in the plugin’s folder. You don’t need to copy the whole classes, just add the names, like that:

    .something{}

    .something_else{}

    .my_class{}

    .my_other_class{}

= I’ve just installed this plugin, but it doesn’t do anything. =

Log out of WordPress, clear your browser cache, quit and restart the browser and try again. If that does not work, check to see if you uploaded the tinymce plugins to the right directory - wp-includes/js/tinymce/plugins. You should see 19 subdirectories there named after the plugins.

= The Media plugin does not work. =

The Media plugin does not work right in IE6. It seems that it’s conflicting with some of the other js loaded when editing posts in WordPress. However it works nicely in both Firefox and Opera.

= I can see the menus in full screen mode in Firefox =

Just click "Save and continue editing" to refresh the window.

= Other questions? Screenshots? =

Please visit the homepage for [TinyMCE Advanced](http://www.laptoptips.ca/projects/tinymce-advanced/). 

    
