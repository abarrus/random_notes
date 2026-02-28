<?php
require "../helpers/db.php";
include "../helpers/join_game.php";

$nickname = $_POST["nickname"];
$gameId = $_POST["id"];

header("Location: /game.php");
exit;