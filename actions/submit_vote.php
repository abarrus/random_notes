<?php
include "../helpers/db.php";

$vote = $_POST["vote"];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

submit_vote($gameId, $playerId, $vote);

header("LOCATION: /vote_wait.php");
exit;