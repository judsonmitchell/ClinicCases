function elPrint(el,title) {
	var a = window. open('','','scrollbars=yes,width=600,height=400');
	var d = new Date();
    var doPrint = function() {
        a.print();
        a.close();
    };
	a.document.open('text/html');
	a.document.write('<html><head><title>' + title + '</title><link rel="stylesheet" href="html/css/print_layout.css" /></head>');
	a.document.write('<body><div class="print_header"><img src="html/images/logo_sm.png"></div>');
	a.document.write('<h3>' + title + '</h3>');
	a.document.write('<p><small>Printed: ' +  d + ' </small></p>');
	a.document.write(el.html());
	a.document.write('</body></html>');
	a.document.close();
    setTimeout(doPrint, 500);
}
