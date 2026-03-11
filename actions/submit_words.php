<?php
include "../helpers/db.php";

$submission = $_POST["submission"];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

submit_sentence($gameId, $playerId, $submission);

header("LOCATION: /wait.php");
exit;