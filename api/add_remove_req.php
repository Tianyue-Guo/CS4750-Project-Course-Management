<?php

require_once "../utils/session_start.php";
require_once "../db_utils.php";

if (!is_professor())
    die("permission denied");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['action']) || !isset($data['list']) || !isset($data['cid'])) {
    die("invalid arguments");
}
$c_list = $data['list'];
if (count($c_list) === 0) {
    die("course list cannot have length zero!");
}

$cid = $data['cid'];
if ($data['action'] === 'add_list') {
    $sql = "INSERT INTO part_of VALUES";
    foreach ($c_list as $val) {
        $sql .= "(?, ?), ";
    }
    $sql = substr($sql, 0, strlen($sql) - 2);
    $args = [];
    foreach ($c_list as $val) {
        array_push($args, $cid);
        array_push($args, $val);
    }
} else if ($data['action'] === 'remove_list') {
    $sql = "DELETE FROM part_of WHERE ";
    foreach ($c_list as $val) {
        $sql .= "(major_name=? AND course_id=?) OR";
    }
    $sql = substr($sql, 0, strlen($sql) - 3);
    $args = [];
    foreach ($c_list as $val) {
        $temp = explode("-", $val);
        array_push($args, $temp[0]);
        array_push($args, $temp[1]);
    }
} else {
    die ("invalid action");
}

$stmt = $db->prepare($sql);
if ($stmt->execute($args)) echo "{$data['action']} success";
