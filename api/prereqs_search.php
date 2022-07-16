<?php
require_once "../db_utils.php";
require_once "../utils/session_start.php";

if (!isset($_GET['query']) || !isset($_GET['id'])) {
    die("query not set");
}

$query = $db->prepare("SELECT C.course_id, C.name,
CASE 
    WHEN C.course_id=:cid THEN 'Cannot list a course itself as its prereq'
    WHEN C.course_id IN (SELECT P.prereq_course_id from is_prereq P WHERE P.course_id=:cid) THEN 'Course is already a prereq'
    ELSE ''
END AS reason 
    from Course C where LOWER(course_id) LIKE :q OR LOWER(name) LIKE :q");

$u = strtolower($_GET['query']);
$query->execute([":cid" => $_GET['id'], ":q" => "%$u%"]);

echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
?>