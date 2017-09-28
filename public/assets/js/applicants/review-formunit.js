/*!
 * @project: <MyNTI>
 * @file: <review-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https://www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form interaction/submission for the review side of the app}
 * @remarks: {module script}
 */


;(function(w, d){

	/* 
		set a delay before unloading the current
		page especially on a 302/301 redirect from
		the PHP server [Laravel Style]
	*/

	w._delayUnloadUntil = 3000; /* 3 secs delay ;) */

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools"], function(box){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			submit_button,

			file_input,

			request_forms,

			certify_check,

			photo_image,

			print_button,

			photo_upload_button,

			photo_upload_iframe,

			application_form_iframe,

			body,

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
		    * Determines if the document is a valid one
		    * based on the file extension text or type
		    * only.
		    *
		    *
		    * @params fileExtension - {String}
		    * @return - {Boolean}
		    */

		    isValidDocFile = function(fileExtension){

		    	if(!fileExtension){

		    		fileExtension = "";
		    	}

		    	return /\.(?:pdf)$/.test(fileExtension);
		    },

		   /**
		    * Determines if the image file is a valid one 
		    * or not based on the file extension text
		    * only
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
			* Normalizes across multiple browsers to be able to
			* render the image for of the file to be uploaded or
			* previewed.
			*
			*
			* @params base64String - {String}
			* @return - {Object}
			*/

			fetchImageForPreview = function(base64String){

					/* 
						depending on whether it's IE/Safari, the base64 string will either
						be displayed as is on the browser via an image tag src attribute
						or it'll be sent to the server for processing into an actual image
						in binary format

						e.g.

						<img src="https://www.mynti.com.ng/base64/processing/{png}/?img_url=encodeURIComponent(base64string)&size=150by300" alt="">

						OR

						<img src="base64string" alt="">

					*/

					var context = E.emit("imagepreview", {
							'image_url':base64String,
							'ie_version':(/*@cc_on!@*/false && d.documentMode),
							'saf_version':(null)
					});

					return context.imagepreview[0];
			},

		   /**
		    * This makes a request to the server for the PDF
		    * version of the aplicant application form to be
		    * printed/downloaded and/or saved.
		    *
		    *
		    * @params url - {String}
		    * @params callback - {Function}
		    * @return - {Object}
		    */

		    iframePDFRequest = function(url, callback){

		    			var _origin = w.location.origin || (w.location.protocol + "//" + w.location.host);
							
								
						if(typeof application_form_iframe.attachEvent === 'function'){

							/* IE 8-9,  Opera 9-12 */

							application_form_iframe.attachEvent('onload', callback);
						
						}else if(typeof photo_upload_iframe.addEventListener === 'function'){

							/* Firefox, Chrome, Safari */

							application_form_iframe.addEventListener('load', callback, false);

						}


						application_form_iframe.src = _origin + url;
		    },	

		   /**
			* Makes an asynchronous request to the [PHP] 
			* server using Ajax or hidden-iframe.
			*
			*
			*
			* @params payload - {Object}
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
			 * @param - errorEvent {Object}
			 * @return - {Undefined}
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
			 * @param url - {String}
			 * @return - {Undefined}
			 */

			fetchImageCallback = function(url){
				
					E.emit('sweetalert', {
						title:"Photo Upload Preview",
						text:"<h5>Be sure this is the photo you "+
								"wish to upload. <br><br> Proceed to Upload ? </h5><br><br><div class='text-center'>"+
								"<img width='80' height='80' src='"+url+"'></div>",
						html:true,
						type:"info",
						showCancelButton:true
					}, 
					function(){
	
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
						})
	
					}, function(){
	
						photo_upload_button.removeAttr('disabled');

						file_input.val("");
					});
														
		};

		return {

			init:function(){

					body.on('click', '.btn', function(event){

							if($(this).is(print_button.selector.replace('.print-application', ''))){

								$(this).addClass('spinner-active');
								
								iframePDFRequest(
								'/applicants/review/print',
								function(e) {
										
									application_form_iframe.contentWindow.print();

								});
                                
							
							}

							else if($(this).is(photo_upload_button.selector.replace('[disabled]',''))){

								setTimeout(function(){

									file_input.click();

									photo_upload_button.attr('disabled', 'disabled');

								}, 0);
							}

							else if($(this).is(submit_button.selector)
									&& !$(this).is('[disabled]')) {

								E.emit('sweetalert', {
									title:"Hold On A Bit!",
									text:"Are you sure you are done reviewing your application properly \r\n\t\t and it's now ready for submission ?",
									type:"info",
									showCancelButton:true,
									confirmButtonText:"Yes, Submit It Now!",
									cancelButtonText:"Errmm No, Wait Let Me Check Again",
									closeOnConfirm:true
								}, function(){

									/*@TODO: move this piece of code to a submit handler for the [info_form] later... */

									/*
										pass the {Laravel} CSRF token from the meta tag
										to the submission form to avoid the dreaded

										:: [TokenMismatchException]
									*/

									if(!([].slice($('.mynti-context-form').get(0).elements)
										.map(function(el){
											return el.name;
										}).indexOf('_token') + 1)){

										$('<input/>')
											.attr({type:'hidden', 'name':'_token'})
												.val($("html").data('token'))
													.appendTo($('.mynti-context-form'));
								
									}

									$('.mynti-context-form').trigger('submit');
								},

								function(){

								});
							}

					});

					request_forms.on('change', 'input[type]', function(event){

								var $target = $(event.target);
									 
								if($target.is(file_input.selector)
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

								else if($target.is(certify_check.selector)){

									if($target.is(":checked")){

										$($target.get(0).form).find('.btn')
											.removeAttr('disabled');
										
									} else {

										$($target.get(0).form).find('.btn')
											.attr('disabled', 'disabled');

									}
								}
					});

			},
			defineVars:function(){

				body = $(d.body);

				request_forms = $('form[target]');

				submit_button = $('a.mynti-button-calm.continue');

				photo_image = $('.profile-image');

				certify_check = $('input[name="certify_info"]');

				file_input = $('.profile-picture-file');

				photo_upload_button = $('a[upload-file-async]'); // accordion-open

				photo_upload_iframe = $('iframe[name="avatarupload_sink"]').get(0);

				print_button = $('a.mynti-button-groovy.print-application');

				application_form_iframe = $('iframe[name="application_form_sink"]').get(0);

			},
			stop:function(){

				body.off('click');

			},
			destroy:function(){
				
				E = null;
				T = null;

				body = null;

				request_forms = null;
				submit_button = null;
				print_button = null;

				certify_check = null;

				photo_image = null;
				photo_upload_button = null;
				photo_upload_iframe = null;

				application_form_iframe = null;

			}
		}

	});

}(this, this.document));