import asset from './../functions/asset.js';

export default {
    methods: {
        dropzoneAdded: function(file) {
            $(file.previewElement).find('.dz-status').html('Bezig met uploaden');
            $(file.previewElement).parent().prepend(file.previewElement);
        },
        dropzoneSuccess: function (file, response) {
        	$(file.previewElement).remove();
        	this.dropzoneFiles.unshift(response);
        },
        dropzoneError: function(file) {
        	if(file.xhr.status == 500) {
        		let response = JSON.parse(file.xhr.responseText);
        	}
        },
        dropzoneUploadProgress: function(totaluploadprogress, totalBytes, totalBytesSent) {
        	return 100 - totaluploadprogress;
        },
        dropzoneProgress: function(file, progress, bytesSent) {
        	console.log(progress);
        },
        dropzoneTemplate: function() {
            return `
				<div class="dz-preview dz-file-preview">
					<div class="dz-image">
						<div class="dz-progress">
							<span class="dz-upload" data-dz-uploadprogress></span>
						</div>
					    <img data-dz-thumbnail />
					</div>
					<div class="dz-name">
						<span data-dz-name></span>
					</div>
					<div class="dz-error">
						<span>Dit document kon niet verwerkt worden.</span>
					</div>
				</div>
			`;
        }
    }
}
