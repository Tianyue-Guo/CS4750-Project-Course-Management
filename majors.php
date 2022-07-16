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
        const actionDict = {
            "remove_list": new Set(),
            "add_list": new Set()
        };

        let activeCourse = null;

        function courseOnClick(ele) {
            if (ele.classList.contains("active")) {
                ele.classList.remove("active");
                activeCourse = null;
            } else {
                ele.classList.add("active");
                if (activeCourse)
                    activeCourse.classList.remove("active");
                activeCourse = ele;
            }
            <?php if (is_professor()) { ?>
            if (activeCourse) {
                document.getElementById("alt1").style.display = "none";
                document.getElementById("alt2").style.display = "";
                onchange();
            } else {
                document.getElementById("alt1").style.display = "";
                document.getElementById("alt2").style.display = "none";
            }
            <?php } ?>
        }

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
            axios.post("api/add_remove_req.php", {
                action,
                list: [...actionDict[action]],
                cid: activeCourse ? activeCourse.id : ''
            }).then(res => {
                // console.log(res);
                // console.log([...actionDict[action]]);
                window.location.reload(true);
            });
        }

        function declareMajor(action, major_name) {
            axios.post("api/declare.php", {
                action,
                major_name
            }).then(res => {
                // console.log(res);
                window.location.reload(true);
            });
        }
    </script>
    <div class="row">
        <div class="col-md-6">
            <h3>Browse Majors</h3>
            <div class="mb-3">
                <label for="query" class="form-label">Filter</label>
                <input type="text" class="form-control" onkeydown="filterOnChange(this)">
                <div id="emailHelp" class="form-text">Type enter to search</div>
            </div>
            <div class="list-group" id="splist" style="max-height: 60vh; overflow-y: auto">
                <?php
                if (is_professor())
                    $major_rows = $db->query("SELECT M.major_name, M.department FROM Major M")->fetchAll(PDO::FETCH_ASSOC);
                else
                    $major_rows = $db->query("SELECT M.major_name, M.department, D.computing_id FROM Major M LEFT JOIN (SELECT * FROM `declare` WHERE computing_id='$id') AS D ON M.major_name=D.major_name")->fetchAll(PDO::FETCH_ASSOC);
                $req_sql = $db->prepare("SELECT course_id FROM part_of WHERE major_name=?");
                foreach ($major_rows as $row) {
                    $cid = $row['major_name'];
                ?>
                    <a class="list-group-item list-group-item-action course-list" data-bs-toggle="collapse" href="#row-<?php echo $cid; ?>" role="button" onclick="courseOnClick(this)" id="<?php echo $cid ?>">
                        <?php echo $cid . " " . $row['department'] ?>
                        <button class="btn btn-sm btn-secondary" onclick="location.href='majors_detail.php?major_name=<?php echo $cid; ?>'">Details</button>

                        <?php
                        if (!is_professor()) {
                            if ($row['computing_id'] === null) {
                                echo "<button class=\"btn btn-sm btn-primary\" onclick=\"declareMajor('declare', '$cid')\">Declare</button>";
                            } else {
                                echo "<button class=\"btn btn-sm btn-danger\" onclick=\"declareMajor('undeclare', '$cid')\">Undeclare</button>";
                            }
                        }
                        ?>
                    </a>
                    <div class="collapse ms-4 my-2" id="row-<?php echo $cid; ?>">
                        <h6>Required courses</h6>
                        <?php
                        $req_sql->execute([$cid]);
                        $all = $req_sql->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($all as $req) {
                            $pid = $req['course_id'];
                        ?>
                            <a href="#" class="list-group-item list-group-item-action py-1" onclick="listOnClick(this, 'remove_list', '<?php echo $cid . '-' . $pid ?>')">
                                <?php echo $pid; ?>
                            </a>
                        <?php
                        }
                        if (count($all) === 0)
                            echo "None";
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <?php if (is_professor()) { ?>
                <button class="btn btn-primary mt-4" onclick="addDrop('remove_list')">Remove from req</button>
            <?php } ?>
        </div>
        <?php if (is_professor()) { ?>
            <div class="col-md-6">
                <div id="alt1">
                    <h6>Select a major on the left side to begin</h6>
                </div>
                <div id="alt2" style="display: none;">
                    <h3>Select required courses:</h3>
                    <div class="mb-3">
                        <label for="query" class="form-label">Filter</label>
                        <input type="email" class="form-control" id="query" name="query" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">Type enter to search</div>
                    </div>
                    <div class="list-group" id="classlist" style="max-height: 60vh; overflow-y: auto">
                    </div>
                    <button class="btn btn-primary mt-4" onclick="addDrop('add_list')">Add to req</button>
                </div>
            </div>
            <script>
                const queryEle = document.getElementById("query");
                const classList = document.getElementById("classlist");

                function onchange(ev) {
                    const data = axios.get("api/reqs_search.php", {
                        params: {
                            query: queryEle.value.toLowerCase(),
                            id: activeCourse.id
                        }
                    });
                    data.then(res => {
                        const data = res.data;
                        console.log(data);
                        let html = '';
                        for (const row of data) {
                            html +=
                                `<a href="#" class="list-group-item list-group-item-action ${row['reason'].length ? 'disabled' : ''}" 
                            onclick="listOnClick(this, 'add_list', '${row['course_id']}')">
                            ${row['course_id']} ${row['name']} <small class="text-warning">${row['reason']}</small>
                        </a>`;
                        }
                        classList.innerHTML = html;
                    });
                }
                queryEle.onkeydown = onchange;
            </script>
        <?php } ?>
    </div>

    <script>
        const courses = document.getElementsByClassName("course-list");

        function filterOnChange(queryEle) {
            const q = queryEle.value.toLowerCase();
            for (const course of courses) {
                if (course.innerHTML.toLowerCase().indexOf(q) === -1) {
                    course.style.display = "none";
                    document.getElementById(`row-${course.id}`).style.display = "none";
                } else {
                    course.style.display = "";
                    document.getElementById(`row-${course.id}`).style.display = "";
                }
            }
        }
    </script>
</body>

</html>