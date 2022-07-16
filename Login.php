<!doctype html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<?php include "bar.php" ?>
	<?php session_start(); ?>
	<?php
	if (isset($_SESSION["computing_id"])) {
		header("Location: user_profile.php");
		exit;
	}
	?>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Login</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-5">
					<div class="login-wrap p-md-5" style="padding-bottom: 100px !important;">
						<div class="icon d-flex align-items-center justify-content-center">
							<span class="fa fa-user-o"></span>
						</div>
						<h3 class="text-center mb-4">Have an account?</h3>
						<form action="Login.php" method="post" class="login-form">
							<div class="form-group">
								<input type="text" name="computing_id" class="form-control rounded-left" placeholder="Username" required>
							</div>
							<div class="form-group d-flex">
								<input type="password" name="password" class="form-control rounded-left" placeholder="Password" required>
							</div>
							<div class="form-group">
								<button type="submit" name="login" class="btn btn-primary rounded submit p-3 px-5">Get Started</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>

</html>


<?php

//register.php

/**
 * Include our MySQL connection.
 */
require 'db_utils.php';

//If the POST var "register" exists (our submit button), then we can
//assume that the user has submitted the registration form.
if (isset($_POST['computing_id'])) {

	//Retrieve the field values from our registration form.
	$computing_id = !empty($_POST['computing_id']) ? trim($_POST['computing_id']) : null;
	$password = !empty($_POST['password']) ? trim($_POST['password']) : null;
	$sql = "SELECT * FROM UVA_People WHERE computing_id = ? AND password = PASSWORD(?)";
	$stmt = $db->prepare($sql);
	$stmt->execute([$computing_id, $password]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user === false) {
		//What you do here is up to you!
		// echo '$user';
		echo 'Comrade, the password/username is not correct';
	} else {

		//Provide the user with a login session.
		$id = $user['computing_id'];
		$_SESSION['computing_id'] = $id;
		$temp = $db->query("SELECT * from Professor_email_address WHERE computing_id='$id' LIMIT 0, 1");
		if ($temp->fetch()) {
			$_SESSION['type'] = 'professor';
		} else {
			$_SESSION['type'] = 'student';
		}
		//Redirect to our protected page, which we called home.php
		echo 'thank you comrade';
		header('Location: user_profile.php');
		exit;
	}
}

?>