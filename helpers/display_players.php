<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

$players = get_players($gameId);

// send as JSON
header('Content-Type: application/json');
echo json_encode($players);

exit;