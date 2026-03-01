<?php

function display_players($gameId)
{
    $players = get_players($gameId);
    echo ("Players: " . implode(", ", $players));
}

function display_game()
{
    include "session.php";
    require_once "db.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $gameId = $_SESSION["game_id"];
    $playerId = $_SESSION["player_id"];

    display_players($gameId);

    echo ("<br>");
    echo ("player id: " . $playerId);
    echo ("<br>");
    echo ("game id: " . $gameId);
}
