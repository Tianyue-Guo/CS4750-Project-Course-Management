<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            min-height: 110vh;
            background-color: #4ca1af;
            background-image: linear-gradient(135deg, #4ca1af 0%, #c4e0e5 100%);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #BA68C8
        }

        .profile-button {
            background: rgb(99, 39, 120);
            box-shadow: none;
            border: none
        }

        .profile-button:hover {
            background: #682773
        }

        .profile-button:focus {
            background: #682773;
            box-shadow: none
        }

        .profile-button:active {
            background: #682773;
            box-shadow: none
        }

        .back:hover {
            color: #682773;
            cursor: pointer
        }

        .labels {
            font-size: 11px
        }

        .add-experience:hover {
            background: #BA68C8;
            color: #fff;
            cursor: pointer;
            border: solid 1px #BA68C8
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    require_once "db_utils.php";
    require_once "utils/session_start.php";
    require_once "bar.php";

    if (isset($_POST['submit'])) {
        $fn = !empty($_POST['fn']) ? trim($_POST['fn']) : null;
        $ln = !empty($_POST['ln']) ? trim($_POST['ln']) : null;
        $sql1 = "UPDATE UVA_People SET first_name = ?, last_name = ? WHERE computing_id = '$id';";
        $stmt1 = $db->prepare($sql1);
        $stmt1->execute([$fn, $ln]);
        if (!is_professor()) {
            $year = !empty($_POST['year']) ? trim($_POST['year']) : null;
            if ($year > 2025 || $year < 1823) {
                echo "<script>alert('invalid year')</script>";
            } else {
                $sql2 = "UPDATE Student SET `year` = ? WHERE computing_id = '$id';";
                $stmt2 = $db->prepare($sql2);
                $stmt2->execute([$year]);
            }
        }
    }

    $name = $db->query("SELECT * FROM UVA_People WHERE computing_id = '$id' ");
    $email = $db->query("SELECT * FROM Professor_email_address WHERE computing_id = '$id' ");
    $year = $db->query("SELECT * FROM Student WHERE computing_id = '$id' ");
    $major = $db->query("SELECT * FROM `declare` WHERE computing_id = '$id' ");
    $courses_taken = $db->query("SELECT * FROM takes WHERE computing_id = '$id' ");
    $courses_teaches = $db->query("SELECT * FROM teach WHERE computing_id = '$id' ");
    $row_name = $name->fetch(PDO::FETCH_ASSOC);
    $row_email = $email->fetchALL(PDO::FETCH_ASSOC);
    $row_year = $year->fetch(PDO::FETCH_ASSOC);

    $Name = $row_name['name'];
    $first_name = $row_name['first_name'];
    $last_name = $row_name['last_name'];
    if (is_professor()) {
        $Identity = 'Professor';
    } else {
        $Identity = 'Student';
    }

    ?>

    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="uva.jpg"><span class="font-weight-bold"><?php echo "{$Name}" ?></span><span class="text-black-50"><?php echo "{$Identity}" ?></span><span> </span></div>
            </div>
            <form action="user_profile.php" method="post" class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">First Name</label><input type="text" name="fn" class="form-control" value=<?php echo "{$first_name}" ?>></div>
                        <div class="col-md-6"><label class="labels">Last Name</label><input type="text" name="ln" class="form-control" value=<?php echo "{$last_name}" ?>></div>
                    </div>
                    <div class="row mt-3">
                        <?php if ($Identity == 'Professor') {
                            echo '<div class="col-md-12"><label class="labels">Courses Teaches</label></div>';
                            while ($row_courses_teaches = $courses_teaches->fetch(PDO::FETCH_ASSOC)) {
                                echo "<label>{$row_courses_teaches['course_id']}<br/></label>";
                            }
                        } else {
                            echo "<div class='col-md-12'><label class='labels'>Year</label><input type='text' name = 'year' class='form-control' value={$row_year['year']}></div>";
                            echo '<div class="col-md-12"><label class="labels">Major</label></div>';
                            while ($row_major = $major->fetch(PDO::FETCH_ASSOC)) {
                                echo "<label>{$row_major['major_name']}</label>";
                            }
                            echo '<div class="col-md-12"><label class="labels">Computing ID</label></div>';
                            echo "<label>{$id}</label>";
                            echo '<div class="col-md-12"><label class="labels">Courses Take</label></div>';
                            while ($row_courses_taken = $courses_taken->fetch(PDO::FETCH_ASSOC)) {
                                echo "<label>{$row_courses_taken['course_id']}<br/></label>";
                            }
                        }
                        ?>
                    </div>

                    <div class="mt-5 text-center"><input class="btn btn-primary profile-button" type="submit" name="submit" value="Save Profile" /></div>
                </div>
            </form>
            <?php if ($Identity == 'Professor') {
            ?>
                <div class="col-md-4">
                    <div class="d-flex mt-5 mb-2 justify-content-between align-items-center experience">
                        <span>Email Addresses (press enter to save)</span>
                    </div>
                    <div id="email-container">
                        <?php
                        foreach ($row_email as $emails) {
                            $email = $emails['email_address'];
                        ?>
                            <div class="row g-0 mb-2">
                                <div class="col-md-9">
                                    <input type='text' class='form-control' value="<?php echo $email; ?>" onchange="updateEmail('<?php echo $email; ?>', this)" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/>
                                </div>
                                <div class="col-sm-auto ms-auto">
                                    <button onclick="deleteEmail('<?php echo $email; ?>')">Delete</button>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <button class="btn btn-primary mt-4" id="add-email-btn" onclick="addEmail()">Add email</button>
                </div>
            <?php }
            ?>
        </div>
    </div>
</body>
<script>
    function addEmail() {
        const cont = document.getElementById('email-container');
        cont.innerHTML += `
        <div class="row g-0">
            <div class="col-md-9">
                <input type='text' class='form-control' id="add-temp-email" placeholder="type your email address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/>
            </div>
            <div class="col-sm-auto ms-auto">
                <button onclick="finishAddEmail()">Finish</button>
            </div>
        </div>
        `;
        document.getElementById('add-email-btn').style.display = "none";
    }

    function finishAddEmail() {
        const input = document.getElementById("add-temp-email");
        if (!input.checkValidity())
            return alert("invalid email address");
        axios.post("api/add_edit_emails.php", {
            action: "add",
            email: input.value
        }).then(res => {
            // console.log(res);
            window.location.reload(true);
        })
    }

    function updateEmail(old, input) {
        if (!input.checkValidity())
            return alert("invalid email address");
        axios.post("api/add_edit_emails.php", {
            action: "edit",
            old,
            email: input.value
        }).then(res => {
            // console.log(res);
            window.location.reload(true);
        })
    }

    function deleteEmail(email) {
        axios.post("api/add_edit_emails.php", {
            action: "delete",
            email
        }).then(res => {
            // console.log(res);
            window.location.reload(true);
        })
    }
</script>

</html>