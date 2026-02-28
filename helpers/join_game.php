<?php
include "session.php";

function give_starting_words($playerId, $gameId)
{
    include "consts.php";
    include "random_word.php";

    for ($i = 0; $i < $numStartWords; $i++) {
        $word = random_word();
        give_word($gameId, $playerId, $word);
    }
}

function join_game($gameId, $nickname)
{
    $playerId = login($nickname);
    $_SESSION["game_id"] = $gameId;

    if (!is_in_game($gameId, $playerId)) {
        give_starting_words($playerId, $gameId);
    }
}