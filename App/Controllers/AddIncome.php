<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Flash;
use \App\Models\User;
use \App\Auth;


/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
class AddIncome extends Authenticated
{

  

    /**
     * Show income page
     *
     * @return void
     */
    public function newAction()
    {
          View::renderTemplate('Income/new.html', [
            'categories' => Income::getIncomesCategories()
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
		$income = new Income($_POST);

		if ($income->saveIncome())
		{
			Flash::addMessage('Dodano przychód');
			
			$this->redirect('/add-income');
		}

        else 
		{
			View::renderTemplate('Income/new.html', [
                'categories' => Income::getIncomesCategories(),
                'income' => $income
            ]);
		}
    }
}
