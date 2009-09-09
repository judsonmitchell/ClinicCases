function printDiv(theId)
    {
    var a = window. open('','','scrollbars=yes,width=600,height=400');

    a.document.open("text/html");
    a.document.write('<html><head><link rel="stylesheet" href="print.css" /><style type="text/css">#frame{background-image:none;background-color:#FFFFFF;}</style></head><body style="padding-left:20px;background-image:none;background-color:#FFFFFF;">');
    a.document.write(document.getElementById(theId).innerHTML);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
    }

function printDivComplex(theId)
    {
    var a = window. open('','','scrollbars=yes,width=600,height=400');

    a.document.open("text/html");
    a.document.write('<html><head><link rel="stylesheet" href="print.css" /><style type="text/css">#frame{background-image:none;background-color:#FFFFFF;}</style></head><body style="padding-left:20px;background-image:none;background-color:#FFFFFF;">');
    a.document.write(document.getElementById('print_name').innerHTML);   
    a.document.write('<br>');
    a.document.write(document.getElementById('print_title').innerHTML);
    a.document.write(document.getElementById(theId).innerHTML);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
    }





















































































































































































