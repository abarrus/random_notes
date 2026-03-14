<?php
require_once "db.php";

function prompt()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $gameId = $_SESSION["game_id"];

    return get_prompt($gameId);
}