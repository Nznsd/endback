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

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools", "DocxJS", "noswfupload"], function(box, accessControl){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			docx_js = new box.DocxJS() || {},

			noswfupload = box.noswfupload || {},

			selectedDocumentList,

			uploadList,

			forms_batch,

			upload_nodes,

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
		    */

		    randomIntFromInterval = function(min,max){
    
    			return Math.floor(Math.random()*(max-min+1)+min);
			},	

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

			       		var _origin = w.location.origin,
			       			hasFiles = !!(payload.node.wrap.files.length),
			       			id = payload.node.wrap.dom.wrap.parentNode.id;

			       		// set wrap url

			       		payload.node.wrap.url = _origin + '/applicants/uploads/save/'+id;


            			// set wrap object properties and methods (events)

			       		payload.node.wrap.onloadstart = function loadstart(){

			       			var _that = this;
		            
		                	// we need to show progress bars and disable input file (no choice during upload)
		                	_that.show(0);
		                
		                	// write something in the span info
		                	noswfupload.text(this.dom.info, "Preparing for Upload ... ");

		                	setTimeout(function(){

		                		// write something in the span info again...

		                		noswfupload.text(_that.dom.info, "Upload Started... ");

		                	},100);

		                	
		            	};

			       		payload.node.wrap.onload = function complete(rpe, xhr){
			                
			                var _that = this;

			                // just show everything is fine ...
			                
			                // ... and after a second reset the component
			                setTimeout(function(){

			                    //_that.clean();   // remove files from list
			                    //_that.hide();    // hide progress bars and enable input file
			                    
			                    noswfupload.text(_that.dom.info, "Upload complete");
			                    
			                    // enable again the submit button/element
			                    submit.removeAttribute("disabled");

			                }, 1500);

		            	};

			       		payload.node.wrap.onerror = function error(){
		                	
		                	// if there is an error pertaining the upload file, show it

	                		noswfupload.text(this.dom.info, "WARNING: Unable to Upload " + (this.file.fileName || this.file.name));
			       		};

			       		payload.node.wrap.onprogress = function progress(rpe, xhr){

				            	var size = (this.file.fileSize || this.file.size),
				            		percent_total =  ((this.sent + rpe.loaded) * 100 / this.total),
				            		percent_sent = (rpe.loaded * 100 / rpe.total);
				            
				                // percent for each bar
				                this.show(percent_total, percent_sent, "width", "width");
				                
				                // info to show during upload
				                noswfupload.text(this.dom.info, percent_sent+"% Uploading: ");
				                
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
				                    // if fileSize is -1 browser is using an iframe because it does not support
				                    // files sent via Ajax (XMLHttpRequest)
				                    // We can still show some information
				                    noswfupload.text(this.dom.info,
				                        "Uploading: " + (this.file.fileName || this.file.name),
				                        "Sent: " + (this.sent / 100) + " out of " + (this.total / 100)
				                    );
				                }
            			};

            			if(hasFiles){
			       			asyncServerUpload({
			       				target:payload.node.wrap
					       	});
			       		}

			       		return id;
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

		    	return /\.(?:jpe?g|pdf|png|docx?)$/.test(fileExtension.toLowerCase());
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

		    proceedToUpload = function(unode){

					uploadList.push({
						upload_id:documentFileUpload({
							node:unode
						})
					});
								
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


	        	
	        var form = $(payload.target.dom.wrap);

                // the input type file to wrap
                input   = form.find('input[type="file"]').get(0),
                
                // the submit button
                submit  = form.prev('input[type="button"]').get(0);
            	
            
            
            	// assign event to the submit button
            	noswfupload.event.add(submit, "click", function(e){
            
                		// only if there is at least a file to upload
		                if(payload.target.files.length){

		                    submit.setAttribute("disabled", "disabled");

		                    
		                    payload.target.upload(
		                        // it is possible to declare events directly here
		                        // via Object
		                        // {onload:function(){ ... }, onerror:function(){ ... }, etc ...}
		                        // these callbacks will be injected in the wrap object
		                        // In this case events are implemented manually
		                        
		                     );
		                } else {

		                    noswfupload.text(payload.target.dom.info, "No files selected");

		                }
		                
		                submit.blur();
		                
		                // block native events
		                return  noswfupload.event.stop(e);
            	});	

            	setTimeout(function(){
            		    // console.log("Ready to Upload Now....");
            			submit.click();
            	},200);
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
						var _text;
							if(name == 'photo'){
									photo_upload_button.removeAttr('disabled');
				
									$('p', '.sweet-alert').find('img').remove();
				
									photo_image.addClass('transparency-80');

									_text = photo_upload_button.text();
				
									photo_upload_button.text('Uploading Photo...');
				
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

										var _origin = w.location.origin,
											img_src = ((data.src.indexOf('http') == 0 ? "" : _origin) + data.src || _src);
										
										photo_image.removeClass('transparency-80')
												.prop({'src':img_src});
				
										_status = $form.find('[aria-has-uploaded]').attr('aria-has-uploaded');
										
										if (_status === "false") {
											$form
												.find("[aria-has-uploaded]")
												.attr("aria-has-uploaded", "true");
										}

										photo_upload_button
											.filter(":visible")
											.text(_text);

										photo_upload_button = $("a[upload-file-async]:visible");

										E.emit("navavatarchange", {
												avatar_url: img_src
											});
				
										
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
						$(upload_element).on("click", function(){ console.log("Adobe Flash Dialog: showing"); });

						w.swfobject.embedSWF("/flash/FileToDataURI.swf", "file-object", "80px", 
											"50px", "10", "/flash/expressInstall.swf", {}, {}, {});

						// w.swfobject.embedSWF("test.swf", "myContent", "300", "120", "9.0.0", "expressInstall.swf");

					}else{

						upload_element = d.createElement('input');
						upload_element.type = "file";
						upload_element.accept = "*/*";
						upload_element.className = "hidden";

						$(upload_element).on("change", $.debounce(120, function(e){

							var files = e.target.files, file, reader, URLCreator, mimeMap,
								unode =  (upload_nodes[select_button.attr('aria-state')] || {});

								select_button.removeAttr('aria-state');

							try{
								e.stopPropagation();
							}catch(err){

							}

							if(!files 
								|| files.length === 0){

								E.emit('sweetalert', {
									title:"Document Preview Not Available",
									text:"<h5>However if you are sure that this is the correct<br><br> document you wish to upload, Kindly press <b>OK</b> to continue <br><br> upload.",
									type:"info",
									html:true,
									showCancelButton:true
								}, 
								proceedToUpload.bind({type:""}, unode), 
								function(){

									if(unode.wrap){
									  	unode.wrap.clean();
									  	unode.wrap.hide();
									} 	

								});

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
								&& /\.((?:pn|jpe?)g)$/i.test(file.name)){

								reader.onload = function(ev){

									w.Flash.getFileData(ev.target.result, unode, file.type);
								};

								reader.readAsDataURL(file); // reader.readAsText(), reader.readAsBinaryString()
							}

							else if(URLCreator
									&& /\.(?:pdf|docx?)$/i.test(file.name)){

									/* 
										Bug-Alert:

										[Webkit] can safely load a PDF/DOC/DOCX into an iframe from local URL resource
										howevr, [Blink] throws an error while loading local URL resource on an iframe
										due to a bug detailed as below:

										Chrome Bugs in 52+ (Blink)

										See: https://bugs.chromium.org/p/chromium/issues/detail?id=478389/
											 https://bugs.chromium.org/p/chromium/issues/detail?id=379206/

									*/

									mimeMap = {
										'application/pdf':'pdf',
										'application/msword':'doc',
										'application/vnd.openxmlformats-officedocument.wordprocessingml.document':'docx'
									};

									if(w.webpage.engine.webkit){

										w.Flash.getFileURL(URLCreator.createObjectURL(file), unode, mimeMap[file.type], file);

									}else{

										reader.onload = function(ev){

											w.Flash.getFileURL(ev.target.result, unode, mimeMap[file.type], file);
										};

										reader.readAsDataURL(file);
									}
							}
						}));
					}

			};

			w.Flash = {
				getFileData:function(base64, _unode, _type){
					
					var promise = fetchImageForPreview(base64);

						promise.then(function (img_url){
								
								E.emit('sweetalert',{
									title:"Document Upload Preview",
									text:"<h5>Be sure this is the document you "+
											"wish to upload. <br><br> Proceed to Upload ?"+
											" </h5><br><br> <div class='text-center'><img src='"+
											img_url+"' width='150' height='200'"+
											" onerror='alert(\'This file is corrupted!\')'></div>",
									type:"info",
									html:true,
									showCancelButton:true
								},
								proceedToUpload.bind({type:_type}, _unode) 
								, function(){
									
									if(_unode.wrap){	
									 	_unode.wrap.clean();
									 	_unode.wrap.hide();
									} 	

								});
								
						});

				},
				getFileURL:function(blobURL, _unode, _type, blob){

					var alertConfig, h5, div, iframe, span, parent = d.createDocumentFragment();

					/*

						See: http://jsreports.com/blog/undocumented-feature-render-pdf-directly-into-iframe/

					*/

					if (_type === 'doc'){
						alert("Binary DOC file are NOT ALLOWED HERE");
						return;
					}
					
					if(_type === 'docx'){
						h5 = d.createElement('h5');
						h5.appendChild(
							d.createTextNode(
								'Be sure this is the document you wish to upload'
							)
						)
						parent.appendChild(
							h5
						);

						parent.appendChild(
							d.createElement('br')
						)

						parent.appendChild(
							d.createElement('br')
						)
						
						div = d.createElement('div');
						div.className = 'text-center';
						iframe = d.createElement('iframe');

						iframe.frameBorder = '0';
						iframe.scrolling = 'no';
						iframe.width = '150';
						iframe.height = '200';
						iframe.src = 'about:blank';
						iframe.marginWidth = '0';
						iframe.marginHeight = '0';

						div.appendChild(
							iframe
						);

						parent.appendChild(
							div
						);

						span = d.createElement('span');

						span.appendChild(
							d.createTextNode('Please Wait...')
						);

						parent.appendChild(
							span
						);

						alertConfig = {
							title: "Document Upload Preview",
							content: parent,
							type: "info",
							html: true,
							showCancelButton: true
						};

						docx_js.parse(blob, function(){
							var _elem = d.createElement('div');
							docx_js.render(_elem, function (result) {
								if (result.isError) {
									_elem.innerHTML = "";
									console.log("Error loading DOCX file for preview");
								} else {
									setTimeout(function(){
										var iframe_doc = (iframe.contentDocument || iframe.contentWindow.document);
										if(!!iframe_doc){
											if (window.opera || window.external) {
												iframe_doc.open();
											}
											// Chrome/Edge/Safari ;)
											else {
												iframe_doc.open('text/htmlreplace');
											}

											iframe_doc.write(_elem.innerHTML);
											iframe_doc.close();
										
										}
									}, 1500);
									console.log("Success loading DOCX file for preview");
								}

							});
						})
					}else{

						alertConfig = {
							title: "Document Upload Preview",
							text: "<h5>Be sure this is the document you " +
							"wish to upload. <br><br> Proceed to Upload ?" +
							"</h5><br><br><div class='text-center'><iframe type='application/" + _type + "'" +
							" scrolling='no' marginwidth='0' marginheight='0' width='150'" +
							" height='200' src='" + blobURL + "' framborder='0'></iframe></div>" +
							"<span>Please Wait...</span>",
							type: "info",
							html: true,
							showCancelButton: true
						};
					}

					E.emit(
						'sweetalert', 
						alertConfig,
						proceedToUpload.bind({type:_type}, _unode)
					, function(){

						 if(_unode.wrap){	
						 	_unode.wrap.clean();
						 	_unode.wrap.hide();
						 } 
						 
					});
				},
				getButtonLabel:function(){

					return "Choose File";
				}
			};

		return {

			init:function(){

				/*
					    Initialize Popovers
				*/

				$('[data-toggle="popover"]').popover();

				/*
				   Trigger the popover (Bootstrap) on the photo
				   upload button
				*/

				if (photo_upload_button.filter(":visible")
						.is('.upload-btn')) {
					photo_upload_button.queue(function (next) {

						var _this = $(this);
						setTimeout(function () {
							_this.popover('destroy');
							next();
						}, 7800);

					}).focusin();
				}


					/*
						clear out all former values in all forms on
						the page
					*/

					request_forms.trigger('reset');

					/*

					*/

					setTimeout(function(){
						$('.continue')
								.queue(function(next){
									var _self = $(this);
									setTimeout(function(){
										_self.popover('destroy');
										next();
									},5800);
						}).focusin();
					},1200);


					body.on('click', continue_button.selector.replace('[disabled]', ',[accordion="true"]'), function(event){

							if($(this).is(select_button.selector)){

								setTimeout(function(){

									forms_batch.children('#'+select_button.attr('aria-state'))
										.removeClass('hidden').find('input[type="file"]').trigger("click");

									select_button.attr('unselectable', '').removeClass('shake-effect');

								},0);
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

								var $target = $(this), document_name, input_val = $target.val(),
									input_btn, input_file, doc_upload_box, _wrap;
									 
									if($target.is(select_dropdown.selector)){
											 
											document_name = input_val;

											if((selectedDocumentList.indexOf(document_name) + 1)){

												E.emit("sweetalert", {
													title:"Document Selected More Than Once",
													text:"Be informed that you have selected the same document type \r\n\t\t more than once for upload. This is not necessarily bad. \r\n\t\t\t\t But, you need to make sure you limit it to 2!",
													type:"info",
													timer:3800
												});

												doc_upload_box = forms_batch.find(("#"+document_name));

											}else{

												selectedDocumentList.push(document_name);

												doc_upload_box = null;

											}

											if(document_name !== "-"){

												if(doc_upload_box === null){

														input_btn = $(d.createElement('input')).attr({
																name:'file-submit',
																type:'button'
														}).addClass('hidden').val("_"),
													
														input_file = $(upload_element).clone(true).attr({
																name:'document',
																accept:"*/*",
																type:'file'
														}).addClass('hidden'),

														doc_upload_box = $(d.createElement('div'))
																.attr('id', document_name)
																	.addClass("noswfupload-box");

														doc_upload_box.addClass('hidden').append
															.apply(doc_upload_box, [input_btn, input_file])
																.appendTo(forms_batch);

										            	// create the noswfupload.wrap Object with 500kb of limit
										            	_wrap = noswfupload.wrap(input_file.get(0), 500 * 1024);

										            	_wrap.fileType = "Images (*.jpg, *.jpeg, *.png, *.docx, *.pdf, *.doc)";

										            	_wrap.csrfHeader.name = "X-CSRF-TOKEN";
										            	_wrap.csrfHeader.value = $("html").data('token');

										            	upload_nodes[document_name] = {
										            		wrap:_wrap
										            	};

										            	select_button.attr('aria-state', document_name);
										        }

												select_button.removeAttr('unselectable').addClass('shake-effect');
												
											}else{

												 select_button.attr('unselectable', '').removeClass('shake-effect');
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

				upload_nodes = {};

				file_input = $('.profile-picture-file');

				photo_upload_button = $('a[upload-file-async]:visible'); // accordion-open

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
				upload_nodes = null;
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
