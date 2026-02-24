<?php
include "login.php";
include "consts.php";

function join_game($conn, $gameId, $nickname)
{
    // no empty vars
    if (strlen($nickname) === 0) {
        $nickname = "Player";
    }

    $playerId = login($nickname, $conn);

    // check if they already have words
    $sql = "SELECT * FROM Words WHERE player_id = ? AND game_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($playerId, $gameId);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) === 0) {
        // they don't have words; they JUST joined this game
        // give them words
    }
}