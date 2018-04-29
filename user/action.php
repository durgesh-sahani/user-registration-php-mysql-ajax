<?php 
	session_start();
	require 'users.php';
	require '../mailer/PHPMailerAutoload.php';
	require '../mailer/credential.php';

	if(isset($_POST['action']) && $_POST['action'] == 'checkCookie') {
		if(isset($_COOKIE['email'], $_COOKIE['pass'])) {
			$data = ['email'=>$_COOKIE['email'], 'pass'=>base64_decode($_COOKIE['pass'])];
			echo json_encode($data);
		}
	}

	if(isset($_POST['action']) && $_POST['action'] == 'updatePass') {
		$users = validateUpdatePassForm();
		$data  = json_decode( base64_decode($users['token']), true );
		$currTime = strtotime(date('d-m-Y h:i:s'));
		$expTime  = strtotime($data['expTime']);
		if($currTime > $expTime) {
			echo json_encode( ["status" => 0, "msg" => "Token expired."] );
			exit;
		}

		$objUser = new Users();
		$objUser->setId($data['id']);
		$userData = $objUser->getUserById();
		if(is_array($userData) && count($userData) > 0) {
			if($data['token'] == $userData['token']) {
				$objUser->setPass(md5($users['pass']));
				if($objUser->updatePass()) {
					echo json_encode( ["status" => 1, "msg" => "Password Updated."] );
					exit;
				} else {
					echo json_encode( ["status" => 0, "msg" => "Failed to update password."] );
					exit;
				}
			} else {
				echo json_encode( ["status" => 0, "msg" => "Token is not valid."] );
				exit;
			}
		} else {
			echo json_encode( ["status" => 0, "msg" => "User not found."] );
			exit;
		}
 		
	}

	if(isset($_POST['action']) && $_POST['action'] == 'resetPass') {
		$email = filter_input(INPUT_POST, 'remail', FILTER_VALIDATE_EMAIL);
		if(false == $email) {
			echo json_encode( ["status" => 0, "msg" => "Enter valid Email"] );
			exit;
		}

		$objUser = new Users();
		$objUser->setEmail($email);
		$userData = $objUser->getUserByEmail();
		if(is_array($userData) && count($userData)>0) {
			$data['id'] = $userData['id'];
			$data['token'] = sha1( $userData['email'] );
			$data['expTime'] = date('d-m-Y h:i:s', time() + (60*60*2));
			$urlToken = base64_encode(json_encode($data));
			$objUser->setId($userData['id']);
			$objUser->setToken($data['token']);
			if($objUser->updateToken()) {
				$url = 'http://' . $_SERVER['SERVER_NAME'] . '/user/reset.php?token=' .$urlToken;
				$html = '<div>You have requested a password reset for your user account at Localhost. You can do this by clicking the link below.:<br>'.$url.'<br><br><strong>Please note this link is valid for 2 hours.</strong></div>';

				$mail = new PHPMailer;

			// $mail->SMTPDebug = 4;                               // Enable verbose debug output

				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = EMAIL;                 // SMTP username
				$mail->Password = PASS;                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				$mail->setFrom(EMAIL, 'Dsmart Tutorials');
				$mail->addAddress($objUser->getEmail());     // Add a recipient

				$mail->addReplyTo(EMAIL);
				
				$mail->isHTML(true);                                  // Set email format to HTML

				$mail->Subject = 'Reset your password';
				$mail->Body    = $html;

				if(!$mail->send()) {
				    echo json_encode( ["status" => 0, "msg" => "Message could not be sent."] );
				    echo json_encode( ["status" => 0, "msg" => 'Mailer Error: ' . $mail->ErrorInfo] );
				} else {
				   echo json_encode( ["status" => 1, "msg" => "Reset password link is send to your email."] );
				}
			} else {
				echo json_encode( ["status" => 0, "msg" => "Failed to set token."] );
			}
		} else {
			echo json_encode( ["status" => 0, "msg" => "User is not found."] );
		}

	}

	if(isset($_POST['action']) && $_POST['action'] == 'register') {
		$users = validateRegForm();
		
		$objUser = new Users();
	 	
	 	$objUser->setName($users['fname']);
	 	$objUser->setMobile($users['mobile']);
	 	$objUser->setEmail($users['uemail']);
	 	$objUser->setPass(md5($users['pass']));
	 	$objUser->setActivated(0);
	 	$objUser->setToken(NULL);
	 	$objUser->setCreatedOn(date('Y-m-d'));

	 	$userData = $objUser->getUserByEmail();
		if($userData['email'] == $users['uemail']) {
			echo 'Email is already registered';
			exit;
		}
	 	if($objUser->save()) {
	 		$lastId = $objUser->conn->lastInsertId();
	 		$token = sha1($lastId);
	 		$url = 'http://' . $_SERVER['SERVER_NAME'] . '/user/verify.php?id=' . $lastId . '&token=' .$token;
	 		$html = '<div>Thanks for registering with localhost. Please click this link to complete your registration:<br>'.$url.'</div>';

			$mail = new PHPMailer;

			// $mail->SMTPDebug = 4;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = EMAIL;                 // SMTP username
			$mail->Password = PASS;                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom(EMAIL, 'Dsmart Tutorials');
			$mail->addAddress($objUser->getEmail());     // Add a recipient

			$mail->addReplyTo(EMAIL);
			
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Confirm your email';
			$mail->Body    = $html;

			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    echo "Congratulation, Your registration done on our site. Please verify your email.";
			}
	 		
	 	} else {
	 		echo " Failed to save";
	 	}
	}	

	if(isset($_POST['action']) && $_POST['action'] == 'login') {
		$users = validateLoginForm();
		$objUser = new Users();
		$objUser->setEmail($users['email']);
	 	$objUser->setPass(md5($users['pwd']));
	 	$userData = $objUser->getUserByEmail();
	 	$rememberMe = isset($_POST['remember-me']) ? 1 : 0;
	 	if(is_array($userData) && count($userData) > 0) {
	 		if($userData['pass'] == $objUser->getPass()) {
	 			if($userData['activated'] == 1 ) {
	 				if($rememberMe == 1) {
	 					setcookie('email', $objUser->getEmail());
	 					setcookie('pass', base64_encode($users['pwd']));
	 				}
	 				$_SESSION['id'] = session_id();
	 				$_SESSION['name'] = $userData['name'];
	 				echo json_encode( ["status" => 1, "msg" => "login successfull."] );
	 			} else {
	 				echo json_encode( ["status" => 0, "msg" => "Please activate your account to login."] );
	 			}
	 		} else {
	 			echo json_encode( ["status" => 0, "msg" => "Email or Password is wrong."] );
	 		}
	 	} else {
	 		echo json_encode( ["status" => 0, "msg" => "Email or Password is wrong."] );
	 	}
	}
		
	function validateUpdatePassForm() {
		$users['token'] = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
		if(false == $users['token']) {
			echo json_encode( ["status" => 0, "msg" => "Not a valid request."] );
			exit;
		}

		$users['pass'] = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
		if(false == $users['pass']) {
			echo json_encode( ["status" => 0, "msg" => "Enter valid valid pass"] );
			exit;
		}

		$users['cfm_pass'] = filter_input(INPUT_POST, 'cfm_pass', FILTER_SANITIZE_STRING);
		if(false == $users['cfm_pass']) {
			echo json_encode( ["status" => 0, "msg" => "Enter valid confirm pass"] );
			exit;
		}

		if($users['pass'] != $users['cfm_pass']) {
			echo json_encode( ["status" => 0, "msg" => "Password and confirm password not match"] );
			exit;
		}

		return $users;
	}

	function validateLoginForm() {
		$users['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if(false == $users['email']) {
			echo json_encode( ["status" => 0, "msg" => "Enter valid Email"] );
			exit;
		}

		$users['pwd'] = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_STRING);
		if(false == $users['pwd']) {
			echo json_encode( ["status" => 0, "msg" => "Enter valid valid pass"] );
			exit;
		}

		return $users;
	}
		
	function validateRegForm() {
		$users['fname'] = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
		if(false == $users['fname']) {
			echo "Enter valid name";
			exit;
		}

		$users['mobile'] = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_NUMBER_INT);
		if(false == $users['mobile']) {
			echo "Enter valid number";
			exit;
		}

		$users['uemail'] = filter_input(INPUT_POST, 'uemail', FILTER_VALIDATE_EMAIL);
		if(false == $users['uemail']) {
			echo "Enter valid Email";
			exit;
		}

		$users['pass'] = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
		if(false == $users['pass']) {
			echo "Enter valid valid pass";
			exit;
		}
		$users['cfm_pass'] = filter_input(INPUT_POST, 'cfm_pass', FILTER_SANITIZE_STRING);
		if(false == $users['cfm_pass']) {
			echo "Enter valid valid confirm pass";
			exit;
		}

		if($users['pass'] != $users['cfm_pass']) {
			echo 'Password and confirm password not match';
			exit;
		}

		return $users;
	}
?>