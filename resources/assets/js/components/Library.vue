<template>
	<transition name="fade">
	    <div class="library" v-show="visible">
	    	<div class="library-modal">
	    		<transition>
		    		<div class="library-tab" v-if="tab == 1">
				        <div class="library-header">
				        	<div class="library-close" v-on:click="close()">&times;</div>
				        </div>
			        	<vue-dropzone
			        		class="library-body"
			        		id="myVueDropzone"
			        		url="https://httpbin.org/post"
			        		:use-font-awesome="true"
			        		v-on:vdropzone-success="dzSuccess">
					        <input type="hidden" name="_token" :value="token">

					        <div class="library-file" v-for="file in dzFiles">
					        	<img :src="file.thumb" v-on:click="setImage(file.full)">
					        	<div class="library-file-name">
					        		{{ file.name }}
					        	</div>
					        </div>

					    </vue-dropzone>
				        <div class="library-footer"></div>
				    </div>
				</transition>
				<transition>
				    <div class="library-tab" v-if="tab == 2">
				    	<div class="library-header">
				    		<!-- <input type="file" name="image" accept="image/*"
    		                    style="font-size: 1.2em; padding: 10px 0;"
    		                    v-on:change="setImage"
    		                /> -->
				        	<div class="library-close" v-on:click="close()">&times;</div>
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
									<button v-on:click="cropperZoomOut()"><i class="fa fa-minus"></i></button>
								</li>
								<li>
									<button v-on:click="cropperZoomIn()"><i class="fa fa-plus"></i></button>
								</li>
								<li class="divider"></li>
								<li>
									<button v-on:click="cropperRotateLeft()"><i class="fa fa-undo"></i></button>
								</li>
								<li>
									<button v-on:click="cropperRotateRight()"><i class="fa fa-repeat"></i></button>
								</li>
								<li class="divider"></li>
								<li>
									<button v-on:click="cropperFlipVertical()"><i class="fa fa-arrows-v"></i></button>
								</li>
								<li>
									<button v-on:click="cropperFlipHorizontal()"><i class="fa fa-arrows-h"></i></button>
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
	import dz from '../functions/dropzone.js'
	Vue.component('vue-dropzone', require('vue2-dropzone'));
	import vCropper from 'vue-cropperjs';
	Vue.component( 'vue-cropper', vCropper );
    export default {
    	data: function() {
    		return {
    			test: true,
    			visible: false,
    			tab: 1,
    			editor: null,
    			imgSrc: null,
	            cropImg: null,
	            scaleX: 1,
	            scaleY: 1,
    			token: $('meta[name="csrf-token"]').attr('content'),
    			dzFiles: [
    				{
    					filename: 'Some name',
    					thumb: 'http://lorempixel.com/100/100',
    					full: 'http://localhost/wysiwyg/public/uploads/hd1.jpg',
    					id: 1,
    					type: 'image',
    				},
    				{
    					filename: 'Some name',
    					thumb: 'http://lorempixel.com/100/100',
    					full: 'http://localhost/wysiwyg/public/uploads/nature10.jpg',
    					id: 1,
    					type: 'image',
    				},
    			],
    		}
    	},
    	methods: {
    		// popup + tabs
    		open: function(event) {
    			this.tabSwitch(1);
    			this.editor = event.editor;
				this.visible = true;
			},
			close: function() {
				this.visible = false;
			},
			tabSwitch: function(tabNr) {
				this.tab = tabNr;
			},

    		// dropzone
    		dzSuccess: function() {

    		},

			// cropper
			openCropper: function() {
				this.tabSwitch(2);
			},
			insert: function(file) {
				this.editor.insertContent('<img class="wysiwyg-image-'+file.id+'" src="'+file.full+'">');
				this.close();
    		},
    		setImage: function(url) {
    			this.tabSwitch(2);
    			this.imgSrc = url;
    		},
	        cropImage () {
	            // get image data for post processing, e.g. upload or setting image src
	            this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
	        },
	        cropperRotateLeft: function () {
	            // guess what this does :)
	            this.$refs.cropper.rotate(-1);
	        },
	        cropperRotateRight: function () {
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

	        // common
	        browserWidth: function() {
	        	return $(window).width();
	        },
	        browserHeight: function() {
	        	return $(window).height() - 100;
	        }
    	},
        mounted() {
        	$('body').on('library-open', this.open);

            console.log('Component mounted.')
            // $(document).on("newMessage", () => {
            // 	this.open
            // });
        }
    }
</script>
