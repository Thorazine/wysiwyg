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
			        		use-font-awesome="true"
			        		v-on:vdropzone-success="dzSuccess">
					        <input type="hidden" name="_token" :value="token">

					        <div class="library-file" v-for="file in dzFiles">
					        	<img :src="file.thumb" v-on:click="openCropper(file)">
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
				    		<input type="file" name="image" accept="image/*"
    		                    style="font-size: 1.2em; padding: 10px 0;"
    		                    v-on:change="setImage"
    		                />
				        	<div class="library-close" v-on:click="close()">&times;</div>
				        </div>
				    	<div class="library-body library-body-full">
				    		<vue-cropper
		                        ref="cropper"
		                        :guides="true"
		                        :view-mode="1"
		                        :check-orientation="false"
						        :check-cross-origin="false"
		                        drag-mode="crop"
		                        :auto-crop-area="1"
		                        :min-container-width="browserWidth()"
		                        :min-container-height="browserHeight()"
		                        :background="true"
		                        :rotatable="true"
		                        :aspect-ratio="16/9"
		                        src="imgSrc"
		                        alt="Source Image">
		                    </vue-cropper>
						</div>
						<div class="library-footer">
							<ul class="list-unstyled list-horizontal">
								<li>
									<button v-on:click="cropperRotateLeft()"><i class="fa fa-undo"></i></button>
								</li>
								<li class="divider"></li>
								<li>
									<button v-on:click="cropperRotateRight()"><i class="fa fa-repeat"></i></button>
								</li>
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
	// Vue.component('vue-cropper', require('vue-cropperjs'));
    export default {
    	data: function() {
    		return {
    			test: true,
    			visible: false,
    			tab: 2,
    			editor: null,
    			imgSrc: 'http://lorempixel.com/2000/1000',
	            cropImg: null,
    			token: $('meta[name="csrf-token"]').attr('content'),
    			dzFiles: [
    				{
    					filename: 'Some name',
    					thumb: 'http://lorempixel.com/100/100',
    					full: 'http://lorempixel.com/2000/1000',
    					id: 1,
    					type: 'image',
    				},
    				{
    					filename: 'Some name',
    					thumb: 'http://lorempixel.com/100/100',
    					full: 'http://lorempixel.com/2000/1000',
    					id: 1,
    					type: 'image',
    				},
    			],
    		}
    	},
    	methods: {
    		open: function(event) {
    			this.editor = event.editor;
				this.visible = true;
			},
			close: function() {
				this.visible = false;
			},
			openCropper: function() {
				// cropper(document.getElementById('cropper'), {
				// 	aspectRatio: 16 / 9,
				// });
				this.tabSwitch(2);
			},
			tabSwitch: function(tabNr) {
				this.tab = tabNr;
			},
			insert: function(file) {
				this.editor.insertContent('<img class="wysiwyg-image-'+file.id+'" src="'+file.full+'">');
				this.close();
    		},
    		dzSuccess: function() {

    		},
    		setImage (e) {
	            const file = e.target.files[0];

	            if (!file.type.includes('image/')) {
	                alert('Please select an image file');
	                return;
	            }

	            if (typeof FileReader === 'function') {
	                const reader = new FileReader();

	                reader.onload = (event) => {
	                    this.imgSrc = event.target.result;
	                    // rebuild cropperjs with the updated source
	                    this.$refs.cropper.replace(event.target.result);
	                };

	                reader.readAsDataURL(file);
	            } else {
	                alert('Sorry, FileReader API not supported');
	            }
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
	        cropperReset: function() {
	        	this.$refs.cropper.reset();
	        },
	        browserWidth: function() {
	        	return $(document).width();
	        },
	        browserHeight: function() {
	        	return $(document).height() - 100;
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
