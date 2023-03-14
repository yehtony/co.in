$(function(){
	var loading ="<div id='block'><div id='loading'><img src='/co.in/model/images/loading.gif'/></div></div>";
	var progressTimer;				// 延遲出現時間，當超過一分鐘後，開始跑！
	$(document).ajaxStart(function(){
		progressTimer = setTimeout(function(){
			$("#block").remove();
			$(".loading").append(loading);
			$("#block").css({
				"position" : "fixed",
				"width"    : "100%",
				"height"   : "100%",
				"top"      : "0px",
				"left" 	   : "0px",
				"background": "url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzAwMDAwMCIgc3RvcC1vcGFjaXR5PSIwLjY1Ii8+CiAgICA8c3RvcCBvZmZzZXQ9IjElIiBzdG9wLWNvbG9yPSIjMDAwMDAwIiBzdG9wLW9wYWNpdHk9IjAuNjUiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iIzAwMDAwMCIgc3RvcC1vcGFjaXR5PSIwLjY1Ii8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=)",
				"background": "-moz-linear-gradient(top,  rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.65) 1%, rgba(0,0,0,0.65) 100%)",
				"background": "-webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0.65)), color-stop(1%,rgba(0,0,0,0.65)), color-stop(100%,rgba(0,0,0,0.65)))",
				"background": "-webkit-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 1%,rgba(0,0,0,0.65) 100%)",
				"background": "-o-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 1%,rgba(0,0,0,0.65) 100%)",
				"background": "-ms-linear-gradient(top,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 1%,rgba(0,0,0,0.65) 100%)",
				"background": "linear-gradient(to bottom,  rgba(0,0,0,0.65) 0%,rgba(0,0,0,0.65) 1%,rgba(0,0,0,0.65) 100%)",
				"filter": "progid:DXImageTransform.Microsoft.gradient( startColorstr='#a6000000', endColorstr='#a6000000',GradientType=0 )",
				"cursor": "default",
				"z-index":"99999"
			});	
			$("#loading").css({
				"position"	:"relative",
				"margin" : "0 auto",
				"width" : "40px",
				"background-color" :"white",
				"border"    :"solid 5px #d4d4d4",
				"border-radius" :"10px",
				"display"	:"block",
				"top"		:window.outerHeight/2 - 100,
				"z-index"   :"99999999999999999999999999999999999999999999"
			});
			$("#loading img").css({
				"top":"0px",
				"left":"0px",
				"padding":"10px",
				"display":"block",
			});
		}, 1000)	
	});
	$(document).ajaxStop(function(){
		$( "#block, #loading" ).hide();
		$( "#loading" ).remove();
		clearTimeout(progressTimer);
	});
 })