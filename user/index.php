<!DOCTYPE html>
<html>
<head>
	<title>Complete user registration system in php and MySQL using Ajax</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<script src="../js/jquery-3.2.1.min.js"></script>
	<script src="../js/bootstrap.js"></script>
	<style type="text/css">
		body {
			font-family: 'Verdana';
			font-size: 16px;
			font-weight: bold;
		}
		.panel {
			border: 0;
		}
		form {
			padding: 0 10px;
		}
		.addon-diff-color {
	      background-color: #f0ad4e;
	      color: white;
	    }
	   .panel-title {
	   		color: #f0ad4e;
	   		font-weight: bolder;
	    }
	    .sign-up, .forgot-pass{
			display: none;
		}
	    .alert, #loader {
	    	display: none;
	    }
	</style>
	<script type="text/javascript">
		$(document).ready(function(){

			$.ajax({
				url: 'action.php',
				method: 'post',
				data: 'action=checkCookie'
			}).done(function(result){
				if(result) {
					console.log(result)
					var data = JSON.parse(result);
					$('#email').val(data.email);
					$('#pwd').val(data.pass);
				}
			})

			$('#fname').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[a-zA-Z ]+$/;
				if(regexp.test($('#fname').val())) {
					$('#fname').closest('.form-group').removeClass('has-error');
					$('#fname').closest('.form-group').addClass('has-success');
				} else {
					$('#fname').closest('.form-group').addClass('has-error');
				}
			})
		
			$('#mobile').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[0-9]{10}$/;
				if(regexp.test($('#mobile').val())) {
					$('#mobile').closest('.form-group').removeClass('has-error');
					$('#mobile').closest('.form-group').addClass('has-success');
				} else {
					$('#mobile').closest('.form-group').addClass('has-error');
				}
			})
		
			$('#uemail').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[a-zA-Z0-9._]+@[a-zA-Z0-9._]+\.[a-zA-Z]{2,4}$/;
				if(regexp.test($('#uemail').val())) {
					$('#uemail').closest('.form-group').removeClass('has-error');
					$('#uemail').closest('.form-group').addClass('has-success');
				} else {
					$('#uemail').closest('.form-group').addClass('has-error');
				}
			})
		
			$('#pass').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[a-zA-Z0-9]{6,50}$/;
				if(regexp.test($('#pass').val())) {
					$('#pass').closest('.form-group').removeClass('has-error');
					$('#pass').closest('.form-group').addClass('has-success');
				} else {
					$('#pass').closest('.form-group').addClass('has-error');
				}
			})
		
			$('#cfm_pass').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[a-zA-Z0-9]{6,50}$/;
				if(regexp.test($('#cfm_pass').val())) {
					if($('#cfm_pass').val() == $('#pass').val()) {
						$('#cfm_pass').closest('.form-group').removeClass('has-error');
						$('#cfm_pass').closest('.form-group').addClass('has-success');
					} else {
						$('#cfm_pass').closest('.form-group').addClass('has-error');
					}
				} else {
					$('#cfm_pass').closest('.form-group').addClass('has-error');
				}
			})

			$('#register').click(function(event){
				event.preventDefault();
				var formData = $('#sign-up-frm').serialize();
				console.log(formData);
				$.ajax({
					url: 'action.php',
					method: 'post',
					data: formData + '&action=register'
				}).done(function(result){
					$('.alert').show();
					$('#result').html(result);
				})
			})

			$('#email').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[a-zA-Z0-9._]+@[a-zA-Z0-9._]+\.[a-zA-Z]{2,4}$/;
				if(regexp.test($('#email').val())) {
					$('#email').closest('.form-group').removeClass('has-error');
					$('#email').closest('.form-group').addClass('has-success');
				} else {
					$('#email').closest('.form-group').addClass('has-error');
				}
			})
		
			$('#pwd').keyup(function(){
				// var regexp = new RegExp(/^[a-zA-Z]+$/);
				var regexp = /^[a-zA-Z0-9]{6,50}$/;
				if(regexp.test($('#pwd').val())) {
					$('#pwd').closest('.form-group').removeClass('has-error');
					$('#pwd').closest('.form-group').addClass('has-success');
				} else {
					$('#pwd').closest('.form-group').addClass('has-error');
				}
			})

			$('#login').click(function(event){
				event.preventDefault();
				var formData = $('#sign-in-frm').serialize();
				console.log(formData);
				$.ajax({
					url: 'action.php',
					method: 'post',
					data: formData + '&action=login'
				}).done(function(result){
					console.log(result);
					var data = JSON.parse(result);
					$('.alert').show();
					if(data.status == 0) {
						$('#result').html(data.msg);
					} else {
						document.location = 'welcome.php';
					}
					
				})
			})

			$('#reset').click(function(event){
				event.preventDefault();
				var formData = $('#forgot-pass-frm').serialize();
				console.log(formData);
				$('#loader').show();
				$.ajax({
					url: 'action.php',
					method: 'post',
					data: formData + '&action=resetPass'
				}).done(function(result){
					$('#loader').hide();
					console.log(result);
					var data = JSON.parse(result);
					$('.alert').show();
					if(data.status == 0) {
						$('#result').html(data.msg);
					} else {
						$('#result').html(data.msg);
					}
					
				})
			})

		})

	</script>
</head>

<body>
	<div class="container">
		<h2 class="text-center">Sign Up, Sign In, Forgot Password in php and MySQL using Ajax</h2>
		<h3 class="text-center">Part 8: Send reset password link with expiry time</h3>
		<hr>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
					<div id="result"></div>
				</div>
				<center><img src="img/loader.gif" id="loader"></center>
			</div>
		    <div class="col-md-6 col-md-offset-3 sign-up">
				<div class="panel">
					<div class="panel-heading">
					    <h3 class="panel-title text-center">SIGN UP FORM</h3>
					</div>
		  			<div class="panel-body">
						<form id="sign-up-frm" role="form" method="post" action="" class="form-horizontal">
							<div class="form-group">
                              	<div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-user"></span>
	                                </div>
	                                <input type="text" class="form-control" id="fname" name="fname" placeholder="Full Name">
                              	</div>
                            </div>
			                <div class="form-group">
			                	<div class="input-group">
			                		<div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-earphone"></span>
	                                </div>
				                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number">
				                </div>
			                </div>
			                <div class="form-group">
			                	<div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-envelope"></span>
	                                </div>
			                    	<input type="email" class="form-control" id="uemail" name="uemail" placeholder="Email Address">
			                	</div>
			                </div>
			                <div class="form-group">
			                	<div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-lock"></span>
	                                </div>
			                    	<input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
			                    </div>
			                </div>
			                <div class="form-group">
			                    <div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-lock"></span>
	                                </div>
			                    	<input type="password" class="form-control" id="cfm_pass" name="cfm_pass" placeholder="Confirm Password">
			                    </div>
			                </div>
			                <div class="form-group">
			                    <input type="submit" value="REGISTER" class="btn btn-warning btn-block" id="register" name="register">
			                </div>
			                <div class="form-group">
                                <div class="col-md-12 control">
                                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        Already have an account! 
                                    <a href="#" onClick="$('.sign-up').hide(); $('.sign-in').show()">
                                        Sign in Here
                                    </a>
                                    </div>
                                </div>
                            </div>    
				        </form>
		    		</div>
				</div>
		  	</div>
		  	<div class="col-md-6 col-md-offset-3 sign-in">
				<div class="panel">
			 		<div class="panel-heading">
					    <h3 class="panel-title text-center">SIGN IN FORM</h3>
					    <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#" onClick="$('.sign-in').hide(); $('.forgot-pass').show()">Forgot Password?</a></div>
					</div>
		  			<div class="panel-body">
						<form id="sign-in-frm" role="form" method="post" action="" class="form-horizontal">
			                <div class="form-group">
			                	<div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-envelope"></span>
	                                </div>
			                    	<input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
			                	</div>
			                </div>
			                <div class="form-group">
			                	<div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-lock"></span>
	                                </div>
			                    	<input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
			                    </div>
			                </div>
			                <div class="form-group">
			                   <input type="checkbox" class="form-control" id="remember-me" name="remember-me" style="width: 30px;"><div style="position: relative; top: -30px; left: 40px;"> Remember Me </div>
			                </div>
			                <div class="form-group">
			                    <input type="submit" value="Login" class="btn btn-warning btn-block" id="login" name="login">
			                </div>
			                <div class="form-group">
                                <div class="col-md-12 control">
                                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        Don't have an account! 
                                    <a href="#" onClick="$('.sign-in').hide(); $('.sign-up').show()">
                                        Sign Up Here
                                    </a>
                                    </div>
                                </div>
                            </div>    
				        </form>
		    		</div>
				</div>
		  	</div>
		  	<div class="col-md-6 col-md-offset-3 forgot-pass">
				<div class="panel">
			 		<div class="panel-heading">
					    <h3 class="panel-title text-center">RECOVER YOUR PASSWORD</h3>
					    <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#" onClick="$('.forgot-pass').hide(); $('.sign-in').show()">Sign In</a></div>
					</div>
		  			<div class="panel-body">
						<form id="forgot-pass-frm" role="form" method="post" action="" class="form-horizontal">
			                <div class="form-group">
			                	<div class="input-group">
	                                <div class="input-group-addon addon-diff-color">
	                                    <span class="glyphicon glyphicon-envelope"></span>
	                                </div>
			                    	<input type="email" class="form-control" id="remail" name="remail" placeholder="Email Address">
			                	</div>
			                </div>
			                <div class="form-group">
			                    <input type="submit" value="GENERATE NEW PASSWORD" class="btn btn-warning btn-block" id="reset" name="reset">
			                </div>
				        </form>
		    		</div>
				</div>
		  	</div>
		</div>
	</div>
</body>
</html>