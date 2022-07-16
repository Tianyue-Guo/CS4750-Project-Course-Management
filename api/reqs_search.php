<?php
require_once "../db_utils.php";
require_once "../utils/session_start.php";

if (!isset($_GET['query']) || !isset($_GET['id'])) {
    die("query not set");
}

$query = $db->prepare("SELECT C.course_id, C.name,
CASE 
    WHEN C.course_id IN (SELECT P.course_id from part_of P WHERE P.major_name=:m_name) THEN 'Course is already a req in this major'
    ELSE ''
END AS reason 
    from Course C where LOWER(course_id) LIKE :q OR LOWER(name) LIKE :q");

$u = strtolower($_GET['query']);
$query->execute([":m_name" => $_GET['id'], ":q" => "%$u%"]);

echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
?>