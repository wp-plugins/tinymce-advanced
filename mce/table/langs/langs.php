<?php
$lang_file = dirname(__FILE__) . '/' . $mce_locale . '_dlg.js';

if ( is_file($lang_file) && is_readable($lang_file) ) {
	$strings .= getFileContents($lang_file);
}
?>