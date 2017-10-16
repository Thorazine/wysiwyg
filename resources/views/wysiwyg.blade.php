<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="app">
		<library></library>
		<textarea id="wysiwyg1"></textarea>
		<textarea id="wysiwyg2"></textarea>
	</div>
	<script src="https://use.fontawesome.com/5daec6a801.js"></script>
	<script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
	<script>
		var BASE_URL = "{{ env('APP_URL') }}";
	</script>
	<script src="{{ asset('js/bundle.js') }}"></script>
	<script src="{{ asset('js/library.js') }}"></script>

	@if(env('APP_DEBUG'))
		<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"></script>
	@endif


</body>
</html>
