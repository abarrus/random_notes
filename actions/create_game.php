<?php

require_once '../helpers/db.php';
include "../helpers/join_game.php";

$nickname = $_POST["nickname"];
$gameName = $_POST["game-name"];

// no empty vars
if (strlen($gameName) === 0) {
    $gameName = "Game";
}

$gameId = bin2hex(random_bytes(8)); // 16-char hex

make_game($gameName, $gameId);

// TODO - go to game instead. can't have 2 headers.
// this part of the code doesn't run yet btw
join_game($gameId, $nickname);
header("Location: /game.php");
exit;