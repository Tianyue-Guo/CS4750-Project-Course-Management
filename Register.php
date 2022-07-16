<!doctype html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>

<?php
require 'db_utils.php';
require 'bar.php';
session_start();
if (isset($_SESSION['computing_id'])) {
    header("Location: user_profile.php");
    exit();
}

$message = '';

function registerUser($db)
{
    //Retrieve the field values from our registration form.
    if (!isset($_POST['computing_id']) || !isset($_POST['fname']) || !isset($_POST['lname']) || !isset($_POST['password']) || !isset($_POST['confirm_password']) || !isset($_POST['year'])) {
        die("invalid arguments");
    }
    $computing_id = trim($_POST['computing_id']);
    $first_name = trim($_POST['fname']);
    $last_name = trim($_POST['lname']);
    $password = trim($_POST['password']);
    $confpass = trim($_POST['confirm_password']);
    $year = trim($_POST['year']);
    $year = intval($year);

    $sql = "SELECT COUNT(computing_id) AS num FROM UVA_People WHERE computing_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$computing_id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        return "Computing id $computing_id already exists!";
    }

    if ($password !== $confpass)
        return "password does not match";

    $sql = "INSERT INTO UVA_People (computing_id, first_name, last_name, `password`) VALUES (?, ?, ?, PASSWORD(?))";
    $stmt = $db->prepare($sql);
    $stmt->execute([$computing_id, $first_name, $last_name, $password]);
    $stmt = $db->prepare("INSERT INTO Student VALUES ('$computing_id', ?)");
    $stmt->execute([$year]);

    $_SESSION['computing_id'] = $computing_id;
    $_SESSION['type'] = 'student';
    header("Location: user_profile.php");
    exit();
}
if (isset($_POST['submit'])) {
    $message = registerUser($db);
}

?>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Register</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-md-5" style="padding-bottom: 100px !important;">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>
                        <h3 class="text-center mb-3">Student Registration</h3>
                        <div class="text-danger"><?php echo $message; ?></div>
                        <form action="Register.php" method="post" class="login-form">
                            <div class="form-group">
                                <input type="text" name="computing_id" class="form-control rounded-left" placeholder="Computing ID" required pattern="[0-9a-zA-Z]{1,9}">
                            </div>
                            <div class="form-group">
                                <input type="text" name="fname" class="form-control rounded-left" placeholder="First Name" required pattern=".{1,20}">
                            </div>
                            <div class="form-group">
                                <input type="text" name="lname" class="form-control rounded-left" placeholder="Last Name" required pattern=".{1,20}">
                            </div>
                            <div class="form-group">
                                <input type="text" name="year" class="form-control rounded-left" placeholder="Year (e.g. 2022)" pattern="[0-9]{4}" required>
                            </div>
                            <div class="form-group d-flex">
                                <input type="password" name="password" class="form-control rounded-left" placeholder="Password" required>
                            </div>
                            <div class="form-group d-flex">
                                <input type="password" name="confirm_password" class="form-control rounded-left" placeholder="Confirm Password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary rounded submit p-3 px-5">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>