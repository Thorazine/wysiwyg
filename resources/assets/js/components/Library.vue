<template>
	<transition name="fade">
	    <div class="library" v-show="visible">
	    	<div class="library-modal">
	    		<alert :alerts="alerts" :type="alertType"></alert>
	    		<transition>
		    		<div class="library-tab" v-if="tab == 1">
				        <div class="library-header">
				        	<div class="library-close" v-on:click="libraryClose()">&times;</div>
				        </div>
			        	<vue-dropzone
			        		class="library-body"
			        		id="myVueDropzone"
			        		:url="dropzoneUrl"
			        		:use-font-awesome="true"
			        		v-on:vdropzone-success="dropzoneSuccess()">
					        <input type="hidden" name="_token" :value="token">

					        <div class="library-file" v-for="file in dropzoneFiles">
					        	<img :src="file.thumb" v-on:click="libraryOpenCropper(file.full)">
					        	<div class="library-file-name">
					        		{{ file.name }}
					        	</div>
					        </div>

					    </vue-dropzone>
				        <div class="library-footer">
				        	<button v-on:click="changeAlert()">Alert iets anders</button>
				        </div>
				    </div>
				</transition>
				<transition>
				    <div class="library-tab" v-if="tab == 2">
				    	<div class="library-header">
				    		<button type="button" class="btn btn-default" v-on:click="libraryTabSwitch(1)">
				    			<i class="fa fa-cloud-upload"></i>
				    		</button>
				        	<div class="library-close" v-on:click="libraryClose()">&times;</div>
				        </div>
				    	<div class="library-body library-body-full">
				    		<vue-cropper class="cropper"
		                        ref="cropper"
		                        :view-mode="1"
		                        :check-orientation="false"
						        :check-cross-origin="false"
		                        :auto-crop-area="1"
		                        :aspect-ratio="16/9"
		                        :src="this.imgSrc"
		                        alt="Source Image">
		                    </vue-cropper>
						</div>
						<div class="library-footer">
							<ul class="list-unstyled list-horizontal" v-if="imgSrc">
								<li>
									<button v-on:click="cropperZoomOut()" class="btn btn-default"><i class="fa fa-minus"></i></button>
								</li>
								<li>
									<button v-on:click="cropperZoomIn()" class="btn btn-default"><i class="fa fa-plus"></i></button>
								</li>
								<li class="divider"></li>
								<li>
									<button v-on:click="cropperRotateLeft()" class="btn btn-default"><i class="fa fa-undo"></i></button>
								</li>
								<li>
									<button v-on:click="cropperRotateRight()" class="btn btn-default"><i class="fa fa-repeat"></i></button>
								</li>
								<li class="divider"></li>
								<li>
									<button v-on:click="cropperFlipVertical()" class="btn btn-default"><i class="fa fa-arrows-v"></i></button>
								</li>
								<li>
									<button v-on:click="cropperFlipHorizontal()" class="btn btn-default"><i class="fa fa-arrows-h"></i></button>
								</li>
								<li class="divider"></li>
							</ul>
						</div>
				    </div>
				</transition>
		    </div>
	    </div>
	</transition>
</template>

<script>
	Vue.component('alert', require('./Alert.vue'));
	Vue.component('vue-dropzone', require('vue2-dropzone'));
	import vCropper from 'vue-cropperjs';
	Vue.component( 'vue-cropper', vCropper );
	import DropzoneMixin from '../mixins/dropzone';
	import CropperMixin from '../mixins/cropper';
	import api from '../functions/api';
	import asset from '../functions/asset';
	import helper from '../functions/helper';

    export default {
    	data: function() {
    		return {
    			alerts: [],
    			alertType: 'danger',
    			visible: false,
    			tab: 1,
    			editor: null,
    			dropzoneUrl: 'http://localhost/wysiwyg/public/dropzone',
    			imgSrc: null,
	            cropImg: null,
	            scaleX: 1,
	            scaleY: 1,
    			token: $('meta[name="csrf-token"]').attr('content'),
    			dropzoneFiles: [
    				{
    					filename: 'Some name',
    					thumb: 'http://localhost/wysiwyg/public/uploads/placeholder/100x100.jpg',
    					full: 'http://localhost/wysiwyg/public/uploads/original/hd1.jpg',
    					id: 1,
    					type: 'image',
    				},
    				{
    					filename: 'Some name',
    					thumb: 'http://localhost/wysiwyg/public/uploads/placeholder/100x100.jpg',
    					full: 'http://localhost/wysiwyg/public/uploads/original/nature10.jpg',
    					id: 1,
    					type: 'image',
    				},
    			],
    		}
    	},
    	mixins: [
    		DropzoneMixin,
    		CropperMixin
    	],
    	methods: {
    		// popup + tabs
    		libraryOpen: function(event) {
    			this.libraryTabSwitch(1);
    			this.editor = event.editor;
				this.visible = true;
			},
			libraryClose: function() {
				this.visible = false;
			},
			libraryTabSwitch: function(tabNr) {
				this.tab = tabNr;
			},

			// cropper
			libraryOpenCropper: function(url) {
				this.libraryTabSwitch(2);
				this.cropperSetImage(url);
			},
			insert: function(file) {
				this.editor.insertContent('<img width="'+file.width+'" height="'+file.height+'" class="wysiwyg-image-'+file.id+'" src="'+file.full+'">');
				this.libraryClose();
    		},
    		changeAlert: function() {
    			this.alerts = ['bla bla'];
    		}
    	},
        mounted() {
        	// register event
        	$('body').on('library-open', this.libraryOpen);
        }
    }
</script>
