<?php
require_once "db.php";


function echo_results()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $gameId = $_SESSION["game_id"];

    $results = get_results($gameId) ?? [];

    foreach ($results as $row) {
        echo $row["submission"] . " by ".$row["name"].": " . $row["vote_count"] . " votes<br>";
    }
}