<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

$submissions = get_submissions($gameId);

// send as JSON
header('Content-Type: application/json');
echo json_encode($submissions);