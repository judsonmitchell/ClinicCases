function printDiv(theId)
    {
    var a = window. open('','','scrollbars=yes,width=600,height=400');

    a.document.open("text/html");
    a.document.write('<html><head><link rel="stylesheet" href="print.css" /><style type="text/css">#frame{background-image:none;background-color:#FFFFFF;}div{overflow:visible}</style></head><body style="padding-left:20px;background-image:none;background-color:#FFFFFF;">');
    a.document.write(document.getElementById(theId).innerHTML);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
    }

    

function printDivComplex(theId)
    {
    var a = window. open('','','scrollbars=yes,width=600,height=400');

    a.document.open("text/html");
    a.document.write('<html><head><link rel="stylesheet" href="print.css" /><style type="text/css">#frame{background-image:none;background-color:#FFFFFF;}div{overflow:visible}</style></head><body style="padding-left:20px;background-image:none;background-color:#FFFFFF;">');
    a.document.write(document.getElementById('print_name').innerHTML);   
    a.document.write('<br>');
    a.document.write(document.getElementById('print_title').innerHTML);
    a.document.write(document.getElementById(theId).innerHTML);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
    }

function printCaseNotes(theId)
    {
        var a = window. open('','','scrollbars=yes,width=600,height=400');
        var txt = document.getElementById(theId).innerHTML;
        var dummy = txt.replace(/<\/td>/g,"\\n")
        var strip = dummy.stripTags();
        var printTxt = strip.replace(/\\n/g,"<br><br>");
             
        a.document.open("text/html");
        a.document.write('<html><head><title>Print Case Note</title><link rel="stylesheet" href="print.css" /><style type="text/css">#frame{background-image:none;background-color:#FFFFFF;}</style></head><body style="padding-left:20px;background-image:none;background-color:#FFFFFF;text-align:left">');
        a.document.write(document.getElementById('print_name').innerHTML);   
        a.document.write('<br>');
        a.document.write(document.getElementById('print_title').innerHTML);
        a.document.write('<br>');
        a.document.write(printTxt);
        a.document.write('</body></html>');
        a.document.close();
        a.print();
    }

function printPost(theId)
    {
    var a = window. open('','','scrollbars=yes,width=600,height=400');

    a.document.open("text/html");
    a.document.write('<html><head><title>Print Board Post</title><link rel="stylesheet" href="print.css" /><style type="text/css">.title{font-size:14pt; color:blue;font-family:garamond, times, serif;text-decoration:none;margin-bottom:20px;}img{display:block;}</style></head><body style="padding-left:20px;background-image:none;background-color:#FFFFFF;">');
    a.document.write(document.getElementById(theId).innerHTML);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
    }


















































































































































































