<?php 
require_once "../utils/session_start.php";
require_once "../db_utils.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['action']) || !isset($data['major_name'])) {
    die("invalid arguments");
}
if (is_professor())
    die("permission denied");

$action = $data['action'];
if ($action === 'declare') {
    $stmt = $db->prepare("INSERT INTO `declare` VALUES ('$id', ?)");
} else {
    $stmt = $db->prepare("DELETE FROM `declare` WHERE computing_id='$id' AND major_name=?");
}
echo $action . "-" . $data['major_name'];
if ($stmt->execute([$data['major_name']]))
    echo "$action success";
else
    echo "$action failed";
?>