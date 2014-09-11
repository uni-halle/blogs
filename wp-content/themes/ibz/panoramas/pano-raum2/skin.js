// Garden Gnome Software - Skin
// Pano2VR 4.0/3102
// Filename: simplex_notooltip-jb03.ggsk
// Generated Sa. Mär 16 21:02:52 2013

function pano2vrSkin(player,base) {
	var me=this;
	var flag=false;
	var nodeMarker=new Array();
	var activeNodeMarker=new Array();
	this.player=player;
	this.player.skinObj=this;
	this.divSkin=player.divSkin;
	var basePath="";
	// auto detect base path
	if (base=='?') {
		var scripts = document.getElementsByTagName('script');
		for(var i=0;i<scripts.length;i++) {
			var src=scripts[i].src;
			if (src.indexOf('skin.js')>=0) {
				var p=src.lastIndexOf('/');
				if (p>=0) {
					basePath=src.substr(0,p+1);
				}
			}
		}
	} else
	if (base) {
		basePath=base;
	}
	this.elementMouseDown=new Array();
	this.elementMouseOver=new Array();
	var cssPrefix='';
	var domTransition='transition';
	var domTransform='transform';
	var prefixes='Webkit,Moz,O,ms,Ms'.split(',');
	var i;
	for(i=0;i<prefixes.length;i++) {
		if (typeof document.body.style[prefixes[i] + 'Transform'] !== 'undefined') {
			cssPrefix='-' + prefixes[i].toLowerCase() + '-';
			domTransition=prefixes[i] + 'Transition';
			domTransform=prefixes[i] + 'Transform';
		}
	}
	
	this.player.setMargins(0,0,0,0);
	
	this.updateSize=function(startElement) {
		var stack=new Array();
		stack.push(startElement);
		while(stack.length>0) {
			e=stack.pop();
			if (e.ggUpdatePosition) {
				e.ggUpdatePosition();
			}
			if (e.hasChildNodes()) {
				for(i=0;i<e.childNodes.length;i++) {
					stack.push(e.childNodes[i]);
				}
			}
		}
	}
	
	parameterToTransform=function(p) {
		return 'translate(' + p.rx + 'px,' + p.ry + 'px) rotate(' + p.a + 'deg) scale(' + p.sx + ',' + p.sy + ')';
	}
	
	this.findElements=function(id,regex) {
		var r=new Array();
		var stack=new Array();
		var pat=new RegExp(id,'');
		stack.push(me.divSkin);
		while(stack.length>0) {
			e=stack.pop();
			if (regex) {
				if (pat.test(e.ggId)) r.push(e);
			} else {
				if (e.ggId==id) r.push(e);
			}
			if (e.hasChildNodes()) {
				for(i=0;i<e.childNodes.length;i++) {
					stack.push(e.childNodes[i]);
				}
			}
		}
		return r;
	}
	
	this.addSkin=function() {
		this._controller=document.createElement('div');
		this._controller.ggId='controller';
		this._controller.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._controller.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 286px;';
		hs+='height: 50px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._controller.setAttribute('style',hs);
		this._autorotate=document.createElement('div');
		this._autorotate.ggId='autorotate';
		this._autorotate.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._autorotate.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  180px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._autorotate.setAttribute('style',hs);
		this._autorotate__img=document.createElement('img');
		this._autorotate__img.setAttribute('src',basePath + 'images/autorotate.svg');
		this._autorotate__img.setAttribute('style','position: absolute;top: 0px;left: 0px;width: 32px;height: 32px;');
		this._autorotate.appendChild(this._autorotate__img);
		this._autorotate.onclick=function () {
			me.player.toggleAutorotate();
		}
		this._autorotate.onmouseover=function () {
			me._autorotate__img.src=basePath + 'images/autorotate__o.svg';
		}
		this._autorotate.onmouseout=function () {
			me._autorotate__img.src=basePath + 'images/autorotate.svg';
		}
		this._controller.appendChild(this._autorotate);
		this._fullscreen=document.createElement('div');
		this._fullscreen.ggId='fullscreen';
		this._fullscreen.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._fullscreen.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  10px;';
		hs+='width: 32px;';
		hs+='height: 32px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='cursor: pointer;';
		this._fullscreen.setAttribute('style',hs);
		this._fullscreen__img=document.createElement('img');
		this._fullscreen__img.setAttribute('src',basePath + 'images/fullscreen.svg');
		this._fullscreen__img.setAttribute('style','position: absolute;top: 0px;left: 0px;width: 32px;height: 32px;');
		this._fullscreen.appendChild(this._fullscreen__img);
		this._fullscreen.onclick=function () {
			me.player.toggleFullscreen();
		}
		this._fullscreen.onmouseover=function () {
			me._fullscreen__img.src=basePath + 'images/fullscreen__o.svg';
		}
		this._fullscreen.onmouseout=function () {
			me._fullscreen__img.src=basePath + 'images/fullscreen.svg';
		}
		this._controller.appendChild(this._fullscreen);
		this.divSkin.appendChild(this._controller);
		this._loading=document.createElement('div');
		this._loading.ggId='loading';
		this._loading.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loading.ggVisible=true;
		this._loading.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-105 + w/2) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-30 + h/2) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -105px;';
		hs+='top:  -30px;';
		hs+='width: 210px;';
		hs+='height: 60px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		this._loading.setAttribute('style',hs);
		this._loading.onclick=function () {
			me._loading.style[domTransition]='none';
			me._loading.style.visibility='hidden';
			me._loading.ggVisible=false;
		}
		this._loadingbg=document.createElement('div');
		this._loadingbg.ggId='loadingbg';
		this._loadingbg.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loadingbg.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 210px;';
		hs+='height: 60px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='opacity: 0.5;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='border-radius: 10px;';
		hs+=cssPrefix + 'border-radius: 10px;';
		hs+='background-color: #000000;';
		this._loadingbg.setAttribute('style',hs);
		this._loading.appendChild(this._loadingbg);
		this._loadingbrd=document.createElement('div');
		this._loadingbrd.ggId='loadingbrd';
		this._loadingbrd.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loadingbrd.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: -1px;';
		hs+='top:  -1px;';
		hs+='width: 208px;';
		hs+='height: 58px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='opacity: 0.5;';
		hs+='visibility: inherit;';
		hs+='border: 2px solid #ffffff;';
		hs+='border-radius: 10px;';
		hs+=cssPrefix + 'border-radius: 10px;';
		this._loadingbrd.setAttribute('style',hs);
		this._loading.appendChild(this._loadingbrd);
		this._loadingtext=document.createElement('div');
		this._loadingtext.ggId='loadingtext';
		this._loadingtext.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loadingtext.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 16px;';
		hs+='top:  12px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #ffffff;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._loadingtext.setAttribute('style',hs);
		this._loadingtext.ggUpdateText=function() {
			this.innerHTML="Loading... "+(me.player.getPercentLoaded()*100.0).toFixed(0)+"%";
		}
		this._loadingtext.ggUpdateText();
		this._loading.appendChild(this._loadingtext);
		this._loadingbar=document.createElement('div');
		this._loadingbar.ggId='loadingbar';
		this._loadingbar.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._loadingbar.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 15px;';
		hs+='top:  35px;';
		hs+='width: 181px;';
		hs+='height: 12px;';
		hs+=cssPrefix + 'transform-origin: 0% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 1px solid #808080;';
		hs+='border-radius: 5px;';
		hs+=cssPrefix + 'border-radius: 5px;';
		hs+='background-color: #ffffff;';
		this._loadingbar.setAttribute('style',hs);
		this._loading.appendChild(this._loadingbar);
		this.divSkin.appendChild(this._loading);
		this._userdata=document.createElement('div');
		this._userdata.ggId='userdata';
		this._userdata.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._userdata.ggVisible=false;
		this._userdata.ggUpdatePosition=function() {
			this.style[domTransition]='none';
			if (this.parentNode) {
				w=this.parentNode.offsetWidth;
				this.style.left=(-120 + w/2) + 'px';
				h=this.parentNode.offsetHeight;
				this.style.top=(-80 + h/2) + 'px';
			}
		}
		hs ='position:absolute;';
		hs+='left: -120px;';
		hs+='top:  -80px;';
		hs+='width: 240px;';
		hs+='height: 140px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: hidden;';
		this._userdata.setAttribute('style',hs);
		this._userdata.onclick=function () {
			me._userdata.style[domTransition]='none';
			me._userdata.style.visibility='hidden';
			me._userdata.ggVisible=false;
		}
		this._userdatabg=document.createElement('div');
		this._userdatabg.ggId='userdatabg';
		this._userdatabg.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._userdatabg.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 0px;';
		hs+='top:  0px;';
		hs+='width: 240px;';
		hs+='height: 140px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='opacity: 0.5;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='border-radius: 10px;';
		hs+=cssPrefix + 'border-radius: 10px;';
		hs+='background-color: #000000;';
		this._userdatabg.setAttribute('style',hs);
		this._userdata.appendChild(this._userdatabg);
		this._userdatabrd=document.createElement('div');
		this._userdatabrd.ggId='userdatabrd';
		this._userdatabrd.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._userdatabrd.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: -1px;';
		hs+='top:  -1px;';
		hs+='width: 238px;';
		hs+='height: 138px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='opacity: 0.5;';
		hs+='visibility: inherit;';
		hs+='border: 2px solid #ffffff;';
		hs+='border-radius: 10px;';
		hs+=cssPrefix + 'border-radius: 10px;';
		this._userdatabrd.setAttribute('style',hs);
		this._userdata.appendChild(this._userdatabrd);
		this._title=document.createElement('div');
		this._title.ggId='title';
		this._title.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._title.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  10px;';
		hs+='width: 220px;';
		hs+='height: 20px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #ffffff;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._title.setAttribute('style',hs);
		this._title.ggUpdateText=function() {
			this.innerHTML="<b>"+me.player.userdata.title+"<\/b>";
		}
		this._title.ggUpdateText();
		this._userdata.appendChild(this._title);
		this._description=document.createElement('div');
		this._description.ggId='description';
		this._description.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._description.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  30px;';
		hs+='width: 220px;';
		hs+='height: 20px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #ffffff;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._description.setAttribute('style',hs);
		this._description.ggUpdateText=function() {
			this.innerHTML=me.player.userdata.description;
		}
		this._description.ggUpdateText();
		this._userdata.appendChild(this._description);
		this._author=document.createElement('div');
		this._author.ggId='author';
		this._author.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._author.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  50px;';
		hs+='width: 220px;';
		hs+='height: 20px;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #ffffff;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._author.setAttribute('style',hs);
		this._author.ggUpdateText=function() {
			this.innerHTML=me.player.userdata.author;
		}
		this._author.ggUpdateText();
		this._userdata.appendChild(this._author);
		this._datetime=document.createElement('div');
		this._datetime.ggId='datetime';
		this._datetime.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._datetime.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  70px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #ffffff;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._datetime.setAttribute('style',hs);
		this._datetime.ggUpdateText=function() {
			this.innerHTML=me.player.userdata.datetime;
		}
		this._datetime.ggUpdateText();
		this._userdata.appendChild(this._datetime);
		this._copyright=document.createElement('div');
		this._copyright.ggId='copyright';
		this._copyright.ggParameter={ rx:0,ry:0,a:0,sx:1,sy:1 };
		this._copyright.ggVisible=true;
		hs ='position:absolute;';
		hs+='left: 10px;';
		hs+='top:  110px;';
		hs+='width: auto;';
		hs+='height: auto;';
		hs+=cssPrefix + 'transform-origin: 50% 50%;';
		hs+='visibility: inherit;';
		hs+='border: 0px solid #000000;';
		hs+='color: #ffffff;';
		hs+='text-align: left;';
		hs+='white-space: nowrap;';
		hs+='white-space: nowrap;';
		hs+='padding: 0px 1px 0px 1px;';
		hs+='overflow: hidden;';
		this._copyright.setAttribute('style',hs);
		this._copyright.ggUpdateText=function() {
			this.innerHTML="&#169; "+me.player.userdata.copyright;
		}
		this._copyright.ggUpdateText();
		this._userdata.appendChild(this._copyright);
		this.divSkin.appendChild(this._userdata);
		this.divSkin.ggUpdateSize=function(w,h) {
			me.updateSize(me.divSkin);
		}
		this.divSkin.ggViewerInit=function() {
		}
		this.divSkin.ggLoaded=function() {
			me._loading.style[domTransition]='none';
			me._loading.style.visibility='hidden';
			me._loading.ggVisible=false;
		}
		this.divSkin.ggReLoaded=function() {
			me._loading.style[domTransition]='none';
			me._loading.style.visibility='inherit';
			me._loading.ggVisible=true;
		}
		this.divSkin.ggEnterFullscreen=function() {
		}
		this.divSkin.ggExitFullscreen=function() {
		}
		this.skinTimerEvent();
	};
	this.hotspotProxyClick=function(id) {
	}
	this.hotspotProxyOver=function(id) {
	}
	this.hotspotProxyOut=function(id) {
	}
	this.changeActiveNode=function(id) {
		var newMarker=new Array();
		var i,j;
		var tags=me.player.userdata.tags;
		for (i=0;i<nodeMarker.length;i++) {
			var match=false;
			if (nodeMarker[i].ggMarkerNodeId==id) match=true;
			for(j=0;j<tags.length;j++) {
				if (nodeMarker[i].ggMarkerNodeId==tags[j]) match=true;
			}
			if (match) {
				newMarker.push(nodeMarker[i]);
			}
		}
		for(i=0;i<activeNodeMarker.length;i++) {
			if (newMarker.indexOf(activeNodeMarker[i])<0) {
				if (activeNodeMarker[i].ggMarkerNormal) {
					activeNodeMarker[i].ggMarkerNormal.style.visibility='inherit';
				}
				if (activeNodeMarker[i].ggMarkerActive) {
					activeNodeMarker[i].ggMarkerActive.style.visibility='hidden';
				}
				if (activeNodeMarker[i].ggDeactivate) {
					activeNodeMarker[i].ggDeactivate();
				}
			}
		}
		for(i=0;i<newMarker.length;i++) {
			if (activeNodeMarker.indexOf(newMarker[i])<0) {
				if (newMarker[i].ggMarkerNormal) {
					newMarker[i].ggMarkerNormal.style.visibility='hidden';
				}
				if (newMarker[i].ggMarkerActive) {
					newMarker[i].ggMarkerActive.style.visibility='inherit';
				}
				if (newMarker[i].ggActivate) {
					newMarker[i].ggActivate();
				}
			}
		}
		activeNodeMarker=newMarker;
	}
	this.skinTimerEvent=function() {
		setTimeout(function() { me.skinTimerEvent(); }, 10);
		this._loadingtext.ggUpdateText();
		var hs='';
		if (me._loadingbar.ggParameter) {
			hs+=parameterToTransform(me._loadingbar.ggParameter) + ' ';
		}
		hs+='scale(' + (1 * me.player.getPercentLoaded() + 0) + ',1.0) ';
		me._loadingbar.style[domTransform]=hs;
		this._title.ggUpdateText();
		this._description.ggUpdateText();
		this._author.ggUpdateText();
		this._datetime.ggUpdateText();
		this._copyright.ggUpdateText();
	};
	this.addSkin();
};