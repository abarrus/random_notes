<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$gameId = $_SESSION["game_id"];

$prompt = get_prompt($gameId);

// send as JSON
header('Content-Type: application/json');
echo json_encode($prompt);