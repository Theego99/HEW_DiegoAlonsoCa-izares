<?php
$id = filter_var($_POST["route-id"], FILTER_SANITIZE_SPECIAL_CHARS); 

$mysqli = require __DIR__ . "/account_management/database.php";

$sql = "DELETE FROM shortcuts WHERE id = $id";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $mysqli->error);
}

if ($stmt->execute()) {
    header("location: mypage.php");
    exit;
} else {
    die($mysqli->error . " " . $mysqli->errno);
}
