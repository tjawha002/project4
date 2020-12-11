<?php
	session_start();
	
	include "db_connection.php";
		
	$name = "";
	$email_msg = "";
	$msg = "";
	$profile = "";
	$adminPage = "";
	$logout = "";
	
	if(isset($_SESSION['username'])){
		$email = $_SESSION['username'];
		$name = substr($_SESSION['fname'], 0, 1) . substr($_SESSION['lname'], 0, 1);
		$name = "<div class='login-icon'>$name</div>";
		$profile = "<a href='profile.php?email=$email'>Profile</a>";
		//$adminPage = "<a href='add.php'>Add </a>";
		$logout = "<a href='logout.php' class='text-danger'>Logout</a>";
	}

	//Login
	if(isset($_POST['submit'])){
		$email = $_POST['email'];
		$password = $_POST['password'];

		//Check User Info Query
		$sql = mysqli_query($db,"Select * from users where email = '$email' ");
		$row = mysqli_fetch_array($sql);


		if(password_verify($password, $row['password'])){
			$fname =  $row['fname'];
			$lname =  $row['lname'];
			$password = $row['password'];



			$_SESSION['username'] = $email;
			$_SESSION['fname'] = $fname;
			$_SESSION['lname'] = $lname;
			
			$name = substr($fname, 0, 1) . substr($lname, 0, 1);
			$name = "<div class='login-icon'>$name</div>";
			$email_msg = $row['email'];
			
			$profile = "<a href='profile.php?email=$email_msg'>Profile</a>";
			//$adminPage = "<a href='add.php'>Add t</a>";
			$logout = "<a href='logout.php' class='text-danger'>Logout</a>";
			header("location: search.php");

		}
		else{
			$msg = "Wrong Username/Password. Try again";
		}
	}
	
	//Create New Account
	if(isset($_POST['submit2'])){
		$fname = $_POST['fName'];
		$lname = $_POST['lName'];
		$email = $_POST['email'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$q1 = $_POST['question1'];
		$q2 = $_POST['question2'];
		$user_code = rand(10000,99999); //To generate random code to send it to register email
		$user_ver = "Not Veryfied"; //User Registeration will be verifird after entering the code

		//Insert New User Query
		$sql = mysqli_query($db, "Insert into users(email, fname, lname, password, q1, q2, user_code, user_ver) Values('$email','$fname','$lname','$password','$q1','$q2','$user_code','$user_ver')");
		if($sql){
			$msg = "Acount has been created successfully";
			
			require 'class/class.phpmailer.php';
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail ->Port = 465; // or 587
			$mail->SMTPAuth = true;
			$mail->Username = 'tjawhari40@gmail.com';
			$mail->Password = '0556629134Rr';
			$mail->SMTPSecure = 'ssl';
			$mail->From = 'tjawhari40@gmail.com';
			$mail->FromName = 'Restaurant';
			$mail->AddAddress($email);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = 'Verification code for Verify Your Email Address';

			$message_body = '
			<p>For verify your email address, enter this verification code when prompted: <b>'.$user_code.'</b>.</p>
			<p>Sincerely,</p>
			';
			$mail->Body = $message_body;

			if($mail->Send())
			{
				$msg = "Acount has been created successfully<br>Please Check Your Email for Verification Code";
				header("location: account_verification.php?email=$email");
			}
			else
			{
				$message = $mail->ErrorInfo;
			}
		}
		else {
			$msg = "Error: Cannot create the account. Account maybe exist";
		}

	}

	//Reset Password
	if(isset($_POST['forget_password'])){
		$email = $_POST['email'];
		$ans1 = $_POST['answer1'];
		$ans2 = $_POST['answer2'];
		
		//Check User Info Query
		$sql = mysqli_query($db,"Select * from users where email = '$email' and q1='$ans1' and q2='$ans2' ");
		if(mysqli_num_rows($sql) == 1){
			$temp_password = rand(1000,9999);
			$password = password_hash($temp_password, PASSWORD_DEFAULT);
			
			$sql2 = mysqli_query($db,"Update users set password='$password' where email = '$email'");
			$msg = "Password has been reset and send to your email successfully";
			
			require 'class/class.phpmailer.php';
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail ->Port = 465; // or 587
			$mail->SMTPAuth = true;
			$mail->Username = 'tjawhari40@gmail.com';
			$mail->Password = '0556629134Rr';
			$mail->SMTPSecure = 'ssl';
			$mail->From = 'tjawhari40@gmail.com';
			$mail->FromName = 'Restaurant';
			$mail->AddAddress($email);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = 'Reset Password for Your Account';

			$message_body = '<p>Upon your request, your password has been reseted. Your new password is: <b>'.$temp_password.'</b>.</p>
				<p>Sincerely,</p>';
				
			$mail->Body = $message_body;

			if($mail->Send())
			{
				$msg = "Password has been reset successfully<br>Please Check Your Email for new password";
			}
			else
			{
				$message = $mail->ErrorInfo;
			}
		}
		else{
			$msg = "Error: your email or security question answer not correct";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Search Engine</title>
	</head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<style type="text/css">
		.login-icon {
			background-color: #0275d8;
			color: white;
			font-weight: bold;
			font-size: 20px;
			padding: 5px;
			text-align: center;
			border-radius: 20px;

		}
	</style>

	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <a class="navbar-brand" href="add.php">add</a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav">
			  <li class="nav-item active">
				<a class="nav-link" href="index.php">Home</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="profile.php">Profile</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="search.php">Search</a>
			  </li>
			</ul>
		  </div>
		</nav>

		<div class="container">
			<div class="row py-5 px-5">
				<div class="col-9">
					<!-- Search -->
					<!--form action="search.php" method="post">
						<div class="row">
							<div class="col-9">
								<input type="search" name="search" class="form-control" required>
							</div>
							<div class="col-3">
								<button type="submit" class="btn btn-primary" name="searchButton">Search</button><br>
							</div>
							<div class="col-12">
								<h5><a data-toggle="collapse" href="#advanceSearch">Advance Search</a></h5>
							</div>
						</div>
					</form--><!-- End Search -->
					
					<!-- Advance Search -->
					<div class="collapse col-9 py-3" id="advanceSearch" style="background: #F8F9F9; border-radius: 5px;">
						<form action="search.php" method="post">
							<div class="row py-2">
								<div class="col-6">
									<label>Search by Restaurant Name:</label>
									<input type="text" name="Rname" class="form-control">
								</div>
								<div class="col-6">
									<label>Search by Country:</label>
									<select name="Rcountry" class="form-control">
										<option value=''>All Countries</option>
										<?php 
										$countries = mysqli_query($db, "Select r_country from restaurants group by r_country");
										while($c = mysqli_fetch_array($countries)){
										echo "<option value='".$c['r_country']."'>".$c['r_country']."</option>";
										}
										?>
									</select>
								</div>
							</div>

							<div class="row py-2">
								<div class="col-6">
									<label>Search by Cuisines type:</label>
									<input type="text" name="Rtype" class="form-control">
								</div>

								<div class="col-6">
									<label>Search by average rate:</label>
									<select name="Rrate" class="form-control">
										<option value=''>All Rates</option>
										<?php 
										for($r =0; $r<=5; $r++)
											echo "<option value='$r'>$r</option>";
										?>
									</select>
								</div>
							</div>

							<button type="submit" class="btn btn-success" name="advanceSearchButton">Advance Search</button>
							
						</form>
					</div><!-- End Advance Search -->
					<br><br>
					
					<!-- Create New Account -->
					<div class="row">
						<div class="col-lg-5 py-3" style="background: #F8F9F9; border-radius: 5px;">
							<nav>
							  	<div class="nav nav-tabs" id="nav-tab" role="tablist">
								    <a class="nav-item nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
								    <a class="nav-item nav-link" id="signup-tab" data-toggle="tab" href="#signup" role="tab" aria-controls="signup" aria-selected="false">Create New Account</a>
							  	</div>
							</nav>
							<div class="tab-content" id="nav-tabContent">
								<!-- Login Section -->
							  	<div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
							  		<h5 align="center">Login</h5>
							  		<form action="" method="post">
										<input type="email" name="email" placeholder="Enter your email" class="form-control" required><br>
										<input type="password" name="password" placeholder="Enter your password" class="form-control" required><br>
										<div class="g-recaptcha" data-sitekey="6LeCreQZAAAAAOX6WSNmpNpwnOO46mWlj9R9XjQr"></div>
										<button type="submit" class="btn btn-info" name="submit">Login</button>
										<a data-toggle="collapse" href="#forget-password" aria-expanded="false" aria-controls="forget-password">Forget Password</a><br>
									</form>
							  	</div>

							  	<!-- Create New Account Section -->
							  	<div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
							  		<h5 align="center">Create New Account</h5>
							  		<form action="" method="post">
							  			<div class="row">
							  				<div class="col-lg-6">
							  					<input type="text" name="fName" placeholder="First Name" class="form-control" required>
							  				</div>
							  				<div class="col-lg-6">
							  					<input type="text" name="lName" placeholder="Last Name" class="form-control" required>
							  				</div>
							  			</div>
										
										<input type="email" name="email" placeholder="Enter your email" class="form-control" required>
										<input type="password" name="password" placeholder="Enter your password" class="form-control" required>
										<p>Please answer the following questions and remmeber them to reset your password in the future<br>
										What is your best friend's name?</p>
										<input type="text" name="question1" class="form-control" required>
										<p>What is the name of the city you was born?</p>
										<input type="text" name="question2" class="form-control" required>
										<div class="g-recaptcha" data-sitekey="6LeCreQZAAAAAOX6WSNmpNpwnOO46mWlj9R9XjQr"></div>
										<button type="submit" name="submit2" class="btn btn-success">Create</button>

									</form>
							  	</div>
							</div>
							<span align="center"><?php echo $msg; ?></span>
						</div>
						<div class="col-lg-2"></div>

						<!-- Reset Password -->
						<div class="col-lg-5 py-3 collapse" id="forget-password" style="background: #F8F9F9; border-radius: 5px;">
							<form action="" method="post">
								<input type="email" name="email" placeholder="Enter your email" class="form-control" required>
								<p>What is your best friend's name?</p>
								<input type="text" name="answer1" class="form-control" required>
								<p>What is the name of the city you was born?</p>
								<input type="text" name="answer2" class="form-control" required><br>
								<button type="submit" name="forget_password" class="btn btn-success">Reset Password</button>
							</form>
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="row">
						<div class="col-lg-3">
							<?php echo $name; ?>
						</div>
						<div class="col-lg-9">
							<p><?php echo $profile; ?></p>
							<p><?php echo $adminPage; ?></p>
							<p><?php echo $logout; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>