function elPrint(el,title)
{
	var a = window. open('','','scrollbars=yes,width=600,height=400');
	a.document.open("text/html");
	a.document.write('<html><head><link rel="stylesheet" href="html/css/print.css" /></head>');
	a.document.write('<body><div class="print_header"><img src="html/images/logo_sm.png"></div>');
	a.document.write('<h3>' + title + '</h3>');
	var d = new Date();
	a.document.write('<p><small>Printed: ' +  d + ' </small></p>');
	a.document.write(el.html());
	a.document.write('</body></html>');
	a.document.close();
	a.print();
}