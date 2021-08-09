<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;
use \App\Flash;
use \App\Models\User;
use \App\Auth;


/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
class ShowBalance extends Authenticated
{

  

     private function showTemplate($startDate, $endDate)
    {
     
        $a['incomes'] = Balance::getIncomes($startDate, $endDate);
        $a['expenses'] = Balance::getExpenses($startDate, $endDate);
        $a['totalIncomesAmount'] = $this->calcSum($a['incomes']);
        $a['totalExpensesAmount'] = $this->calcSum($a['expenses']);;
		$a['periodBalanceMsg'] = "Bilans za okres od " . $startDate . " do " . $endDate;
		$a['chartElements'] = $this->getChartElements($a['expenses']);
        

        View::renderTemplate('Balance/index.html', $a);
    }

    public function showCustomPeriodAction()
    {

			
				 $startDate = $_POST['dateFrom'];
				 $endDate = $_POST['dateTill'];
				
				 $this->showTemplate($startDate, $endDate);
	
	
			
    }

    public function showPreviousMonthAction()
    {
        
       $startDate = date('Y-m-01',strtotime('last month'));
      $endDate = date('Y-m-t',strtotime('last month'));
	

        $this->showTemplate($startDate, $endDate);
    }

    public function showCurrentMonthAction()
    {
      
        $startDate = date('Y-m-01');
        $endDate = date("Y-m-d");

        $this->showTemplate($startDate, $endDate);
    }

    public function showCurrentYearAction()
    {
     
        $startDate =  date('Y-01-01');
        $endDate = date("Y-m-d");

        $this->showTemplate($startDate, $endDate);
    }

    private function calcSum($sqlArray)
    {
        $sum = 0.0;
        foreach ($sqlArray as $values) {
            $sum += floatval($values['Sum_of_amounts']);
        }
        return $sum;
    }

    private function getChartElements($expenses)
    {
        $chartElements = array();
        foreach ($expenses as $expense) {
            $chartElements[$expense['Category']] = $expense['Sum_of_amounts'];
        }
        return $chartElements;
    }
}
