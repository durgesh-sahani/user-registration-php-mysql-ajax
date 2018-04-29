<!DOCTYPE html>
<html>
<head>
	<title>Complete user registration system in php and MySQL using Ajax</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<script src="../js/jquery-3.2.1.min.js"></script>
	<script src="../js/bootstrap.js"></script>
	<style type="text/css">
		#loader {
	    	display: none;
	    }
	    .addon-diff-color {
	      background-color: #f0ad4e;
	      color: white;
	    }
	   .title {
	   		color: #f0ad4e;
	   		font-weight: bolder;
	    }
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
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

				$('#update').click(function(event){
					event.preventDefault();
					var formData = $('#update-pass-frm').serialize();
					var token 	 = "<?php echo $_GET['token']; ?>";
					console.log(token);
					$('#loader').show();
					$.ajax({
						url: 'action.php',
						method: 'post',
						data: formData + '&action=updatePass&token='+token
					}).done(function(result){
						$('#loader').hide();
						console.log(result);
						var data = JSON.parse(result);
						if(data.status == 0) {
							$('#result').html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>'+data.msg+'</div>');
						} else {
							$('#result').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>'+data.msg+'</div>');
						}
					})
				})
			})	
	</script>
</head>
<body>
	<div class="container">
		<h2 class="text-center">Sign Up, Sign In, Forgot Password in php and MySQL using Ajax</h2>
		<h3 class="text-center">Part 9: Validate reset password link and update password</h3>
		<hr>
		<h2 class="text-center title">Update your password</h2><br>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div id="result"></div>
				<center><img src="img/loader.gif" id="loader"></center><br>
			</div>
			<div class="col-md-6 col-md-offset-3">
				<form id="update-pass-frm" role="form" method="post" action="" class="form-horizontal">
					<div class="form-group">
	                	<div class="input-group">
                            <div class="input-group-addon addon-diff-color">
                                <span class="glyphicon glyphicon-lock"></span>
                            </div>
	                    	<input type="password" class="form-control" id="pass" name="pass" placeholder="New Password">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <div class="input-group">
                            <div class="input-group-addon addon-diff-color">
                                <span class="glyphicon glyphicon-lock"></span>
                            </div>
	                    	<input type="password" class="form-control" id="cfm_pass" name="cfm_pass" placeholder="Confirm new Password">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <input type="submit" value="UPDATE PASSWORD" class="btn btn-warning btn-block" id="update" name="update">
	                </div>
			    </form>
			</div>
		</div>
	</div>
</body>
</html>