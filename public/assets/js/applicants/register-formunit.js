/*!
 * @project: <MyNTI>
 * @file: <register-formunit.js>
 * @author: <https://www.omniswift.com>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form interacion/submission for the registration side of the app}
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

	           	/*
	           		@var - {Date-Type Input jQuery Object}
	           	*/

	           date_input,

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
							
								//payload.form.attr('action', payload.url);
								//payload.form.attr('method', payload.method);
							 	//payload.form.attr('target', "submit_sink");


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
							
							delete payload.form;

							try{
								hidden_iframe.contentWindow.onmessage(payload);
							}catch(err){
								/* 
									Firefox throws an error, so we need to catch it
									and work out the fix.
								 */ 
								if(err.name === "TypeError"){
									;
								}

								hidden_iframe.contentWindow.postMessage(payload, "about:blank");
							}
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
				* 
				*/

				setCaretPosition = function(elem, caretPos){
					if(elem !== null){
						if(elem.createTextRange){
							var range = elem.createTextRange();
							range.move('character', caretPos);
							range.select();
						}else{
							if(elem.selectionStart){
								elem.focus();
								elem.setSelectionRange(caretPos, caretPos);
							}else{
								elem.focus();
							}
						}
					}
				},

			   /**
				* Checks the initial password and conform password and
				* ensures equality	
				*
				*
				* @params elem - {HTMLInputElement|HTMLSelectElement}
				* @params result - {Boolean}
				* @return - {Undefined}
				*/

			   checkIn = function(elem, result){

			   			var passvalue;

			   			switch(elem.name){

			   				case "password_confirm":

                        	    passvalue = E.emit(
                        	  						"appstate", {
                        	  							key:'password',
                        	  							mode:'get',
                        	  							actionDispatch:null
                        	  					});

                        	  	if(elem.value !== passvalue.appstate[0]){
                        	  		
                        	  		E.emit("inlinealert",
                        	  			">Passwords don't match - Please, check again",
                        	  				setMessageCallback);

                        	  		result = false;
                        	  	}

                        	break;
							case "password_try":

                        	  		E.emit(
                        	  			"appstate", {
                        	  				key:'', 
                        	  				mode:'set',
                        	  				actionDispatch:{
                    	  						type:'storeRegPassword',
            	  								data:elem.value
                        	  				}
                        	  		});
                        	break;
                        }

                        return result;

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
                        }else{
                        		
                        	  		result = checkIn(elem, result);
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

							/*
								if the broswer has JavaScript enabled, then stop
								the browser from doing its' own native form 
								validation
							*/
													   
							if(body.parent().is('.js')){

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

	       					date_input.daterangepicker({
								singleDatePicker: true, 
	       						showDropdowns:true
	       					}, function(start, end, label){
							    
							    var now = moment();
							    var years_difference = moment().diff(start, 'years');
							    if(years_difference < 23){
							    	
							    	/* more code here later ... */

							    	return false;
							    } 
							});

							date_input.val('');


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
									

									$(event.target).trigger('change', ['fromKeys']);
									
								}

							}));
							

							date_input.on('show.daterangepicker', function(event, picker){ 
								
								/* 
									adjust the position of the 'picker' 
									DOM element relative to available
									screen rea estate
							 	*/

							 	// var fullHeight = picker.outerHeight();

							});
							

							date_input.on('hide.daterangepicker', function(event, picker){ 
								
								/* 
									forward focus to the next input element
									if and only if it has no error class
									(.input-error)
							 	*/

							 	var currentTabIndex = date_input.attr('tabindex'),
							 		currentInputTab = $('input[tabindex="'+currentTabIndex+'"]');

							 		if(!date_input.is('.input-error')){
							 			
							 			currentInputTab.trigger('focus');
									 }
									 
									
							});

							body.on('click', 'a.mynti-button-calm', function(event){

								/* 
									the only DOM element of interest here is the submit
									button. when ever a click is detected on the submit
									button, submit the form.

									any other DOM element should pass and return control
									as {true}.
								*/

								if($(this).is(submit_button.selector)){
									if(submit_button.is("[disabled]")){
										
										return false;
									}
									currentFocusFormElement = null;
								}else{
									return true;
								}

								E.emit('sweetalert', {
									  title: "Hold On A Bit!",
									  text: "Are you sure you entered all information correctly ?",
									  type: "info",
									  html: true,
									  showCancelButton: true,
									  confirmButtonText: "Okay, Submit Form!",
									  cancelButtonText: "Errmm, Wait Let Me Check",
									  closeOnConfirm: false,
									  showLoaderOnConfirm: true
								}, function(){

									form.data('fill-list', null);

									/*
										pass the {Laravel} CSRF token from the meta tag
										to the submission form to avoid the dreaded

										:: [TokenMismatchException]
									*/
									
									var fdata = T.query_to_json(form.serialize().toLowerCase(), true);

									if(!({}).hasOwnProperty.call(fdata, '_token')){

										fdata['_token'] = $("html").data('token');
									}

									form.data('form-data', JSON.stringify(fdata));

									/* 
										after all the "scobby-doo" above, now submit
										the form proper
									*/

									return form.trigger('submit');

								}, function(){

										/* focus into the form for the user to correct it */

										$('[autofocus]:not(:focus)').eq(0).focus();

								});
								
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
									$(this).trigger('change', ['fromKeys']);
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

								if(currentFocusFormElement.nodeName.indexOf("SELECT") + 1){
										// currentFocusFormElement.blur();
										currentFocusFormElement.click();
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
									filltotal = form.find('input,select').length,
									percent = 0,
									hasError = true,
									statusElem = null,
									formerElem = null,
									currentTabIndex = -1,
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

									currentTabIndex = parseInt(statusElem.attr('tabindex')) - 1;

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

									currentTabIndex = parseInt(statusElem.attr('tabindex')) - 1;

									if(index){
										filllist.splice(index, 1);
									}

								}

								formerElem = $(".form-control[tabindex='"+currentTabIndex+"']", form);

								if(formerElem.length){

									if(formerElem.value.length == 0){

										formerElem.addClass('input-error');
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
									/*setTimeout(function(){
										this.select();
									},0);*/
									setCaretPosition(this, $(this).val().length + 1);
								}
							});

							form.on('submit', function(event){


									event.preventDefault();

									var data = form.data('form-data');

									asyncServerRequest({
										method:'POST',
										data:JSON.parse(data),
										requestContentType:"json",
										responseContentType:"text",
										progress:function(){},
										form:form
									}, true).then(function(resp){ 
										
										return E.emit('sweetalert', {
											title:"Thank You!",
											text:"Your Application was well recieved",
											type:"info",
											timer:2000,
											showConfirmButton: false
										});

									}, function(resp){

										 console.log(resp);

									});

									return false;

							});
	       			},
	       			defineVars:function(){

	       				/* define all variables/references */

	       				date_input = $('input[name="birthdate"]');
	       				submit_button = $('a.mynti-button-register-submit');
	       				hidden_iframe = $('iframe#submit_sink').get(0);


						iframe_doc = (hidden_iframe.contentDocument) ? hidden_iframe.contentDocument : hidden_iframe.contentWindow.document;
                              
	       				suspended_box = $('div.mynti-register-suspended-box');
	       				form = $('form[name="registerform"]');
	       				body = $(d.body);

	       				formrgx = {
							"formValidateMap" : {
			                          // all routines defined here must have the similar signature !!
			                          // MyNTI won't use native HTML5 client-side form input validation : [novalidate] 
			                       "phone":function(value){
			                            if(!(value && value.length)) return ">Please enter your mobile number";
			                            return /^(\+234|0)(?:70|80|81|90|91|71)(?:\d{8})$/.test(value) || "This is not a valid mobile number";
			                       },
			                       "birthdate":function(value, format){
			                          	var pattern = new RegExp(format), match = pattern.exec(value);
			                          	if(value == "11/08/1980"){
			                          		return ">Please enter your date of birth";
			                          	}
			                            return match !== null && (parseInt(match[1]) <= 12 && parseInt(match[2]) <= 31) || "This is not a valid birth date";
			                       },
			                       "email":function(value, format, maxlength){
			                           if(!(value && value.length)) return ">Please enter your email address";
			                           /* This email regex does not allow capital letters in the email text - follows the HTML5 standard too */
			                           return /^[a-zA-Z0-9!\#\$\%\&\'\*\+\/\=\?\^\_`{|}~-]+(?:\.[a-zA-Z0-9!\#\$\%\&\'\*\+\/\=\?\^\_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|co\.uk|co|ca|co\.za|net|ng|com\.ng|gov|mil|biz|info|mobi|name|edu|nti|aero|jobs|museum)\b$/.test (value) || "This is not a valid email address";
			                        },
			                        "password_try":function(value){
			                            if(!(value && value.length)) return ">Please enter your password";
			                            return /^[\w\S]{8,}$/.test(value) || "Password should be at least 8 characters";
			                        },
			                        "amount":function(value){
			                            if(!(value && value.length)) return ">Please enter a valid amount";
			                            return /^\d+\.?\d*$/.test(value) || "This is not a valid amount";
			                        },
			                        "file":function(value, format){
			                        	return true;
			                        },
			                        "maritalstatus":function(value, enums){
			                        	enums = JSON.parse(enums);
										if(value === "-"){
											return ">Please choose your marital status";
										}
										return (value in enums) || "This is not a valid status type";
			                        },
									"address":function(value){
									    if(!(value && value.length)) return ">Please enter your address";
			                            return /^[\w ,.-\/]{39,}$/.test(value) || "This is not a valid address";
									},
									"gender":function(value, enums){
										enums = JSON.parse(enums);
										if(value === "-"){
											return ">Please choose your gender";
										}
										return (value in enums) || "This is not a valid gender type";
									},
									"firstname":function(value, title){
										if(title === "middlename" 
											 && value.length == 0){
											return true;
										}
										if(title != "middlename"){
											if(!(value && value.length)) return ">Please enter your "+title;
										}else{
											if(value && value.length == 1) return ">This is optional, however enter correctly";
										}
										return /^[a-zA-Z\-']{2,60}$/.test(value) || "This is not a valid name";
									}
			                    }	
						};

						formrgx.formValidateMap["password_confirm"] = formrgx.formValidateMap["password_try"];
						formrgx.formValidateMap["lastname"] = formrgx.formValidateMap["middlename"] = formrgx.formValidateMap["firstname"];
						
						currentFocusFormElement = null;
	       			},
	       			destroy:function(){

	       				/* recliam/clean-up memory */
	       				
	       				date_input = null;
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
