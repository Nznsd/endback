/*!
 * @project: <MyNTI>
 * @file: <docsupload-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https://www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form upload for certificate documents of the app}
 * @remarks: {module script}
 */


;(function(w, d){

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools", "noswfupload"], function(box, accessControl){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			noswfupload = box.noswfupload || {},

			selectedDocumentList,

			uploadList,

			forms_batch,

			select_dropdown,

			select_button,

			request_forms,

			photo_image,

			menu_item,

			file_input,

			upload_element,

			continue_button,

			photo_upload_button,

			photo_upload_iframe,

			body,

	       /**
			*
			*
			*
			* @param payload - {Object}
			* @return - {Object|Null}
			*/

	        documentFileUpload = function(payload){

			       		/* 
			       			detect if the form has files to be uploaded and upload it 
			       			immediately
			       		*/

			       		var hasFiles = !!(payload.form.find('input[type="file"]').length);

			       		if(hasFiles){
			       			return asyncServerUpload({
				       				url:'/applicants/uploads/save/other', /*'/applicants/uploads/'+$('button[name="file-attribution"]').eq(uploadList.length).val(),*/
				       				loadstart:loadstart,
				       				complete:complete,
				       				error:error,
				       				progress:progress,
				       				form:payload.form.get(0)
					       	});
			       		}

			       		function loadstart(){
		            
		                	// we need to show progress bars and disable input file (no choice during upload)
		                	this.show(0);
		                
		                	// write something in the span info
		                	noswfupload.text(this.dom.info, "Preparing for Upload ... ");
		            	}

			       		function complete(rpe, xhr){
			                
			                var self = this;
			                // just show everything is fine ...
			                noswfupload.text(this.dom.info, "Upload complete");
			                
			                // ... and after a second reset the component
			                setTimeout(function(){
			                    self.clean();   // remove files from list
			                    self.hide();    // hide progress bars and enable input file
			                    
			                    noswfupload.text(self.dom.info, "");
			                    
			                    // enable again the submit button/element
			                    // submit.removeAttribute("disabled");
			                }, 1000);

		            	}

			       		function error(){
		                	
		                		noswfupload.text(this.dom.info, "WARNING: Unable to Upload " + (this.file.fileName || this.file.name));
			       		}

			       		function progress(rpe, xhr){

				            	var size = this.file.fileSize || this.file.size;
				            
				                // percent for each bar
				                this.show((this.sent + rpe.loaded) * 100 / this.total, rpe.loaded * 100 / rpe.total, "width", "width");
				                
				                // info to show during upload
				                noswfupload.text(this.dom.info, "Uploading: " + (this.file.fileName || this.file.name));
				                
				                // fileSize is -1 only if browser does not support file info access
				                // this if splits recent browsers from others
				                if(size !== -1){
				                
				                    // simulation property indicates when the progress event is fake
				                    if(rpe.simulation){
				                        // in this case sent data is fake but we still have the total so we could show something
				                        noswfupload.text(this.dom.info,
				                            "Uploading: " + (this.file.fileName || this.file.name),
				                            "Total Sent: " + noswfupload.size(this.sent + rpe.loaded) + " of " + noswfupload.size(this.total)
				                        );
				                    } else {
				                        // this is the best case scenario, every information is valid
				                        noswfupload.text(this.dom.info,
				                            "Uploading: " + (this.file.fileName || this.file.name),
				                            "Sent: " + noswfupload.size(rpe.loaded) + " of " + noswfupload.size(rpe.total),
				                            "Total Sent: " + noswfupload.size(this.sent + rpe.loaded) + " of " + noswfupload.size(this.total)
				                        );
				                    }
				                } else  {
				                    // if fileSIze is -1 browser is using an iframe because it does not support
				                    // files sent via Ajax (XMLHttpRequest)
				                    // We can still show some information
				                    noswfupload.text(this.dom.info,
				                        "Uploading: " + (this.file.fileName || this.file.name),
				                        "Sent: " + (this.sent / 100) + " out of " + (this.total / 100)
				                    );
				                }
            			}

			       		return null;
	        },

	       /**
		    *
		    *
		    *
		    *
		    * @params fileName - {Stirng}
		    * @return - {Boolean}
		    */

		    getFileExtension = function(fileName){

		    	if(!fileName){

		    		fileName = "";
		    	}

		    	return (/(\..*)$/.exec(fileName) || [fileName, null])[1];
		    },

		   /**
		    *
		    *
		    *
		    *
		    *
		    * @params fileExtension - {String}
		    * @return - {Boolean}
		    */

		    isValidDocFile = function(fileExtension){

		    	if(!fileExtension){

		    		fileExtension = "";
		    	}

		    	return /\.(?:jpe?g|pdf|docx?)$/.test(fileExtension.toLowerCase());
		    },

		   /**
		    *
		    *
		    *
		    *
		    *
		    * @params fileExtension - {String}
		    * @return - {Boolean}
		    */

		    isValidImageFile = function(fileExtension){

		    	if(!fileExtension){

		    		fileExtension = "";
		    	}

		    	return /\.((?:pn|sv|jpe?)g)$/.test(fileExtension.toLowerCase());
		    },

		   /**
			*
			*
			*
			*
			* @params - {Void}
			* @return - {Undefined}
			*/

		    proceedToUpload = function(){

					var attribution = $(upload_element).attr('file-attribution'),

					input_btn = $(d.createElement('input')).attr({
							name:'file-attribution',
							type:'button'
					}).addClass('hidden').val(attribution),
				
					input_file = $(upload_element.cloneNode(true)).attr({
							id:'file-input-'+attribution,
							name:'file-input-'+attribution,
							type:'file'
					}).addClass('hidden'),

					doc_upload_box = $(d.createElement('div'));

					doc_upload_box.append
						.apply(doc_upload_box, [input_btn, input_file])
							.appendTo(forms_batch);

					uploadList.push({
						file:upload_element.value,
						upload_spec:documentFileUpload({
							form:doc_upload_box
						})
					});

					upload_element.value = ""; /* no change event triggered - safe */
								
		    },

	       /**
			*
			*
			*
			* @params base64String - {String}
			* @return - {Object}
			*/

			fetchImageForPreview = function(base64String, name){
				
					/* 
						depending on the IE/Safari version, the base64 string will either
						be displayed as is on the browser via an image tag src attribute
						or it'll be sent to the server for processing into an actual image
						in binary format

						e.g.

						<img src="https://www.mynti.com.ng/base64/processing/{png}/?img_url={encodeURIComponent(base64string)}&size=150by300" alt="">

						OR

						<img src="{base64string}" alt="">

					*/

					var context = E.emit("imagepreview", {
							'image_url':base64String,
							'target_title':name || "photo",
							'ie_version':(/*@cc_on!@*/false && d.documentMode),
							'saf_version':(null)
					});

					return context.imagepreview[0];
			},

		   /**
			*
			*
			*
			* @params payload - {Object}
			* @return - {Object}
			*/

	        asyncServerUpload = function(payload){

	        	payload.multipart_async_upload = true;

	        	var _origin = w.location.origin,
                // the input type file to wrap
                input   = payload.form.getElementsByTagName("input")[1],
                
                // the submit button
                submit  = payload.form.getElementsByTagName("input")[0],
                
                // the form action to use with noswfupload
                url = _origin + payload.url,
                
                // noswfupload wrap Object
                wrap;
            
            
            	// if we do not need the form ...
                // move inputs outside the form since we do not need it
                with(payload.form.parentNode){
                    appendChild(input);
                    appendChild(submit);
                };
                
                // remove the form
                payload.form.parentNode.removeChild(payload.form);
            
            	// create the noswfupload.wrap Object with 50Mb of limit
            	wrap = noswfupload.wrap(input, 50 * 1024 * 1024);

            	// accepted file types (filter)
            	// fileType could contain whatever text but filter checks *.{extension} if present
            	wrap.fileType = "Images (*.jpg, *.jpeg, *.png, *.doc, *.pdf)";

				// trigger a change event to register the files in {noswfupload}
				T.trigger_event(input, "change", {}, w);
				//input.dispatchEvent(new CustomEvent('change'));
            
            	// form and input are useless now (remove references)
            	//payload.form = input = null;
            
            	// assign event to the submit button
            	noswfupload.event.add(submit, "click", function(e){
            
                		// only if there is at least a file to upload
		                if(wrap.files.length){

		                    submit.setAttribute("disabled", "disabled");

		                    wrap.upload(
		                        // it is possible to declare events directly here
		                        // via Object
		                        // {onload:function(){ ... }, onerror:function(){ ... }, etc ...}
		                        // these callbacks will be injected in the wrap object
		                        // In this case events are implemented manually
		                    );
		                } else {

		                    noswfupload.text(wrap.dom.info, "No files selected");

		                }
		                
		                submit.blur();
		                
		                // block native events
		                return  noswfupload.event.stop(e);
            	});
            
            	// set wrap object properties and methods (events)
            
            	// url to upload files
				wrap.url = url;	
            
            	// handlers
            	wrap.onerror = payload.error;
            
            	// notify user instantly before files are sent
            	wrap.onloadstart = payload.loadstart;

            	// event called during progress. It could be the real one, if browser supports it, or a simulated one.
            	wrap.onprogress = payload.progress;

            	// generated when every file has been sent (one or more, it does not matter)
            	wrap.onload = payload.complete;

            	setTimeout(function(){
            		    // console.log("Ready to Upload Now....");
            			submit.click();
            	},0);

            	return wrap;
	        },

	       /**
			*
			*
			*
			* @params payload - {Object}
			* @params useIframe - {Object}
			* @return - {Object}
			*/

	        asyncServerRequest = function(payload, useIframe){

	       			/* 
	            	 	send a mediator message to the 
	            	 	'app-commsunit' module to initiate 
	            	 	an AJAX request 
	            	 */

		        	var context, iframe, lcallback, ucallback, responseStatus,
		        	_origin = w.location.origin;

		        	/*

		        		hidden iframe method can be used to submit
		        		the form. 
		        	*/

		        	if(useIframe !== true){
						
						 context = (E.emit("ajaxrequest", payload))["ajaxrequest"][0];

					}else{

						
						context = $.Deferred();

						lcallback = function(e){

							var status = responseStatus || e.target.contentWindow.document.title,
								response = e.target.contentWindow.document.body.innerHTML;

							if(response.match(/^(|false)$/)){
								
								context.reject(response);

							}else{

								context.resolve(response);
							}

						};

						ucallback = function(e){

							;
						};

						if(payload.form instanceof $){
								
								if(typeof photo_upload_iframe.attachEvent === 'function'){

									/* IE 8-9,  Opera 9-12 */

									photo_upload_iframe.attachEvent('onload', lcallback);
									photo_upload_iframe.attachEvent('onunload', ucallback);
								
								}else if(typeof photo_upload_iframe.addEventListener === 'function'){

									/* Firefox, Chrome, Safari */

									photo_upload_iframe.addEventListener('load', lcallback, false);
									photo_upload_iframe.addEventListener('unload', ucallback, false);

								}

								payload.form.prop({action:_origin + payload.url,method:payload.method.toLowerCase()});	

								payload.form.trigger('submit');
							

						}
					} 

		        	return context;
	        },

	        /**
			 *
			 *
			 *
			 *
			 * @params - {}
			 * @return - {}
			 */


			fetchImageCalloff = function(errorEvent){

				photo_upload_button.removeAttr('disabled');

				file_input.val("");

			},

			/**
			 *
			 *
			 *
			 *
			 * @params url - {String}
			 * @return - {Undefined}
			 */

			fetchImageCallback = function(url, name){
				
					E.emit('sweetalert', {
						title:(name =='photo'? "Photo" : "Extra Document") +  " Upload Preview",
						text:"<h5>Be sure this is what you "+
								"wish to upload. <br><br> Proceed to Upload ? </h5><br><br><div class='text-center'>"+
								"<img width='80' height='80' src='"+url+"'></div>",
						html:true,
						type:"info",
						showCancelButton:true
					}, 
					function(){
							if(name == 'photo'){
									photo_upload_button.removeAttr('disabled');
				
									$('p', '.sweet-alert').find('img').remove();
				
									photo_image.addClass('transparency-80');
				
									photo_upload_button.filter('.upload-btn').text('Uploading Photo...');
				
									var _status, 
										_src = photo_image.attr('src'),
										$form = $(file_input.get(0).form);

									$('<input/>')
										.attr({type:'hidden', 'name':'_token'})
											.val($("html").data('token'))
												.appendTo($form);
				
									asyncServerRequest({
										url:'/applicants/uploads/save/photo',
										method:'POST',
										form:$form
									}, true).then(function(data){

										file_input.val("");
										
										data = JSON.parse(data.replace(/(<([^>]+)>)/ig, ""));

										var _origin = w.location.origin;
										
										photo_image.removeClass('transparency-80')
												.prop({'src':((data.src.indexOf('http') == 0 ? "" : _origin) + data.src || _src)});
				
										_status = $form.find('[aria-has-uploaded]').attr('aria-has-uploaded');
										
										$form.find('[aria-has-uploaded]').attr('aria-has-uploaded', 'true');
				
										photo_upload_button.filter('.upload-btn').text('Upload Photo');
				
										
									}, function(error){
				
											E.emit('sweetalert', {
												title:"Ooops!",
												text:"Something unexpected happened and your photo upload was unsuccessful. \r\n\t\t Please, try again.",
												timer:5200
											});
				
											photo_image.removeClass('transparency-80');
				
											photo_upload_button.filter('.upload-btn').text('Upload Photo');

											file_input.val("");
									});
							}
	
					}, function(){
	
						photo_upload_button.removeAttr('disabled');

						file_input.val("");
					});
														
		},

		   /**
			*
			*
			*
			* @params - {Void}
			* @return - {Object}
			*/

			prepareFileDialog = function(){

					if(typeof FileReader !== 'function'){

						/* 
							This uses Adobe Flash plugin of version 10 which reached
							93% saturation as at September 2009, so, most browsers
							should have it installed including effin' IE

						*/
						
						upload_element = d.createElement('object');
						upload_element.id = "file-object";
						upload_element.name = "file-object";
						upload_element.className = "absolute snap-off";
						/*
							<h1>Adobe Flash Not Installed</h1>
							<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
						*/
						body.get(0).appendChild(upload_element).onclick = function(){ console.log("Adobe Flash Dialog: showing"); };

						w.swfobject.embedSWF("/flash/FileToDataURI.swf", "file-object", "80px", 
											"50px", "10", "/flash/expressInstall.swf", {}, {}, {});

						// w.swfobject.embedSWF("test.swf", "myContent", "300", "120", "9.0.0", "expressInstall.swf");

					}else{

						upload_element = d.createElement('input');
						upload_element.type = "file";
						upload_element.id = "file-object";
						upload_element.name = "file-object";
						upload_element.accept = "*/*";

						upload_element.onchange = function(e){

							var files = e.target.files, file, reader, URLCreator;

							if(!files 
								|| files.length === 0){

								E.emit('sweetalert', {
									title:"Document Preview Not Available",
									text:"<h5>However if you are sure that this is the correct<br><br> document you wish to upload, Kindly press <b>OK</b> to continue <br><br> upload.",
									type:"info",
									html:true,
									showCancelButton:true
								}, 
								proceedToUpload, 
								function(){

								})

								return true;
							}

							if(!(isValidDocFile(getFileExtension(e.target.value)))){

								E.emit('sweetalert', {
									title:"Invalid Document Format",
									text:"The format of the document you are trying to upload is not correct. \r\n\t\t Please, try again with a PDF or JPEG document file",
									type:"error",
									timer:6500
								})

								return false;
							}

							file = files[0];

							if(typeof FileReader === 'function'){

								reader = new FileReader();
							}

							if(typeof w.URL !== 'undefined'
								|| typeof w.mozURL !== 'undefined'
									|| typeof w.webkitURL !== 'undefined'
										|| typeof w.msURL !== 'undefined'){

								URLCreator = w.URL || w.mozURL || w.msURL || w.webkitURL;
							}

							if(reader 
								&& /\.((?:pn|jpe?)g)$/.test(file.name)){

								reader.onload = function(e){

									w.Flash.getFileData(e.target.result);
								};

								reader.readAsDataURL(file); // readAsText(), readAsBinaryString()
							}

							else if(URLCreator
									&& /\.(?:pdf)$/.test(file.name)){

								w.Flash.getFileURL(URLCreator.createObjectURL(file));
							}
						}
					}

					upload_element.className = "";

			};

			w.Flash = {
				getFileData:function(base64){
					
					var promise = fetchImageForPreview(base64);

						promise.then(function (img_url){
								
								E.emit('sweetalert',{
									title:"Document Upload Preview",
									text:"<h5>Be sure this is the document you "+
											"wish to upload. <br><br> Proceed to Upload ?"+
											" </h5><br><br> <div class='text-center'><img src='"+
											img_url+" width='120' height='380'></div>",
									type:"info",
									html:true,
									showCancelButton:true
								},
								  proceedToUpload 
								, function(){

								});
								
						});

				},
				getFileURL:function(blobURL){

					E.emit('sweetalert', {
						title:"Document Upload Preview",
						text:"<h5>Be sure this is the document you "+
								"wish to upload. <br><br> Proceed to Upload ?"+
								" </h5><br><br> <div class='text-center'><iframe framborder='0'"+
								" scrolling='no' marginwidth='0' marginheight='0' width='150'"+
								" height='200' src='"+blobURL+"'></iframe></div>",
						type:"info",
						html:true,
						showCancelButton:true
					},
					proceedToUpload,
					function(){

					});
				},
				getButtonLabel:function(){

					return "Choose File";
				}
			};

		return {

			init:function(){

					body.on('click', continue_button.selector.replace('[disabled]', ',[accordion="true"]'), function(event){

							if($(this).is(select_button.selector)){

								setTimeout(function(){
									
									upload_element.click();

								},0);

								select_button.attr('unselectable', '');
							}

							if($(this).is(menu_item.selector)){
								
								menu_item
									.toggleClass('accordion-open')
										.slideDown(); 
							}

							if($(this).is(photo_upload_button.selector.replace('[disabled]', ''))){

								setTimeout(function(){
									
									file_input.trigger("click");

									photo_upload_button.attr('disabled', 'disabled');

								},0);

								setTimeout(function(){

									photo_upload_button.removeAttr('disabled');

								},500);
							}

					});

					request_forms.on('change', 'input[type],select', function(event){

								var $target = $(this), document_name, input_val = $target.val();
									 
									if($target.is(select_dropdown.selector)){
											 
											document_name = input_val;

											if((selectedDocumentList.indexOf(document_name) + 1)){

												E.emit("sweetalert", {
													title:"Document Selected More Than Once",
													text:"Be informed that you are selecting the same document \r\n\t\t more than once for upload. This is not necessarily bad. \r\n\t\t\t\t But, you need to make sure you limit it to 2!",
													type:"info",
													timer:3800
												});
											}

											if(document_name !== "-"){

												selectedDocumentList.push(document_name);

												$(upload_element).attr('file-attribution', document_name);

												select_button.removeAttr('unselectable');
												
											}else{

												 select_button.attr('unselectable', '');
											}
									}

									else if($target.is(file_input.selector)
										&& file_input.val().length > 0){


										if(isValidImageFile(getFileExtension(file_input.val()))){ 

											var files = event.target.files, file, reader;

											if(!files || files.length === 0){

												return;
											}

											file = files[0];

											if(typeof FileReader === 'function'){

												var reader = new FileReader();

												reader.onload = function(e){

													fetchImageForPreview(e.target.result)
														.then(
															fetchImageCallback, 
															fetchImageCalloff
														);
												};

												reader.readAsDataURL(file);

											}
									}
								}
					});

			},
			defineVars:function(){

				prepareFileDialog();

				body = $(d.body);

				forms_batch = $('#mynti-upload-forms-batch');

				request_forms = $('form[target]');

				select_button = $('.mynti-select-upload-link');

				select_dropdown = $('.form-control');

				photo_image = $('.profile-image');

				selectedDocumentList = [];

				uploadList = [];

				file_input = $('.profile-picture-file');

				photo_upload_button = $('a[upload-file-async]'); // accordion-open

				photo_upload_iframe = $('iframe[name="avatarupload_sink"]').get(0);

				continue_button = $('.mynti-button-calm[disabled]');

				menu_item = $('.mynti-application-menuitem[accordion="true"]');
			},
			stop:function(){

				body.off('click');

			},
			destroy:function(){
				
				E = null;
				T = null;

				upload_element = null;
				file_input = null;

				body = null;

				request_forms = null;
				forms_batch = null;

				photo_image = null;
				photo_upload_iframe = null;
				photo_upload_button = null;

				continue_button = null;

				select_button = null;
				select_dropdown = null;

				uploadList = null;
				selectedDocumentList = null;
			}
		}

	});

}(this, this.document));
