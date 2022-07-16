<?php
require_once "../db_utils.php";
require_once "../utils/session_start.php";

if (!isset($_GET['query'])) {
    die("query not set");
}

if (is_professor()) {
    $query = $db->prepare("SELECT C.course_id, C.name, 
    CASE 
        WHEN C.course_id IN (SELECT T.course_id from teach T WHERE computing_id='$id') THEN 'you already teach this course'
        ELSE ''
    END 
        AS reason from Course C where LOWER(course_id) LIKE :q OR LOWER(name) LIKE :q");
    $u = strtolower($_GET['query']);
    $query->execute([":q" => "%$u%"]);
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    exit();
}

// we can use the following case in sql to check prereq is satisfied or not, but it is hard to tell users which prereq is not satisfied
// WHEN (SELECT COUNT(*) FROM 
// is_prereq P LEFT JOIN (SELECT computing_id, course_id from taken WHERE computing_id='$id') AS T 
// ON P.prereq_course_id=T.course_id WHERE P.course_id=C.course_id AND computing_id IS NULL
// ) > 0 THEN 'prereq not satisfied'
$query = $db->prepare("SELECT C.course_id, C.name, 
CASE 
    WHEN C.course_id IN (SELECT T.course_id from taken T WHERE computing_id='$id') THEN 'course already taken'
    WHEN C.course_id IN (SELECT T.course_id from takes T WHERE computing_id='$id') THEN 'course already in the list of classes to take'
    ELSE ''
END 
    AS reason from Course C where LOWER(course_id) LIKE :q OR LOWER(name) LIKE :q");
$u = strtolower($_GET['query']);
$query->execute([":q" => "%$u%"]);

$check_prereq_sql = "SELECT P.prereq_course_id AS course_id FROM is_prereq P 
    LEFT JOIN (SELECT computing_id, course_id from taken WHERE computing_id='$id') AS T 
    ON P.prereq_course_id=T.course_id WHERE P.course_id=? AND computing_id IS NULL
";
$prereq_query = $db->prepare($check_prereq_sql);
$result_rows = [];
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    if (strlen($row['reason']) == 0) {
        $prereq_query->execute([$row['course_id']]);

        $prereq_id_arr = [];
        while ($prow = $prereq_query->fetch(PDO::FETCH_ASSOC)) {
            array_push($prereq_id_arr, $prow['course_id']);
        }
        if (count($prereq_id_arr) > 0) {
            $prereqs = implode(', ', $prereq_id_arr);
            $row['reason'] = "Prereq $prereqs not satisfied";
        }
    }
    array_push($result_rows, $row);
}
echo json_encode($result_rows);
