/**
 * plugin загрузки картинок
 */


tinymce.PluginManager.add('img2', function(editor) {
	editor.addCommand('img2', function() {
		tinyMCE.activeEditor.windowManager.open({
		title: "Img Upload",	
		buttons: [{
				text: 'Upload',
				classes:'widget btn primary first abs-layout-item ImgUploadButton',
				disabled:true
			},
			{
				text: 'Close',
				onclick: 'close'
			}],

		url : 'plugins/img2/img2.htm',
			width : 320,
			height : 80
			}, {
			custom_param : 1
		});		
		editor.execCommand('mceInsertContent', false, '<hr />');
	});

	editor.addButton('img2', {
		title: 'Img',
		cmd: 'img2',
		icon: 'image',
	});

	editor.addMenuItem('img2', {
		cmd: 'img2',
		icon: 'image',
		text: 'Image',
		context: 'file'
	});
});
