<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\User;
use \App\Models\Income;
use \App\Models\Expense;


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
        View::renderTemplate('Settings/index.html', [
            'incomeCategories' => Income::getIncomesCategories(),
            'expenseCategories' => Expense::getExpensesCategories(),
            'paymentCategories' => Expense::getPaymentMethods(),
            'user' => $this->user
        ]);
    }
	
}