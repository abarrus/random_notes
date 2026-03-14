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
    <div>
        <span class="d-block small text-secondary text-uppercase">Your name</span>
        <span>$playerName</span>
    </div>
    <div>
        <span class="d-block small text-secondary text-uppercase">game name</span>
        <span>$gameName</span>
    </div>
</div>
EOD;