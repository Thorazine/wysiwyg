// import asset from './../functions/asset.js';

export default {
    methods: {
		cropperSetImage: function(url) {
			this.imgSrc = url;
		},
    	cropperCropImage: function() {
            // get image data for post processing, e.g. upload or setting image src
            this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
        },
        cropperRotateLeft: function() {
            // guess what this does :)
            this.$refs.cropper.rotate(-1);
        },
        cropperRotateRight: function() {
            // guess what this does :)
            this.$refs.cropper.rotate(1);
        },
        cropperFlipHorizontal: function() {
        	this.scaleX = (this.scaleX == 1) ? -1 : 1;
        	this.$refs.cropper.scaleX(this.scaleX);
        },
        cropperFlipVertical: function() {
        	this.scaleY = (this.scaleY == 1) ? -1 : 1;
        	this.$refs.cropper.scaleY(this.scaleY);
        },
        cropperReset: function() {
        	this.$refs.cropper.reset();
        },
        cropperZoomIn: function() {
        	this.$refs.cropper.zoom(0.1);
        },
        cropperZoomOut: function() {
        	this.$refs.cropper.zoom(-0.1);
        },
        cropperCrop: function(e) {
        	this.cropData = e;
        },
        cropperInsert: function() {
        	var id = 1;
        	this.editor.insertContent('<img width="'+this.cropData.detail.width+'" height="'+this.cropData.detail.height+'" class="left wysiwyg-image-'+id+'" src="'+this.$refs.cropper.getCroppedCanvas().toDataURL()+'">');
        }
    }
}
