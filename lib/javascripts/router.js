//Handles routing via hashes

function router()
{

	if (!window.location.hash)
		{console.log('no hash');return false;}
	else
	{

		var formData = [];
		$.each(window.location.hash.replace("#", "").split("/"), function (index,value) {
			formData.push(value);
		});
		console.log(formData);

		switch (formData[0])
		{

			case 'cases':
			callCaseWindow(formData[1]);
			break;

			case 'journals':

			break;

		}
	}

}


//console.log(window.location.hash);