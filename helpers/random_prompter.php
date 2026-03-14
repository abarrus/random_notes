<?php
require_once "db.php";

/**
 * Selects a random user from the game and sets them to be prompter
 * 
 * @param gameId 16-char game ID
 */
function random_prompter($gameId) {
    $players = get_players($gameId);
    $prompterId = $players[array_rand($players)]["id"];

    set_prompter($gameId, $prompterId);
}