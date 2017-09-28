/*!
 * @project: <MyNTI>
 * @file: <payments-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https;//www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form interacion/submission for the payments side of the app}
 * @remarks: {module script}
 */


;(function(w, d){

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter"], function(box){

		var $ = box.jQuery,

			E = box.emitter,

			print_button,

			form,

			continue_button,

			invoice_iframe,

			body,
		    
		    	/**
			 *
			 *
			 *
			 *
			 * @param e - {Event Object}
			 * @return - {Undefined}
			 */
		    
		    	 downloadPDFInvoice = function(e){
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
					in A4 paper size (8.3inches in width),
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
						  filename:     'mynti-invoice.pdf',
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
			 *
			 * @params e - {Object}
			 * @return - {Undefined}
			 */

			 printInvoice = function(e){

				var iframe_win = e.target || e.srcElement;


				iframe_win.contentWindow.print();

				continue_button.removeAttr('disabled').addClass('shake-effect');

			};


		return {

			init:function(){

				setTimeout(function(){

					print_button.addClass('shake-effect');

				}, 2500);

				if(body.parent().is(".js")){

					form.attr('novalidate', '');
				}

				body.on('click', continue_button.selector.replace('[disabled]', ''), function(event){


					if($(this).is('.save-and-print')){
						
						E.emit('sweetalert',
							{
								title:"Invoice Checkout",
								text:"How would you like to retrieve your invoice ?",
								type:"info",
								showConfirmButton:true,
								showCancelButton: true,
							  	confirmButtonText: "Print My Invoice",
							  	cancelButtonText: "Download & Save My Invoice"
							},

							function(){

								/*
	
									if we get here, it means the user wants to print
									his/her invoice. so, initial HTTP request using
									[hidden iframe] and make invoice visible to user
									for viewing

								*/

								E.emit('sweetalert',
								{
									title:"Please Wait...",
									text:"Preparing to print",
									type:"info",
									timer:3000,
									showCancelButton:false
								});

								if(typeof invoice_iframe.attachEvent === 'function'){

									invoice_iframe.attachEvent('onload', printInvoice);

								}else if(typeof invoice_iframe.addEventListener === 'function'){

									invoice_iframe.addEventListener('load', printInvoice);
								}

								var endpoint, _origin = window.location.origin; // added by Onuh

								if(/tuition/.test(verify_button.attr('href'))) {

								     endpoint = '/applicants/invoice/tuition';

								} else {

									endpoint = '/applicants/invoice/';  // added by Onuh
								}
								
								invoice_iframe.src = (_origin + endpoint);
								

							}, function(){ 

								/*

									if we get here, it means the user wants to just
									download and save his/her invoice. so, use 
									[hidden iframe] to get invoice in pdf

									[using JSPDF]
								*/
							
								  invoice_iframe.style.cssText = "display: inline-block; position: absolute; left: -9999px; width:797px !important; height: 100%; top: 0;";
						
								 if(typeof invoice_iframe.attachEvent === 'function'){

									invoice_iframe.attachEvent('onload', downloadPDFInvoice);

								}else if(typeof invoice_iframe.addEventListener === 'function'){

									invoice_iframe.addEventListener('load', downloadPDFInvoice);
								}

								var endpoint, _origin = window.location.origin; // added by Onuh

								if(/tuition/.test(verify_button.attr('href'))) {

								     endpoint = '/applicants/invoice/download/tuition';

								}else {

									endpoint = '/applicants/invoice/download';  // added by Onuh
								}
								
								invoice_iframe.src = (_origin + endpoint);

							});
					}

					if($(this).is('.continue')){

						 if(continue_button.is("[disabled]")){

						 		return false;
						 }

						form.trigger('submit');
						
						 $(this).addClass('spinner-active');
					}

				});

			},
			defineVars:function(){

				form = $('.mynti-payments-form');

				continue_button = $('.mynti-button-calm[disabled]');

				verify_button = $('.verify');  // added by Onuh

				print_button = $('.mynti-button-calm.save-and-print');

				invoice_iframe = $('iframe[name="__invoice"]').get(0);

				body = $(d.body);

			},
			stop:function(){

				body.off('click');
			},
			destroy:function(){
				
				E = null;

				$ = null;

				form = null;

				print_button = null;

				continue_button = null;

				invoice_iframe = null;

				body = null;
			}
		}

	});

}(this, this.document));
