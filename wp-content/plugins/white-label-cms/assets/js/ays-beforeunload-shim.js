/*! For license information please see ays-beforeunload-shim.js.LICENSE.txt */
jQuery((function(){navigator.userAgent.toLowerCase().match(/iphone|ipad|ipod|opera/)&&$("a").bind("click",(function(e){var r=$(e.target).closest("a").attr("href");if(void 0!==r&&!r.match(/^#/)&&""!=r.trim()){var a=$(window).triggerHandler("beforeunload",a);return a&&""!=a&&!confirm(a+"\n\nPress OK to leave this page or Cancel to stay.")||(window.location.href=r),!1}}))}));