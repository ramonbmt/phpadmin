<!-- javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/prettify.js"></script>
<script src="/js/jquery.sparkline.min.js"></script>
<script src="/js/jquery.nicescroll.min.js"></script>
<script src="/js/accordion.jquery.js"></script>
<script src="/js/smart-wizard.jquery.js"></script>
<script src="/js/vaidation.jquery.js"></script>
<script src="/js/jquery-dynamic-form.js"></script>
<script src="/js/fullcalendar.js"></script>
<script src="/js/raty.jquery.js"></script>
<script src="/js/jquery.noty.js"></script>
<script src="/js/jquery.cleditor.min.js"></script>
<!--<script src="/js/data-table.jquery.js"></script>
<script src="/js/TableTools.min.js"></script>
<script src="/js/ColVis.min.js"></script>-->
<script src="/js/plupload.full.js"></script>
<script src="/js/elfinder/elfinder.min.js"></script>
<script src="/js/chosen.jquery.js"></script>
<script src="/js/uniform.jquery.js"></script>
<script src="/js/jquery.tagsinput.js"></script>
<script src="/js/jquery.colorbox-min.js"></script>
<script src="/js/check-all.jquery.js"></script>
<script src="/js/inputmask.jquery.js"></script>
<script src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<script src="/js/plupupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script src="/js/excanvas.min.js"></script>
<script src="/js/jquery.jqplot.min.js"></script>
<script src="/js/chart/jqplot.highlighter.min.js"></script>
<script src="/js/chart/jqplot.cursor.min.js"></script>
<script src="/js/chart/jqplot.dateAxisRenderer.min.js"></script>
<script src="/js/custom-script.js"></script>
<!-- html5.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="/js/respond.min.js"></script>
<script src="/js/ios-orientationchange-fix.js"></script>
<script>
(function($){'use strict';var escape=/["\\\x00-\x1f\x7f-\x9f]/g,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},hasOwn=Object.prototype.hasOwnProperty;$.toJSON=typeof JSON==='object'&&JSON.stringify?JSON.stringify:function(o){if(o===null){return'null';}
var pairs,k,name,val,type=$.type(o);if(type==='undefined'){return undefined;}
if(type==='number'||type==='boolean'){return String(o);}
if(type==='string'){return $.quoteString(o);}
if(typeof o.toJSON==='function'){return $.toJSON(o.toJSON());}
if(type==='date'){var month=o.getUTCMonth()+1,day=o.getUTCDate(),year=o.getUTCFullYear(),hours=o.getUTCHours(),minutes=o.getUTCMinutes(),seconds=o.getUTCSeconds(),milli=o.getUTCMilliseconds();if(month<10){month='0'+month;}
if(day<10){day='0'+day;}
if(hours<10){hours='0'+hours;}
if(minutes<10){minutes='0'+minutes;}
if(seconds<10){seconds='0'+seconds;}
if(milli<100){milli='0'+milli;}
if(milli<10){milli='0'+milli;}
return'"'+year+'-'+month+'-'+day+'T'+
hours+':'+minutes+':'+seconds+'.'+milli+'Z"';}
pairs=[];if($.isArray(o)){for(k=0;k<o.length;k++){pairs.push($.toJSON(o[k])||'null');}
return'['+pairs.join(',')+']';}
if(typeof o==='object'){for(k in o){if(hasOwn.call(o,k)){type=typeof k;if(type==='number'){name='"'+k+'"';}else if(type==='string'){name=$.quoteString(k);}else{continue;}
type=typeof o[k];if(type!=='function'&&type!=='undefined'){val=$.toJSON(o[k]);pairs.push(name+':'+val);}}}
return'{'+pairs.join(',')+'}';}};$.evalJSON=typeof JSON==='object'&&JSON.parse?JSON.parse:function(str){return eval('('+str+')');};$.secureEvalJSON=typeof JSON==='object'&&JSON.parse?JSON.parse:function(str){var filtered=str.replace(/\\["\\\/bfnrtu]/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,'');if(/^[\],:{}\s]*$/.test(filtered)){return eval('('+str+')');}
throw new SyntaxError('Error parsing JSON, source is not valid.');};$.quoteString=function(str){if(str.match(escape)){return'"'+str.replace(escape,function(a){var c=meta[a];if(typeof c==='string'){return c;}
c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+(c%16).toString(16);})+'"';}
return'"'+str+'"';};}(jQuery));
</script>
	<script>
		var pagination=0;
		
		//setInterval("checkUpdates()",5000);
	</script>