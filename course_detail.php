<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Detail Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <?php
    require_once "db_utils.php";
    require_once "utils/session_start.php";
    $cid = $_GET['course_id'];
    $query = "SELECT * FROM Course WHERE course_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$cid]);
    $row_course_detail = $stmt->fetch(PDO::FETCH_ASSOC); ?>

    <?php include "bar.php" ?>
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column ">

        <main role="main" class="inner cover">
            <h1 class="cover-heading ">Course Details</h1> <br>
            <p class="lead"><?php echo "Course ID: {$row_course_detail['course_id']}<br/>"; ?></p>
            <p class="lead"><?php echo "Course Name: {$row_course_detail['name']}<br/>"; ?></p>
            <p class="lead"><?php echo "Course Description: {$row_course_detail['description']}<br/>"; ?></p>
            <p class="lead"><?php echo "Semester: {$row_course_detail['semester']}<br/>"; ?></p>
            <p class="lead"><?php echo "Credit: {$row_course_detail['credit']}<br/>"; ?></p>
            <p class="lead">
                <?php if (is_professor()) { ?>
                    <a href="course_detail_edit.php?course_id=<?php echo $cid; ?>" class="btn btn-lg btn-secondary">Edit</a>
                <?php } ?>
            </p>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="../../assets/js/vendor/popper.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
</body>

</html>