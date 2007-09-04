function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertEmotion(code) {

	var html = ' ' + code + ' ';
	
    tinyMCE.execCommand('mceInsertContent', false, html);
	tinyMCEPopup.close();
}
