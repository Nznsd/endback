(function() {
  var Idle;

  if (!document.addEventListener) {
    if (document.attachEvent) {
      document.addEventListener = function(event, callback, useCapture) {
        return document.attachEvent("on" + event, callback, useCapture);
      };
    } else {
      document.addEventListener = function() {
        return {};
      };
    }
  }

  if (!document.removeEventListener) {
    if (document.detachEvent) {
      document.removeEventListener = function(event, callback) {
        return document.detachEvent("on" + event, callback);
      };
    } else {
      document.removeEventListener = function() {
        return {};
      };
    }
  }

  "use strict";

  Idle = {};

  Idle = (function() {
    Idle.isAway = false;

    Idle.awayTimeout = 3000;

    Idle.awayTimestamp = 0;

    Idle.awayTimer = null;

    Idle.onAway = null;

    Idle.onAwayBack = null;

    Idle.onVisible = null;

    Idle.onHidden = null;

    function Idle(options) {
      var activeMethod, activity;
      if (options) {
        this.awayTimeout = parseInt(options.awayTimeout, 10);
        this.onAway = options.onAway;
        this.onAwayBack = options.onAwayBack;
        this.onVisible = options.onVisible;
        this.onHidden = options.onHidden;
      }
      activity = this;
      activeMethod = function() {
        return activity.onActive();
      };

      activeMethod.$$onload = window.onload
      activeMethod.$$onclick = window.onclick;
      activeMethod.$$onmousemove = window.onmousemove;
      activeMethod.$$onmousedown = window.onmousedown;
      activeMethod.$$ontouchstart = window.ontouchstart;
      activeMethod.$$ontouchmove = window.ontouchmove;
      activeMethod.$$onkeydown = window.onkeydown;
      activeMethod.$$onscroll = window.onscroll;
      activeMethod.$$onmousewheel = window.onmousewheel;

      if(typeof document.documentMode == 'number'
          && (document.documentMode >= 8 
            && document.documentMode <= 9)){
          // IE 8+ allows `Object.defineProperty` on DOM nodes
          Object.defineProperty(document, 'oldMsHidden', {
                status:false,
                get:function(){
                    return this.status;
                },
                set:function(value){
                  if(typeof value != 'boolean'){
                    return;
                  }
                  this.status = value;
                }
          });
      }

      window.onload = function(){
           if(activeMethod.$$onload){ 
              activeMethod.$$onload();
           }
           activeMethod();
      }     
      window.onclick = function(){
         if(activeMethod.$$onclick){ 
              activeMethod.$$onclick();
         }
         activeMethod();
      }
      window.onmousemove = function(){
        if(activeMethod.$$onmousemove){ 
              activeMethod.$$onmousemove();
         } 
         activeMethod();
      }
         
      window.onmousedown = function(){
        if(activeMethod.$$onmousedown){
            activeMethod.$$onmousedown();
        }
        activeMethod();
      } 

      window.ontouchstart = function(){ 
          if(activeMethod.$$ontouchstart){
              activeMethod.$$ontouchstart();
          }
          activeMethod();
      }

      window.ontouchmove = function(){ 
          if(activeMethod.$$ontouchmove){
              activeMethod.$$ontouchmove();
          }
          activeMethod();
      }
          
      window.onkeydown = function(){
          if(activeMethod.$$onkeydown){
              activeMethod.$$onkeydown();
          }
          activeMethod();
      }    

      window.onscroll = function(){
          if(activeMethod.$onscroll){
              activeMethod.$$onscroll();
          }
          activeMethod();
      }

      window.onmousewheel = function(){
          if(activeMethod.$$onmousewheel){
              activeMethod.$$onmousewheel();
          }
          activeMethod();
      }

    }

    Idle.prototype.onActive = function() {
      this.awayTimestamp = new Date().getTime() + this.awayTimeout;
      if (this.isAway) {
        if (this.onAwayBack) {
          this.onAwayBack();
        }
        this.start();
      }
      this.isAway = false;
      return true;
    };

    Idle.prototype.start = function() {
      var activity;
      if (!this.listener) {
        this.listener = (function(e) {
              if (!/^focus(?:in|out)$/.test(e.type) || (
                    (e.toElement === undefined || e.toElement === null)  &&
                    (e.fromElement === undefined || e.fromElement === null) &&
                    (e.relatedTarget === undefined || e.relatedElement === null) &&
                    typeof document.documentMode === 'number'
                  )
              ) {
          
                  if((document.oldMsHidden 
                      || /^(?:blur|focusout)$/.test(e.type)) 
                          && !document.hasFocus()){
                      document.oldMsHidden = true;
                  }else{
                      document.oldMsHidden = false;
                  }
        
              }

              return activity.handleVisibilityChange();
        });
        
        if(typeof document.addEventListener === 'function'){
          
            if('hidden' in document &&
              !('webkitHidden' in document
                  || 'oHidden' in document
                    || 'mozHidden' in document
                        || 'msHidden' in document)){
                         document.addEventListener("visibilitychange", this.listener, false);
            }else{
                    document.addEventListener("webkitvisibilitychange", this.listener, false);
                    document.addEventListener("msvisibilitychange", this.listener, false);
                    document.addEventListener("ovisibilitychange", this.listener, false);
                    document.addEventListener("mozvisibilitychange", this.listener, false);
            }
        }else if(typeof document.attachEvent === 'function'){
            document.attachEvent('focusin', this.listener);
            document.attachEvent('focusout', this.listener);
            window.attachEvent('focus', this.listener);
            window.attachEvent('blur', this.listener);
        }
      }
      this.awayTimestamp = new Date().getTime() + this.awayTimeout;
      if (this.awayTimer !== null) {
        clearTimeout(this.awayTimer);
      }
      activity = this;
      this.awayTimer = setTimeout((function() {
        return activity.checkAway();
      }), this.awayTimeout + 100);
      return this;
    };

    Idle.prototype.stop = function() {
      if (this.awayTimer !== null) {
        clearTimeout(this.awayTimer);
      }
      if (this.listener !== null) {

        if(typeof document.removeEventListener === 'function'){
            if('hidden' in document &&
                  !('webkitHidden' in document
                      || 'oHidden' in document
                        || 'mozHidden' in document
                            || 'msHidden' in document)){
                        document.removeEventListener("visibilitychange", this.listener);
            }else{
                    document.removeEventListener("webkitvisibilitychange", this.listener);
                    document.removeEventListener("msvisibilitychange", this.listener);
                    document.removeEventListener("ovisibilitychange", this.listener);
                    document.removeEventListener("mozvisibilitychange", this.listener);
            }
        }else if(typeof document.attachEvent === 'function'){
            document.detachEvent('focusin', this.listener);
            document.detachEvent('focusout', this.listener);
            window.detachEvent('focus', this.listener);
            window.detachEvent('blur', this.listener);
        }

        this.listener = null;
      }
      return this;
    };

    Idle.prototype.setAwayTimeout = function(ms) {
      this.awayTimeout = parseInt(ms, 10);
      return this;
    };

    Idle.prototype.checkAway = function() {
      var activity, t;
      t = new Date().getTime();
      if (t < this.awayTimestamp) {
        this.isAway = false;
        activity = this;
        this.awayTimer = setTimeout((function() {
          return activity.checkAway();
        }), this.awayTimestamp - t + 100);
        return;
      }
      if (this.awayTimer !== null) {
        clearTimeout(this.awayTimer);
      }
      this.isAway = true;
      if (this.onAway) {
        return this.onAway();
      }
    };

    Idle.prototype.handleVisibilityChange = function() {
      if (document.hidden || document.msHidden 
              || document.webkitHidden || document.mozHidden 
                  || document.oHidden || document.oldMsHidden) {
        if (this.onHidden) {
          return this.onHidden();
        }
      } else {
        if (this.onVisible) {
          return this.onVisible();
        }
      }
    };

    return Idle;

  })();

  if (typeof define === 'function' && define.amd) {
    define([], Idle);
  } else if (typeof exports === 'object') {
    module.exports = Idle;
  } else {
    window.Idle = Idle;
  }

}).call(this);
