<?php

require_once "../utils/session_start.php";
require_once "../db_utils.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['action']) || !isset($data['email'])) {
    die("invalid arguments");
}

$action = $data['action'];
if ($action === "edit") {
    if (!isset($data['old'])) {
        die("invalid arguments");
    }
    $stmt = $db->prepare("UPDATE Professor_email_address SET email_address=? WHERE computing_id='$id' AND email_address=?");
    if ($stmt->execute([$data['email'], $data['old']]))
        echo "edit success";
} else if ($action === "delete") {
    $stmt = $db->prepare("DELETE FROM Professor_email_address WHERE computing_id='$id' AND email_address=?");
    if ($stmt->execute([$data['email']]))
        echo "delete success";
} else if ($action === "add") {
    $stmt = $db->prepare("INSERT INTO Professor_email_address VALUES ('$id', ?)");
    if ($stmt->execute([$data['email']]))
        echo "add success";
}
