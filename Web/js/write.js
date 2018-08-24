$(function()
{
	tinyMCE.init({
		mode: 'textareas',
		plugin: 'a_tinymec_plugin',
		a_plugin_option: 'true',
		a_configuration_option: 300,
		plugins: 'autolink link preview',
		toolbar: [
			'undo redo | bold italic underline | alignleft aligncenter alignright | link | preview'
		]
	});
	// $('.formAdd').on('click', '#bPub', function()
	// {
		// var book = window.location.href;
		// book = book.split('-');
		// var Fdata = [
			// $('#bTitle').val(),
			// book[1]
		// ];
		// var Content = encodeURIComponent(tinyMCE.get('bDesc').getContent());
		// $.ajax({
			// type: "POST",
			// url: "ajax/form.php",
			// data: "role=publish&fData="+Fdata+"&sData="+Content,
			// success: function(data)
			// {
				// if(data == true)
				// {
					// var url = document.location.href;
					// var nUrl = url.split('?');
					// window.location = nUrl[0]+'?success';
				// }
				// else
					// $('.error').html(data);
			// }
		// });
	// });
	// $('.formMod').on('click', '#bMod', function()
	// {
		// var id = window.location.href;
		// id = id.split('-');
		// var Fdata = [
			// $('#bTitle').val(),
			// $('#bCat').val(),
			// id[1]
		// ];
		// var Content = encodeURIComponent(tinyMCE.get('bDesc').getContent());
		// $.ajax({
			// type: "GET",
			// url: "ajax/form.php",
			// data: "role=modifBillet&fData="+Fdata+"&sData="+Content,
			// success: function(data)
			// {
				// if(data == true)
				// {
					// var url = document.location.href;
					// var nUrl = url.split('?');
					// window.location = nUrl[0]+'?success';
				// }
				// else
					// $('.error').html(data);
			// }
		// });
	// });
});