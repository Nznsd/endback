/*!
 * @project: <MyNTI>
 * @file: <personalinfo-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https://www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form for updating personal info for user on the app}
 * @remarks: {module script}
 */


;(function(w, d){

	/* 
		set a delay before unloading the current
		page especially on a 302/301 redirect from
		the PHP server [Laravel Style]
	*/

	w._delayUnloadUntil = 1800; /* 1 sec and 800 millisecs delay ;) */

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools"], function(box, accessControl){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			work_start,

			work_end,

			request_forms,

			file_input,

			photo_upload_button,

			continue_button,

			body,

			info_form,

			photo_image,

			menu_item,

			photo_upload_iframe,

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
		    * @params base64String - {String}
			* @return - {Object}
			*/

			fetchImageForPreview = function(base64String){

					/* 
						depending on the IE/Safari version, the base64 string will either
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
			*
			*
			*
			* @params payload - {Object}
			* @return - {Object}
			*/

	        asyncServerUpload = function(payload){

	       		var noswfupload_form = payload.form;

	       		/* more code here ... */

	       		return (E.emit("ajaxrequest", payload))["ajaxrequest"][0];
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
		        	_origin = w.location.origin || (w.location.protocol + "//" + w.location.host);

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

			init:function(promise){


					/*
						clear out all former values in all forms on
						the page
					*/

					request_forms.trigger('reset');

					work_start.daterangepicker({
	       						singleDatePicker: true, 
	       						showDropdowns:true
	       					}, function(start, end, label){
							    
							    work_start.data('stored', start);

							    var end = work_end.data('stored');
							    

							    if(end && end.diff(start, 'years') < -1){

							    	E.emit('sweetalert', {
							    		title:"Wrong Work Experience Dates",
							    		text:"The dates for your work experience don't add up. \r\n\t\t Please, check agian",
							    		info:"error",
							    		showCancelButton:false
							    	}, function(){

							    		setTimeout(function(){
											
											work_start.val("11/08/2005").addClass('wrong');

							    		},1);
							    	});


							    	return false;
							    }

							    if(work_start.is('.wrong')){
									work_start.removeClass('wrong');
							    }
					});

					work_end.daterangepicker({
	       						singleDatePicker: true, 
	       						showDropdowns:true
	       					}, function(start, end, label){
							    
							    work_end.data('stored', start);
							    
							    var end = work_start.data('stored');
							    

							    if(start && start.diff(end, 'years') < -1){

							    	E.emit('sweetalert', {
							    		title:"Wrong Work Experience Dates",
							    		text:"The dates for your work experience don't add up. \r\n\t\t Please, check agian",
							    		info:"error",
							    		showCancelButton:false
							    	}, function(){

							    		setTimeout(function(){

							    			work_end.val("11/08/2011").addClass('wrong');

							    		}, 1);

							    	});

							    	return false;
							    }

							    if(work_end.is('.wrong')){
									work_end.removeClass('wrong');
							    } 
					});

					/*
						if javascript is enabled add novalidate
						attribute
					*/

					if(body.parent().is('.js')){

						info_form.attr('novalidate', '');
					}


					/*
						setup click event handler to capture clicks page-wide
					*/

					body.on('click', continue_button.selector.replace('[disabled]', ',[accordion="true"]'), function(event){

							if($(this).is(photo_upload_button.selector)){

								setTimeout(function(){
									
									file_input.trigger('click');

									photo_upload_button.attr('disabled', 'disabled');

								},0);

							}

							if($(this).is(menu_item.selector)){
								
								menu_item
									.toggleClass('accordion-open')
										.slideDown(); 
							}

							if($(this).is(".continue") && 
								!$(this).is('[disabled]')){

								info_form.trigger('submit');
							}
					});

					$('[placeholder]').on("input", $.debounce(300, function(event){
						
						if(event.target.value.length >= 4){
							
							$(this).trigger('change');
						}
					}));

					/*
						setup change event handler to capture text/dropdown
						input
					*/

					request_forms.on('change', 'input[type],textarea', function(event){

							var form_data = (info_form.data("fill-list") || '[]'),
								filllist = JSON.parse(form_data),
								fillprogress = filllist.length,
								filltotal = info_form.find('input[placeholder],textarea').length,
								percent = 0,
								$target = $(this),
								input_value = $target.val(),
								offset = filllist.indexOf(event.target.name);


							if($target.is(file_input.selector) 
										&& $target.val().length > 0){ 

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
									}else{
										
										photo_upload_button.removeAttr('disabled');
										
										E.emit('sweetalert', {
											title:'Invalid Photo Upload File',
											text:'Please, ensure that you are uploading an image of yourself \r\n\t\t preferably, a PNG or JPEG image',
											info:'error',
											timer:4500,
											showCancelButton:false	
										});
									}

							}else {

									if(input_value !== ""){
										
										if(((this.name === "work_start" 
												|| this.name === "work_end")
											&& !!(this.className.indexOf('wrong') + 1))){

											/*return true;*/
										}

										if(!(offset + 1)){
											filllist.push(this.name);
										}
									}else{
										if((offset + 1)){
											filllist.splice(offset, 1);
										}
									}

										
							}

							info_form.data("fill-list", JSON.stringify(filllist));
							fillprogress = filllist.length;
							percent = Math.round((fillprogress/filltotal) * 100);

							console.log("percne completed: ", percent, "%");

							if(percent === 100){


									/*
										pass the {Laravel} CSRF token from the meta tag
										to the submission form to avoid the dreaded

										:: [TokenMismatchException]
									*/

									if(!([].slice(info_form.get(0).elements)
											.map(function(el){
												return el.name;
											}).indexOf('_token') + 1)){

											$('<input/>')
												.attr({type:'hidden', 'name':'_token'})
													.val($("html").data('token'))
														.appendTo(info_form);
									
									}
							
									continue_button.removeAttr('disabled').addClass('shake-effect');
					
							}
					});

			},
			defineVars:function(){


				body = $(d.body);

				work_start = $('[id="from_date"]');

				work_end = $('[id="to_date"]');

				photo_image = $('.profile-image');

				request_forms = $('form[target]');

				info_form = $('.mynti-context-form');

				file_input = $('.profile-picture-file');

				photo_upload_button = $('a[upload-file-async]');

				photo_upload_iframe = $('iframe[name="avatarupload_sink"]').get(0);

				continue_button = $('.mynti-button-calm[disabled]');

				menu_item = $('.mynti-application-menuitem[accordion="true"]');

			},
			stop:function(){

				body.off('click');

				request_forms.off('change');
			},
			destroy:function(){
				
				E = null;
				T = null;

				photo_image = null;

				photo_upload_button = null;
				photo_upload_iframe = null;
				continue_button = null;

				work_start = null;
				work_end = null;

				body = null;
				menu_item = null;

				file_input = null;
				info_form = null;

				request_forms = null;
			}
		}

	});

}(this, this.document));