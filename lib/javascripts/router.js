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

		switch (navData[0])
		{

			case 'cases': //e.g. Cases.php#cases/1175
				callCaseWindow(navData[1]);

				$('li#item' + navData[2]).livequery(function(){$(this).trigger('click');});  //e.g. Cases.php#cases/1175/3 - simulates click to the correct tab
			break;

			case 'journals': //e.g. Journals.php#journals/33989
				callJournal(navData[1]);

			break;

		}
	}

}