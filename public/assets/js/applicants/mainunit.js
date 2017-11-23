/*!
 * @project: <MyNTI>
 * @file: <mainunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https;//www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module for main/common interaction point(s) of the app}
 * @remarks: {module script}
 */
 
;(function(w, d, undefined){


		$cdvjs.Application.registerModule("mainunit", ["jQuery", "emitter", "utils", "tools", "Radixx", "Idle"], function(box){

						/* 
							@var - {jQuery Constructor/Object} 
					 	*/
			       
			       var $ = box.jQuery,

			       		/*
			       			@var - {PubSub/Mediator Pattern Object for event sourcing}
			       		*/

			           E = box.emitter,

			           /*
			       			@var - {Helper APIs for rudimentary tooling functionality}
			       		*/

			           T = box.tools,

			           	/*
			           		@var - {Utitlies Object for helper functionality}
			           	*/

			           U = box.utils,

			           	/*
			           		@var - {Session-Based Application State container object}
			           	*/

			           R = box.Radixx, 

			           	/*
			           		@var - {User Idle/Activity Feature Constructor}
			           	*/
			           I = box.Idle,

			           	/*
			           		@var - {User Idle/Activity Monitor object}
			           	*/
			           user_monitor,

			           	/*
			           		@var - {Review Mode Status string}
			           	*/

			           mode,
				   
						/*
							@var - {Applicant access control status}
						*/
					   _status,
					   
					   /*
							@var -
					   */
					    nav_avatar,

			           	/*
			           		@var - {Acknowledgement Flag for Review Mode - don't run code more than once}
			           	*/

			           review_mode_ack,


			           	/*
			           		@var - {Navigation Bar jQuery object}
			           	*/
			           
			           navigation,

			           	/*
			           		@var - {Form Validation Alert Message jQuery object}

			           	*/

			           alertbox,

			           	/* 
			           		@var - {Application State Action Creator object}

			           	*/

			           action,


			           	/*
							@var - {Network State Observable Object}

							This object watches the internet connection availability status
			           	*/

			           	networkObservable,

			           	/*
							@var - {}

			           	*/

			           	_unloadSwitchActive,

			           	/*
			           		@var - {Application State Store object}

							This state store does not persist any data
							after page unload/reload 

			           	*/

			           store,

			           /**
						*
						*
						*
						* @params payload- {Object}
						* @params callback - {Function}
						* @return - {Undefined}
						*/

			           sweetAlert = function(payload, callback, calloff){

			           		var hasCallOff = typeof calloff === 'function',
			           			noCallBack = typeof callback !== 'function';
			           		
			           		if(noCallBack){
			           			swal(payload);
			           		}else{
			           			swal(payload, function(){
			           				$('.sa-button-container > .cancel', '.sweet-alert').off('click.ntiapp');
			           				callback.apply(this, [].slice(arguments));
			           			});
			           		}

			           		if(hasCallOff){
			           			$('.sa-button-container > .cancel', '.sweet-alert').one('click.ntiapp', calloff);
			           		}

			           },

			           /**
						*
						*
						*
						* @params message - {String}
						* @params msg - {Function}
						* @return - {Boolean}
						*/

			           inlineAlert = function(message, callback){

			           		if(typeof message == "string"){
			           			
			           			alertbox.text(message.replace('>', ''));
			           			
			           			if(typeof callback == 'function'){
			           				
			           				return callback(alertbox, message);
			           			}
			           		}

			           		return false;
			           },

			           /**
						*
						*
						*
						*
						* @params payload - {Object}
						* @return - {Object}
						*/

						imagePreview = function(payload){

							var image, imageSrc, promise = $.Deferred();

							if(typeof payload.ie_version == 'number'){

								if(payload.ie_version <= 9){

									image = new Image();

									image.onload = function(e){

										promise.resolve(imageSrc, payload.target_title);
									};

									image.onerror = function(e){

										promise.reject(e, payload.payload.target_title);
									};

									image.src = imageSrc = "/?img_url=" + payload.image_url;

									return promise;

								}
							}else if(typeof payload.saf_version == 'number'){

									imageSrc = '/?';

									return promise;

							}

							setTimeout(function(){ 

								imageSrc = payload.image_url;

								promise.resolve(imageSrc, payload.target_title);

							}, 0);
							

							return promise;

						},

			           /**
						*
						*
						*
						*
						* @params payload - {Object}
						* @return - {Object}
						*/

			           appState = function(payload){
			           		
			           		var key = payload.key,
			           			mode = payload.mode,
			           			actionDispatch = payload.actionDispatch,
			           			accessControlSafe = (_status === 'logged-out');

			           		if(mode === "set"){

			           			/*
									the user idle activity monitor should not
									be able to write to the store when the page
									is the registration page.
			           			*/

			           			if(actionDispatch.type === 'storeSessionLockOnIdle'
			           					&& accessControlSafe){

			           				return null;
			           			}

			           			/*
			           				
			           				if the action dispatch type is available 
			           				for dispatch as on the actions object
			           				(instance prototype) call the action creator 
			           				method to dispatch the action data to the
			           				store

			           			*/

			           			if(({}).hasOwnProperty.call(
			           					action.constructor.prototype, 
			           					actionDispatch.type)){
			           				action[actionDispatch.type](actionDispatch.data, key);
			           			}

			           			return {};

			           		}else if(mode === "get"){
			           			
			           			return store.getState(key? key : undefined);
			           		}
		            },
				   
				   /**
					*
					*
					*
					* @param imageObj - {Object}
					* @return - {Undefined}
					*/
				   
				    avatarChange = function(imageObj){

				   		var _url = imageObj.avatar_url;
							
						if(nav_avatar.length){
							setTimeout(function(){
								nav_avatar.attr({src:_url});
							},0);
						}
				   		
				    },

		           /**
					*
					*
					*
					* @params storeTitle - {String}
					* @params actionKey - {String}
					* @return - {Undefined}
					*/

					storeListener = function(storeTitle, actionKey){

								console.log("store changed event fired !!");

								if(user_monitor.checkAway()){

									/* 
										if applicant user session is locked
										or applicant user is idle, do 
										not respond to any store change 
										events
									*/

									return;
								}

								var state = this.getState(actionKey? actionKey : undefined);


								/*
									deal with locking the session for the
									LOCK-SCREEN feature
								*/

								switch(actionKey){

									case 'session_lock':

										console.log("session_lock active");

										/*
											make the lock screen overlay and modal
											(Bootstrap CSS) visible and setup and
											send an Ajax request to server to lock
											session

										*/

									break;

									default:

										/*

											detect if the user is reviewing his/her
											registration, if so, modify all links on
											the page to have the review mode query
											string key and value

										*/

										if(state.review_mode && !review_mode_ack){
											
											review_mode_ack = true;

											$('a[rel="next"]').each(function(index){

												this.href += '?mode='+mode;

											});
										}

									break;
								}
						};


			           	E.on('sweetalert', sweetAlert)
			           		.on('inlinealert', inlineAlert)
			           			.on('appstate', appState)
			           				.on('imagepreview', imagePreview)
										.on('navavatarchange', avatarChange);

					  	return {
						       init:function(promise){

							       /*
							       	  Ready all static assets for offline support
									  MyNTI should work basically without internet
									  access.
								  
							       */
							       
							       if(('serviceWorker' in w.navigator)){
							       	 	
								       	 	w.UpUp && w.UpUp.start({
												'content-url': '/offline-v2.html',
		  										'assets': [
		  											'/assets/img/png/mynti-banner.png',
													'/assets/img/svg/myntilogo.svg', 
													'/assets/css/applicants.css',
													'/assets/css/index.css',
													'/assets/img/svg/remita.svg',
													'/assets/img/svg/profile_avatar.svg',
													'/assets/img/svg/camera.svg',
													'/assets/img/svg/certificate.svg',
													'/assets/img/svg/blank-paper.svg',
													'/assets/img/svg/blank-paper2.svg',
													'/assets/img/png/remita.png',
													'/assets/img/png/pdf-file.png',
													'/assets/img/icons/gif/loader-grey.gif',
													'/assets/img/icons/gif/loader-grey-2x.gif',
													'/assets/img/icons/gif/loader-white.gif',
													'/assets/img/icons/gif/loader-white-2x.gif',
													'/assets/js/shim/es5shim.min.js',
													'/assets/js/shim/manup.min.js',
													'/assets/js/modernizr.min.js',
													'/assets/js/browsengine.min.js',
													'/assets/js/app-bootstrap.js',
													'/assets/js/applicants/mainunit.js',
													'/assets/js/applicants/app-commsunit.js',
													'/assets/js/applicants/register-formunit.js',
													'/assets/js/applicants/login-formunit.js',
													'/assets/js/applicants/payments-formunit.js',
													'/assets/js/applicants/review-formunit.js',
													'/assets/js/applicants/verify-formunit.js',
													'/assets/js/applicants/docsupload-formunit.js',
													'/assets/js/applicants/peronalinfo-formunit.js',
													'/assets/js/applicants/programme-formunit.js',
													'/assets/js/applicants/educate-formunit.js',
													'/assets/js/applicants/dashboard-formunit.js',
													'/assets/js/lib/lib/idle.js',
													'/assets/js/lib/lib/radixx.js',
													'/assets/js/lib/lib/cdv_js.js',
													'/assets/js/lib/lib/jquery.combopack.min.js',
													'/assets/js/lib/lib/sweetalert.js',
													'/manifest.json'
												]
											});
							       }
							       else if(('applicationCache' in w)){
							       		
							       			w.Cachr && Cachr.go({
									
											});
							       }
								
						       		/* 
						       			fix browsers (IE infact) that don't support
						       			"placeholder" attribute on input DOM elements
						       		*/

						       		if(w.Modernizr.input.placeholder !== true){

						       			$('[placeholder]').placeholder({

						       			});
						       		}
								   
						       		/*
						       			setup the application state store
						       		*/
						       		
						       		store.setChangeListener(storeListener);

						       		store.hydrate({
						       			password:'',
			           					session:{},
			           					review_mode:(mode === 'review')
			           				});

									
									$(w).on('mousemove scroll', function(event){
										  
											if(_unloadSwitchActive){
												user_monitor.start();
											}
								  
								    });
				                    
								    $('.jspTrack').on('mousemove', function(event){
									      var $context = $(this).closest('.jspScrollable');
										  var $bar = $(this).parent('.jspVerticalBar');
									      if(!$context.is('.now-scrolling')){
										     if(parseInt($bar.siblings('.jspPane').css("top")) < 0){
										         $context.addClass('now-scrolling');
											 }
										  }
										  
										  if(parseInt($bar.siblings('.jspPane').css("top")) === 0){
										         $context.removeClass('now-scrolling');
										  }
								  });
								  
								  $('.jspDrag').hide();
								  $('.jspScrollable').mouseenter(function(){
								      	  $('.jspDrag').stop(true, true).fadeIn('slow');
								  });
								  
								  $('.jspScrollable').mouseleave(function(){
								      	  $('.jspDrag').stop(true, true).fadeOut('slow');
								  });

								  $(d).on('requestunloadswitch', function(e){
								  		
								  		user_monitor.stop(_unloadSwitchActive = true);
								  		
								  });
					
								  navigation.on('click', 'a.mynti-link.action-link', function(event){

								  });

								  promise.then(function(){
									  
									  	  _status = $("html").data('status');

										  if(w.webpage.engine.gecko){
												d.addEventListener('focus', function(e){
													
													var currentFocusElement = e.target || e.srcElement;
													var previousFocusElement = e.relatedTarget || e.fromElement;

													w.previousFocusElement = previousFocusElement;
													w.currentFocusElement = currentFocusElement;

												}, true);
										  }else if(!w.webpage.engine.trident){
												d.addEventListener('focusin', function(e){
													
													var currentFocusElement = e.target || e.srcElement;
													var previousFocusElement = e.relatedTarget || e.fromElement;

													w.previousFocusElement = previousFocusElement;
													w.currentFocusElement = currentFocusElement;

												},false);
										  }

										  networkObservable = (function(n){
		
												var isStarted = false,
													_allowDelay = 6500, // 5 and a half seconds 
													_lastNetCheckTime = 0,
													_descriptor = null,
													_networkCheckDone = true,
													hasOwn = ({}).hasOwnProperty, 
													onLine = false, 
													lastOnLineStatus = false,
													_testFuncCalled = false,
													resetFlags = function(e){ 
														if(e.type != 'load' &&
															this.readyState != 4){
															return;
														} 

														/* 
															if the request timed out or the status is friendly, 
															then we have network connection
															
															Also sometimes, IE reports a status of 1223 instead of 204 
														*/

														if((this.status === 0 || 
										                         (this.status >= 200 && this.status <= 304)
										                         	|| this.status === 1223)){
															onLine = true;
														}
														
														_networkCheckDone = true;
														
													},
													_shouldUseBodyOnlineListener = (w.webpage.engine.trident 
																	&& typeof d.documentMode == 'number' 
																		&& d.documentMode <= 9);
												

												function testConnection(){
												 	var xhr;

												 	if(!_networkCheckDone){
												 		return onLine;
												 	}

												 	if('ActiveXObject' in w){
												 		xhr = new w.ActiveXObject('Microsoft.XMLHTTP');
												 	}else if('XDomainRequest' in w){
												 		xhr = new w.XDomainRequest();
												 	}else{
												 		xhr = new XMLHttpRequest();
												 	}

												 	/*
														Avoiding the browser from returning it's cache contents
														by including a fresh value for the "no_cache" query string
														parameter

														This AJAX request will always be synchronous for obvious 
														reasons :-)
												 	*/
												 	xhr.open('HEAD', w.location.origin+'/?no_cache='+Math.random().toString().replace('.',''), false); // sync request
		 											xhr.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
												 	
												 	if('onreadystatechange' in xhr){
												 		xhr.onreadystatechange = resetFlags;
												 	}else{
												 		xhr.onload = resetFlags;
												 	}

												 	xhr.ontimeout = function(){ onLine = true; };

												 	/*!!
												 	 * Avoid InvalidAccessError: synchronous XMLHttpRequests do not support [timeout] and [responseType]
												 	 * in Firefox (Gecko) browser
												 	 */
												 	if(!w.webpage.engine.gecko){
												 		xhr.timeout = 0; // enforce no delay for request timeouts 
												 		xhr.onprogress = function(){};
												 	}

												 	try{
												 		// setTimeout(function(){
												 			xhr.send(_networkCheckDone = false); 
												 		//}, 0);

												 		onLine = true; 
											 		}catch(e){
											 		 	onLine = false; 
													}finally{
														xhr = null;
													} 

													return onLine;

												};

												/*
												 	Setting up a Polyfill for [navigator.onLine] where possible
												 	as a result of browser inconsistencies

												 	Let's GO! :)
												*/


												if(!!n.constructor){
													
													if(!('onLine' in n) && !hasOwn.call(n.constructor.prototype, "onLine")){
														n.constructor.prototype.__onlineFlagIsShim  = true;
														n.constructor.prototype.onLine = testConnection();
													}

													/* 
														Trying to figure out if we're in "Work Offline" mode flag
														fuckup-trance in Firefox 4.0 - 40.0 / Opera 7.0 - 14.0

														This make old Firefox/Opera report the wrong network
														status

														see: https://developer.mozilla.org/en-US/docs/Web/API/NavigatorOnLine/onLine

													*/ 

													if((w.webpage.engine.gecko || w.webpage.engine.presto
														&& ((w.opera.version() || w.operamini.version()) >= 7.0))
														&& n.onLine !== onLine){ 
														n.constructor.prototype.__onlineFlagIsShim  = true;


														/*
															Ruthlessly override the native 'onLine' flag
															using a property descriptor definition.

															Quite miraculously... this works! 

															:-)
														*/

														if('defineProperty' in Object){

															_descriptor = Object.getOwnPropertyDescriptor(n, "onLine") || {};
															
															Object.defineProperty(n, 'onLine', {
																enumerable:false,
																configurable:false,
																get:function(){
																	return testConnection();
																},
																set:(_descriptor.set || function(){})
															});
														}else if('__defineGetter__' in n){
															n.__defineGetter__('onLine', testConnection);
															n.__defineSetter__('onLine', (_descriptor.set || function(){}))
														}	
													}
												}else{
												 	if(!('onLine' in n)){
												 		n['onLine'] = testConnection();
													}
												}



												function $start(){

														isStarted = true;

														$(d).on('click', function(e){
															var now = (new Date).getTime(),
																node = e.target;

															if((node.nodeType == 3 
																|| (node.nodeType == 1 
																	&& /^(?:A|BUTTON|INPUT)$/.test(node.nodeName)))
																		&& (now - (_lastNetCheckTime || now)) >= _allowDelay){
																if(n.__onlineFlagIsShim 
																	|| hasOwn.call(n, "onLine")){
																		_lastNetCheckTime = now;
																		T.trigger_event('networkcheck', w, {}, w);
																}
															}
														});

														if(!n.onLine 
															|| (n.onLine !== true)){
															T.trigger_event('offline', 
																(_shouldUseBodyOnlineListener? d.body : w), {}, w);	
														}

														if(n.__onlineFlagIsShim 
															|| hasOwn.call(n, "onLine")){

																$(w).on('networkcheck', function(e){
																	
																	if(n.onLine !== lastOnLineStatus){

																		if(!_testFuncCalled){
																			testConnection();
																		}

																		T.trigger_event((onLine ? 'online' : 'offline'), 
																			(_shouldUseBodyOnlineListener? d.body : w), {}, w);

																		lastOnLineStatus = onLine;
																		
																		if(hasOwn.call(n, "onLine")){
																			n.onLine = lastOnLineStatus;
																		}else{
																			n.constructor.prototype.onLine = lastOnLineStatus;
																		}

																		_testFuncCalled = false;

																	}

																});
															}

														}
												
														return {
												
															on:function(event, handler){
																if(_shouldUseBodyOnlineListener){
																	$(d.body).on(event, handler);		
																}else{
																	$(w).on(event, handler);
																}

																if(!isStarted){
																	$start();
																}
															},
															off:function(event){
																if(_shouldUseBodyOnlineListener){
																 	$(d.body).off(event);
																}else{
																	$(w).off(event);
																}
															}
														}
												
												}(w.navigator));

												
												/*
													Tell the user whenever he/she is online or offline 
													{Internet Connection Availablility}
												*/

												networkObservable.on('online', function(e){

													E.emit('sweetalert', {
														title:"Internet Okay!",
														text:"You are now online. Please, resume your application",
														type:"info",
														timer:2500,
														showCancelButton:false,
														showConfirmButton:false
													});
												});

												networkObservable.on('offline', function(e){

													E.emit('sweetalert', {
														title:"Internet Not Working!",
														text:"You are now offline. This means you cannot continue with your application",
														type:"error",
														showCancelButton:false,
														showConfirmButton:false
													});
												});
								  });
							      
						       },
						       defineVars:function(){

						       		/* define all variables/references */

						       	   mode = U.handle_query('mode') || 'application';
							   
						           navigation = $('nav.mynti-desktop-navigation');
						           alertbox = $('p.mynti-alert');

						           user_monitor = new I({
										awayTimeout:960000,
								        onAway:function(){ 
										  console.log("user not active"); 
										
										  /*var user = JSON.parse(w.localStorage.getItem('MYNTI_APPLICANT_LOGIN'));
										  
										  console.log(new Date().toTimeString() + ": away");
										  
										  asyncServerRequest({
											  url:'/guard/session',
											  method:'POST',
											  data:'{"email":"'+user.email+'","lock":true}',
											  responseContentType:'json'
										  }).then(function(res){
												  if(res.data.lockConfirmed){
													  user.isLocked = res.data.lockConfirmed;
													  // the code below triggers the 'storage' event
													   w.locationStorage.setItem('MYNTI_APPLICANT_LOGIN', user);
												  }
										  });*/
									  },
								        onAwayBack:function(){ console.log("user active"); },
								        onVisible:function(){ console.log("user refocued on window/tab ");  },
								        onHidden:function(){ console.log("user minimized/blurred window/tab"); }
						           });

						           user_monitor.start();
								   
								   nav_avatar = $('img#nav-avatar');

						           action = R.createAction({
						           		'storeRegPassword':'STORE_REG_PASSWORD',
						           		'storeSessionLockOnIdle':'IDLE_SESSION_LOCK'
						           });

						           store = R.createStore('myntiapp', function(action, area){

						           		var state = area.get();

						           		switch(action.actionType){
						           			case "STORE_REG_PASSWORD":
						           				state.password = action.actionData;
						           			break;
						           			case "IDLE_SESSION_LOCK":
						           				if(action.actionKey){
						           					state.session[action.actionKey] = action.actionData;
						           				}
						           			break;
						           			default:
						           				user_monitor.handleVisibilityChange();
						           				return null;
						           			break;
						           		}

						           		return area.put(state);

						           }, {});
						       },
						       stop:function(){

						       		/* unbind event handlers/listeners */

						       		user_monitor.stop();

						       		navigation.off('click');

						       		$(d).off('click');

						       		$(w).off('scroll');

						       		$(w).off('networkcheck');


						       		E.off('sweetalert')
						       			.off('inlinealert')
						       				.off('appstate');

						       		networkObservable.off('online');
						       		networkObservable.off('offline');

						       		store.unsetChangeListener(storeListener);
						       		store.disconnect();


								   	store.destroy();
							    
						       },
						       destroy:function(){

						       		/* recliam/clear-up memory */	

							       navigation = null;
							       alertbox = null;

								   action = null;
							       	   _status = null;
								   store = null;

								   mode = null;
								   user_monitor = null;
								   networkObservable = null;
								   review_mode_ack = null;

							       E = null;
							       U = null;
							       T = null;
							       $ = null;
							       R = null;

						       }
       
  						}

		});

}(this, this.document));
