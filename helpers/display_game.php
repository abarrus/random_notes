<?php

function display_players($gameId) {
    $players = get_players($gameId);
    echo("Players: ".implode(", ", $players));
}

function display_game() {
    include "session.php";
    include "db.php";
    session_start();

    $gameId = $_SESSION["game_id"];
    $playerId = $_SESSION["player_id"];

    display_players($gameId);
    
    echo("<br>");
    echo("user id: ".$_SESSION["player_id"]);
    echo("<br>");
    echo("game id: ".$_SESSION["game_id"]);
}