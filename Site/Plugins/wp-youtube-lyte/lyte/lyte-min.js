function loadSC(a){scr=d.createElement("script");scr.src=a;scr.type="text/javascript";d.getElementsByTagName("head")[0].appendChild(scr)}function setST(a,b){if(typeof a.setAttribute==="function")a.setAttribute("style",b);else if(typeof a.style.setAttribute==="object")a.style.setAttribute("cssText",b)}function drawT(a,b){p=d.getElementById(a);c=d.createElement("div");c.className="tC";p.appendChild(c);setST(c,"margin:-"+(p.clientHeight/2+15)+"px 5px;");t=d.createElement("div");t.className="tT";c.appendChild(t);t.innerHTML=b}function parsePL(a){thumb=a.feed.entry[0].media$group.media$thumbnail[1].url;idu=a.feed.id.$t;id="lyte_"+idu.substring(idu.length-16);title="Playlist: "+a.feed.title.$t;pl=d.getElementById(id);pH=pl.style.height;pW=pl.style.width;if(scheme=="https"&&thumb.indexOf("https"==-1)){thumb=thumb.replace("http://","https://")}setST(pl,"height:"+pH+";width:"+pW+";background:url('"+thumb+"') no-repeat scroll center -10px rgb(0, 0, 0); background-size:contain;");drawT(id,title)}function parseV(a){tI=a.entry.title.$t;idu=a.entry.id.$t;id="lyte_"+idu.substring(idu.length-11);drawT(id,tI)}function plaYT(){tH=this;tH.onclick="";vid=tH.id.substring(4);if(tH.className.indexOf("hidef")===-1){hidef=0}else{hidef=1}if(tH.className.indexOf("playlist")===-1){eU=scheme+"://www.youtube.com/embed/"+vid}else{eU=scheme+"://www.youtube.com/embed/p/"+vid}qsa=getQ(vid);tH.innerHTML='<iframe class="youtube-player" type="text/html" width="'+tH.clientWidth+'" height="'+tH.clientHeight+'" src="'+eU+"?autoplay=1&wmode=opaque&rel=0&egm=0&iv_load_policy=3&probably_logged_in=false&hd="+hidef+qsa+'" frameborder="0"></iframe>'}function getQ(a){qsa="";if(typeof w.lst!=="undefined"&&typeof w.lst[a]!=="undefined")qsa=w.lst[a];return qsa}function lyte(){lytes=getElementsByClassName("lyte","div");for(var a=0;a<lytes.length;a++){lyte_id=lytes[a].id;vid=lyte_id.substring(4);p=d.getElementById(lyte_id);p.className+=" lP";pW=p.clientWidth;pH=p.clientHeight;pl=d.createElement("div");p.appendChild(pl);p.onclick=plaYT;pl.id="lyte_"+vid;pl.className="pL";qsa=getQ(vid);if(p.className.indexOf("audio")!==-1){setST(pl,"height:"+pH+"px;width:"+pW);pl.innerHTML='<img src="'+bU+"controls-"+pW+'.png" width="100%" id="ctrl" alt="" style="max-width:'+pW+'px;"/>'}else if(p.className.indexOf("playlist")!==-1){setST(pl,"height:"+pH+"px;width:"+pW+"px;");pl.innerHTML='<img src="'+bU+'play.png" alt="Click to play this playlist" style="margin-top:'+(pH/2-30)+'px;opacity:0.7;" onmouseover="this.style.opacity=1;" onmouseout="this.style.opacity=0.8;"/><img src="'+bU+"controls-"+pW+'.png" width="100%" id="ctrl" alt="" style="max-width:'+pW+'px;"/>';jsonUrl=scheme+"://gdata.youtube.com/feeds/api/playlists/"+vid+"?v=2&alt=json-in-script&callback=parsePL&fields=id,title,entry";loadSC(jsonUrl)}else{setST(pl,"height:"+pH+"px;width:"+pW+"px;background:url('"+scheme+"://img.youtube.com/vi/"+vid+"/0.jpg') no-repeat scroll center -10px rgb(0, 0, 0);background-size:contain;");pl.innerHTML='<img src="'+bU+'play.png" alt="Click to play this video" style="margin-top:'+(pH/2-30)+'px;opacity:0.7;" onmouseover="this.style.opacity=1;" onmouseout="this.style.opacity=0.8;"/><img src="'+bU+"controls-"+pW+'.png" width="100%" id="ctrl" alt="" style="max-width:'+pW+'px;"/>';if(p.className.indexOf("widget")===-1&&qsa.indexOf("showinfo=0")===-1){jsonUrl=scheme+"://gdata.youtube.com/feeds/api/videos/"+vid+"?fields=id,title&alt=json-in-script&callback=parseV";loadSC(jsonUrl)}}}}var d=document;var cI="lcss";var w=window;var myUrl=d.getElementById("lytescr").src;var bU=myUrl.substring(0,myUrl.lastIndexOf("/")+1);scheme="http";if(myUrl.indexOf("https")!=-1){scheme+="s"}if(!d.getElementById(cI)){lk=d.createElement("link");lk.id=cI;lk.rel="stylesheet";lk.type="text/css";lk.href=bU+"lyte.css";d.getElementsByTagName("head")[0].appendChild(lk)}var getElementsByClassName=function(a,b,c){if(d.getElementsByClassName){getElementsByClassName=function(a,b,c){c=c||d;var e=c.getElementsByClassName(a),f=b?new RegExp("\\b"+b+"\\b","i"):null,g=[],h;for(var i=0,j=e.length;i<j;i+=1){h=e[i];if(!f||f.test(h.nodeName)){g.push(h)}}return g}}else if(d.evaluate){getElementsByClassName=function(a,b,c){b=b||"*";c=c||d;var e=a.split(" "),f="",g="http://www.w3.org/1999/xhtml",h=d.documentElement.namespaceURI===g?g:null,i=[],j,k;for(var l=0,m=e.length;l<m;l+=1){f+="[contains(concat(' ', @class, ' '), ' "+e[l]+" ')]"}try{j=d.evaluate(".//"+b+f,c,h,0,null)}catch(n){j=d.evaluate(".//"+b+f,c,null,0,null)}while(k=j.iterateNext()){i.push(k)}return i}}else{getElementsByClassName=function(a,b,c){b=b||"*";c=c||d;var e=a.split(" "),f=[],g=b==="*"&&c.all?c.all:c.getElementsByTagName(b),h,i=[],j;for(var k=0,l=e.length;k<l;k+=1){f.push(new RegExp("(^|\\s)"+e[k]+"(\\s|$)"))}for(var m=0,n=g.length;m<n;m+=1){h=g[m];j=false;for(var o=0,p=f.length;o<p;o+=1){j=f[o].test(h.className);if(!j){break}}if(j){i.push(h)}}return i}}return getElementsByClassName(a,b,c)};lyte()