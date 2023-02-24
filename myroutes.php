<?php
$mysqli = require __DIR__ . "/account_management/database.php";
$user_id = $_SESSION["user_id"];
// 検索
$stmt = $mysqli->prepare("SELECT * FROM shortcuts WHERE user_id = $user_id;");

$stmt->execute();
$resultSet = $stmt->get_result();
$results = $resultSet->fetch_All(MYSQLI_ASSOC);
if (isset($_POST["ajax"])) {
    echo json_encode($results);
}