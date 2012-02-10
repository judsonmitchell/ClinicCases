/*
 * Lightweight RTE - jQuery Plugin, v1.2
 * Basic Toolbars
 * Copyright (c) 2009 Andrey Gayvoronsky - http://www.gayvoronsky.com
 */
var rte_tag		= '-rte-tmp-tag-';

var	rte_toolbar = {
	disable         : {ignore: true},
	s1				: {separator: true},
	bold			: {command: 'bold', tags:['b', 'strong']},
	italic			: {command: 'italic', tags:['i', 'em']},
	strikeThrough	: {command: 'strikethrough', tags: ['s', 'strike'] },
	underline		: {command: 'underline', tags: ['u']},
	s2				: {separator: true },
	justifyLeft   	: {command: 'justifyleft'},
	justifyCenter	: {command: 'justifycenter'},
	justifyRight	: {command: 'justifyright'},
	justifyFull		: {command: 'justifyfull'},
	s3				: {separator : true},
	indent			: {command: 'indent'},
	outdent			: {command: 'outdent'},
	s4				: {separator : true},
	subscript		: {command: 'subscript', tags: ['sub']},
	superscript		: {command: 'superscript', tags: ['sup']},
	s5				: {separator : true },
	orderedList		: {command: 'insertorderedlist', tags: ['ol'] },
	unorderedList	: {command: 'insertunorderedlist', tags: ['ul'] },
	s6				: {separator : true },
	block			: {command: 'formatblock', select: '\
<select>\
	<option value="">Format</option>\
	<option value="<p>">Paragraph</option>\
	<option value="<h1>">Header 1</option>\
	<option value="<h2>">Header 2</options>\
	<option value="<h3>">Header 3</option>\
	<option value="<h4>">Header 4</options>\
	<option value="<h5>">Header 5</option>\
	<option value="<h6>">Header 6</options>\
</select>\
	', tag_cmp: lwrte_block_compare, tags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']},
	font			: {command: 'fontname', select: '\
<select>\
	<option value="arial" >Arial</option>\
	<option value="courier new">Courier New</options>\
	<option value="georgia">Georgia</option>\
	<option value="helvetica">Helvetica</options>\
	<option value="impact">Impact</option>\
	<option value="times new roman" selected="selected">Times New Roman</options>\
	<option value="trebuchet ms">Trebuchet</options>\
	<option value="verdana">Verdana</options>\
</select>\
	', tags: ['font']},
	size			: {command: 'fontsize', select: '\
<select>\
	<option value="1">8pt</option>\
	<option value="2">10pt</option>\
	<option value="3" selected="selected">12pt</options>\
	<option value="4">14pt</option>\
	<option value="5">16pt</options>\
	<option value="6">18pt</option>\
	<option value="7">20pt</options>\
</select>\
	', tags: ['font']},
	style			: {exec: lwrte_style, init: lwrte_style_init},
	color			: {exec: lwrte_color},
	link			: {exec: lwrte_link, tags: ['a'] },
	unlink			: {command: 'unlink'},
	s8				: {separator : true },
	removeFormat	: {exec: lwrte_unformat},
	print			: {command: 'print'}
};

var html_toolbar = {
	s1				: {separator: true},
	word			: {exec: lwrte_cleanup_word},
	clear			: {exec: lwrte_clear}
};

/*** tag compare callbacks ***/
function lwrte_block_compare(node, tag) {
	tag = tag.replace(/<([^>]*)>/, '$1');
	return (tag.toLowerCase() == node.nodeName.toLowerCase());
}

/*** init callbacks ***/
function lwrte_style_init(rte) {
	var self = this;
	self.select = '<select><option value="">- no css -</option></select>';

	// load CSS info. javascript only issue is not working correctly, that's why ajax-php :(
	if(rte.css.length) {	
		$.ajax({
			url: "lib/javascripts/lwrte/styles.php", 
			type: "POST",
			data: { css: rte.css[rte.css.length - 1] }, 
			async: false,
			success: function(data) {
				var list = data.split(',');
				var select = "";

				for(var name in list)
					select += '<option value="' + list[name] + '">' + list[name] + '</option>';
	
				self.select = '<select>' + select + '</select>';
			}});
	}
}

/*** exec callbacks ***/
function lwrte_style(args) {
	if(args) {
		try {
			var css = args.options[args.selectedIndex].value
			var self = this;
			var html = self.get_selected_text();
			html = '<span class="' + css + '">' + html + '</span>';
			self.selection_replace_with(html);
			args.selectedIndex = 0;
		} catch(e) {
		}
	}
}

function lwrte_color(){
	var self = this;
	var panel = self.create_panel('Set color for text', 385);
	var mouse_down = false;
	var mouse_over = false;
	panel.append('\
<div class="colorpicker1"><div class="rgb" id="rgb"></div></div>\
<div class="colorpicker1"><div class="gray" id="gray"></div></div>\
<div class="colorpicker2">\
	<div class="palette" id="palette"></div>\
	<div class="preview" id="preview"></div>\
	<div class="color" id="color"></div>\
</div>\
<div class="clear"></div>\
<p class="submit"><button id="ok">Ok</button><button id="cancel">Cancel</button></p>'
).show();

	var preview = $('#preview', panel);
	var color = $("#color", panel);
	var palette = $("#palette", panel);
	var colors = [
		'#660000', '#990000', '#cc0000', '#ff0000', '#333333',
		'#006600', '#009900', '#00cc00', '#00ff00', '#666666',
		'#000066', '#000099', '#0000cc', '#0000ff', '#999999',
		'#909000', '#900090', '#009090', '#ffffff', '#cccccc',
		'#ffff00', '#ff00ff', '#00ffff', '#000000', '#eeeeee'
	];
			
	for(var i = 0; i < colors.length; i++)
		$("<div></div>").addClass("item").css('background', colors[i]).appendTo(palette);
			
	var height = $('#rgb').height();
	var part_width = $('#rgb').width() / 6;

	$('#rgb,#gray,#palette', panel)
		.mousedown( function(e) {mouse_down = true; return false; } )
		.mouseup( function(e) {mouse_down = false; return false; } )
		.mouseout( function(e) {mouse_over = false; return false; } )
		.mouseover( function(e) {mouse_over = true; return false; } );

	$('#rgb').mousemove( function(e) { if(mouse_down && mouse_over) compute_color(this, true, false, false, e); return false;} );
	$('#gray').mousemove( function(e) { if(mouse_down && mouse_over) compute_color(this, false, true, false, e); return false;} );
	$('#palette').mousemove( function(e) { if(mouse_down && mouse_over) compute_color(this, false, false, true, e); return false;} );
	$('#rgb').click( function(e) { compute_color(this, true, false, false, e); return false;} );
	$('#gray').click( function(e) { compute_color(this, false, true, false, e); return false;} );
	$('#palette').click( function(e) { compute_color(this, false, false, true, e); return false;} );

	$('#cancel', panel).click( function() { panel.remove(); return false; } );
	$('#ok', panel).click( 
		function() {
			var value = color.html();

			if(value.length > 0 && value.charAt(0) =='#') {
				if(self.iframe_doc.selection) //IE fix for lost focus
					self.range.select();

				self.editor_cmd('foreColor', value);
			}
					
			panel.remove(); 
			return false;
		}
	);

	function to_hex(n) {
		var s = "0123456789abcdef";
		return s.charAt(Math.floor(n / 16)) + s.charAt(n % 16);
	}			

	function get_abs_pos(element) {
		var r = { x: element.offsetLeft, y: element.offsetTop };

		if (element.offsetParent) {
			var tmp = get_abs_pos(element.offsetParent);
			r.x += tmp.x;
			r.y += tmp.y;
		}

		return r;
	};
			
	function get_xy(obj, event) {
		var x, y;
		event = event || window.event;
		var el = event.target || event.srcElement;

		// use absolute coordinates
		var pos = get_abs_pos(obj);

		// subtract distance to middle
		x = event.pageX  - pos.x;
		y = event.pageY - pos.y;

		return { x: x, y: y };
	}
			
	function compute_color(obj, is_rgb, is_gray, is_palette, e) {
		var r, g, b, c;

		var mouse = get_xy(obj, e);
		var x = mouse.x;
		var y = mouse.y;

		if(is_rgb) {
			r = (x >= 0)*(x < part_width)*255 + (x >= part_width)*(x < 2*part_width)*(2*255 - x * 255 / part_width) + (x >= 4*part_width)*(x < 5*part_width)*(-4*255 + x * 255 / part_width) + (x >= 5*part_width)*(x < 6*part_width)*255;
			g = (x >= 0)*(x < part_width)*(x * 255 / part_width) + (x >= part_width)*(x < 3*part_width)*255	+ (x >= 3*part_width)*(x < 4*part_width)*(4*255 - x * 255 / part_width);
			b = (x >= 2*part_width)*(x < 3*part_width)*(-2*255 + x * 255 / part_width) + (x >= 3*part_width)*(x < 5*part_width)*255 + (x >= 5*part_width)*(x < 6*part_width)*(6*255 - x * 255 / part_width);

			var k = (height - y) / height;

			r = 128 + (r - 128) * k;
			g = 128 + (g - 128) * k;
			b = 128 + (b - 128) * k;
		} else if (is_gray) {
			r = g = b = (height - y) * 1.7;
		} else if(is_palette) {
			x = Math.floor(x / 10);
			y = Math.floor(y / 10);
			c = colors[x + y * 5];
		}

		if(!is_palette)
			c = '#' + to_hex(r) + to_hex(g) + to_hex(b);

		preview.css('background', c);
		color.html(c);
	}
}

function lwrte_image() {
	var self = this;
	var panel = self.create_panel('Insert image', 385);
	panel.append('\
<p><label>URL</label><input type="text" id="url" size="30" value=""><button id="view">View</button></p>\
<div class="clear"></div>\
<p class="submit"><button id="ok">Ok</button><button id="cancel">Cancel</button></p>'
).show();

	var url = $('#url', panel);
	var upload = $('#file', panel).upload( {
		autoSubmit: false,
		action: 'uploader.php',
		onSelect: function() {
			var file = this.filename();
			var ext = (/[.]/.exec(file)) ? /[^.]+$/.exec(file.toLowerCase()) : '';
			if(!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
				alert('Invalid file extension');
				return;
			}

			this.submit();
		},
		onComplete: function(response) { 
			if(response.length <= 0)
				return;

			response	= eval("(" + response + ")");
			if(response.error && response.error.length > 0)
				alert(response.error);
			else
				url.val((response.file && response.file.length > 0) ? response.file : '');
		}
	});

	$('#view', panel).click( function() {
			(url.val().length >0 ) ? window.open(url.val()) : alert("Enter URL of image to view");
			return false;
		}
	);
			
	$('#cancel', panel).click( function() { panel.remove(); return false;} );
	$('#ok', panel).click( 
		function() {
			var file = url.val();
			self.editor_cmd('insertImage', file);
			panel.remove(); 
			return false;
		}
	)
}

function lwrte_unformat() {
	this.editor_cmd('removeFormat');
	this.editor_cmd('unlink');
}

function lwrte_clear() {
	if(confirm('Clear Document?')) 
		this.set_content('');
}

function lwrte_cleanup_word() {
	this.set_content(cleanup_word(this.get_content(), true, true, true)); 
	
	function cleanup_word(s, bIgnoreFont, bRemoveStyles, bCleanWordKeepsStructure) {
		s = s.replace(/<o:p>\s*<\/o:p>/g, '') ;
		s = s.replace(/<o:p>[\s\S]*?<\/o:p>/g, '&nbsp;') ;

		// Remove mso-xxx styles.
		s = s.replace( /\s*mso-[^:]+:[^;"]+;?/gi, '' ) ;

		// Remove margin styles.
		s = s.replace( /\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '' ) ;
		s = s.replace( /\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"" ) ;

		s = s.replace( /\s*TEXT-INDENT: 0cm\s*;/gi, '' ) ;
		s = s.replace( /\s*TEXT-INDENT: 0cm\s*"/gi, "\"" ) ;

		s = s.replace( /\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"" ) ;

		s = s.replace( /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"" ) ;

		s = s.replace( /\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"" ) ;

		s = s.replace( /\s*tab-stops:[^;"]*;?/gi, '' ) ;
		s = s.replace( /\s*tab-stops:[^"]*/gi, '' ) ;

		// Remove FONT face attributes.
		if (bIgnoreFont) {
			s = s.replace( /\s*face="[^"]*"/gi, '' ) ;
			s = s.replace( /\s*face=[^ >]*/gi, '' ) ;

			s = s.replace( /\s*FONT-FAMILY:[^;"]*;?/gi, '' ) ;
		}

		// Remove Class attributes
		s = s.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;

		// Remove styles.
		if (bRemoveStyles)
			s = s.replace( /<(\w[^>]*) style="([^\"]*)"([^>]*)/gi, "<$1$3" ) ;

		// Remove style, meta and link tags
		s = s.replace( /<STYLE[^>]*>[\s\S]*?<\/STYLE[^>]*>/gi, '' ) ;
		s = s.replace( /<(?:META|LINK)[^>]*>\s*/gi, '' ) ;

		// Remove empty styles.
		s =  s.replace( /\s*style="\s*"/gi, '' ) ;

		s = s.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;

		s = s.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;

		// Remove Lang attributes
		s = s.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;

		s = s.replace( /<SPAN\s*>([\s\S]*?)<\/SPAN>/gi, '$1' ) ;

		s = s.replace( /<FONT\s*>([\s\S]*?)<\/FONT>/gi, '$1' ) ;

		// Remove XML elements and declarations
		s = s.replace(/<\\?\?xml[^>]*>/gi, '' ) ;

		// Remove w: tags with contents.
		s = s.replace( /<w:[^>]*>[\s\S]*?<\/w:[^>]*>/gi, '' ) ;

		// Remove Tags with XML namespace declarations: <o:p><\/o:p>
		s = s.replace(/<\/?\w+:[^>]*>/gi, '' ) ;

		// Remove comments [SF BUG-1481861].
		s = s.replace(/<\!--[\s\S]*?-->/g, '' ) ;

		s = s.replace( /<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;' ) ;

		s = s.replace( /<H\d>\s*<\/H\d>/gi, '' ) ;

		// Remove "display:none" tags.
		s = s.replace( /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none[\s\S]*?<\/\1>/ig, '' ) ;

		// Remove language tags
		s = s.replace( /<(\w[^>]*) language=([^ |>]*)([^>]*)/gi, "<$1$3") ;

		// Remove onmouseover and onmouseout events (from MS Word comments effect)
		s = s.replace( /<(\w[^>]*) onmouseover="([^\"]*)"([^>]*)/gi, "<$1$3") ;
		s = s.replace( /<(\w[^>]*) onmouseout="([^\"]*)"([^>]*)/gi, "<$1$3") ;

		if (bCleanWordKeepsStructure) {
			// The original <Hn> tag send from Word is something like this: <Hn style="margin-top:0px;margin-bottom:0px">
			s = s.replace( /<H(\d)([^>]*)>/gi, '<h$1>' ) ;

			// Word likes to insert extra <font> tags, when using MSIE. (Wierd).
			s = s.replace( /<(H\d)><FONT[^>]*>([\s\S]*?)<\/FONT><\/\1>/gi, '<$1>$2<\/$1>' );
			s = s.replace( /<(H\d)><EM>([\s\S]*?)<\/EM><\/\1>/gi, '<$1>$2<\/$1>' );
		} else {
			s = s.replace( /<H1([^>]*)>/gi, '<div$1><b><font size="6">' ) ;
			s = s.replace( /<H2([^>]*)>/gi, '<div$1><b><font size="5">' ) ;
			s = s.replace( /<H3([^>]*)>/gi, '<div$1><b><font size="4">' ) ;
			s = s.replace( /<H4([^>]*)>/gi, '<div$1><b><font size="3">' ) ;
			s = s.replace( /<H5([^>]*)>/gi, '<div$1><b><font size="2">' ) ;
			s = s.replace( /<H6([^>]*)>/gi, '<div$1><b><font size="1">' ) ;

			s = s.replace( /<\/H\d>/gi, '<\/font><\/b><\/div>' ) ;

			// Transform <P> to <DIV>
			var re = new RegExp( '(<P)([^>]*>[\\s\\S]*?)(<\/P>)', 'gi' ) ;	// Different because of a IE 5.0 error
			s = s.replace( re, '<div$2<\/div>' ) ;

			// Remove empty tags (three times, just to be sure).
			// This also removes any empty anchor
			s = s.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
			s = s.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
			s = s.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
		}

		return s;
	}
}

function lwrte_link() {
	//Mitchell: this function was heavily modified from the lwrte originial.

	var self = this;
	var panel = self.create_panel("Create link", 385);

	panel.append('\
<p><label>URL</label><input type="text" id="url" size="30" value=""><button id="view">Test</button></p>\
<div class="clear"></div>\
<p><label>Title</label><input type="text" id="title" size="30" value=""><label>Target</label><select id="target"><option value="">default</option><option value="_blank">new</option></select></p>\
<div class="clear"></div>\
<p class="submit"><button id="ok">Ok</button><button id="cancel">Cancel</button></p>'
).show();

	$('#cancel', panel).click( function() { panel.remove(); return false; } );

	var url = $('#url', panel);
	// var upload = $('#file', panel).upload( {
	// 	autoSubmit: true,
	// 	action: 'uploader.php',
	// 	onComplete: function(response) { 
	// 		if(response.length <= 0)
	// 			return;

	// 		response	= eval("(" + response + ")");

	// 		if(response.error && response.error.length > 0)
	// 			alert(response.error);
	// 		else
	// 			url.val((response.file && response.file.length > 0) ? response.file : '');
	// 	}
	// });

	$('#view', panel).click( function() {
		(url.val().length >0 ) ? window.open(url.val()) : alert("Enter URL to view");
		return false;
	}
	);

	$('#ok', panel).click( 
		function() {
			var url = $('#url', panel).val();
			var target = $('#target', panel).val();
			var title = $('#title', panel).val();

			if(self.get_selected_text().length <= 0) {
				alert('Please select the text you wish to link.');
				return false;
			}

			panel.remove(); 

			if(url.length <= 0)
				return false;

			self.editor_cmd('unlink');

			// we wanna well-formed linkage (<p>,<h1> and other block types can't be inside of link due to WC3)

			//var linkText = self.get_selected_text();			
			self.editor_cmd('createLink', url);

			//var tmp = $('<span></span>').append(self.get_selected_html());

			if(target.length > 0)
				$(self.iframe).contents().find('a[href="' + url +'"]').attr('target', target);

			if(title.length > 0)
				$(self.iframe).contents().find('a[href="' + url +'"]').attr('title', title);

			//$('a[href*="' + rte_tag + '"]', tmp).attr('href', url);
				
			//self.selection_replace_with(tmp.html());

			return false;
		}
	)
}
