<?php 
	/**
	* 
	*/
	class Users
	{
		protected $id;
		protected $name;
		protected $mobile;
		protected $email;
		protected $pass;
		protected $activated;
		protected $token;
		protected $createdOn;
		public $conn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setMobile($mobile) { $this->mobile = $mobile; }
		function getMobile() { return $this->mobile; }
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setPass($pass) { $this->pass = $pass; }
		function getPass() { return $this->pass; }
		function setActivated($activated) { $this->activated = $activated; }
		function getActivated() { return $this->activated; }
		function setToken($token) { $this->token = $token; }
		function getToken() { return $this->token; }
		function setCreatedOn($createdOn) { $this->createdOn = $createdOn; }
		function getCreatedOn() { return $this->createdOn; }


		function __construct() {
			require 'DbConnect.php';
			$db = new DbConnect();
			$this->conn = $db->connect();
		}

		public function save()
		{
			$sql = "INSERT INTO `users`(`id`, `name`, `mobile`, `email`, `pass`, `activated`, `token`, `created_on`) VALUES (null,:name,:mobile,:email,:pass,:activated,:token,:cdate)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':mobile', $this->mobile);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':pass', $this->pass);
			$stmt->bindParam(':activated', $this->activated);
			$stmt->bindParam(':token', $this->token);
			$stmt->bindParam(':cdate', $this->createdOn);
			try {
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public function getUserByEmail() {
			$stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
			$stmt->bindParam(':email', $this->email);
			try {
				if($stmt->execute()) {
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			return $user;
		}

		public function getUserById() {
			$stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
			$stmt->bindParam(':id', $this->id);
			try {
				if($stmt->execute()) {
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			return $user;
		}

		public function activateUserAccount() {
			$stmt = $this->conn->prepare('UPDATE users SET activated = 1 WHERE id = :id');
			$stmt->bindParam(':id', $this->id);
			try {
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}		
		public function updateToken() {
			$stmt = $this->conn->prepare('UPDATE users SET token = :token WHERE id = :id');
			$stmt->bindParam(':token', $this->token);
			$stmt->bindParam(':id', $this->id);
			try {
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		public function updatePass() {
			$stmt = $this->conn->prepare('UPDATE users SET pass = :pass WHERE id = :id');
			$stmt->bindParam(':pass', $this->pass);
			$stmt->bindParam(':id', $this->id);
			try {
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

	}


 ?>