const getLimit = document.getElementById('category');

		
	
function getData()  {
	let amount = document.getElementById('amount').value;
	let category = document.getElementById('category').value;
	let dateString = document.getElementById('theDate').value;
	let date = new Date(dateString);
	
	let n =  new Date();
	let currentMonth = n => n.getMonth() + 1;
	let nextMonth = n => n.getMonth() + 2;
	let currentYear = n => n.getFullYear();
	
	let cm = currentMonth(n);
	let nm;
	let cy;
	let ny;
	if(cm == 12) {	
		nm = '01';
		cy = currentYear(n);
		ny = cy + 1;
	}
	else {
		nm = nextMonth(n);
		cy = currentYear(n);
		ny = currentYear(n);
	}

	let currentMonthStart = new Date(cy + '-' + cm + '-01');
	let currentMonthEnd = new Date(ny + '-' + nm + '-01');
		
		let categoryName;
		let limit;
		
	fetch('/add-expense/get-limit-of-expense').then(response => {
		return response.json();
	}).then(responseData =>{
		console.log(responseData)
		
				
			// Create a variable to store HTML
			let div0 = '<div class="col-12 text-light" style="font-size:18px; font-weight: bold;">Zestawienie dla bieżącego miesiąca</div>';
			let div1 = '<div class="col-8 text-light">Limit dla kategorii:</div>';
				
			// Loop through each data and add a table row
			responseData.forEach(expense => {
				
				if((`${expense.id}` == category) && (`${expense.expenseLimit}` > '0')) {
					div1 += `<div style="text-align:right;" class="col-4 text-light">${expense.expenseLimit} zł</div>`;
					categoryName = `${expense.name}`;
					limit = `${expense.expenseLimit}`;
				
				}
			});
		
				if(limit > 0) {
					document.getElementById("limit0").innerHTML = div0;
					document.getElementById("limit1").innerHTML = div1;
					document.getElementById("limit2").innerHTML = div2;
					document.getElementById("limit3").innerHTML = div3;
					document.getElementById("limit4").innerHTML = div4;
				}
				else {
					document.getElementById("limit0").innerHTML = '';
					document.getElementById("limit1").innerHTML = '';
					document.getElementById("limit2").innerHTML = '';
					document.getElementById("limit3").innerHTML = '';
					document.getElementById("limit4").innerHTML = '';					
				}
	});
	
	fetch('/add-expense/get-amount-of-expense-this-month').then(response => {
		return response.json();
	}).then(responseSumData =>{
		console.log(responseSumData)
		});
};




		
getLimit.addEventListener('change', getData );

