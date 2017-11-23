/*!
 * @project: <MyNTI>
 * @file: <app-bootstrap.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https;//www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {application start up module }
 * @remarks: {startup script for the NTIv2 app}
 */
    

;(function(w, d, undefined){

	
	jQuery("#avoidFOUC").remove();

  
   /*! 
    * define start-up routines/vars
	*/

 var  beforeUnloadTimer = null,
 
      formerUnloadCb = null,
     
     __cancelStorage = false,

      __hasDeactivated = false,
      
      unloadCb = function(){

      		if(!__hasDeactivated){

      			w.$cdvjs && w.$cdvjs.Application.deactivateAllModules(null);

      			jQuery(d.documentElement).data('token', null);
      		}
      	
      	     console.log("unloading page....");

	      if(__cancelStorage){
		      
		      	alert("You are logging out!!!!");
      	     		w.localStorage.removeItem("MYNTI_APPLICANT_LOGIN");
	      }
      	   
      	     if(typeof formerUnloadCb === 'function'){
      	     	  formerUnloadCb();
      	     }

      	     delay(w._delayUnloadUntil);
      },

      delay = function (timeout) {
         
         var now, until = (typeof timeout === 'number' 
         					&& timeout !== NaN) 
         						? new Date().getTime() + timeout
         						: 0;

         // lock browser until delay is met
         if (until) {
                do {
                     now = new Date();
                } while (now.getTime() < until);
         }

         return true;
     },
   
	 // detect if browser is IE 8 or less 
     isIE8AndLess =  function(){
         // use regex testing just to be safe, since Chrome/Safari also implements {window.clientInformation}!!
         return /*@cc_on!@*/false && (d.documentMode && d.documentMode <= 8 || w.clientInformation.appVersion.match(/\s*MSIE\s*6/)); 
     };
		

     jQuery.ajaxTransport(function(options, originalOptions, jqXHR) {
	       		var token;
	           if (!options.crossDomain) {
	                token = jQuery('meta[name="_token"]').attr('content');
	                if (token) {
	                    return jqXHR.setRequestHeader('X-Token', token);
	                }
	           }
       });

	   // EASING EQUATION
       // Credits: Robert Penners easing equations (http://www.robertpenner.com/easing/).
       jQuery.easing['BounceEaseOut'] = function(p, t, b, c, d) {
                  	if ((t/=d) < (1/2.75)) {
                        	return c*(7.5625*t*t) + b;
                    } else if (t < (2/2.75)) {
                        	return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
                    } else if (t < (2.5/2.75)) {
                        	return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
                    } else {
                        	return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
                    }
       };
	
       jQuery(w).on("beforeunload", function(e){
										
	       // push to event UI queue to ensure execution!!
	       // as beforeunload dialog blocks the JS thread so the timeout
	       // callback will not fire until the user has clicked
	       // either button "Leave this page" OR "Stay on Page"...
		   
	       console.log('beforeunload ==> e.g. this very console-log will never execute unless a beforeunload dialog is shown');

	       // see: https://greatgonzo.net/i-know-what-you-did-on-beforeunload/

	        var lastActivatedNode = (e.originalEvent.explicitOriginalTarget // old/new Firefox
						|| (e.originalEvent.srcDocument && e.originalEvent.srcDocument.activeElement) // old Chrome/Safari
							|| (e.originalEvent.currentTarget && e.originalEvent.currentTarget.document.activeElement) // Sarafi/Chrome/Opera/IE
									|| w.currentFocusElement // Mobile Browsers
										|| w.previousFocusElement), // Mobile Browsers
	       	 	leaveMessage = "Are you sure you want to logout and/or leave ?",
	       	 	isLogout = ((lastActivatedNode.className == "dropdown-item") && ('href' in lastActivatedNode) && (lastActivatedNode.href.indexOf('/applicants/logout') === 0)),
	       	 	isDownload = ((lastActivatedNode.className == "download-trigger") && ('download' in lastActivatedNode));
	       
	       __cancelStorage = ((lastActivatedNode.className == "dropdown-item") && ('href' in lastActivatedNode) && (lastActivatedNode.href.indexOf('/applicants/logout') === 0));

	       beforeUnloadTimer = setTimeout(function(){
	       			
      	     		w.$cdvjs && w.$cdvjs.Application.deactivateAllModules(null);
                    
                    	console.log('setimeout before unload task...');                    

		           	__hasDeactivated = true;

	       }, 0);  

	       if((isLogout || isDownload)){ 
	       		e.originalEvent.returnValue = leaveMessage; // IE/Firefox/Chrome 34+
	       }
	       
	       return ((isLogout || isDownload))?  clearTimeout(beforeUnloadTimer) || jQuery(d).trigger('requestunloadswitch') && leaveMessage : undefined; /* no beforeunload dialog */
	       
   });
	
	// IE 6, 7 have a way to latch on firmly to the event via a special script tag
	// in the <head>... In IE 8, setting 'unload' on the document object ensures execution...
	// Again, if a dialog appears from 'beforeonunload' event and its 'cancel' button is
	// clicked, then the 'unload' event never fires!!!
	
	formerUnloadCb = w.onunload;
	
	if(isIE8AndLess()){
		
	        var id  = d.documentMode;
	   
	        if(id && id === 8){
	        	
	              d.attachEvent('onunload', function(){
	              	   formerUnloadCb = undefined; // IE 8 doesn't need this anymore...
	              	   unloadCb();
	              });
	              
	        }else{
	   	
	   	  
	            	$("head").append("<script for='window' event='onunload' type='text/javascript'>window.$cdvjs && window.$cdvjs.Application.deactivateAllModules(!!document.alive? document.alive : null);</script>");
                 
                	unloadCb = undefined; // IE 6 / 7 doesn't need this anymore...
	        	 
	        }
	    
	}else{
		
     	         w.onunload  = unloadCb;
    }


jQuery.fn.extend({
    fullyReady:function(fn){
     return this.each(function(){
       if(this !== d){ return; }
       if(d.readyState == "complete"){
              setTimeout(fn, 10);
       }else if(d.readyState == "interactive"
                 || d.readyState == "loading"){
             d.onreadystatechange = function(){
                    if(d.readyState == "complete"){
                         setTimeout(fn, 10);
                    }
             }
       }else{
              if(typeof d.documentMode != 'number' 
                 && ('addEventListener' in d)){
                   d.addEventListener("DOMContentLoaded", fn);
              }
       }
    });
    }
 });

jQuery(d).fullyReady(function(){

	var promise = new $.Deferred();

	setTimeout(function(){


				w.$cdvjs.Application.activateAllModules({
					formunit:promise,
					mainunit:promise,
					commsunit:promise
				}, function(){
					
					
				/*
					Start watching the session
				*/

				jQuery(d).trigger("sessionready");
					
				/*
					Extract and cache the Laravel PHP CSRF
					token fom th DOM.
					
					As well as the status of the MYNTI
					applicant (`logged-in` or `logged-out`)
				*/

				var _token = jQuery('meta[name="_token"]')
								.remove()
									.attr('content');
					
				var _status = jQuery('meta[name="_status"]')
								.remove()
									.attr('content');

				var _email = jQuery('meta[name="_email"]')
								.remove()
									.attr('content');
					
					jQuery(d.documentElement).data('token', _token);
					jQuery(d.documentElement).data('status', (_status || 'guest'));

				var _ihtml = jQuery(d.scripts["iframe_document"])
								.remove()
									.html();
				if(_ihtml){
					jQuery(d.body).data('iframe-html', _ihtml);
				}

				var _slinput1 = jQuery(d.scripts["skip_logic_course_input_bed"])
								.remove()
									.html();
				if(_slinput1){
					jQuery(d.body).data('slinput1-html', _slinput1);
				}

				var _slinput2 = jQuery(d.scripts["skip_logic_course_input_nce"])
								.remove()
									.html();
				if(_slinput2){
					jQuery(d.body).data('slinput2-html', _slinput2);
				}

				var _uform = jQuery(d.scripts["upload_progress_form"])
								.remove()
									.html();
				if(_uform){
					jQuery(d.body).data('uform-html', _uform);
				}

				var _rform = jQuery(d.scripts["cert_results_form"])
								.remove()
									.html();
				if(_rform){
					jQuery(d.body).data('rform-html', _rform);
				}
					
				var futuresDelayId, storageDelta = '{"email"\
							:"'+_email+'"\
							,"isLocked"\
							:false\
							,"status"\
							:"'+jQuery(d.documentElement).data('status')+'"}';
				
				/*
					Setup the parameters for cross-tab 
					communication via `storage events`
					for when user in logged in on one
					tab and all other tab(s) are notified

					LocalStorage is cleared only when
					user logs out 
				*/

				w.localStorage.setItem('MYNTI_APPLICANT_LOGIN', storageDelta);
					
				/*
					Wait for the `webpage` object to be available
					on the window
				*/
				
				futuresDelayId = setTimeout(function(){
					if(w.webpage){
						promise.resolve(true);
						return clearTimeout(futuresDelayId);
					}
					
					futuresDelayId = setTimeout(arguments.callee, 20);
				}, 500);
			});        
	}, 900);
	
	// signal that it's all done!!
	console.log("application startup complete!!");

});


}(this, this.document));
