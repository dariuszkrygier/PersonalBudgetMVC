<?php

namespace App\Models;

use PDO;


/**
 * User model
 *
 * PHP version 7.0
 */
class Balance extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
	
	  public function checkCustomPeriod() {
		  
			$startDate = $_POST['dateFrom'];
            $endDate = $_POST['dateTill'];
		
		   if($startDate > $endDate) {
            $this->errors[] = 'Początkowa data musi być mniejsza od daty końcowej'; 
		}
			
		   if($startDate > date("Y-m-d")) {
            $this->errors[] = 'Początkowa data nie może wybiegać w przyszłość'; 
    
		}
		   }
	 public function validateDate($date, $format = 'Y-m-d'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    } 
		   
	
	
	public static function getIncomes($startDate, $endDate)
    {
        $id = $_SESSION['user_id'];
        $sql = "
        SELECT
            i_userid.name AS 'Category',
            SUM(i.amount) AS 'Sum_of_amounts'
        FROM
            incomes AS i,
            incomes_category_assigned_to_users AS i_userid
        WHERE
            i_userid.id = i.income_category_assigned_to_user_id AND
            i.date_of_income >= '$startDate' AND
            i.date_of_income <= '$endDate' AND
            i.user_id='$id'
            GROUP BY i.income_category_assigned_to_user_id
        ";

        $db = static::getDB();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
	
	 public static function getExpenses($startDate, $endDate)
    {
        $id = $_SESSION['user_id'];
        $sql = "
        SELECT
            e_userid.name AS 'Category',
            SUM(e.amount) AS 'Sum_of_amounts'
        FROM
            expenses AS e,
            expenses_category_assigned_to_users AS e_userid
        WHERE
            e_userid.id = e.expense_category_assigned_to_user_id AND
            e.date_of_expense >= '$startDate' AND
            e.date_of_expense <= '$endDate' AND
            e.user_id='$id'
            GROUP BY e.expense_category_assigned_to_user_id
        ";

        $db = static::getDB();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

	
   
}


