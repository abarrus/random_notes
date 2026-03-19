<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

$prompt_info = get_prompt_info($gameId, $playerId);

$isPrompter = $prompt_info["prompter"] === $playerId;

// send as JSON
header('Content-Type: application/json');
echo json_encode($isPrompter);