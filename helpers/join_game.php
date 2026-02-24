<?php
include "login.php";

function has_words($conn, $playerId, $gameId)
{
    $sql = "SELECT * FROM Words WHERE player_id = ? AND game_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($playerId, $gameId);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return count($results) > 0;
}

function give_starting_words($conn, $playerId, $gameId)
{
    include "consts.php";
    include "random_word.php";

    $sql = "INSERT INTO Words (game_id, player_id, word) VALUES (?,?,?);";
    $stmt = $conn->prepare($sql);
    for ($i = 0; $i < $numStartWords; $i++) {
        $word = random_word();
        $stmt->execute($gameId, $playerId, $word);
    }
}

function join_game($conn, $gameId, $nickname)
{
    // no empty vars
    if (strlen($nickname) === 0) {
        $nickname = "Player";
    }

    $playerId = login($nickname, $conn);

    if (!has_words($conn, $playerId, $gameId)) {
        give_starting_words($conn, $playerId, $gameId);
    }
}