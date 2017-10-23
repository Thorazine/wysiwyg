<template>
	<transition name="fade">
	    <div class="library" v-show="visible">
	    	<div class="library-modal">
	    		<alert :alerts="alerts" :type="alertType"></alert>
	    		<transition>
		    		<div class="library-tab" v-if="tab == 1">
				        <div class="library-header">
				        	<span class="library-header-center">{{ trans('cms.library.upload-files') }}</span>
				        	<div class="library-close" v-on:click="libraryClose()">&times;</div>
				        </div>
			        	<vue-dropzone
			        		class="library-body"
			        		id="myVueDropzone"
			        		:accepted-file-types="acceptedFileTypes"
			        		:url="dropzoneUrl"
			        		:use-font-awesome="true"
			        		:preview-template="dropzoneTemplate"
			        		v-on:vdropzone-success="dropzoneSuccess"
			        		v-on:vdropzone-error="dropzoneError"
			        		v-on:upload-progress="dropzoneProgress">
					        <input type="hidden" name="_token" :value="token">

					        <div class="dz-preview dz-file-preview" v-for="file in dropzoneFiles">
								<div class="dz-image" v-on:click="libraryOpenCropper(file.full)">
									<div class="dz-edit" v-on:click.stop="libraryEdit(file)">
										<i class="fa fa-pencil"></i>
									</div>
									<div class="dz-delete" v-on:click.stop="libraryRemove(file)">
										<i class="fa fa-trash"></i>
									</div>
								    <img data-dz-thumbnail :src="file.thumb" />
								</div>
								<div class="dz-name">
									<span data-dz-name>{{ file.filename }}</span>
								</div>
							</div>

					    </vue-dropzone>
				        <div class="library-footer">
				        	<ul class="pagination">
				        		<li :class="{disabled : (paginationSelected == 1)}">
				        			<a v-on:click="libraryGetMedia(1)" aria-label="Previous">
					        			<span aria-hidden="true">&laquo;</span>
					        		</a>
				        		</li>
				        		<li v-for="pagination in paginationCount" :class="{disabled : (pagination == paginationSelected)}">
				        			<a v-on:click="libraryGetMedia(pagination)">{{ pagination }}</a>
				        		</li>
				        		<li :class="{disabled : (paginationSelected == paginationCount)}">
				        			<a v-on:click="libraryGetMedia(paginationCount)" aria-label="Next">
					        			<span aria-hidden="true">&raquo;</span>
					        		</a>
				        		</li>
				        	</ul>
				        </div>
				    </div>
				</transition>
				<transition>
				    <div class="library-tab" v-if="tab == 2">
				    	<div class="library-header">
				    		<button type="button" class="btn btn-default" v-on:click="libraryTabSwitch(1)">
				    			<i class="fa fa-cloud-upload"></i>
				    		</button>
				    		<span class="library-header-center">{{ trans('cms.library.resize-images') }}</span>
				        	<div class="library-close" v-on:click="libraryClose()">&times;</div>
				        </div>
				    	<div class="library-body library-body-full">
				    		<vue-cropper class="cropper"
		                        ref="cropper"
		                        :view-mode="1"
		                        :check-orientation="false"
						        :check-cross-origin="false"
		                        :auto-crop-area="1"
		                        :aspect-ratio="ratio"
		                        :src="this.imgSrc"
		                        :crop="cropperCrop"
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
							<button class="btn btn-primary library-acceptfile" v-on:click="cropperInsert()">Use</button>
						</div>
				    </div>
				</transition>
				<transition>
				    <div class="library-tab" v-if="tab == 3">
				    	<div class="library-header">
				    		<button type="button" class="btn btn-default" v-on:click="libraryTabSwitch(1)">
				    			<i class="fa fa-angle-left"></i>
				    		</button>
				    		<span class="library-header-center">{{ trans('cms.library.edit-file') }}</span>
				        	<div class="library-close" v-on:click="libraryClose()">&times;</div>
				        </div>
				    	<div class="library-body library-edit">
				    		<div class="row">
				    			<div class="col-sm-6">
				    				<img class="library-edit-preview" :src="fileEdit.full">
				    			</div>
				    			<div class="col-sm-6">
						    		<form class="form form-horizontal">
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.filename') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<span class="form-text">{{ fileEdit.filename }}</span>
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.filetype') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<span class="form-text">{{ fileEdit.filetype }}</span>
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.uploaded') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<span class="form-text">{{ fileEdit.uploaded }}</span>
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.filesize') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<span class="form-text">{{ fileEdit.filesize }} KB</span>
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.dimensions') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<span class="form-text">{{ fileEdit.width }} × {{ fileEdit.height }}</span>
						    				</div>
						    			</div>

						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.title') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<input type="text" class="form-control" :placeholder="trans('cms.library.edit.title')">
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.alt') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<input type="text" class="form-control" :placeholder="trans('cms.library.edit.alt')">
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.copyright') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<input type="text" class="form-control" :placeholder="trans('cms.library.edit.copyright')">
						    				</div>
						    			</div>
						    			<div class="form-group">
						    				<label class="col-sm-3 control-label">
						    					{{ trans('cms.library.edit.photographer') }}
						    				</label>
						    				<div class="col-sm-9">
						    					<input type="text" class="form-control" :placeholder="trans('cms.library.edit.photographer')">
						    				</div>
						    			</div>
						    		</form>
						    	</div>
						    </div>
						</div>
						<div class="library-footer">
							<button class="btn btn-primary library-acceptfile" v-on:click="cropperInsert()">{{ trans('cms.library.edit.update') }}</button>
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
    			upload: '',
    			alerts: [],
    			alertType: 'danger',
    			visible: false,
    			tab: 1,
    			editor: null,
    			dropzoneUrl: 'http://localhost/wysiwyg/public/api/media/upload',
    			acceptedFileTypes: 'image/*,application/pdf',
    			imgSrc: null,
	            cropImg: null,
	            cropData: null,
	            ratio: null,
	            scaleX: 1,
	            scaleY: 1,
    			token: $('meta[name="csrf-token"]').attr('content'),
    			dropzoneFiles: [],
    			paginationCount: 0,
    			paginationSelected: 0,
    			fileEdit: {},
    		}
    	},
    	mixins: [
    		DropzoneMixin,
    		CropperMixin
    	],
    	methods: {
    		// trans: function(string) {
    		// 	console.log(window.i18n);
    		// 	return _.get(window.i18n, string);
    		// },
    		// popup + tabs
    		libraryOpen: function(event) {
    			this.libraryGetMedia(1);
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
			libraryGetMedia: function(page) {
				this.paginationSelected = page;
				api.mediaGet({page: page}).then((response) => {
					this.dropzoneFiles = response.data.medias.data;
					this.paginationCount = response.data.medias.last_page;
				})
				.catch((error) => {
					console.log(error);
				});
			},
			libraryEdit: function(file) {
				api.mediaEdit({id: file.id}).then((response) => {
					this.fileEdit = response.data.data;
					this.libraryTabSwitch(3);
				});
			},
			libraryRemove: function(file) {

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
        	this.upload = trans('cms.library.upload-files');
        }
    }
</script>
