/**!
 *
 * Async JS Loader
 *
 *
 */

;(function(w, d){

	var api = null, list = null, xhr = null, 
	isIE = ('XDomainRequest' in w), 
	_as = (d.currentScript || d.scripts[d.scripts.length - 1]).getAttribute('src-load-list');

	list = (new Function('return (' + _as + ');'))() || []; 

	try {
		if(isIE){
			xhr = new XDomainRequest();
		}else{
			xhr = new XMLHttpRequest();

			if(!('withCredentials' in xhr)){
				xhr = null;
				xhr = d.createElement('iframe');
				xhr.id = Math.random().toString();
				xhr.style.display = 'inline-block';
				xhr.style.height = '0px';
				xhr.style.width = '0px';
				xhr.style.position = 'absolute';
				xhr.style.left = '-9999px';
				xhr.style.top = '0px';
				xhr.tabIndex = '-1';
				xhr.visibility = 'hidden';
			}
		}
	}catch(e){ return; }


	api = {
		fetch:function(url){	
			var _self = this, callback = function(){ 
					if('nodeType' in this){ 
							if(_self.dom_anchor == 'html'){ 
									d.documentElement.removeChild(this); 
							}else{ 
									d.body.removeChild(this); 
							} 
					} 

					var _s = d.createElement('script');
					_s.type='text/javascript';
					_s.src = _self.currentScriptURL;
					(d.getElementsByTagName('head')[0]).appendChild(_s);

					_self.nextInQueue.call(_self);  
				};

			_self.currentScriptURL = url; 	

			if('nodeType' in xhr){
				xhr.src = url;

				if(d.readyState != 'complete'){
					// hack for early loading...
					d.documentElement.appendChild(xhr);
					_self.dom_anchor = 'html';
				}else{
					_self.dom_anchor = 'body';
					d.body.appendChild(xhr);
				}

				if('attachEvent' in xhr){
					// IE8 and below won't work with [{IFRAME}.onload = function(){}]
					// Opera 7/8/9 too will honour this
					xhr.attachEvent('onload', callback); 
				}else{
					xhr.onload = callback;
				}
			}else if(isIE){
				xhr.open('GET', url);
				xhr.onload = callback;
				xhr.onerror = callback;
				xhr.ontimeout = callback;
				xhr.onprogress = function(e){};
				xhr.timeout = 0;
			}else{
				xhr.open('GET', url, true);
				xhr.onreadystatechange = function(){
					if(this.readyState == 4){
						callback();
					}
				}
			}
		},
		nextInQueue:function(){
			if(list.length > 0){
				this.fetch(list.unshift());
			}
		}
  	};

	api.nextInQueue();

}(this, this.document));

/*;(function(w, d){

	var obj = d.createElement('object');

	obj.data = absoluteFilePath;

	obj.width = 0;
	obj.height = 0;

	obj.onload = function(){

		var script = d.createElement('script');
		script.src = file;
		script.type = "tex/javascript";
		script.onreadystatechange = script.onload = function(){

		}
	}

}(this, this.document));*/