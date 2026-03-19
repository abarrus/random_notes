<?php

function join_game($gameId, $nickname)
{
    include "session.php";
    include "random_word.php";
    include "consts.php";

    $playerId = login($nickname);
    $_SESSION["game_id"] = $gameId;

    if ($nickname == "") {
        $nickname = "Player";
    }

    if (!is_in_game($gameId, $playerId)) {
        give_random_words($playerId, $gameId, $numStartWords);
        add_to_game($playerId, $gameId, $nickname);
    }
}