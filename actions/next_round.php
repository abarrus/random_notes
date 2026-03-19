<?php
require_once "../helpers/db.php";
include "../helpers/random_prompter.php";

// double check that the person requesting next round is the prompter
// otherwise any player could go to this page to skip a round or to start the game
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];
$prompt_info = get_prompt_info($gameId);
if ($prompt_info["prompter"] != $playerId) {
    echo "Not allowed - you're not the player who was supposed to click the next round / start game button.";
    exit;
}

incremement_round($gameId);
if (get_round($gameId) !== 1) {
    set_prompt($gameId, null);
    random_prompter($gameId);
}

header("LOCATION: /prompt_write.php"); // even if they aren't the writer, it will redirect to prompt_wait and be fine
exit;
