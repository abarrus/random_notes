<?php

require_once "db.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$playerId = $_SESSION["player_id"];
$gameId = $_SESSION["game_id"];
$playerName = get_nickname($playerId);
$gameName = get_game_name($gameId);

echo <<<EOD
<div class="bg-dark text-light p-3 d-flex justify-content-between">
    <span>$playerName</span>
    <span>$gameName</span>
</div>
EOD;