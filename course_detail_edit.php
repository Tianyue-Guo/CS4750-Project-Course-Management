<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Detail EDIT Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<?php
require_once "db_utils.php";
require_once "utils/session_start.php";

if (isset($_POST['submit'])) {
    // $major_name = $_POST['major_name'];
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $semester = htmlspecialchars($_POST['semester']);
    $credit = htmlspecialchars($_POST['credit']);
    $cid = $_POST['course_id'];

    $query = "UPDATE Course SET name=?, description=?, semester=?, credit=? WHERE course_id = ?";
    $stmt = $db->prepare($query);
    // $stmt->bindParam(':department', $department);
    // $stmt->bindParam(':description', $description);
    $stmt_exec = $stmt->execute([$name, $description, $semester, $credit, $cid]);

    if ($stmt_exec) {
        echo '<script>alert("data updated")</script>';
        header("Location: course_detail.php?course_id=$cid");
    } else {
        echo '<script>alert("data NOT updated")</script>';
    }
    exit();
}

$query = "SELECT * from Course WHERE course_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['course_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Invalid major name");
}


?>

<body>
    <?php include "bar.php" ?>
    <div class="reg-input-field">
        <h3>Update Course Details</h3>
        <form method="post" action="course_detail_edit.php">
            <!-- <div class="form-group">
            <label>Major Name</label>
            <input type="text" class="form-control" name="major_name" style="width:20em;" placeholder="Major" required />
          </div> -->
            <div class="form-group">
                <label>Course Name</label>
                <input type="text" class="form-control" name="name" style="width:20em;" placeholder="course name" value="<?php echo $result['name']; ?>" />
            </div>
            <div class="form-group">
                <label>Course Description</label>
                <input type="text" class="form-control" name="description" style="width:20em;" placeholder="course description" value="<?php echo $result['description']; ?>">
            </div>
            <div class="form-group">
                <label>Semester</label>
                <input type="text" class="form-control" name="semester" style="width:20em;" placeholder="semester" value="<?php echo $result['semester']; ?>">
            </div>
            <div class="form-group">
                <label>Credit</label>
                <input type="text" class="form-control" name="credit" style="width:20em;" placeholder="credit" value="<?php echo $result['credit']; ?>">
            </div>
            <input type="hidden" class="form-control" name="course_id" value="<?php echo $_GET['course_id']; ?>">
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" /><br><br>
            </div>
        </form>
    </div>
    <a class="btn btn-info" style="width:20em; margin:0;" href="course_detail.php?course_id=<?php echo $_GET['course_id'] ?>">Course detail page</a>

</html>

</body>

</html>