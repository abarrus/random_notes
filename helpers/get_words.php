<?php
include "db.php";

session_start();
$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

$words = get_words($gameId, $playerId);

// send as JSON
header('Content-Type: application/json');
echo json_encode($words);