//Handles routing via hashes

function router()
{

	if (!window.location.hash)
		{return false;}
	else
	{

		var navData = [];

		$.each(window.location.hash.replace("#", "").split("/"), function (index,value) {
			navData.push(value);
		});
		console.log(navData);

		switch (navData[0])
		{

			case 'cases': //e.g. Cases.php#cases/1175
			callCaseWindow(navData[1]);

			$('li#item' + navData[2]).livequery(function(){$(this).trigger('click');});
			break;

			case 'journals':

			break;

		}
	}

}