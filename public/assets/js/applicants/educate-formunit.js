/*!
 * @project: <MyNTI>
 * @file: <educate-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https://www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form for updating educational info for user on the app}
 * @remarks: {module script}
 */


;(function(w, d){

	/* 
		set a delay before unloading the current
		page especially on a 302/301 redirect from
		the PHP server [Laravel Style]
	*/

	w._delayUnloadUntil = 500; /* 500 millisecs delay ;) */

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools"], function(box, accessControl){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			request_forms,

			state_dropdown,

			menu_item,

			subjects_selected_list,

			tracker,

			check_box,

			tracker_index,

			ordinals,

			result_type_dropdown,

			cert_file_input,

			cert_upload_btn,

			add_sitting_btn,

			file_input,

			photo_upload_button,

			continue_button,

			body,

			info_form,

			photo_image,
		    
		    edu_section_html,

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

		    isValidImageFile = function(fileExtension, pdfAllowed){

		    	if(!fileExtension){

		    		fileExtension = ".";
				}

				if(fileExtension.toLowerCase() === ".pdf" 
					&& pdfAllowed !== true){
					return false;
				}

		    	return /\.((?:pn|sv|jpe?)g|pdf)$/.test(fileExtension.toLowerCase());
		    },

		   /**
		    *
		    *
		    *
		    *
		    * @param base64String - {String}
			* @return - {Object}
			*/

			fetchImageForPreview = function(base64String, name){
				
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
							'target_title':name,
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

		        	var context, iframe, lcallback, ucallback,
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

							var status = e.target.contentWindow.document.title,
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
			* @params DOM object - {HTMLElement|jQuery}
			* @return - {Undefined}
			*/

			loadDropdown = function(endpoint, $elem){

				if('nodeType' in $elem
					 && $elem.nodeType === 1){

					$elem = $($elem);
				}


				if(typeof endpoint !== "string"
					|| !(endpoint.indexOf('/') + 1)){

					return null;
				}

				var _url = endpoint,
					 cascade_link = $elem.data("casacade-select-target");


				if($elem.data("select-loaded") !== undefined){
					
					return asyncServerRequest({
						url:_url,
						method:'GET',
						requestContentType:'json',
						data:null
					}).done(function(data){
						
						var   data = data || [], 
							$fragment = $(d.createDocumentFragment()),
							_options = $.map(data, function(value, key){
								var o = new Option;
								o.text = value.name;
								o.value = value.id;
								return o;
							});
						
							if($elem.children("option").length > 1){
								$elem.find("option").remove("[value!='-']");
							}

							$elem.append(
								$fragment.append.apply($fragment, _options)
							).data(
								"select-loaded",
								"true"
							);

					}).fail(function(){

						E.emit('sweetalert', {
							title:'Ooops!',
							text:'Something unexpected happened and we\'re sorry. \r\n\t\t Please, contact <a href="/">Support</a>',
							html:true,
							info:'error',
							timer:3500,
							showCancelButton:false
						});

					});
				}
							
			},

			/**
			 *
			 *
			 *
			 *
			 * @params - {}
			 * @return - {}
			 */


			fetchImageCalloff = function(errorEvent, name){
				
				if(name == 'photo'){
					photo_upload_button.removeAttr('disabled');

					file_input.val("");
				}

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
						title: (name =='photo'? "Photo" : "Certificate") + " Upload Preview",
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
							}
					}, function(){
						if(name == 'photo'){
							photo_upload_button.removeAttr('disabled');

							file_input.val("");
						}else{
							cert_file_input.val("");
						}
					});
														
		};

			

		return {

			init:function(){


					/*
					  	initialize Popovers
					*/

					$('[data-toggle="popover"]').popover();


					/*
						clear out all former values in all forms on
						the page
					*/

					request_forms.trigger('reset');


					if(body.parent().is('.js')){

						info_form.attr('novalidate', '');
					}


					/*
						setup click event handler to capture clicks page-wide
					*/

					body.on('click', continue_button.selector.replace('[disabled]', ',.mynti-button-groovy,[accordion="true"]'), function(event){

							if($(this).is(photo_upload_button.selector)){

								setTimeout(function(){
									
									file_input.trigger('click');

									photo_upload_button.attr('disabled', 'disabled');

								},0);

								setTimeout(function(){

									photo_upload_button.removeAttr('disabled');

								},500);

							}

							if($(this).is(add_sitting_btn.selector)
								&& !$(this).is('[disabled]')){
								
								$('.mynti-edu-details:last').after(edu_section_html.replace(new RegExp("\\bfirst","gm"), 
								(tracker = ordinals[++tracker_index])));

								cert_file_input = $('input[name$="[certificate]"]:last');

								add_sitting_btn.attr('disabled', 'disabled');

								check_box.attr('disabled', 'disabled');

							}

							if($(this).is(cert_upload_btn.selector)){

								setTimeout(function(){

									cert_file_input.trigger('click'); 

									cert_upload_btn.attr('disabled', 'disabled');

								},0);

								setTimeout(function(){

									cert_upload_btn.removeAttr('disabled');

								},500);
							}

							if($(this).is(menu_item.selector)){
								
								menu_item
									.toggleClass('accordion-open')
										.slideDown(); 
							}
							
							if($(this).is(".continue") &&
								!$(this).is('[disabled]')){

									/* @TODO: move this piece of code to a submit handler for the [info_form] later... */

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

									info_form.trigger('submit');
							}
					});

					/*
						setup change event handler to capture text/dropdown
						input
					*/

					request_forms.on('change', 'input[type],select', function(event){

							var form_data = (info_form.data("fill-list") || '[]'),
								filllist = JSON.parse(form_data),
								fillprogress = filllist.length,
								filltotal = info_form.find('input[type],select').length,
								percent = 0,
								$target = $(event.target),
								$subtarget = null,
								file_ext = null,
								input_value = $target.val(),
								offset = filllist.indexOf(event.target.name);


							if(($target.is(file_input.selector) 
										|| $target.is(cert_file_input.selector))
										&& input_value.length > 0){ 

									file_ext = getFileExtension(input_value);

									if(isValidImageFile(file_ext, $target.is(cert_file_input.selector))){

											var files = event.target.files, file, reader;

											if(!files || files.length === 0){

												return;
											}

											file = files[0];

											if(typeof FileReader === 'function'){

												var reader = new FileReader(), URLCreator;

												if(typeof w.URL !== 'undefined'
													|| typeof w.mozURL !== 'undefined'
														|| typeof w.webkitURL !== 'undefined'
															|| typeof w.msURL !== 'undefined'){

													URLCreator = w.URL || w.mozURL || w.msURL || w.webkitURL;
												}

												reader.onload = function(e){

													fetchImageForPreview(file_ext !== ".pdf" ? e.target.result : w.location.origin+"/assets/img/png/pdf-file.png", $target.attr('name'))
														.then(
															fetchImageCallback, 
															fetchImageCalloff
														);
												};

												reader.readAsDataURL(file); // readAsText(),  readAsBinaryString()

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

									if($target.is("select[data-casacade-select-target]")){

											$subtarget = $($target.data("casacade-select-target"));

											$subtarget.data('placeholder-text', $subtarget.find("option:eq(0)").text())
												.children("option:eq(0)").text("LOADING...");
											
											loadDropdown('/services/lga/'+input_value, $subtarget)
												.then(function(){
													$subtarget.find("option:eq(0)").text($subtarget.data('placeholder-text'));
													$subtarget.data('placeholder-text', null);
													$subtarget = null;
											});
											
									}

									if(input_value !== "-"){
										
										if(event.target.type
											  && event.target.type.search(/^(?:text|file)$/) > -1
											&& input_value.length > 0 
											&& event.target.name.search(/^(?:sittings)\[(?:[a-z]+)\](?:\[school_name\])?$/) > -1
												&& !(/^([\w .-]{9,})$/.test(input_value))){
												
											return true;
										}

										if(event.target.nodeName.toLowerCase() == "select"){

											if(event.target.name.indexOf("[subjects]") + 1){

												if(subjects_selected_list.indexOf(input_value) + 1){
													E.emit('sweetalert', {
														title:"Exam Subject Repitition",
														text:"Errmm, you seem to have choosen the same subject more than once.",
														type:"info",
														timer:3800,
														showCancelButton:false
													});
													event.preventDefault();
													return false;
												}

												subjects_selected_list.push(input_value);
											}
										}

										if(event.target.type == "checkbox") {
											
											if($(event.target).is(":checked")){
												
												continue_button.removeAttr('disabled').addClass('shake-effect');
											}else{
												if(!continue_button.is('[disabled]')){
												
													continue_button.removeClass('shake-effect')
														.attr('disabled', 'disabled');
											    }
											}
										}

										if(!(offset + 1)){
											filllist.push(event.target.name);
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

							if(percent >= 60){

									check_box.removeAttr('disabled');
							}else{

									if(!check_box.is('[disabled]')){
										check_box.attr('disabled', 'disabled');
									}

							}
							
							if(percent >= 92){

								add_sitting_btn.removeAttr('disabled')
									.addClass('shake-effect')
										.queue(function(next){
											var _self = $(this);
											setTimeout(function(){
												_self.popover('destroy');
												subjects_selected_list = [];
												next();
											},7800);
										}).focusin();
					
							}else{

								if(!add_sitting_btn.is('[disabled]')){
									add_sitting_btn.removeClass('shake-effect')
										.attr('disabled', 'disabled');
								}
								
							}
					});

			},
			defineVars:function(){


				body = $(d.body);

				photo_image = $('.profile-image');

				request_forms = $('form[target]');

				check_box = $('input[name="badge"]');

				info_form = $('.mynti-context-form');

				state_dropdown = $('select[name$="[state]"]');

				result_type_dropdown = $('select[name$="[result_type]"]');

				cert_file_input = $('input[name$="[certificate]"]');

				add_sitting_btn = $('a.mynti-button-groovy.add');

				cert_upload_btn = $('a.upload-cert');

				ordinals = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];

				subjects_selected_list = [];

				tracker_index = 0;

				tracker = ordinals[tracker_index];

				file_input = $('.profile-picture-file');

				photo_upload_button = $('a[upload-file-async]');

				photo_upload_iframe = $('iframe[name="avatarupload_sink"]').get(0);

				continue_button = $('.mynti-button-calm[disabled]');

				menu_item = $('.mynti-application-menuitem[accordion="true"]');
				
				edu_section_html = $(".mynti-edu-details:first").get(0).outerHTML;

			},
			stop:function(){

				body.off('click');

				request_forms.off('change');
			},
			destroy:function(){
				
				E = null;
				T = null;

				ordinals = null;

				tracker = null;

				photo_image = null;
				menu_item = null;

				photo_upload_button = null;
				photo_upload_iframe = null;
				continue_button = null;

				result_type_dropdown = null;
				edu_section_html = null;
				subjects_selected_list = null;
				cert_upload_btn = null;

				body = null;

				cert_file_input = null;
				add_sitting_btn = null;

				file_input = null;
				check_box = null;
				state_res_dropdown = null;
				state_dropdown = null;
				info_form = null;

				request_forms = null;
			}
		}

	});

}(this, this.document));
