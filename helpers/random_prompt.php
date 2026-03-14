<?php

require_once "db.php";
$conn = connect();

$sql = "SELECT prompt FROM Prompts ORDER BY RAND() LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();

$prompt = $stmt->fetchColumn();

// send as JSON
header('Content-Type: application/json');
echo json_encode($prompt);