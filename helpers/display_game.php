<?php

function display_players() {
    // TODO
}

function display_your_words($gameId, $playerId) {
    $words = get_words($gameId, $playerId);

    if (empty($words)) {
        echo "No words... uh oh.";
        return;
    }

    // comma-separated list
    echo implode(", ", $words);
}

function display_game() {
    include "session.php";
    include "db.php";
    session_start();

    $gameId = $_SESSION["game_id"];
    $playerId = $_SESSION["player_id"];

    display_players();
    display_your_words($gameId, $playerId);

    echo("<br>");
    echo("user id: ".$_SESSION["player_id"]);
    echo("<br>");
    echo("game id: ".$_SESSION["game_id"]);
}