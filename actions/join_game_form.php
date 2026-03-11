<?php
include "../helpers/db.php";
include "../helpers/join_game.php";

$nickname = $_POST["nickname"];
$gameId = $_POST["id"];

join_game($gameId, $nickname);

header("Location: /submission_write.php");
exit;