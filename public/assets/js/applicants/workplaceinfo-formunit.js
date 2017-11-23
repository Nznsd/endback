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

			text_inputs,

			request_forms,

			file_input,

			photo_upload_button,

			continue_button,

			body,

			initial_vals,

			info_form,

			photo_image,

			menu_item,

			skip_btn,

			add_btn,

			add_section_html,

			photo_upload_iframe,

			ordinals,
				
			tracker_index,
				
			tracker,

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
	
						var _text;

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

							photo_upload_button.filter(":visible").text(_text);

							photo_upload_button = $("a[upload-file-async]:visible");

							E.emit("navavatarchange", { avatar_url: img_src });
	
							
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

					var _self = this;

					/*

					  	Initialize Popovers
					*/

					$('[data-toggle="popover"]').popover();

					/*
					   Trigger the popover (Bootstrap) on the photo
					   upload button
					*/

					if (photo_upload_button.filter(":visible")
						.is('.upload-btn')){
						photo_upload_button.queue(function (next) {
							
								var _this = $(this);
								setTimeout(function () {
									_this.popover('destroy');
									next();
								}, 7800);
							
						}).focusin();
					}

					if (skip_btn.length) {
						skip_btn.queue(function (next) {

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

						run [repeatable] code logic section for (date-picking event) and (input event) handlers
					*/

					_self.repeat(false);

					/*

						if javascript is enabled add `novalidate` attribute
					*/

					if(body.parent().is('.js')){

						info_form.attr('novalidate', '');
					}


					/*

						setup click event handler to capture clicks page-wide
					*/

					body.on('click', continue_button.selector.replace('[disabled]', ',.mynti-button-groovy,[accordion="true"]'), function(event){

							if($(this).is(add_btn.selector) 
								&& !$(this).is('[disabled]')){

								 $('.work_details_block:last').after(add_section_html.replace(new RegExp("\\bfirst","igm"), 
								(tracker = ordinals[++tracker_index])));

								 setTimeout(function(){
								 	
								 	_self.repeat(true);

								 }, 0);

								 add_btn.attr('disabled', 'disabled');

								 continue_button.attr('disabled', 'disabled').removeClass('shake-effect');
							}

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

					request_forms.on('change', 'input[type],textarea', function(event, data){

							var form_data = (info_form.data("fill-list") || '[]'),
								filllist = JSON.parse(form_data),
								fillprogress = filllist.length,
								filltotal = info_form.find('input:not(.no-pick),textarea').length,
								percent = 0,
								$target = $(this),
								input_value = $target.val(),
								offset = filllist.indexOf((
												event.target.name.indexOf("[current_date]") > -1 
												? 
													$(this).parent().prev()
														.children(work_end.selector+':eq(0)').attr('name') 
												: 
													event.target.name
										));

								if(!data
									&& $target.data('early-trigger')){

									$target.data('early-trigger', false);
									return true;
								}

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

									if(this.className.indexOf("current_date") + 1){
										if(this.checked){
											
											$target
												.removeClass('no-pick');	

											$target
												.parent().prev()
												.find('input:eq(0)')
												.val(moment().format('M/D/Y'))
													.addClass('no-pick')
														.attr('unselectable', '')
															.parent()
																.addClass('form-group-disabled');	

										}else{
											$target
												.addClass('no-pick');

											$target
												.parent().prev()
												.find('input:eq(0)')
												.val(initial_vals.end_date)
													.removeClass('no-pick')
														.removeAttr('unselectable')
															.parent()
																.removeClass('form-group-disabled');

											input_value = "";
										}
									}

									if(input_value !== ""){

										
										if(((this.name.search(/\[from_date\]$/) != -1)
												|| (this.name.search(/\[to_date\]$/) != -1))
													&& ($target.attr('aria-changed') === 'false')){

											return true;

										}else{

											$target.attr('aria-changed', 'false');

										}

										if(!(offset + 1)){
											filllist.push((
													this.name.indexOf("[current_date]") > -1
													? 
														$(this).parent()
															.prev()
																.children(work_end.selector+':eq(0)')
																	.attr('name') 
													: 
														this.name
											));
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

							//console.log("percent: ", percent, "%");

							if(percent > 0){

								skip_btn.attr({'disabled':'disabled','unselectable':''});

							}else if(percent == 0){

								skip_btn.removeAttr('disabled').removeAttr('unselectable');
							}

							if(percent === 100){
							
								add_btn.removeAttr('disabled')
											.queue(function(next){
												if(tracker == "first"){
													var _this = $(this);
													setTimeout(function(){
														_this.popover('destroy');
														next();
													},7800);
												}else{
													next();
												}
											}).focusin();

								continue_button.removeAttr('disabled').addClass('shake-effect');
					
							}else{

								add_btn.attr('disabled', 'disabled');

								continue_button.attr('disabled', 'disabled').removeClass('shake-effect');
							}
					});

			},
			repeat:function(again){

				

				if(again === true){


					work_start = $('[name$="[from_date]"]').last();

					work_end = $('[name$="[to_date]"]').last();

					text_inputs = $('[placeholder]', '.work_details_block:last');

				}

				text_inputs.on("blur input", $.debounce(250, function(event){
						
						if((event.type === "blur"
								&& event.target.value.length == 0)
									|| (event.type === "input" 
											&& event.target.value.length >= 3)){

							$(this).data('early-trigger', true).trigger('change', {fakeEvent:true});

						}
				}));


				work_start.daterangepicker({
	       						singleDatePicker: true, 
	       						showDropdowns:true
	       					}, function(start, end, label){
							    

							    var  _target = this.element,

							    	 work_end_elem = $(_target.attr('name').replace(
							    							"[from_date]", "[to_date]"
							    						)),

							    	 _end =  new Date(
							    						(work_end_elem.val() 
							    						|| initial_vals.end_date)
							    			), 

							    	_start = new Date(
							    						(start.format('M/D/Y') 
							    							|| _target.val())
							    			);

							    _target.data('stored', _start);

							    _target.attr('aria-changed', true);
							    

							    if((work_end_elem.attr('aria-changed') === true)
							    	
							    	&& (_end.getTime() < _start.getTime())){

							    	E.emit('sweetalert', {
							    		title:"Wrong Work Experience Dates",
							    		text:"The dates for your work experience don't add up. \r\n\t\t Please, check agian",
							    		info:"error",
							    		showCancelButton:false
							    	}, function(){

											
										_target.val(initial_vals.start_date).addClass('wrong');


							    	});


							    	return false;
							    }

							    if(_target.is('.wrong')){

									_target.removeClass('wrong');
							    }
					});

					work_end.daterangepicker({
	       						singleDatePicker: true, 
	       						showDropdowns:true
	       					}, function(start, end, label){
							    
							    
							    var _target = this.element,


							    	work_start_elem = $(_target.attr('name').replace(
							    							"[to_date]", "[from_date]"
							    						)),
							    	_start =  new Date(
							    						(work_start_elem.val() 
							    						|| initial_vals.start_date)
							    				),

							     	_end  = new Date( 
							     					(start.format('M/D/Y') 
							     						|| _target.val()) 
							     			);
							    
							    _target.data('stored', _end);

							    _target.attr('aria-changed', true);			    

							    if((work_start_elem.attr('aria-changed') === true)

							    	&& (_start.getTime() > _end.getTime())){

							    	E.emit('sweetalert', {
							    		title:"Wrong Work Experience Dates",
							    		text:"The dates for your work experience don't add up. \r\n\t\t Please, check agian",
							    		info:"error",
							    		showCancelButton:false
							    	}, function(){


							    			_target.val(initial_vals.end_date).addClass('wrong');


							    	});

							    	return false;
							    }

							    if(_target.is('.wrong')){

									_target.removeClass('wrong');
							    } 
					});

			},
			defineVars:function(){


				body = $(d.body);

				work_start = $('[name$="[from_date]"]');

				work_end = $('[name$="[to_date]"]');

				text_inputs = $('[placeholder]');

				photo_image = $('.profile-image');

				request_forms = $('form[target]');

				info_form = $('.mynti-context-form');

				file_input = $('.profile-picture-file');

				photo_upload_button = $('a[upload-file-async]:visible');

				photo_upload_iframe = $('iframe[name="avatarupload_sink"]').get(0);

				continue_button = $('.mynti-button-calm[disabled]');

				menu_item = $('.mynti-application-menuitem[accordion="true"]');

				skip_btn = $('.skip');

				add_btn = $('.add');

				initial_vals = {
					start_date:work_start.val(),
					end_date:work_end.val()
				}

				add_section_html = $('.work_details_block:first').get(0).outerHTML;

				ordinals = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];

				tracker_index = 0;

				tracker = ordinals[tracker_index];

			},
			stop:function(){

				body.off('click');

				request_forms.off('change');
			},
			destroy:function(){
				
				E = null;
				T = null;

				ordinals = null;
				tracker_index = null;
				tracker = null;

				photo_image = null;

				photo_upload_button = null;
				photo_upload_iframe = null;
				continue_button = null;
				text_inputs = null;
				initial_vals = null;

				work_start = null;
				work_end = null;

				body = null;
				menu_item = null;
				add_section_html = null;
				add_btn = null;
				skip_btn = null;

				file_input = null;
				info_form = null;

				request_forms = null;
			}
		}

	});

}(this, this.document));