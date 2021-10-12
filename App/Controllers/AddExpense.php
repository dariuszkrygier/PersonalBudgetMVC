<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Flash;
use \App\Models\User;
use \App\Auth;
use \App\Models\Tables;


/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
class AddExpense extends Authenticated
{

  
    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**
     * Show expense page
     *
     * @return void
     */
    public function newAction()
    {
		$payMethod = Expense::getPaymentMethods();
		$expensesCategory = Expense::getAllFromCategories();
		
          View::renderTemplate('Expense/new.html', [
            'categories' => $expensesCategory,
			'methods' => $payMethod
			
			
        ]);;
    }

    /**
     * Show an item
     *
     * @return void
     */
    public function showAction()
    {
        echo "show action";
    }
	
	  public function createAction()
    {
		$expense = new Expense($_POST);
		
        

		if ($expense->saveExpense())
		{
			Flash::addMessage('Dodano wydatek');
			
			$this->redirect('/add-expense');
		}

        else 
		{
			View::renderTemplate('Expense/new.html', [
                'categories' => Expense::getAllFromCategories(),
				'methods' => Expense::getPaymentMethods(),
				'expense' => $expense,
                
				
       
            ]);
		}
    }
	
	public function getAmountOfExpenseThisMonthAction() {
		$date_start = date('Y-m').'-01';
		$date_end = date('Y-m-d');
		$this->expense = Expense::getExpenseAssignedToUser(Auth::getUser(), $date_start, $date_end);
		echo json_encode($this->expense);		
	}
	
	public function getLimitOfExpenseAction() {		
		$this->expensesCategory = Tables::getExpenseCategory(Auth::getUser());
		echo json_encode($this->expensesCategory);
	}
}
