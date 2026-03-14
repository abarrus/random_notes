<?php
require_once "../helpers/db.php";

// double check that the correct prompter submitted it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];
$prompt_info = get_prompt_info($gameId);
if ($prompt_info["prompter"] != $playerId) {
    echo "Not allowed - you're not the player who was supposed to submit the prompt.";
    exit;
}

$prompt = $_POST["prompt"];
set_prompt($gameId, $prompt);

header("LOCATION: /submission_write.php");
exit;