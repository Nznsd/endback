/*!
 * @project: <MyNTI>
 * @file: <dashboard-formunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https;//www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module for applicants dashboard section the app}
 * @remarks: {module script}
 */
 
(function(w, d, undefined){



		$cdvjs.Application.registerModule("mainunit", ["jQuery", "emitter", "utils"], function(box){

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
			           		@var - {}

			           	*/

			           body,
			           	

			           	/* 
			           		@var - {}

			           	*/

			           carouselbox,

			           	/*

							@var - {}

			           	*/

			           	carouselitem,

			           	/*

							@var - {}

			           	*/

			           	leftpin,


			           	/*
			           		@var - {} 

			           	*/

			           	rightpin;



					  	return {
						       init:function(){

						       		/* 
						       			hide the arrows if the screen stretches enough 
										and the contents of the carousel items fit
										into the carousel box/panel completely
									*/

						       		if((carouselbox.outerWidth() + 35) >= carouselbox.get(0).scrollWidth){

										 leftpin.hide();
										 rightpin.hide();
									}

									/*
										check the carousel pins
										for clicks and respond
										accordingly
									*/ 

						       		body.on('click', 'a', function(e){

						       			var s_wo = (carouselbox.get(0).scrollWidth - carouselitem.outerWidth()),
						       				p_w = carouselitem.outerWidth()+"px";

						       			if($(this).is(leftpin.selector)){
						       				if(rightpin.attr('unselectable') != void 0){
						       					rightpin.removeAttr('unselectable');
						       				}

						       				carouselitem.stop().animate({
						       					marginLeft:"+="+p_w
						       				},100,"easeInCirc", function(){
						       					if(Math.abs(carouselitem.css('marginLeft')) <= 0){
						       						leftpin.attr('unselectable', 'unselectable');
						       					}
						       				});

						       			}

						       			if($(this).is(rightpin.selector)){
						       				if(leftpin.attr('unselectable') != void 0){
						       					leftpin.removeAttr('unselectable');
						       				}

						       				carouselitem.stop().animate({
						       					marginLeft:"+=-"+p_w
						       				},100,"easeInCirc", function(){
						       					if(Math.abs(carouselitem.css('marginLeft')) >= s_wo){
						       						rightpin.attr('unselectable', 'unselectable');
						       					}
						       				});
						       			}

						       		});
							      
						       },
						       defineVars:function(){

						       		/* define all variables/references */

						       		body = $(d.body);

						       		carouselbox = $(".mynti-dashlet-carousel");

						       		carouselitem = carouselbox.children(":eq(0)");

						       		leftpin = $('a.carousel-left-pin');

						       		rightpin = $('a.carousel-right-pin');

						       	   
						       },
						       stop:function(){

						       		/* unbind event handlers/listeners */

						       		body.off('click');
							    
						       },
						       destroy:function(){

						       		/* recliam/clear-up memory */	

								   body = null;
								   leftpin = null;

								   rightpin = null;
								   carouselbox = null;
								   carouselitem = null;

							       E = null;
							       U = null;
							       $ = null;

						       }
       
  						}

		});

}(this, this.document));