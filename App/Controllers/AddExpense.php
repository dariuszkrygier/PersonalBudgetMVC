<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Flash;
use \App\Models\User;
use \App\Auth;


/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
class AddExpense extends Authenticated
{

  

    /**
     * Show expense page
     *
     * @return void
     */
    public function newAction()
    {
          View::renderTemplate('Expense/new.html', [
            'categories' => Expense::getExpensesCategories(),
			'methods' => Expense::getPaymentMethods()
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
                'categories' => Expense::getExpensesCategories(),
				'methods' => Expense::getPaymentMethods(),
				'expense' => $expense,
                
				
       
            ]);
		}
    }
}
