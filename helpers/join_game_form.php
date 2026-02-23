<?php
require "db.php";
include "join_game.php";

$nickname = $_POST["nickname"];
$gameId = $_POST["id"];

header("Location: /game.php");
exit;