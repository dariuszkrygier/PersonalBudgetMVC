<?php

namespace App\Models;

use PDO;


/**
 * User model
 *
 * PHP version 7.0
 */
class Income extends \Core\Model
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
     * Get categories of incomes from database and display them as options
	 * in form of adding new income
     *
     * @return array with categories
     */	
	public static function getIncomesCategories()
	{
		if (isset($_SESSION['user_id']))
		{
			$user_id = $_SESSION['user_id'];
		
			$sql = "SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :user_id";

			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

			$stmt->execute();
			
			return $stmt->fetchAll();
		}
	}
	
	 public function getIncomeId()
    {
		
	
		$user_id = $_SESSION['user_id'];
		$income_category = isset($this->category) ? $this->category: false;
		
		
		$sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $income_category, PDO::PARAM_STR);

        $stmt->execute();
		
		return $stmt->fetchColumn();
		
		
    
		
    } 
    
	
	
	public function saveIncome()
	{
		$this->validate();
		
		$income_category_id = $this->getIncomeId();
		
		if (empty($this->errors)) 
		{
			$sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) 
					VALUES (:user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)';

			$db = static::getDB();
			$stmt = $db->prepare($sql);

			$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt->bindValue(':income_category_assigned_to_user_id', $income_category_id, PDO::PARAM_INT);
			$stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
			$stmt->bindValue(':date_of_income', $this->dates, PDO::PARAM_STR);
			$stmt->bindValue(':income_comment', $this->comment, PDO::PARAM_STR);

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

        //Check if category is set
        if(!isset($this->category)) {
            $this->errors[] = 'Kategoria jest wymagana';
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
	

   
}


