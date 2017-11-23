/*!
 * @project: <MyNTI>
 * @file: <login-formunit.js>
 * @author: <https://www.omniswift.com>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form interaction/submission for the login side of the app}
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

				/* 
					@var - {jQuery Constructor/Object} 
			 	*/
	       var $ = box.jQuery,

	       		/*
	       			@var - {PubSub/Mediator Pattern Object for event sourcing}
	       		*/

	           E = box.emitter,

	           	/*
	           		@var - {Utitlies Object for helper functionality}
	           	*/

	           T = box.tools,


	           suspended_box,

	           hidden_iframe,

	           iframe_doc,

	           submit_button,

	           form,

	           body,

	           formrgx,

	           currentFocusFormElement,

	           /**
				*
				*
				*
				* @params payload - {Object}
				* @params useIframe - {Boolean}
				* @return - {Object}
				*/

	           asyncServerRequest = function(payload, useIframe){
	            	 
	            	 /* 
	            	 	send a mediator message to the 
	            	 	'app-commsunit' module to initiate 
	            	 	an AJAX request 
	            	 */

		        	var context, iframe, lcallback, ucallback,
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

							var status = e.target.contentWindow.document.title,
							response = e.target.contentWindow.document.body.innerHTML;

							if((status.search(/[/d]+|WAM/) + 1)
								|| response.match(/^|false$/)){
								
								context.reject(response);
							}else{

								context.resolve(response);
							}

						};

						ucallback = function(e){

							;
						};

						if(payload.form instanceof $){
							


								if(typeof hidden_iframe.attachEvent === 'function'){

									/* IE 8/9,  Opera */
									hidden_iframe.attachEvent('onload', lcallback);
									hidden_iframe.attachEvent('onunload', ucallback);
								
								}else if(typeof hidden_iframe.addEventListener === 'function'){

									/* Firefox, Chrome, Safari */

									hidden_iframe.addEventListener('load', lcallback, false);
									hidden_iframe.addEventListener('unload', ucallback, false);

								}
							
							payload.url = _origin + form.attr('action');

							payload.method = form.attr('method');

							// console.log(payload.url, ":::::", payload.method);
							
							delete payload.form;

							hidden_iframe.contentWindow.onmessage(payload);
						}
					} 

		        	return context;
		       },

		       /**
				*
				*
				*
				* @params elem - {jQuery}
				* @params msg - {String}
				* @return - {Boolean}
				*/

		       setMessageCallback = function(elem, msg){

                  		if(msg.indexOf('>') + 1){
                  			if(!elem.is('.bg-info')){
                      			elem.addClass('bg-info');
                      		}
                  		}else{
                      		if(!elem.is('bg-danger')){
                      			elem.addClass('bg-danger');
                      		}
                      	}

                    	elem.removeClass('hidden');

                    	return false;
              },

              /**
			   *
			   *
			   *
			   * @params elem - {jQuery}
			   * @params msg - {String}
			   * @return - {Undefined}
			   */

		      clearMessageCallback = function(elem, msg){
						
						if(elem.is('.hidden')){
							return;
						}

						if(!elem.is('.bg-info')){
                  			elem.addClass('bg-info');
                  		}

						if(elem.is('.bg-danger')){
							elem.removeClass('bg-danger');
						}

						elem.addClass('hidden');
			   },

			   /**
				* Validates the contents of each form input
				*
				*
				* @params elem - {HTMLInputElement|HTMLSelectElement}
				* @return - {Stirng|Boolean}
				*/

		       validateFormElement = function(elem){

                        var result = false;
		        		
                        if(!elem || !(elem.nodeType && elem.form)){ // detect as <select>, <input> element(s) only
                            return result;
                        }

                        result = formrgx.formValidateMap[elem.name](
           						elem.value || (elem.options 
           										&& elem.options[elem.selectedIndex].value) || "", 
           						elem.getAttribute("aria-validate-filter") || null,
           						elem.getAttribute("max-length")
           				);

                        if(typeof result == "string"){
                             		
                             		result = E.emit("inlinealert", 
	                              				result,
	                              					setMessageCallback);

                             		result = result.inlinealert[0];
                        }

                        return result;
               };


	       		return {
	       			init:function(promise){

	       					/* 
       							statically load registration submission 
       							hidden iframe

	       					*/

	       					setTimeout(function(){
		       					// Opera OR old IE/Firefox :(
		       					if(window.opera || window.external){ 
		       						iframe_doc.open(); 
		       					}
		       					// Chrome/Edge/Safari ;)
		       					else{ 
		       						iframe_doc.open('text/htmlreplace'); 
		       					}
								
								promise.then(function(){
									iframe_doc.write(body.data('iframe-html'));
									iframe_doc.close();
								});
	       					}, 0);

	       					/*
	       						fix Internet Explorers' lack of support for
	       						"autofocus" attribute on input DOM elements
	       					*/

	       					if(w.Modernizr.input.autofocus !== true){
	       						
	       						$('[autofocus]:not(:focus)').eq(0).focus();

							}
													   
							if(body.parent().is(".js")){

								form.attr('novalidate', '');
							}

	       					/*
	       						setup animation for suspended box to appear
	       						centralized on the screen
	       					*/

	       					setTimeout(function(){

	       						if(!suspended_box.is('suspended-box-in')){
	       							
	       							suspended_box.addClass('suspended-box-in');
	       						}

	       					}, 0);

	       					/*
	       						 reset the form - clean out its' input fields 
	       					*/

	       					form.trigger('reset');
	       					
	       					/* 
	       						setup date-picker widget for the date input 
       						*/


							$(d).on('keydown', 'input[placeholder]', $.debounce(250, function(event){

								/* 
									watchout for the user tabbing through 
									the screen inputs and links.
									Whenever the current focus element is an
									input, trigger a change event.
								*/

								if(event.keyCode == 9){
									if(currentFocusFormElement !== null){
										$(event.target).trigger('change', ['fromKeys']);
									}
								}else{
									if (event.keyCode === 13 &&
										(typeof event.target.type != "undefined") &&
										(event.target.value.length > 0) &&
										(event.target.className.indexOf('input-error') == -1)) {
										
										event.preventDefault();
										submit_button.removeAttr('disabled');
										submit_button.trigger('click');
										return false;
									}

									$(event.target).trigger('change', ['fromKeys']);
									
								}

							}));
							

							body.on('click', 'a.btn', function(event){

								/* 
									the only DOM element of interest here is the submit
									button. when ever a click is detected on the submit
									button, submit the form.

									any other DOM element should pass and return control
									as {true}.
								*/

								if($(this).is(submit_button.selector)){

									if($(this).is('[disabled]')){
										return false;
									}

									currentFocusFormElement = null;
									
								}else{ 

									return true;

								}

								var fdata = T.query_to_json(
														form.serialize()
														.toLowerCase(),
														true);


								/*
									pass the {Laravel} CSRF token from the meta tag
									to the submission form to avoid the dreaded

									:: [TokenMismatchException]
								*/

								if(!(({}).hasOwnProperty.call(fdata, '_token'))){

									fdata['_token'] = $("html").data('token');
								}

								form.data('form-data', JSON.stringify(fdata));

								/* 
									after all the "scobby-doo" above, now submit
									the form proper
								*/

								return form.trigger('submit');

							});

							form.on('blur', 'input[placeholder],select', function(event){
								/*
									if the input element is about to loose focus, detectect
									whether it has been flagged to contain a wrong input
									
									if so, stop the blur action, select the wrong input
									text and send focus back to it.

									if not, trigger a change event.
								*/
								if($(this).is('.input-error')){
									$(this).trigger('focus').trigger('select');
									return false;
								}else{
									$(this).trigger('change',['fromKeys']);
								}

						});

						form.on('focusin', 'input[placeholder],select', function(event){

								/*
									detect whenever any input DOM elelent recieves focus
									and update the inline alert elements' visibility 
									accordingly.

									if focus is activated on an input DOM element that isn't
									the next element in line to be filled with content, then
									disbale focus on that input DOM element

								*/


								var formerFocusInput = currentFocusFormElement;
								
								if(formerFocusInput !== event.target){
									 	E.emit('inlinealert',
								 				"",
								 					clearMessageCallback);
								}

								if(event.refocused){
									
										return true;
								}

								currentFocusFormElement = event.target; 

								if((formerFocusInput && formerFocusInput.tabIndex + 1 || -1) != currentFocusFormElement.tabIndex){
									
										return false;
								}


							});

							form.on('change', 'input[placeholder],select', function(event, param){

								/*
									detect changes in the contents of each input DOM element
									then validate the contents of each field in turn. Also,
									track the completion progress of the form.

									update inline alerts depending on the result of input 
									content validation.

								*/
								
								var form_data = (form.data("fill-list") || '[]'),
									filllist = JSON.parse(form_data),
									fillprogress = filllist.length,
									filltotal = form.find('input[placeholder],select').length,
									percent = 0,
									hasError = true,
								    	statusElem = null,
									index = filllist.indexOf(this.name) + 1;
								
									if(param != 'fromKeys'){
										if(fillprogress === filltotal 
											|| (this.nodeName.toLowerCase() == 'input' 
												&& !$(this).is('.input-error'))){
										   	return true;
										}
									}

								if(validateFormElement(this)){
									E.emit("inlinealert", 
										"",
										clearMessageCallback);

									if($(this).is('select')){
										statusElem = $(this).next('.stub');
										statusElem.removeClass('input-error');
										statusElem.text($(this).find("option:selected").text());
									}else{
										statusElem = $(this);
										statusElem.removeClass('input-error');
									}

									statusElem.addClass('input-correct');

									if(!index){
										filllist.push(this.name);
									}

								}else{

									if($(this).is('select')){
										statusElem = $(this).next('.stub');
										statusElem.addClass('input-error');
										statusElem.text($(this).find("option:selected").text());
									}else{
										statusElem = $(this);
										statusElem.addClass('input-error');
									}

									statusElem.removeClass('input-correct');

									if(index){
										filllist.splice(index, 1);
									}

								}

								form.data("fill-list", JSON.stringify(filllist));
								fillprogress = filllist.length;

								percent = Math.round((fillprogress/filltotal) * 100);


								if(percent === 100){
									
									hasError = !!form.find('.input-error').length;

									if(!hasError){
										submit_button.removeAttr('disabled')
													.removeClass('mynti-submission-invalid')
												 		.addClass('mynti-submission-valid');
										
									}

									return true;
								}

								hasError = statusElem.is('.input-error');

								if(hasError){
									
									;
								}
							});

							form.on('submit', function(event){


									var data = form.data('form-data');

									asyncServerRequest({
										
										data:JSON.parse(data),
										requestContentType:"json",
										responseContentType:"text",
										progress:function(){},
										form:form

									}, true).then(function(resp){ 
										
										return E.emit('sweetalert', {
											title:"Login",
											text:"Your Login Attempt Was Successful",
											type:"info",
											timer:2000,
											showConfirmButton:false
										});

									}, function(resp){

										// alert(resp);

									});

									return false;

							});
	       			},
	       			defineVars:function(){

	       				/* define all variables/references */

	       				submit_button = $('a.mynti-button-login-submit');
	       				hidden_iframe = $('iframe#submit_sink').get(0);


						iframe_doc = (hidden_iframe.contentDocument) ? hidden_iframe.contentDocument : hidden_iframe.contentWindow.document;
                              
	       				suspended_box = $('div.mynti-login-suspended-box');
	       				form = $('.mynti-login-form');
	       				body = $(d.body);

	       				formrgx = {
							"formValidateMap" : {
			                          // all routines defined here must have the similar signature !!
			                          // MyNTI won't use native HTML5 client-side form input validation : [novalidate] 
			                      
			                       "email":function(value, format, maxlength){
			                           if(!(value && value.length)) return ">Please enter your email address";
			                           /* This email regex does not allow capital letters in the email text - follows the HTML5 standard too */
			                           return /^[a-zA-Z0-9!\#\$\%\&\'\*\+\/\=\?\^\_`{|}~-]+(?:\.[a-zA-Z0-9!\#\$\%\&\'\*\+\/\=\?\^\_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[\.A-Z]{2,4}|com|org|co\.uk|co|ca|co\.za|net|ng|com\.ng|gov|mil|biz|info|mobi|name|edu|nti|aero|jobs|museum)\b$/.test (value) || "This is not a valid email address";
			                        },
			                        "password":function(value){
			                            if(!(value && value.length)) return ">Please enter your password";
			                            return /^[\w\S]{8,}$/.test(value) || "Something isn't right with this password";
			                        }
			                    }	
						};

						
						currentFocusFormElement = null;
	       			},
	       			destroy:function(){

	       				/* recliam/clean-up memory */
	       				
	       				submit_button = null;
	       				form = null;
	       				body = null;
	       				suspended_box = null;
	       				hidden_iframe = null;

	       				iframe_doc = null;
	       				currentFocusFormElement = null;
	       			},
	       			stop:function(){

	       				/* unbind all event handlers */
	       				
	       				form.off('focusin')
	       					.off('submit')
	       					.off('change');

	       				body.off('click');

	       				$(d).off('keydown');


	       			}
	       }
	 });

}(this, this.document));
