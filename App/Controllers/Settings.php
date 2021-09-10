<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\User;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\Tables;
use \App\Flash;
use \App\Models\Profile;



/**
 * Settings controller
 *
 * PHP version 7.0
 */
class Settings extends Authenticated
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
     * Show settings
     *
     * @return void
     */
   public function indexAction() {
	   
	    $payMethod = Tables::getPayMethods(Auth::getUser());
		$expensesCategory = Tables::getExpenseCategory(Auth::getUser());		
		$incomesCategory = Tables::getIncomeCategory(Auth::getUser());
        View::renderTemplate('Settings/index.html', [
            'incomeCategories' => $incomesCategory,
            'expenseCategories' => $expensesCategory,
            'paymentCategories' => $payMethod,
            'user' => $this->user
        ]);
    }
	
	//INCOMES
	public function updateIncomesCategoryAction() {
		
		$incomesCategory = Tables::getIncomeCategory(Auth::getUser());			
		if(Tables::updateIncomesCategory($_POST, $incomesCategory, Auth::getUser())) {		
			Flash::addMessage('Zmieniono nazwę');
		}
		else {
			Flash::addMessage('Taka nazwa już istnieje, wprowadź inną nazwę', Flash::WARNING);
		}
		
		$this->index();
	}
	
	public function deleteIncomesCategoryAction() {
		
		if(Tables::deleteIncomesCategory($_POST, Auth::getUser())) {		
			Flash::addMessage('Usunięto wskazaną kategorię przychodów');
		}
		
		$this->index();
	}

	public function addIncomesCategoryAction() {
		
		$incomesCategory = Tables::getIncomeCategory(Auth::getUser());	
		if(Tables::addIncomesCategory($_POST, $incomesCategory, Auth::getUser())) {		
			Flash::addMessage('Dodano nową kategorię przychodów');
		}
		else {
			Flash::addMessage('Taka nazwa już istnieje, wprowadź inną nazwę', Flash::WARNING);
		}	
		
		$this->index();
	}
	
	//EXPENSES
	public function deleteLimitExpensesCategory() {
							
		if(Tables::deleteLimitExpensesCategory($_POST, Auth::getUser())) {		
			Flash::addMessage('Usunięto wskazany limit');
		}
		
		$this->index();
	}
	
	public function limitExpensesCategoryAction() {
					
		if(Tables::limitExpensesCategory($_POST, Auth::getUser())) {		
			Flash::addMessage('Dodano limit');
		}
	
		$this->index();
	}
	
	public function updateExpensesCategoryAction() {
		
		$expensesCategory = Tables::getExpenseCategory(Auth::getUser());			
		if(Tables::updateExpensesCategory($_POST, $expensesCategory, Auth::getUser())) {		
			Flash::addMessage('Zmieniono nazwę');
		}
		else {
			Flash::addMessage('Taka nazwa już istnieje, wprowadź inną nazwę', Flash::WARNING);
		}
	
		$this->index();
	}
	
	public function deleteExpensesCategoryAction() {
		
		if(Tables::deleteExpensesCategory($_POST, Auth::getUser())) {		
			Flash::addMessage('Usunięto wskazaną kategorię wydatków');
		}
		
		$this->index();
	}
	
		public function addExpensesCategoryAction() {
		
		$expensesCategory = Tables::getExpenseCategory(Auth::getUser());	
		if(Tables::addExpensesCategory($_POST, $expensesCategory, Auth::getUser())) {		
			Flash::addMessage('Dodano nową kategorię wydatków');
		}
		else {
			Flash::addMessage('Taka nazwa już istnieje, wprowadź inną nazwę', Flash::WARNING);
		}	
		
		$this->index();
	}
	
		//PAY METHODS
	public function updatePaymentMethodAction() {
		
		$payMethod = Tables::getPayMethods(Auth::getUser());			
		if(Tables::updatePayMethods($_POST, $payMethod, Auth::getUser())) {		
			Flash::addMessage('Zmieniono nazwę');
		}
		else {
			Flash::addMessage('Taka nazwa już istnieje, wprowadź inną nazwę', Flash::WARNING);
		}
		
		$this->index();
	}

	public function deletePaymentMethodAction() {
		
		if(Tables::deletePayMethods($_POST, Auth::getUser())) {		
			Flash::addMessage('Usunięto wskazaną metodę płatności');
		}
		
		$this->index();
	}

	public function addPaymentCategoryAction() {
		
		$payMethod = Tables::getPayMethods(Auth::getUser());	
		if(Tables::addPayMethods($_POST, $payMethod, Auth::getUser())) {		
			Flash::addMessage('Dodano nową metodę płatności');
		}
		else {
			Flash::addMessage('Taka nazwa już istnieje, wprowadź inną nazwę', Flash::WARNING);
		}	
		
		$this->index();
	}
	
		public function changeNameAction() {
		 if ($this->user->updateName($_POST)) {		
			Flash::addMessage('Zmieniono imię');
		}
		
		$this->index();		
	}
	
	public function updatePasswordAction() {
		if(Profile::updatePassword($_POST, Auth::getUser())) {		
			Flash::addMessage('Zmieniono hasło');
		}
		else {
			Flash::addMessage('Nie udało się zmienić hasła! Hasło powinno składać się co najmniej z 6 znaków w tym chociaż 1 litera i 1 liczba.', Flash::WARNING);
		}	
	
		$this->index();		
	}
	
	  /**
     * Delete account
     *
     * @return void
     */
    public function deleteAccountAction() {
        
        if(User::deleteAccount()) {
            Auth::logout();
			Flash::addMessage('Konto zotało usunięte!', Flash::WARNING);
           View::renderTemplate('Home/index.html');
        }
        else {
            Flash::AddMessage('Konto nie zotało usunięte!');
            $this->redirect('/settings');
        }
    }

	

	
}