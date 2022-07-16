<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Major Detail EDIT Page</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<?php
require_once "db_utils.php";
require_once "utils/session_start.php";

if (isset($_POST['submit'])) {
  // $major_name = $_POST['major_name'];
  $department = htmlspecialchars($_POST['department']);
  $description = htmlspecialchars($_POST['description']);
  $major_name = $_POST['major_name'];

  $query = "UPDATE Major SET department=?, description=? WHERE major_name = ?";
  $stmt = $db->prepare($query);
  // $stmt->bindParam(':department', $department);
  // $stmt->bindParam(':description', $description);
  $stmt_exec = $stmt->execute([$department, $description, $major_name]);

  if ($stmt_exec) {
    echo '<script>alert("data updated")</script>';
    header("Location: majors_detail.php?major_name=$major_name");
  } else {
    echo '<script>alert("data NOT updated")</script>';
  }
  exit();
}

$query = "SELECT * from Major WHERE major_name = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_GET['major_name']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
  die("Invalid major name");
}


?>

<body>
  <div class="reg-input-field">
    <h3>Update Major Details</h3>
    <form method="post" action="majors_detail_edit.php">
      <!-- <div class="form-group">
            <label>Major Name</label>
            <input type="text" class="form-control" name="major_name" style="width:20em;" placeholder="Major" required />
          </div> -->
      <div class="form-group">
        <label>Department</label>
        <input type="text" class="form-control" name="department" style="width:20em;" placeholder="Department" required pattern="[a-zA-Z .]+" value="<?php echo $result['department']; ?>" />
      </div>
      <div class="form-group">
        <label>Description</label>
        <input type="text" class="form-control" name="description" style="width:20em;" placeholder="description" value="<?php echo $result['description']; ?>">
      </div>
      <input type="hidden" class="form-control" name="major_name" value="<?php echo $_GET['major_name']; ?>">
      <div class="form-group">
        <input type="submit" name="submit" class="btn btn-primary submitBtn" style="width:20em; margin:0;" /><br><br>
      </div>
    </form>
  </div>
  <a class="btn btn-info" style="width:20em; margin:0;" href="majors_detail.php?major_name=<?php echo $_GET['major_name'] ?>">detail page</a>

</html>

</body>

</html>