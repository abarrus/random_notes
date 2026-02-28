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

$sql = "INSERT INTO Games (last_changed, status, name, id)
VALUES (NOW(), 'open', ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$gameName, $gameId]);

header("Location: /index.php");
exit;


join_game($conn, $gameId, $nickname);
header("Location: /game.php");
exit;
