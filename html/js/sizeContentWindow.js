//Sizes the content window (#content) so that the bottom is always visible.

//Get height of the viewport
var viewportHeight = $(window).height();

//Determine the percentage height.

var adjustedHeight = Math.round(viewportHeight * .82);

//set the css after load
$(document).ready(function(){
	$("#content").css({'height':adjustedHeight + 'px'});
	
})

//handle if user resizes window

$(window).resize(function() {
	
	var newViewportHeight = $(window).height();
	var newAdjustedHeight = Math.round(newViewportHeight * .82);
	$("#content").css({'height':newAdjustedHeight + 'px'});


});
