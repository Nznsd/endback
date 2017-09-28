/*!
 * @project: <MyNTI>
 * @file: <verify-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https://www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form interacion/submission for the verify RRR side of the app}
 * @remarks: {module script}
 */


;(function(w, d){

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools"], function(box){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			verify_button,

			verify_text,

			receipt_button,
		    
		    receipt_iframe,

			details_form,
		    
		    continue_button,
		    
		    alert_box,

			body,
		    
		    	/**
			 *
			 *
			 *
			 *
			 * @param e - {Object}
			 * @return - {Undefined}
			 */
		    
		    	printReceipt = function(e){

				var iframe_win = e.target || e.srcElement;


				iframe_win.contentWindow.print();

				continue_button.removeAttr('disabled').addClass('shake-effect');

			},
		    
		    	/**
			 *
			 *
			 *
			 *
			 * @param e - {Event Object}
			 * @return - {Undefined}
			 */
		    
		    	 downloadPDFReceipt = function(e){
        			var iframe_win = e.target || e.srcElement,
				    errorMessageBlock = {
						title:"PDF Not Generated",
						text:"The PDF version cannot be generated now for some reason",
						type:"error",
						showCancelButton:false
					}, hasError = false;
				
				 if(!('html2pdf' in w)){
					 
					 iframe_win.style.cssText = "";
					 return E.emit('sweetalert',  errorMessageBlock); 
				
				 }
				 
				/*
					Since, we want the PDF document to be 
					in A4 paper size (8.3inches in width)
					(11.7inches in height)
					1 inch = 96px; So, 8.3inches = 796.8px
					1 inch = 96px; So, 11.7inches = 1113.6px

					We approximate for the body to allow
					for some padding influence
				 */

				iframe_win.contentWindow.document.body.style.cssText =  "width:790px !important;";
				/*
				  Detect when the iframe loads with the receipt html
				  from the Laravel PHP Server and convert to PDF
				 */
				 try{
					w.html2pdf(iframe_win.contentWindow.document.body, {
						  margin:       1,
						  filename:     'mynti-receipt.pdf',
						  enableLinks: false,
						  image:        { type: 'jpeg', quality: 0.98 },
						  html2canvas:  { dpi: 192, letterRendering: true, width:Math.round(8.3 * 72), height:Math.round(11.7 * 72), useCORS:true, proxy:w.location.origin },
						  jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
					});
				 }catch(e){

					 E.emit('sweetalert', errorMessageBlock);
					 // console.log("Error Occured while trying to genetrate PDF", e);
					 hasError = true;
				 }
				 
				 
				 setTimeout(function(){
				 		 iframe_win.style.cssText = "";
				 		 iframe_win.contentWindow.document.body.style.cssText = "";
				 }, 0);
				 
				 if(!hasError){
					continue_button.removeAttr('disabled').addClass('shake-effect');
				 }
			},

		   	/**
			 *
			 *
			 *
			 * @param payload - {Object}
			 * @return - {Object}
			 */

           		asyncServerRequest = function(payload){
            	 
				 /* 
					send a mediator message to the 
					'app-commsunit' module to initiate 
					an AJAX request 
				 */

				var context;

					context = (E.emit("ajaxrequest", payload))["ajaxrequest"][0];

				return context;
		       };

		return {

			init:function(){

					body.on('click', verify_button.selector.replace('[unselectable]', ''), function(event){

							var $that = $(this); 
						
							if($that.is('.continue')){

								 if(continue_button.is("[disabled]")){

									return false;
								 }

						
								 $that.addClass('spinner-active');
							}

							if($that.is('.mynti-verify-link')){
								
								$that.addClass('spinner-active');
								
								asyncServerRequest({
									url:'/applicants/verify',
									method:'POST',
									requestContentType:'json',
									data:'{"rrr":"'+ verify_text.val() +'"}',
								}).done(function(data, textStatus, xhr) {
						          
											verify_text.val("");  

											verify_button.removeClass('spinner-active')
											.attr('unselectable', '');

											
		    									alert_box
												.text(data.status+": "+data.message)
													.removeClass('hidden')
													.removeClass('bg-danger')  // line edited by Onuh
														.addClass('bg-success');

											details_form.removeClass('hidden')
											.find('input')
											.map(function(index, node){
				
												if(data[node.name] != void 0){
													if(node.name == "amount"){
														node.value = "₦"+data[node.name];
														return;
													}
													node.value = data[node.name];
												}

											});
									setTimeout(function(){
										continue_button.removeAttr('disabled').addClass('shake-effect');
									}, 0);
                                                
                                })
                              	.fail(function(xhr, textStatus) {
                               	  
                                    	verify_text.val("");  
							
							verify_button.removeClass('spinner-active')
							.attr('unselectable', '');

							var data  = $.parseJSON(xhr.responseText);

							alert_box
							.text(data.status+": "+data.message)
							.removeClass('hidden')
							.removeClass('bg-success') // line edited by Onuh
								.addClass('bg-danger');
							
							if(data.status !== '404') {  // line edited by Onuh
								if(details_form.is('.hidden')){
									details_form.removeClass('hidden')
											.find('input')
											.map(function(index, node){
												if(data[node.name] != void 0){
													if(node.name == "amount"){
														node.value = "₦"+data[node.name];
														return;
													}
													node.value = data[node.name];
												}
	
											})
								}  // line edited by Onuh
								continue_button.attr('disabled', 'disabled'); // line edited by Onuh
							}

										});

							
							}else if($that.is(receipt_button.selector)){
									
									E.emit('sweetalert',
									{
										title:"Receipt Checkout",
										text:"How would you like to retrieve your receipt ?",
										type:"info",
										showConfirmButton:true,
										showCancelButton: true,
										confirmButtonText: "Print My Receipt",
										cancelButtonText: "Download & Save My Receipt"
									},

									function(){

										/*

											if we get here, it means the user wants to print
											his/her receipt. so, initial HTTP request using
											[hidden iframe] and make receipt visible to user
											for viewing
										*/

										/*E.emit('sweetalert',
										{
											title:"Please Wait...",
											text:"Preparing to print",
											type:"info",
											timer:3000,
											showCancelButton:false
										});*/

										if(typeof receipt_iframe.attachEvent === 'function'){

											receipt_iframe.attachEvent('onload', printReceipt);

										}else if(typeof receipt_iframe.addEventListener === 'function'){

											receipt_iframe.addEventListener('load', printReceipt);
										}

										var endpoint, _origin = window.location.origin; // added by onuh

										if(/tuition/.test(continue_button.attr('href'))){ 

										     endpoint = '/applicants/receipt/tuition';

										}else{

											endpoint = '/applicants/receipt';  // added by onuh

										}

										receipt_iframe.src = (_origin + endpoint);



									}, function(){ 

										/*
											if we get here, it means the user wants to just
											download and save his/her receipt. so, use 
											[hidden iframe] to get receipt in pdf
											[using JSPDF]
										*/
										
										 receipt_iframe.style.cssText = "display: inline-block; position: absolute; left: -9999px; width:797px !important; height: 100%; top: 0;";
										      
										 if(typeof receipt_iframe.attachEvent === 'function'){

											receipt_iframe.attachEvent('onload', downloadPDFReceipt);

										 }else if(typeof receipt_iframe.addEventListener === 'function'){

											receipt_iframe.addEventListener('load', downloadPDFReceipt);
										 }

										 var endpoint, _origin = window.location.origin;  // added by onuh

										 if(/tuition/.test(continue_button.attr('href'))) {

											endpoint = '/applicants/receipt/download/tuition';

										 }else{

										 	endpoint = '/applicants/receipt/download';  // added by onuh
										 }

										 receipt_iframe.src = (_origin + endpoint);

									});
							}

					});

					verify_text.on('blur', function(event){

						if(event.target.value.length > 0){

							verify_text.trigger('change');
						}
					});

					verify_text.on("input", function(event){

						if(event.target.value.length >= 12){
							
							verify_text.trigger('change');
						}
					});

					verify_text.on('change', function(event){

						if(!(/^(?:[\d]{12,})$/.test(event.target.value))){

							/* 
								fix selecting text in input box in Safari 3- 
							*/

							setTimeout(function(){

								event.target.select();

							}, 0);

							if(!verify_text.is('.input-error')){

								verify_text.addClass('input-error');
							}

							E.emit('sweetalert', {
								title:"Invalid RRR",
								text:"This is not a correct RRR for your payment. \r\n\t Please, try again",
								type:"error",
								timer: 5400,
							  	showConfirmButton: false
							});

							return false;
						}

						if(event.target.value.length > 0){

							if(verify_text.is('.input-error')){

								verify_text.removeClass('input-error');
							}
							
							verify_button.removeAttr('unselectable');

						}else{

							if(!verify_button.attr('unselectable')){

								verify_button.attr('unselectable', '');
							}
						}
					});

			},
			defineVars:function(){

				body = $(d.body);

				receipt_button = $('.save-and-print');
				
				receipt_iframe = $('iframe[name="__receipt"]').get(0);
				
				continue_button = $('.continue');

				details_form = $('.mynti-rrr-verify-form');

				verify_button = $('.mynti-button-calm[unselectable]');

				verify_text = $('.mynti-verify-text > input');
				
				alert_box = $('.mynti-alert');

			},
			stop:function(){

				body.off('click');

				verify_text.off('change');
			},
			destroy:function(){
				
				E = null;
				T = null;

				body = null;

				details_form = null;
				verify_button = null;
				continue_button = null;
				verify_text = null;
				alert_box = null;

				receipt_button = null;
				receipt_iframe = null;

			}
		}

	});

}(this, this.document));
