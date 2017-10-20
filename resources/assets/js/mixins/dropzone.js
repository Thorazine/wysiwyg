import asset from './../functions/asset.js';

export default {
    methods: {
        dropzoneAdded: function(file) {
        	// this.$store.dispatch('maxFileUploads', file);

        	if(! this.$store.state.uploading) {
        		this.$store.state.uploading = true;
        	}

            file.uploading = true;
            $(file.previewElement).find('.dz-status').html('Bezig met uploaden');
        },
        dropzoneSuccess: function (file, response) {
            // // this.$store.dispatch('hasCookie');

            // set the values in the proper items
            let newDocument = response;
            file.DocumentReferenceId = response.DocumentReferenceId;

            // Update the status
            $(file.previewElement).find('.dz-status').html('Bezig met woorden tellen');

            this.$store.dispatch('addToWaitingForCount');

            // Get word count
            axios({
                method: 'get',
                url: asset('api/documents/count'),
                params: {
                    hashid: this.$store.state.hashid,
                    lang: this.$store.state.lang,
                    DocumentReferenceId: response.DocumentReferenceId,
                }
            })
            .then( (response) => {
            	this.$store.dispatch('subtractFromWaitingForCount');
                file.AmountOfWords = response.data.AmountOfWords;
                newDocument.AmountOfWords = response.data.AmountOfWords;
                newDocument.file = file;
                this.$store.state.documents.push(newDocument);

                file.uploading = false;
                this.$store.state.uploading = false;
                $(file.previewElement).find('.dz-uploading-spinner').hide();
                $(file.previewElement).find('.dz-document').show();
                $(file.previewElement).find('.dz-document-words').html(response.data.AmountOfWords+' woorden');
                $(file.previewElement).hide();

                let filesInQueue = this.$store.state.dz.getQueuedFiles();
                if(! filesInQueue.length) {
	                this.$store.dispatch('getOrder');
	            }
            })
            .catch( (error) => {
            	this.$store.dispatch('subtractFromWaitingForCount');
                $(file.previewElement).find('.dz-uploading-spinner').hide();
                $(file.previewElement).find('.dz-document').hide();
                $(file.previewElement).find('.dz-error').show();
            });
        },
        dropzoneQueueComplete: function(file, xhr, formData) {
        	// Get the order
            this.$store.state.uploading = false;
        },
        dropzoneManual: function(file) {

            $.each(this.$store.state.documents, (i, values) => {
                if(file.DocumentReferenceId == values.DocumentReferenceId) {
                	this.$store.state.documents[i].file = file;
                }
            });

            $(file.previewElement).find('.dz-uploading-spinner').hide();
            $(file.previewElement).find('.dz-document').show();
            $(file.previewElement).find('.dz-document-words').html(file.AmountOfWords+' woorden');
            $(file.previewElement).hide();
        },
        dropzoneRemove: function(file, error, xhr) {

        },
        dropzoneError: function(file) {

        	if(file.xhr.status == 500) {
        		let response = JSON.parse(file.xhr.responseText);
        	}
        },
        dropzoneTemplate: function() {
            return `
				<div class="dz-preview dz-file-preview">
					<div class="dz-uploading">
						<div class="dz-uploading-spinner">
							<div class="dz-uploading-spinner-bar"></div>
							<div class="dz-status">Bezig met uploaden</div>

						</div>
					</div>
					<div class="dz-document">
						<div class="dz-document-title"><span data-dz-name></span></div>
						<div class="dz-document-words"></div>
					</div>
					<div class="dz-error">
						<span>Dit document kon niet verwerkt worden.</span>
					</div>
				</div>
			`;
        }
    }
}
