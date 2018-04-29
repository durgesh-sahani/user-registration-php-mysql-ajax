<!DOCTYPE html>
<html>
<head>
	<title>Verify user account</title>
</head>
<body>
	<?php 
		$id 	= $_GET['id'];
		$token 	= $_GET['token'];

		require 'users.php';
	 	$objUser = new Users();
	 	$objUser->setId($id);

	 	$user = $objUser->getUserById();
	 	if(is_array($user) && count($user)>0) {
	 		if(sha1($user['id']) == $token) {
	 			if($objUser->activateUserAccount()) {
	 				echo 'Congratulation, Your account activated. You can login now.<br>';
	 				echo '<a href="index.php">Click here to login</a>';
	 			} else {
	 				echo 'Some problem occurred. Please Try after some time.';
	 			}
	 		} else {
	 			echo 'We can\'t find your detail in our database';
	 		}
	 	} else {
	 		echo 'We can\'t find your detail in our database';
	 	}

	 ?>
</body>
</html>