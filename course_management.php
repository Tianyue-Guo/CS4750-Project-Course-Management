<!DOCTYPE html>
<html lang="en">
<?php
require_once "db_utils.php";
require_once "utils/session_start.php";
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="w-75 mx-auto mt-4">
    <?php include "bar.php" ?>
    <script>
        const add_set = new Set();
        const actionDict = {
            "add": add_set,
            "add_to_taken": add_set,
            "drop": new Set(),
            "remove_from_taken": new Set()
        };

        function listOnClick(ele, action, id) {
            if (ele.classList.contains("active")) {
                ele.classList.remove("active");
                actionDict[action].delete(id);
            } else {
                ele.classList.add("active");
                actionDict[action].add(id);
            }
        };

        function addDrop(action) {
            const grade_option_ele = document.getElementById('grade_option');
            let grade_option = '';
            if (grade_option_ele) {
                grade_option = grade_option_ele.value;
            }
            axios.post("api/add_drop_course.php", {
                action,
                list: [...actionDict[action]],
                grade_option
            }).then(res => {
                console.log(res);
                window.location.reload(true);
            });

        }
    </script>
    <div class="row">
        <div class="col-md-6">
            <?php if (!is_professor()) { ?>
                <h3>Courses Taken</h3>

                <div class="list-group mt-4" id="takenList" style="max-height: 45vh; overflow-y:auto">
                    <?php
                    $q = $db->query("SELECT course_id, name, option_name from taken NATURAL JOIN Course WHERE computing_id='$id'");
                    while ($row = $q->fetch()) {
                    ?>
                        <li class="list-group-item list-group-item-action" onclick="listOnClick(this, 'remove_from_taken', '<?php echo $row['course_id'] ?>')">
                            <?php
                            echo $row['course_id'] . ' ' . $row['name'];
                            if (!is_professor()) {
                                echo " ({$row['option_name']})";
                            }
                            ?>
                            <a href="course_detail.php?course_id=<?php echo $row['course_id']; ?>">details</a>
                        </li>
                    <?php
                    }
                    ?>
                </div>
                <button class="btn btn-primary mt-4" onclick="addDrop('remove_from_taken')">Remove from Taken</button>

                <h3 class="mt-4">Drop Courses</h3>
            <?php } else { ?>
                <h3>Manage Courses Taught</h3>
            <?php } ?>
            <div class="list-group mt-4" id="takesList">
                <?php
                require_once "db_utils.php";
                require_once "utils/session_start.php";

                if (is_professor()) {
                    $q = $db->query("SELECT course_id, name from teach NATURAL JOIN Course WHERE computing_id='$id'");
                } else {
                    $q = $db->query("SELECT course_id, name, option_name from takes NATURAL JOIN Course WHERE computing_id='$id'");
                }

                while ($row = $q->fetch()) {
                ?>
                    <li class="list-group-item list-group-item-action" onclick="listOnClick(this, 'drop', '<?php echo $row['course_id'] ?>')">
                        <?php
                        echo $row['course_id'] . ' ' . $row['name'];
                        if (!is_professor()) {
                            echo " ({$row['option_name']})";
                        }
                        ?>
                        <a href="course_detail.php?course_id=<?php echo $row['course_id']; ?>">details</a>
                    </li>
                <?php
                }
                ?>
            </div>
            <button class="btn btn-primary mt-4" onclick="addDrop('drop')">
                <?php echo is_professor() ? 'Remove' : 'Drop'; ?>
            </button>
        </div>
        <div class="col-md-6">
            <h3>Add Courses</h3>
            <div class="mb-3">
                <label for="query" class="form-label">Query</label>
                <input type="text" class="form-control" id="query">
                <div id="emailHelp" class="form-text">Type enter to search</div>
            </div>
            <div class="list-group" id="classlist" style="max-height: 60vh; overflow-y:auto">
            </div>
            <?php if (is_professor()) { ?>
                <button class="btn btn-primary mt-4" onclick="addDrop('add')">Add</button>
            <?php } else { ?>
                <select class="form-select mt-4" id="grade_option">
                    <?php
                    $query = $db->query("SELECT * FROM Grade_option");
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['option_name'] === 'Graded') {
                            echo "<option selected value=\"{$row['option_name']}\">{$row['option_name']} ({$row['description']})</option>";
                        } else {
                            echo "<option value=\"{$row['option_name']}\">{$row['option_name']} ({$row['description']})</option>";
                        }
                    }
                    ?>
                </select>
                <button class="btn btn-primary mt-2" onclick="addDrop('add')">Add</button>
                <button class="btn btn-primary mt-2" onclick="addDrop('add_to_taken')">Add to Taken</button>
            <?php } ?>
        </div>
    </div>

    <script>
        const queryEle = document.getElementById("query");
        const classList = document.getElementById("classlist");

        function onchange(ev) {
            const data = axios.get("api/course_search.php", {
                params: {
                    query: queryEle.value.toLowerCase()
                }
            });
            data.then(res => {
                const data = res.data;
                console.log(data);
                let html = '';
                for (const row of data) {
                    html +=
                        `<li class="list-group-item list-group-item-action ${row['reason'].length ? 'disabled' : ''}" 
                            onclick="listOnClick(this, 'add', '${row['course_id']}')">
                            ${row['course_id']} ${row['name']} <a href="course_detail.php?course_id=${row['course_id']}">details</a> <small class="text-warning">${row['reason']}</small>
                        </li>`;
                }
                classList.innerHTML = html;
            });
        }
        queryEle.onchange = onchange;
        onchange();
    </script>
</body>

</html>