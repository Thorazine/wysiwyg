$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
        }
    });
});

$(document).ready(function() {
	tinymce.PluginManager.add('library', function(editor, url) {
		// Add a button that opens a window
		editor.addButton('library', {
			// text: '<i class="fa fa-cloud-upload"></i>',
			icon: 'mce-ico mce-i-image',
			onclick: function() {
				var event = jQuery.Event('library-open');
				event.editor = editor;
				parent.$('body').trigger(event);
			}
		});

		// editor.insertContent('Title: ' + e.data.title);

		return {
			getMetadata: function () {
				return    {
					title: "Library plugin",
					url: "http://no-url.com"
				};
			}
		};
	});

	tinymce.init({
		selector:'textarea',
		branding: false,
		relative_urls : false,
		remove_script_host : true,
		document_base_url : BASE_URL,
		image_class_list: [
			{title: 'Left', value: 'left'},
			{title: 'Full', value: 'full'},
			{title: 'Right', value: 'right'}
		],
		image_dimensions: false,
		plugins: [
		    "advlist lists link print hr anchor",
		    "searchreplace visualblocks visualchars code fullscreen",
		    "media nonbreaking contextmenu directionality library",
		    "paste textcolor colorpicker textpattern imagetools noneditable"
		],
		menubar: "insert edit tools",
		toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
		toolbar2: "link media | forecolor backcolor | fullscreen | library | code",
		media_live_embeds: true,
		media_dimensions: false,
		media_poster: false,
		media_alt_source: false,
		media_url_resolver: function (data, resolve/*, reject*/) {
		    if (youtube_parser(data.url)) {
				var embedHtml = '<div class="iframe youtube"><iframe src="https://www.youtube.com/embed/' + youtube_parser(data.url) +
				'" width="1200"></iframe></div>';
				resolve({html: embedHtml});
		    } else {
				resolve({html: ''});
		    }
		},
		style_formats: [
			{ title: "H1", block: "h1" },
			{ title: "H2", block: "h2" },
			{ title: "Normal", block: "p" },
		],
		content_css: [
			// "{{ asset('assets/cms/css/wysiwyg.css') }}",
		],
		file_browser_callback: function(field_name, url, type, win) {
		    win.document.getElementById(field_name).value = 'my browser value';
		},
		file_picker_callback: function(callback, value, meta) {
		    // Provide file and text for the link dialog
		    if (meta.filetype == 'file') {
		    	callback('mypage.html', {text: 'My text'});
		    }

		    // Provide image and alt text for the image dialog
		    if (meta.filetype == 'image') {
		    	// callback('myimage.jpg', {alt: 'My alt text'});

		    }

		    // Provide alternative source and posted for the media dialog
		    if (meta.filetype == 'media') {
		    	callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
		    }
		},
		file_picker_types: 'file image media',
		// images_upload_handler: function (blobInfo, successCallback, failureCallbck) {

		// 	formData = new FormData();
		//     formData.append('file', blobInfo.blob(), blobInfo.filename());

		// 	$.ajax({
		// 		url: BASE_URL+'/image',
		// 		data: formData,
		// 		processData: false,
		// 		contentType: false,
		// 		type: 'POST',
		// 		success: function(data){
		// 			successCallback(data.url);
		// 		},
		// 		error() {
		// 			failureCallbck('HTTP Error: gvd');
		// 		}
		// 	});
		// },
	});



});
