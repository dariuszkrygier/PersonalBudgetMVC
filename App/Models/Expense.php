<?php

namespace App\Models;

use PDO;


/**
 * User model
 *
 * PHP version 7.0
 */
class Expense extends \Core\Model
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
	
	/**
     * Get categories of expenses from database and display them as options
	 * in form of adding new income
     *
     * @return array with categories
     */	
	public static function getExpensesCategories()
	{
		if (isset($_SESSION['user_id']))
		{
			$user_id = $_SESSION['user_id'];
		
			$sql = "SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :user_id";

			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

			$stmt->execute();
			
			return $stmt->fetchAll();
		}
	}
	
	 public function getExpenseId()
    {
		
	
		$user_id = $_SESSION['user_id'];
		$expense_category = $this->category;
		
		$sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $expense_category, PDO::PARAM_STR);

        $stmt->execute();
		
		return $stmt->fetchColumn();
		
		
    
		
    } 
    
	
	
	public function saveExpense()
	{
		$this->validate();
		
		$expense_category_id = $this->getExpenseId();
		$payment_method_id = $this->getPaymentMethodId();
		
		if (empty($this->errors)) 
		{
			$sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)  
					VALUES (:user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';

			$db = static::getDB();
			$stmt = $db->prepare($sql);

			$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt->bindValue(':expense_category_assigned_to_user_id', $expense_category_id, PDO::PARAM_INT);
			$stmt->bindValue(':payment_method_assigned_to_user_id', $payment_method_id, PDO::PARAM_INT);
			$stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
			$stmt->bindValue(':date_of_expense', $this->dates, PDO::PARAM_STR);
			$stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);

			return $stmt->execute();
		}
		
		return false;
		
	}
	
	 public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {

        //Check if value is set
        if($this->amount == '') {
            $this->errors[] = 'Podaj kwotę'; 
            
            //Check if value is numeric
        } else if(!is_numeric($this->amount)) {
            $this->errors[] = 'Wartość nie jest liczbą'; 

            //Check if value is greater than 0
        } else  if($this->amount <= 0) {
            $this->errors[] = 'Kwota musi być większa od 0';
        }
		
		   //Check if payment method is set
        if(!isset($this->payment)) {
            $this->errors[] = "Metoda płatności jest wymagana";
            }
      
		
		

        //Check if category is set
        if(!isset($this->category)) {
            $this->errors[] = 'Kategoria wydatku jest wymagana';
            }
      
        //If comment exist
        if($this->comment != '') {
            //Validate comment length
            if(strlen($this->comment) > 100) {
                $this->errors[] = 'Komentarz jest za długi';
            }
        }

        //Validate date
        if(!$this->validateDate($this->dates)) {
            $this->errors[] = 'Data nieprawidłowa';
        }

    }
	
		/**
     * Get categories of payment methods from database and display them as options
	 * in form of adding new expense
     *
     * @return array with categories
     */	
	public static function getPaymentMethods()
	{
		if (isset($_SESSION['user_id']))
		{
			$user_id = $_SESSION['user_id'];
		
			$sql = "SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :user_id";

			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

			$stmt->execute();
			
			return $stmt->fetchAll();
		}
	}
	
	/**
     * Get the payment method id from database
     *
     * @return integer with id of payment method
     */
	public function getPaymentMethodId()
	{
		$user_id = $_SESSION['user_id'];
		$payment_method = isset($this->payment) ? $this->payment : false;
		
		$sql = 'SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :id AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $payment_method, PDO::PARAM_STR);
		
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
		
		return $stmt->fetchColumn();
	}
	

   
}


