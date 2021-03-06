/*!
 * @project: <MyNTI>
 * @file: <mainunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https;//www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module for main/common interaction point(s) of the app}
 * @remarks: {module script}
 */
 
(function(w, d, undefined){



		$cdvjs.Application.registerModule("mainunit", ["jQuery", "emitter", "utils", "Radixx", "Idle"], function(box){

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

			           U = box.utils,

			           	/*
			           		@var - {Session-Based Application State container}
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
			           		@var - {App Header jQuery object}
			           	*/
			           
			           header,

			           /*
			           		@var - {App Side Bar (Menu) jQuery object}
			           	*/
			           
			           sidebar,

			           	/*
			           		@var - {Form Validation Alert Message jQuery object}

			           	*/

			           alertbox,

			           	/* 
			           		@var - {Application State Action Creator object}

			           	*/

			           action,

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

							var image, promise = $cdvjs.Futures();

							if(typeof payload.ie_version == 'number'){

								if(payload.ie_version <= 9){

									image = new Image();

									image.onload = function(e){

										promise.resolve();
									};

									image.onerror = function(e){

										promise.reject();
									};

									image.src = "/?img_url=" + payload.iamge_url;

									return promise;

								}
							}else if(typeof payload.saf_version == 'number'){


									return promise;

							}

							setTimeout(function(){

								promise.resolve(payload.image_url);

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
			           			isRegPage = !!~d.documentElement.className.indexOf('no-idle-check');

			           		if(mode === "set"){

			           			/*
									the user idle activity monitor should not
									be able to write to the store when the page
									is the registration page.
			           			*/

			           			if(actionDispatch.type === 'storeSessionLockOnIdle'
			           					&& isRegPage){

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
						* @params storeTitle - {String}
						* @params actionKey - {String}
						* @return - {Undefined}
						*/

						storeListener = function(storeTitle, actionKey){

								console.log("store changed event fired !!");

								if(user_monitor.checkAway()){

									/* 
										if user session is locked, do 
										not respond to store change event
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

										return;

									break;
								}
						};


			           	E.on('sweetalert', sweetAlert)
			           		.on('inlinealert', inlineAlert)
			           			.on('appstate', appState)
			           				.on('imagepreview', imagePreview);

					  	return {
						       init:function(){

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
			           					session:{},
			           					dashboard_news:[]
			           				});

									
									$(w).on('scroll', function(event){
										       
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
					
								  header.on('click', 'button.btn', function(event){
								  		
								  		if($(this).is(".mynti-trigger-menu")){
								  			 if(sidebar.is(".flow-in")){
								  			 	sidebar.removeClass("flow-in");
								  			 }else{
								  			 	sidebar.addClass("flow-in");
								  			 }
								  		}
								  });

								  sidebar.on('click', '.capsule', function(event){
			           				
			           						return true;
			           			  });
							      
						       },
						       defineVars:function(){

						       		/* define all variables/references */


						           header = $('header.mynti-main-header');

						           sidebar = $('aside.mynti-side-bar');

						           user_monitor = new I({
										awayTimeout:960000,
								        onAway:function(){ console.log("user not active"); },
								        onAwayBack:function(){ console.log("user active"); },
								        onVisible:function(){ console.log("user refocued on window/tab ");  },
								        onHidden:function(){ console.log("user minimized/blurred window/tab"); }
						           }).start();

						           action = R.createAction({
						           		'storeSessionLockOnIdle':'IDLE_SESSION_LOCK',
						           		'addDsahBoardNews': 'ADD_DASHBOARD_NEWS'
						           });

						           store = R.createStore('myntiapp', function(action, area){

						           		var state = area.get();

						           		switch(action.actionType){
						           			case "IDLE_SESSION_LOCK":
						           				if(action.actionKey){
						           					state.session[action.actionKey] = action.actionData;
						           				}
						           			break;
						           			case "ADD_DASHBOARD_NEWS":

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

						       		header.off('click');

						       		sidebar.off('click');

						       		$(w).off('scroll');


						       		E.off('sweetalert')
						       			.off('inlinealert')
						       				.off('appstate');

						       		user_monitor.stop();

						       		store.unsetChangeListener(storeListener);
						       		store.disconnect();
							    
						       },
						       destroy:function(){

						       		/* recliam/clear-up memory */	

							       header = null;
							       sidebar = null;
							       alertbox = null;

								   store.destroy();

								   action = null;
								   store = null;

								   user_monitor = null;

							       E = null;
							       I = null;
							       U = null;
							       $ = null;
							       R = null;

						       }
       
  						}

		});

}(this, this.document));