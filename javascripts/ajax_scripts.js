
var Targets = {};
var Targets2 = {}
function createTargets(status,target)
{
Targets.status = status;
Targets.target = target;
}




function sendDataPost (url,frm) {

	var pars = Form.serialize(frm);
	var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onLoading: showLoad(status), onComplete: showResponse} );
}



function sendDataGet(url)
{
	var myAjax = new Ajax.Request( url, {method: 'get', onLoading: showLoad(status), onComplete: showResponse} );

}



function sendDataGetAndStripe(url)
{
	var myAjax = new Ajax.Request( url, {method: 'get', onLoading: showLoad(status), onComplete: showResponseStripe} );

}

function sendDataGetAndStripe2(url)
{
	var myAjax = new Ajax.Request( url, {method: 'get', onLoading: showLoad(status), onComplete: showResponseStripe2} );

}

function sendDataGetAndStripeNoStatus(url)
{
	var myAjax = new Ajax.Request( url, {method: 'get',  onComplete: showResponseStripe} );

}

function sendDataGetAndStripeNoStatus2(url)
{
	var myAjax = new Ajax.Request( url, {method: 'get',  onComplete: showResponseStripe2} );

}

function sendDataGetDoNothing(url)
{
	var myAjax = new Ajax.Request( url, {method: 'get'} );

}


function sendDataPostAndStripeNoStatus(url,frm)
{
	var pars = Form.serialize(frm);
	var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: showResponseStripe} );
}

function sendDataPostAndStripeNoStatus2(url,frm)
{
	var pars = Form.serialize(frm);
	var myAjax = new Ajax.Request( url, {method: 'post', parameters: pars, onComplete: showResponseStripe2} );
}

function showLoad (status) {
$(Targets.status).style.display = 'block';
	$(Targets.status).innerHTML = '<img src=images/wait.gif border=0>';

}




function showResponse (originalRequest) {
	var newData = originalRequest.responseText;


	$(Targets.target).innerHTML = newData;
	
}

function showResponseStripe (originalRequest) {
	var newData = originalRequest.responseText;


	$(Targets.target).innerHTML = newData;
		
stripe('display_cases','#fff','#e0e0e0');
}

function showResponseStripe2 (originalRequest) {
	var newData = originalRequest.responseText;


	$(Targets.target).innerHTML = newData;
		
stripe('display_time','#fff','#e0e0e0');
}


function sethtml(div,content)
{
    var search = content;
    var script;
         
    while( script = search.match(/(<script[^>]+javascript[^>]+>\s*(<!--)?)/i))
    {
      search = search.substr(search.indexOf(RegExp.$1) + RegExp.$1.length);
      
      if (!(endscript = search.match(/((-->)?\s*<\/script>)/))) break;
      
      block = search.substr(0, search.indexOf(RegExp.$1));
      search = search.substring(block.length + RegExp.$1.length);
      
      var oScript = document.createElement('script');
      oScript.text = block;
      document.getElementsByTagName("head").item(0).appendChild(oScript);
    }
   
    document.getElementById(div).innerHTML=content;
} 


function updater(url,target2)
{
	Targets2.target2 = target2;
	var myAjax = new Ajax.Request( url, {method: 'get', onComplete: showResponse2} );

	
	
}

function showResponse2 (originalRequest) {
	var newData = originalRequest.responseText;


	$(Targets2.target2).innerHTML = newData;
	
}

