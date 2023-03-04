<?php
error_reporting(E_ALL & ~E_NOTICE);

    session_start();


$posts = $_POST;

$vote = filter_var($posts['vote'], FILTER_SANITIZE_SPECIAL_CHARS);
$vote_int = intval($vote);
$shortcut_id = filter_var($posts['shortcut_id'], FILTER_SANITIZE_SPECIAL_CHARS);
$user_id = $_SESSION["user_id"];


$mysqli = require __DIR__ . "/account_management/database.php";

// Prepare the DELETE statement
$sql_delete = "DELETE FROM votes WHERE user_id = ? AND shortcut_id = ?";
$stmt_delete = $mysqli->prepare($sql_delete);
if (!$stmt_delete) {
    die("Error preparing DELETE statement: " . $mysqli->error);
}

// Bind the parameters and execute the DELETE statement
$stmt_delete->bind_param("ii", $user_id, $shortcut_id);
if (!$stmt_delete->execute()) {
    die("Error executing DELETE statement: " . $stmt_delete->error);
}

// Prepare the INSERT INTO statement
$sql_insert = "INSERT INTO votes (user_id, shortcut_id, vote) VALUES (?, ?, ?)";
$stmt_insert = $mysqli->prepare($sql_insert);
if (!$stmt_insert) {
    die("Error preparing INSERT INTO statement: " . $mysqli->error);
}

// Bind the parameters and execute the INSERT INTO statement
$stmt_insert->bind_param("iii", $user_id, $shortcut_id, $vote);
if (!$stmt_insert->execute()) {
    die("Error executing INSERT INTO statement: " . $stmt_insert->error);
}
include 'myvote.php';