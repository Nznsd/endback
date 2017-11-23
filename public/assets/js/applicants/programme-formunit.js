/*!
 * @project: <MyNTI>
 * @file: <programme-formunit.js>
 * @author: <https://www.omniswift.com>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module that handles form interaction/submission for the course choice and study settings side of the app}
 * @remarks: {module script}
 */

;(function(w, d){

	$cdvjs.Application.registerModule("formunit", ["jQuery", "emitter", "tools"], function(box){

		var $ = box.jQuery,

			E = box.emitter,

			T = box.tools,

			select_boxes, 

			fees,

			body,
		    
		    programmeId,

			continue_button,

			courses_form,

			former_value,

		   /**
			*
			*
			*
			* @params payload - {Object}
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
							
			};

		return {

			init:function(){

				/*

					Initialize Popovers
				*/

				$('[data-toggle="popover"]').popover();
			

				/*
					Clear out the contents of the form 
					just in case..
				*/

				courses_form.trigger('reset');

				if(body.parent().is('.js')){

					courses_form.attr('novalidate', '');
				}

				body.on('click', continue_button.selector.replace('[disabled]', ''), function(event){

					if($(this).is('.continue')
						&& !$(this).is("[disabled]")){
						
							/*
								pass the {Laravel} CSRF token from the meta tag
								to the submission form to avoid the dreaded
								:: [TokenMismatchException]
							*/

							

							$('<input/>')
							.attr({type:'hidden', 'name':'_token'})
								.val($("html").data('token'))
									.appendTo(courses_form);
							
							continue_button.addClass('spinner-active');

							setTimeout(function(){
								
								courses_form.trigger('submit');

							},0);

							E.emit('sweetalert', {
								title:"Thank you!",
								text:"Please, be advised to pay your fees through the bank or via REMITA \r\n\t\t Thank You.",
								type:"info",
								timer:2500,
								showCancelButton:false
							});
						
						
						
					}

				}); 

				courses_form.on('change', select_boxes.selector, function(event){

					var form_data = (courses_form.data("fill-list") || '[]'),
						filllist = JSON.parse(form_data),
						fillprogress = filllist.length,
						filltotal = courses_form.find('select').length,
						percent = 0,
						input_value = $(event.target).val(),
						cascade_select = null,
						offset = filllist.indexOf(event.target.name);


					switch(event.target.name){

						case "programme": // .form-check-control
							if(input_value !== "-"){
								if(!(offset + 1)){
									filllist.push(event.target.name);
								}

								if(typeof former_value === 'string'){
									$(event.target).parents('.form-group').first().next('.form-group.additional').remove();
								}

								if(input_value === '5'){
									former_value = input_value;
									$(event.target).parents('.form-group')
											.first()
												.after($(body.data('slinput1-html')));
								}

								if(input_value === '1'){
									former_value = input_value;
									$(event.target).parents('.form-group')
											.first()
												.after($(body.data('slinput2-html')));
								}

								cascade_select = $($(event.target).data("casacade-select-target"));

								if(cascade_select.length 
									&& cascade_select.is('[data-select-loaded]')){
										
										cascade_select.eq(0).data('placeholder-text', cascade_select.eq(0).find("option:eq(0)").text())
												
										cascade_select.find("option:eq(0)").text("LOADING...");
		
										loadDropdown("/services/specializations/"+input_value, cascade_select)
										.then(function(){
											cascade_select.find("option:eq(0)").text(cascade_select.eq(0).data('placeholder-text'));
											cascade_select.eq(0).data('placeholder-text', null);
											cascade_select = null;
										});
									
								}
								
								
								programmeId = input_value;

							}else{
								if((offset + 1)){
									filllist.splice(offset, 1);
								}

								if(former_value === 'bed' 
									|| former_value === 'nce'){
									
									$(event.target).parents('.form-group')
											.first()
												.next('.form-group.additional')
													.remove();
								}
							}
							
						break;
						case "first_choice":
						case "second_choice":
						case "residence":
						case "study_center":

							if(input_value !== "-"){
								if(!(offset + 1)){
									filllist.push(event.target.name);
								}
							}else{
								if((offset + 1)){
									filllist.splice(offset, 1);
								}
							}

							cascade_select = $($(event.target).data("casacade-select-target"));

							if(event.target.name === "residence" 
								&& cascade_select.length 
									&& cascade_select.is('[data-select-loaded]')){
										cascade_select.data('placeholder-text', cascade_select.find("option:eq(0)").text())
										.find("option:eq(0)").text("LOADING...")

									loadDropdown("/services/centers/"+input_value+"/"+programmeId, cascade_select)
									.then(function(){
										cascade_select.find("option:eq(0)").text(cascade_select.data('placeholder-text'));
										cascade_select.data('placeholder-text', null);
										cascade_select = null;
									});
							}
							
							
						break;
						
						default:
							/* deal with dynamically generated input DOM element here */

							if(event.target.name === "p_entry_level"
								 || event.target.name === "p_entry_year"){
								if(input_value !== "-"){
									if(!(offset + 1)){
										filllist.push(event.target.name);
									}
								}else{
									if((offset + 1)){
										filllist.splice(offset, 1);
									}
								}
							}

						break;
					}

					courses_form.data("fill-list", JSON.stringify(filllist));
					fillprogress = filllist.length;
					percent = Math.round((fillprogress/filltotal) * 100);


					if(percent === 100){
						
						
						var form_fields = courses_form.serialize()
						.replace("&application-fees=", "")
						     .replace("&send=", "");

						form_fields = T.query_to_json(form_fields, true);

						/*
							Trigger the popover (Bootstrap) on the fees
							input
						*/

				
						fees.focusin();

						asyncServerRequest({
							url:'/services/fee/2/'+programmeId+"/"+form_fields.first_choice,
							method:'GET',
							requestContentType:'json',
							data:null
						}).done(function(data){

							fees.popover('destroy');
							fees.val(data.amount);

							$('<input/>')
							.attr({type:'hidden', 'name':'fee_id'})
								.val(data.id)
									.appendTo(courses_form);

							continue_button.removeAttr('disabled').addClass('shake-effect');
						})
						.fail(function(){

							E.emit('sweetalert', {
								title:"Ooops!",
								text:"So sorry, something unexpected happened and we cannot calculate your fees \r\n\t\t Please, contact <a href='mailto:/'>support</a>",
								info:"error",
								html:true,
								timer:4500,
								showCancelButton:false
							});

						});
					}

				});
				
				

			},
			defineVars:function(){

				select_boxes = $('.form-control');

				courses_form = $('.mynti-courses-form');

				fees = $('[name="application-fees"]');

				body = $(d.body);

				continue_button = $('.mynti-button-calm[disabled]');

			},
			stop:function(){

				body.off('click');

				courses_form.data("fill-list", null);

				courses_form.off('change');

			},
			destroy:function(){

				$ = null;
				E = null;
				T = null;
				
				select_boxes = null;

				body = null;
				fees = null;
				continue_button = null;

				programmeId = null;
			}
		}

	});

}(this, this.document));