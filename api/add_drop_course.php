<?php

require_once "../utils/session_start.php";
require_once "../db_utils.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['action']) || !isset($data['list'])) {
    die("invalid arguments");
}
$c_list = $data['list'];
if (count($c_list) === 0) {
    die("course list cannot have length zero!");
}

$action = $data['action'];
// for professors, we do the same on the teach table
if ($action === 'add' || $action === 'add_to_taken') {
    if (is_professor()) {
        $sql = "INSERT INTO teach VALUES ";
        foreach ($c_list as &$val) {
            $sql .= "('$id', ?), ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
    } else {
        $table = $action === 'add' ? 'takes' : 'taken';
        $grade_opt = $data['grade_option'];
        $stmt = $db->prepare("SELECT * from Grade_option WHERE option_name = ?");
        $stmt->execute([$grade_opt]);
        if (!$stmt->fetch(PDO::FETCH_ASSOC))
            die ("Unknown grade option");
        
        $sql = "INSERT INTO $table VALUES ";
        foreach ($c_list as &$val) {
            $sql .= "('$id', ?, '$grade_opt'), ";
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
    }
} else if ($action === 'drop' || $action === 'remove_from_taken') {
    $table = is_professor() ? 'teach' : ($action === 'drop' ? 'takes' : 'taken');
    $sql = "DELETE FROM $table WHERE computing_id='$id' AND course_id IN (";
    foreach ($c_list as &$val) {
        $sql .= "?, ";
    }
    $sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= ")";
} else {
    die ("invalid action");
}

$stmt = $db->prepare($sql);
if ($stmt->execute($c_list)) echo "$action success";
