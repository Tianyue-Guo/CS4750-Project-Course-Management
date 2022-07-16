<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Major Detail Page</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
<?php include "bar.php" ?>
  <?php
  require_once "db_utils.php";
  require_once "utils/session_start.php";
  $m_name = $_GET['major_name'];
  $query = "SELECT * FROM Major WHERE major_name = ?";
  $stmt = $db->prepare($query);
  $stmt->execute([$m_name]);
  $row_major_detail = $stmt->fetch(PDO::FETCH_ASSOC); ?>
  <div class="cover-container d-flex h-100 p-3 mx-auto flex-column ">

    <main role="main" class="inner cover">
      <h1 class="cover-heading ">Major Details</h1> <br>
      <p class="lead"><?php echo "Major name: {$row_major_detail['major_name']}<br/>"; ?></p>
      <p class="lead"><?php echo "Department: {$row_major_detail['department']}<br/>"; ?></p>
      <p class="lead"><?php echo "Description: {$row_major_detail['description']}<br/>"; ?></p>
      <p class="lead">
      <?php if (is_professor()) { ?>
        <a href="majors_detail_edit.php?major_name=<?php echo $m_name; ?>" class="btn btn-lg btn-secondary">Edit</a>
        <?php } ?>
      </p>
    </main>
  </div>
</body>

</html>
