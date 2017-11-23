/*!
 * @project: <MyNTI>
 * @file: <dashboardunit.js>
 * @author: <https://www.omniswift.com>
 * @developer: <https;//www.twitter.com/isocroft>
 * @created: <10/07/2017>
 * @desc: {this is a stand-alone module for students dashboard web app}
 * @remarks: {module script}
 */
 
(function(w, d, undefined){



		$cdvjs.Application.registerModule("pageunit", ["jQuery", "emitter", "tools"], function(box){

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

			           T = box.tools;


			           return {

			           		init:function(){



			           		},
			           		defineVars:function(){

			           		},
			           		stop:function(){

			           		},
			           		destroy:function(){


			           		}

			           };
		});


 }(this, this.document));