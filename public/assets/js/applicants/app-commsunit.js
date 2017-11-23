/*!
 * @project: <MyNTI>
 * @file: <app-commsunit.js>
 * @author: <https://omniswift.com.ng>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module for all server communication to fetch and/or push data async-style}
 * @remarks: {module script}
 */	
 
;(function(w, d){

  
     // requiring stuff
     
     var noop = function(){},

         connect,

         body,

         disconnect,
	 
	 eventIsNative = function(event){
                      
	    return (!('__timertick' in event));
         };

         function TaskQueue(contextObj){

           // @REM: a queue is a FIFO data structure
           
           this.methods = {}; // callback queue container
           this.methodsOrder = [];
           this.response = null; // async/sync response
           this.first = null; // the first task object in the queue
           this.flushed = false; // flush flag
           this.context = contextObj; // queue context object
           this.isDaemon = function(){ return true; };   
           this.setIndex = function(arr, aVal, indx){
              if(typeof arr[indx] == "undefined") indx = arr.length - 1, _temp;
                  for(var i =0; i < arr.length; i++){
                    if(arr[i] === aVal){
                        if(arr[indx] != aVal){ 
                           _temp = arr[i];   
                           arr[i] = arr[indx];
                        }
                        arr[indx] = _temp;
                   }
              }
           }       
        }  

        TaskQueue.prototype.enqueue = function(fn, args){  // fn: Function, args: Array
             var ag = args, 
                 id = get_id();
                if(typeof fn != "function") return;
              ag = (ag instanceof Array && ag) || [ag];
              if(this.flushed){
                  fn.apply(this, ag); // assump: if the queue has been flushed, then the async response should be available as "ag"
              }else{ 
                  //this.methods.unshift({"task":fn,"args":ag});
                   this.methods[id] = {"task":fn,"args":ag};
                   this.methodsOrder.push(id);
                   this.first = this.methods[this.methodsOrder[0]];        
              }

              return id;
        };

        TaskQueue.prototype.dequeue = function(arg){
              
              if(!this.first){
                 return -1;
              }

              this.first["args"].push(arg);
                  this.response = this.first["task"].apply(this, this.first["args"]);
              if(this.methodsOrder.length){
                  delete this.methods[this.methodsOrder[0]];
                  this.methodsOrder.shift();
                  try{
                      this.first = this.methods[this.methodsOrder[0]];
                  }catch(ex){ return -1; }
              }    

              return this.remaining();
        };
             
        TaskQueue.prototype.putToBack = function(id){
           
           var _id, index = this.methodsOrder.indexOf(id);

           if(index == -1){
              return false;
           }

           _id = this.methodsOrder[index];
           this.setIndex(this.methodsOrder, _id, (this.methodsOrder.length - 1));
        };

        TaskQueue.prototype.bringToFront = function(id){
             var index = this.methodsOrder.indexOf(id);
           if(index == -1){
              return false;
           }
           var id = this.methodsOrder[index];
           this.setIndex(this.methodsOrder, id, 0);
        };
             
        TaskQueue.prototype.remaining = function(){

            return this.methodsOrder.length;
        };
             
        TaskQueue.prototype.reinit = function(){
            
            this.flushed = false;
          
            return true;
        };

        TaskQueue.prototype.flushInBackground = function(callback){ 
            
            var _self = this,
                promise =  $.Deferred();// new $cdvjs.Futures(); 
                promise.then(callback);   
                setTimeout(function(){
                    _self.syncFlush(function(){ 
                        promise.resolve.apply(promise, [].slice.call(arguments)); 
                    });
                }, 0);

            return promise;
        };

        TaskQueue.prototype.asyncFlush = function(callback){
          var _self = this,
              next = function(){
                  var id  = _self.methodsOrder.shift(),
                      method = _self.methods[id], 
                      args;

                  delete _self.methods[id];

                  _self.response = [].slice.call(arguments);
                  method["args"].unshift(arguments.callee);
                  method["task"].apply(_self, method["args"]); 
              };

              next(null);
        };

        /*
        function task(next){
         [c] is the response from the preceeding task 
          
        var args = [].slice.call(arguments, 1);

         var currentCalingContext = this.context;
         var precedingTaskResponse = this.response; 
         var v = Math.pow(4, 2); // [v] is the response from this task

         return next(v); 
        }
        */
             
        TaskQueue.prototype.flushWith = function(fnc){
             // fnc - callback to apply to each item in the queue
        };
             
        TaskQueue.prototype.syncFlush = function(callback){
           if(this.flushed) return; // the queue can only be flushed once until it is reinitialised!!
             var remaining = this.remaining(); 
             while(remaining){
                remaing = this.dequeue();
             }
             
             this.flushed = true; // the queue has now been flushed!!
            
             callback(this.flushed);
        };
         
      
        $cdvjs.Application.registerModule("commsunit", ["jQuery", "_", "emitter", "tools", "cookiestore"], function(box, $accessControl){
                   
                   var $ = box.jQuery,

                       _ = box._,

                       E = box.emitter,

                       T = box.tools,

                       C = box.cookiestore,

				               transportHooks = [
      						       "ajax",
      							     "eventsource",
      							     "websockets"
                       ],

                       store_callback = function(e){
                                    
                                    if((this === d && typeof d.documentMode == 'number' && eventIsNative(e))
                                            || (this === d && typeof w.tickstamp == 'undefined' && !eventIsNative(e))
                                                || (this === w && typeof d.documentMode != 'number' && eventIsNative(e))){
                                          if(e.key == 'MYNTI_APPLICANT_LOGIN'){
                                              var value;
                                              
                                              try{
                                                value = w.JSON.parse(e.newValue);
                                              }catch(e){
                                                value = {};
                                              }

                                              //value.tick = e.__timertick;
                              
                                              if(value.isLocked || $.isEmptyObject(value)){
                                                  if('removeEventListener' in this){
                                                      this.removeEventListener('storage', arguments.callee, false);
                                                  }
                                                  else if('detachEvent' in  this){
                                                      this.detachEvent('onstorage', arguments.callee);
                                                  }

                                                  /*E.emit('sweetalert', {
                                                      title:"Session Locked",
                                                      text:"Errm... You haven't been active for quite some time. So, we \r\n logged you off (you're not logged out yet). To log back on, \r\n simply",
                                                      type:"info",
                                                  }, function(){

                                                  });*/

                                                  return true;
                                              }

                                              if(value.email === ""){
                                                  E.emit('sweetalert', {
                                                      title:"Hello There",
                                                      text:"Hope you're enjoying your use of the MyNTI Platform ?",
                                                      type:"info",
                                                      timer:5600,
                                                      showCancelButton:false
                                                  });
                                              }
                              
                                              if(value.email == "applicants.no-reply@mynti.edu.ng" 
                                                  && value.status === "guest"){
                                                  E.emit('sweetalert', {
                                                      title:"Session Ended",
                                                      text:"You seem to have logged out on another tab/window, \r\n Kindly refresh this page to continue afresh.",
                                                      type:"error",
                                                      showConfirmButton:true,
                                                      confirmButtonText: "Refresh Now!",
                                                      showCancelButton:false
                                                  }, function(){
                                                        try{
                                                          w.location.href = '/';
                                                        }catch(er){  }
                                                  });
                                              }
                              
                                              if(value.email != "applicants.no-reply@mynti.edu.ng" 
                                                    && value.status === "user"){
                                                  E.emit('sweetalert', {
                                                      title:"Session Refreshed",
                                                      text:"You seem to have logged in on another tab/window, \r\n Kindly refresh this page to continue smoothly.",
                                                      type:"info",
                                                      timer:5900,
                                                      showConfirmButton:true,
                                                      confirmButtonText: "Refresh and Continue ...",
                                                      showCancelButton:false
                                                  }, function(){
                                                        try{
                                                          w.location.reload();
                                                        }catch(er){  }
                                                  });
                                              }
                                          }

                                    }

                                    if(w.tickstamp){
                                        w.tickstamp = undefined;
                                    }
                        
                                    console.log("===== cross-tab check =====");
                            },

                            watchSession = function(){
                                
                                /*
                                    jQuery can't handle this part because this isn't a regular 
                                    DOM event and it does have some quirks especially in IE8
                    
                                */
                    
                                console.log("session is being watched!!!!!");
                    
                                if(w.addEventListener){
                                    w.addEventListener('storage', store_callback, false);
                                    d.addEventListener('storage', store_callback, false);
                                }else if(d.attachEvent){
                                    /*
                                         IE 8 properly supports the 'storage' event for {`localStorage`}
                                         however, the listener MUST be on the document object
                                    */ 
                    
                                    d.attachEvent('storage', store_callback);
                                }
                            },

		                 transportLines = { 
						            ServerEVENTSOURCE:{ 
    						             sameorigin:true,
    						             callUp:function(opts){
    						                   
                                   this.prelude();

                                   return this.request.call(this, {

                                   });
    							           },
                             request:function(){

                             },
                             preude:function(){

                             }			
    						        },
                        ServerWEBSOCKETS:{
                            sameorigin:true,
                            callUp:function(opts){
                                
                                this.prelude();

                                return this.request.call(this, {

                                });
                            },
                            request:function(opts){

                            },
                            prelude:function(){

                            }
                        },
    						        ServerAJAX:{
			      	              sameorigin:true,
    	                      callUp:function(opts) {

                                  var IE_cross_origin = false;

                                  /*
                                      setup the ajax request for crossdomain
                                      CORS related actions (if any)
                                  */

                                  if(opts.crossDomain){
                                      this.sameorigin = false;

                                      /*
                                          For IE8/9, there is a special constrcutor
                                          [XDomainRequest] that should be used by
                                          jQuery for cross-domain requests
                                      */

                                      if('trident' in webpage.engine){
                                          if(body.is('.IE8')
                                              || body.is('.IE9')){
                                              IE_cross_origin = true;
                                          }
                                      }
                                  }
    	  	
                                  return this.request.call(this, {
                                          method: opts.method || "POST",
                                          IE_cross_origin:IE_cross_origin,
                                          data: opts.data,
                                          success:opts.callback || noop,
                                          progress:opts.progress || noop,
                                          cache:opts.cacheResponse || true,
                                          error:opts.error || noop,
                                          responseContentType:opts.responseContentType || null,
                                          requestContentType:opts.requestContentType || null,
                                          context:opts.contextObj || null,
                                          async: opts.async || true,
                                          atbegin: opts.beginCallback || noop,
                                          url: (w.location.origin || w.location.protocol + "//" + w.location.host + (location.port !== ""?":"+location.port:"")) + opts.url
                                  });
                            },
                            request:function(opts){

                                 	var interval = null, 
                                 	self = this,
                                 	num,
                                 	s,
                                  // setup $.ajax settings for request
                                  ajax_args = {
                                      url: opts.url, // target URL
                                      data:opts.data, // transport content
                                      cache:true, // cache items of the request...
                                      global:false, // do not trigger global ajax events...
                                      success:noop,
                                      error:noop,
                                      timeout:0, // don't allow timeouts...
                                      contentType:opts.responseContentType || 'application/json; charset=utf-8', // type of data to be recieved to the server (set on XMLHttpRequest "Accepts:" header key)
                                      context:opts.context, 
                                      type:opts.method, // http verb for request
                                      dataType: opts.requestContentType || 'text',  // type of data sent to the server
                                      async:opts.async
                                  };
                                      

                                  if(!this.sameorigin){
                                      if(opts.IE_cross_origin){
                                          ajax_args = $.extend(ajax_args, {
                                                xhr:function(){
                                                        var xdr = new XDomainRequest();

                                                            if('prototype' in xdr.constructor){
                                                                
                                                                xdr.constructor.prototype.setRequestHeader = function(header, value){

                                                                };

                                                                xdr.constructor.prototype.getResponseHeader = function(header){
                                                                    if(header === 'Content-Type'){
                                                                        return xdr.contentType;
                                                                    }
                                                                    if(header === 'Content-Length'){
                                                                        return xdr.responseText.length;
                                                                    }
                                                                };

                                                                xdr.constructor.prototype.getAllResponseHeaders = function(){

                                                                };

                                                                xdr.constructor.prototype.overrideMimeType = function(type){

                                                                };
                                                            }

                                                           xdr.open(opts.method, opts.url);
                                                           xdr.ontimeout = opts.ontimeout || noop;
                                                           xdr.onprogress = opts.progress || noop;

                                                           xdr.timeout = 300000;
                                                           
                                                           /*
                                                              if the error callback is called, 
                                                              user may be browsing in InPrivate/Incognito Mode
                                                              in IE 8/9 
                                                           */
                                                           xdr.onerror = opts.onerror || noop;
                                                           xdr.onload = function(data){
                                                                  
                                                                  var json, xml;

                                                                  try{
                                                                     json = $.parseJSON(xdr.responseText);
                                                                     /* if it's not JSON, then try something else */ 
                                                                     if(json === null|| typeof json  === "undefined"){
                                                                        json = $.parseJSON(data && data.firstChild && data.firstChild.textContent);
                                                                     }
                                                                  }catch(er){
                                                                      xml = $.parseXML(xdr.responseXML || xdr.responseText);
                                                                  } 

                                                                 if(opts.callback)
                                                                    opts.callback(json);
                                                           }
                                                            
                                                           return xdr;
                                                }
                                          });
                                      }else{
                                          ajax_args = $.extend(ajax_args, {
                                              xhrFields: {
                                                  withCredentials: true
                                              }
                                          });
                                      }   
                                  }
                                          
                                         
                                  if(opts.atbegin){
      			   
                                   	  ajax_args = $.extend(ajax_args, {
                                   	       beforeSend:function(xhr){
                                   	     	     typeof opts.atbegin === 'function' && opts.atbegin.call(opts, xhr);
                                   	       }	
                                   	  });
                                  }
                                   
                                  return $.ajax(ajax_args)
                                            .always(function(data, textStatus, xhr){

      						       
                                                  if (typeof data === "string") {
                                                       
                                                       data = T.json_parse(data);
                                                  
                                                  }

                                                  if (typeof data !== "string"
                                                      && ('responseText' in data)) {
                                                         xhr = data;
                                                         try{
                                                              data = T.json_parse(data.responseText);
                                                         }catch(e){
                                                              data = null;
                                                         }
                                                  }
      							   
                                                  self.cleanup(opts, data);
      							   
					    	                                  console.log(
                                                        "request returned with status text: ", 
                                                        ('statusText' in xhr) && xhr.statusText,
                                                        "request returned with status code: ",
                                                        ('statusCode' in xhr && typeof xhr.statusCode == 'function') && xhr.statusCode(noop)
                                                  ); 
                                                  
                                                  // return data;
                                            })
                                            .done(function(data, textStatus, xhr) {
      						          
                                                   console.log("Succeded - All done!!");
                                                      
                                            })
                                            .fail(function(xhr, textStatus) {
                                             	  
                                                  console.log("Failed for some reason!!");
                                                
                                            });
                             },
                             cleanup:function(o, data){
      
                                  // C.get_cookie("__served_up");
                             }
                     }
				          };
                   

                    /* 
                        Enable Laravel 5.x to recieve ajax requests in peace ;)

                        avoid [TokenMismatchException] for CSRF protection
                    */

                    $.ajaxSetup({
                          headers:{
                              "X-CSRF-TOKEN": ($('meta[name="_token"]').attr('content'))
                          }
                    });

                    !function(){

                          'use strict';
        
                          var _itemSet, 
                              _itemRemove,
                              _itemClear, 
                              _Storage = w.Storage, 
                              hasCustomEvent = (typeof CustomEvent == 'function');
                      
                      
                              /* 
                                  Most browsers won't trigger storage events using just native implementation, 
                                  so we need to partially shim them to make them work albeit this is actually
                                  some sort of hack!
                                  
                                  ;)
                              */
                          if(typeof _Storage === 'function'
                              && ((w.onstorage === null || w.onstorage === undefined) 
                                   && (typeof d.documentMode == 'number' || typeof StorageEvent == 'function'))){
                      
                              _itemSet = _Storage.prototype.setItem;
                              _itemRemove = _Storage.prototype.removeItem;
                              _itemClear = _Storage.prototype.clear;
                      
                              var __method = function(_old, funcName){

                                      return  function (key/*, value*/){
                                            
                                            var result, 
                                              eventDetails = {url:w.location.href,storageArea:this,source:w,empty:true,__timertick:String(Math.random() * (new Date).getTime()).replace('.','')}, 
                                              errorOccured = false,
                                              errorToBeThrown = null,
                                              args = [].slice.call(arguments);
                                            try{
                                              
                                              eventDetails.oldValue = this[key];
                                              w.tickstamp = eventDetails.__timertick;
                                              result = _old.apply(this, args);
                                              
                                            }catch(e){
                                              errorOccured = true;
                                              errorToBeThrown = e;
                                            }finally{
                                              if(errorOccured === true){
                                                throw errorToBeThrown;
                                                return !errorOccured;
                                              }

                                              eventDetails.key = key;
                                              eventDetails.newValue = String(args.slice(1));
                                              
                                              if(eventDetails.storageArea === w.localStorage){
                                                  if(funcName === "set"){
                                                      if(eventDetails.oldValue !== eventDetails.newValue){
                                                          C.set_cookie("MYNTI_APPLICANT_USER_UPDATE_COUNTER", String((parseInt(C.get_cookie("MYNTI_APPLICANT_USER_UPDATE_COUNTER") || "-1") + 1) || "1"));
                                                      }
                                                  }else if(funcName === "clear"
                                                      || funcName === "remove"){
                                                        C.unset_cookie("MYNTI_APPLICANT_USER_UPDATE_COUNTER");
                                                  }

                                                  setTimeout(function(){
                                                     T.trigger_event('storage', d, eventDetails, w);
                                                  }, 1);
                                              }
                                              
                                              return result;
                                            }
                                          }
                                      };    
                                  

                                      _Storage.prototype.setItem = __method(_itemSet, "set");
                                      _Storage.prototype.removeItem = __method(_itemRemove, "remove");
                                      _Storage.prototype.clear = __method(_itemClear, "clear");

                                      _Storage.prototype.hasKeyValue = function(key){
                                        return (this[key] != void 0);
                                      }

                              };  

                    }();
                   
                   
                    return {

                          init:function(promise){
						                    
                                 var t = 0, hook, old_update_cookie = parseInt(C.get_cookie("MYNTI_APPLICANT_USER_UPDATE_COUNTER") || "0");

                                 for(; t < transportHooks.length; t++){
                                    hook = transportHooks[t];
                                    connect(transportLines["Server"+hook.toUpperCase()], hook);
                                 }

                        				  promise.then(function(){
                        						 $(d).on('mousemove', $.debounce(760, function(){

                        						      /*

                              							IE 9+/Edge don't support {`localstorage`} events across tabs
                              							so we need to provide a fallback to cookie polling or
                                            repeated checking of a given cookie value         

                        						      */
                        						      var _lstore = w.localStorage["MYNTI_APPLICANT_LOGIN"] || "{}",
                        							        newer_update_cookie = parseInt(C.get_cookie("MYNTI_APPLICANT_USER_UPDATE_COUNTER") || "-1");

                        						      if(w.webpage.engine.edgehtml 
                                							|| (w.webpage.engine.trident && d.documentMode >= 9)){
                                							    if((newer_update_cookie > old_update_cookie)
                                                        || (newer_update_cookie === -1)){
                                								      old_update_cookie = newer_update_cookie;
                                                      T.trigger_event('storage', d, {newValue:_lstore,url:w.location.href,key:"MYNTI_APPLICANT_LOGIN"}, w);
                                							    }
                        						      }


                        						 }));
                        				  });

                                 $(d).on('sessionready', watchSession);

                          },
                          defineVars:function(){

                                connect = function(target, hookName){

                                    E.on((hookName+"request"), asyncHandler); 

                                    function asyncHandler(c){
                                         
                                        return target.callUp.call(target, c);
                                    }                 

                                };

                                disconnect = function(which){

                                    E.off("request", which);

                                };

                                body = $(d.body);

                          },
                          stop:function(){
                                
                                disconnect(null);
				  
                        				$(d).off('mousemove');
                        				  
                        				$(d).off('sessionready');

                          },
                          destroy:function(){

                                transportLines.ServerAJAX = null;
                        			 	transportLines.ServerEVENTSOURCE = null;
                        			 	transportLines.ServerWEBSOCKETS = null;

                                connect = null;


                                body = null;

                          }
                    
                    };
      });   

}(this, this.document));
