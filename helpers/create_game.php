<?php
require_once 'db.php';

$nickname = $_POST["nickname"];
$gameName = $_POST["game-name"];

// no empty vars
if (strlen($gameName) === 0) {
    $gameName = "Game";
}

echo("name".$gameName);

$sql = "INSERT INTO Games (last_changed, status, name)
VALUES (NOW(), 'open', ?)";
$stmt = $conn -> prepare($sql);
$stmt->execute([$gameName]);

header("Location: /index.php");