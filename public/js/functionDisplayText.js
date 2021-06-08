// Przeglądaj bilans: napis
function displayText()
{
	
	var differenceInBalanceString;
	var differenceInBalance;
	var text;
	
	differenceInBalanceString = document.getElementById("differenceNumber").innerHTML;
	differenceInBalance = Number(differenceInBalanceString);
	
	
	if (differenceInBalance > 0)
	{
		text = 'Gratulacje. Świetnie zarządzasz finasami!';
	}
	else if (differenceInBalance < 0)
	{
		text = 'Uważaj, wpadasz w długi!';
	}
	else
	{
		text = 'Jest zero.';
	}
	
	document.getElementById("differenceText").innerHTML = text;
}
