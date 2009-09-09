function expCon() {

	// we get all elements with a class set to 'expand'.
	var elements = $$('.expand');
	// size() uses the array's native length property
	// if the array has more than 0 items, we do the loop
	if (elements.size() > 0) {
		for (var index = 0, length = elements.size(); index < length; ++index)	{
			autoExpandContract(elements[index]);
		}
	}


	function getStyleFromCSS(el, style) {

		// get styles from our CSS
		var value = $(el).getStyle(style);

		// if styles are not defined in our CSS
		if(!value) {

			// for other browsers. Actually this equals 'window'. Use that if Opera fails on you.
			if(document.defaultView) {
				//	getComputedStyle() requires two parameters.
				//		The first is a reference to the element.
				//		The second is either the name of a pseudo element ('before', 'after', 'first-line'),
				//		or null just for the element itself
				// getPropertyValue()
				//		returns the value of the property if it has been explicitly set for this declaration block.
				// 		Returns an empty string if the property has not been set.
				value = document.defaultView.getComputedStyle(el, null).getPropertyValue(style);

		   // for IE
			} else if(el.currentStyle) {
				//	As well as being an actual usable value,
				//	the returned value for several styles may be their
				// 	default values, such as 'auto', 'normal', 'inherit',...
				value = el.currentStyle[style];
				if (value.substring(value.length-2,value.length) == "px") {
					alert('style' + style);
					value = value+'px';
				}
			}
		}

		// if the value we got from our element has more than 0 characters...
		if(value.length > 0){

			// if the value returned has the word px in it, we check for the letter x
			if (value.charAt(value.length-1) == "x") {

				// substring() get all characters from the 0th place to the total String length - 2
				// parseInt() Only the first number in the string is returned! Note: Leading and trailing spaces are allowed.
				//  If the first character cannot be converted to a number, parseInt() returns NaN.
				value = parseInt(value.substring(0,value.length-2))
			}
		}
		return value;

	} // end getStyleFromCSS()

	function autoExpandContract(el) {

		// get height of the element
		var __heightFromElement = el.offsetHeight;

		// get height of the element set in the styles
		var __heightFromCSS = parseInt(getStyleFromCSS(el, 'height'));

		// If the height style is set in the CSS and it's bigger than 0px, resize the element to that height
		if (__heightFromCSS > 0) {
			__heightFromElement = __heightFromCSS;
		}

		// adjust the textarea, for all good browsers: overflow:hidden to lose the scrollbars,
		// for IE use overflowX:auto to let that browser decide whether to show scrollbars
		$(el).setStyle({overflow: 'hidden', overflowX: 'auto'});
		
		//JM put the following in to make this work in IE7.  The textarea was hidden so you couldn't focus on it initially.
		$('description').style.display = 'block';

		// set the width and height to the correct values
		el.style.width = getStyleFromCSS(el, 'width')+'px';
		el.style.height = getStyleFromCSS(el, 'height')+'px';


		// create a new element that will be used to track the dimensions
		var dummy_id = Math.floor(Math.random()*99999) + '_dummy';
		var div = document.createElement('div');

		// we use setAttribute(attr, value) here instead of writeAttribute which is a prototype method (IE6 = fail)
		div.setAttribute('id',dummy_id);
		document.body.appendChild(div);
		var dummy = $(dummy_id);

		// match the new elements style to the el
		dummy.style.fontFamily = getStyleFromCSS(el, 'font-family');
		dummy.style.fontWeight = getStyleFromCSS(el, 'font-weight');
		dummy.style.fontSize = getStyleFromCSS(el, 'font-size')+'px';
         //fornicate with IE (not a check for IE7 though, just IE6)
        if (navigator.userAgent.indexOf('MSIE') !=-1) {
            dummy.style.width = getStyleFromCSS(el, 'width');

        // Play  nice with the good browsers
        } else {
            dummy.style.width = getStyleFromCSS(el, 'width')+'px';
        }
		dummy.style.padding = getStyleFromCSS(el, 'padding');
		dummy.style.margin = getStyleFromCSS(el, 'margin');
		dummy.style.overflowX = 'auto';
		// hide the created div away
		dummy.style.position = 'absolute';
		dummy.style.top = '0px';
		dummy.style.left = '-9999px';
		dummy.innerHTML = '&nbsp;42';
		

		var __lineHeight = dummy.offsetHeight;

		var checkExpandContract = function(){
			// place text inside the element in a new var called html
			var html = el.value;
			html = html.replace(/\n/g, '<br />');
			if (dummy.innerHTML != html) {
				dummy.innerHTML = html;
				var __dummyHeight = dummy.offsetHeight;
				var __elHeight = el.offsetHeight;

				// if the height from our element is not the same as the height from our dummy we just made...
				// example: 150px != 250px
				if (__elHeight != __dummyHeight) {

					// example: 250px > 200px
					if (__dummyHeight > __heightFromElement) {

						// example: height = (250+0)px
						el.style.height = (__dummyHeight+__lineHeight) + 'px';
//						el.style.height = (__dummyHeight-__lineHeight) + 'px';

					} else {
						el.style.height = __heightFromElement+'px';
//						el.style.height = (__dummyHeight+__lineHeight) + 'px';

					}
				}
			}
		}

		var expandElement = function()	{
			interval = window.setInterval(function() {checkExpandContract()}, 250);
		}

		var contractElement = function() {
			clearInterval(interval);
		}

		// Put eventListeners to our elements
		$(el).observe('focus', expandElement);
		$(el).observe('blur', contractElement);
		checkExpandContract();
	} // end autoExpandContract()
};
