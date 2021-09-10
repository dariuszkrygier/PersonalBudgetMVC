<?php

namespace App\Models;
use PDO;
use App\Token;
use App\Auth;

class Profile extends \Core\Model {
	
	public $errors = [];
	
	 public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
	
	public static function updatePassword($data, $user) {

		$password = $data['password'];
		$password_hash = password_hash($password, PASSWORD_DEFAULT);
		
		$profile = new Profile();
		$profile->validate();
		
		if (empty($profile->errors)) {
			$sql = 'UPDATE users SET password_hash=:password_hash WHERE id=:id';
						
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':id', $user->id, PDO::PARAM_STR);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
						
			return $stmt->execute();
		}
		return false;
	}
	
	public function validate(){

		if(isset($_POST["password"])) {
			//walidacja długości hasła min 6 znaków
			if(strlen($_POST["password"]) < 6) {
				$this->errors[] = 'Hasło musi składać się co najmniej z 6 znaków';
			}
			
			//walidacja hasła - minimum jedna litera
			if (preg_match('/.*[a-z]+.*/i', $_POST["password"]) == 0) {
				$this->errors[] = 'Hasło musi zawierać co najmniej 1 literę';
			}
			
			//walidacja hasła - minimum jedna cyfra
			if (preg_match('/.*\d+.*/i', $_POST["password"]) == 0) {
				$this->errors[] = 'Hasło musi zawierać co najmniej 1 liczbę';
			}
		}			
	}
}