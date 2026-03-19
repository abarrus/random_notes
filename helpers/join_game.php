<?php
include "session.php";

function give_starting_words($playerId, $gameId)
{
    include "consts.php";
    include "random_word.php";

    $words = [];
    for ($i = 0; $i < $numStartWords; $i++) {
        $newWord = random_word();
        array_push($words, $newWord);
    }

    give_words($gameId, $playerId, $words);
}

function join_game($gameId, $nickname)
{
    $playerId = login($nickname);
    $_SESSION["game_id"] = $gameId;

    if ($nickname == "") {
        $nickname = "Player";
    }

    if (!is_in_game($gameId, $playerId)) {
        give_starting_words($playerId, $gameId);
        add_to_game($playerId, $gameId, $nickname);
    }
}
