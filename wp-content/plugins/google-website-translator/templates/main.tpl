
<style type="text/css">
<!--
{{ is_inline.true:begin }}
.prisna-gwt-align-left {
	text-align: left !important;
}
.prisna-gwt-align-right {
	text-align: right !important;
}
{{ is_inline.true:end }}
{{ has_flags.true:begin }}
.prisna-gwt-flags-container {
	list-style: none !important;
	margin: 0 !important;
	padding: 0 !important;
	border: none !important;
	clear: both !important;
}
.prisna-gwt-flag-container {
	list-style: none !important;
	display: inline-block;
	margin: 0 2px 0 0 !important;
	padding: 0 !important;
	border: none !important;
}
.prisna-gwt-flag-container a {
	display: inline-block;
	margin: 0 !important;
	padding: 0 !important;
	border: none !important;
	background-repeat: no-repeat !important;
	background-image: url({{ flags_image_path }}/all.png) !important;
	width: 22px !important;
	height: 16px !important;
}
{{ flags_css }}
{{ has_flags.true:end }}
{{ hide_banner.true:begin }}
body {
	top: 0 !important;
}
.goog-te-banner-frame {
	display: none !important;
	visibility: hidden !important;
}
{{ hide_banner.true:end }}
#goog-gt-tt,
.goog-tooltip,
.goog-tooltip:hover {
	display: none !important;
}
.goog-text-highlight {
	background-color: transparent !important;
	border: none !important;
	box-shadow: none !important;
}
.translated-rtl font,
.translated-ltr font {
	background-color: transparent !important;
	box-shadow: none !important;
	box-sizing: border-box !important;
	-webkit-box-sizing: border-box !important;
	-moz-box-sizing: border-box !important;
}
{{ custom_css }}
-->
</style>
{{ exclude_selector.empty.false:begin }}
<script type="text/javascript">
/*<![CDATA[*/
jQuery(document).ready(function() {
	jQuery("{{ exclude_selector }}").addClass("notranslate");
});
/*]]>*/
</script>
{{ exclude_selector.empty.false:end }}
{{ on_before_load.empty.false:begin }}
<script type="text/javascript">
/*<![CDATA[*/
{{ on_before_load }}
/*]]>*/
</script>
{{ on_before_load.empty.false:end }}
{{ has_flags.true:begin }}
<script type="text/javascript">
/*<![CDATA[*/
var PrisnaGWT = {

	_fire_event: function(_element, _event) {
		
		try {
			if (document.createEvent) {
				var ev = document.createEvent("HTMLEvents");
				ev.initEvent(_event, true, true);
				_element.dispatchEvent(ev);
			} 
			else {
				var ev = document.createEventObject();
				_element.fireEvent("on" + _event, ev);
			}
		} 
		catch (e) {
			console.log("Prisna GWT: Browser not supported!");
		}
		
	},

	_restore: function() {

		var b, d = false;
		var c =  document.getElementById("\:1\.container");
		if (c)
			d = c.contentWindow.document.getElementById("\:1\.restore");
		
		if (d)
			this._fire_event(d, "click");

	},
	
	translate: function(_language) {
	
		var element;
		var combos = document.getElementsByTagName("select"); // IE8 doesn't support getElementsByClassName
		
		for (var i=0; i<combos.length; i++)
			if (combos[i].className == "goog-te-combo")
				element = combos[i];
		
		if (!element)
			return;
		
		if (_language == "{{ from }}")
			this._restore();
		else {
			element.value = _language;
			this._fire_event(element, "change");
		}

	}
	
};
/*]]>*/
</script>
{{ flags_formatted }}
{{ has_flags.true:end }}
{{ has_container.true:begin }}<div id="google_translate_element" class="prisna-gwt-align-{{ align_mode }}"></div>{{ has_container.true:end }}
<script type="text/javascript">
/*<![CDATA[*/
function initializeGoogleTranslateElement() {
	new google.translate.TranslateElement({
{{ options_formatted }}	}{{ has_container.true:begin }}, "google_translate_element"{{ has_container.true:end }});{{ on_after_load.empty.false:begin }}
{{ on_after_load }}{{ on_after_load.empty.false:end }}
}
/*]]>*/
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=initializeGoogleTranslateElement"></script>