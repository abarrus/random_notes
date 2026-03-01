<?php

require_once "db.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_SESSION["player_id"];

echo <<<EOD
<div class="bg-dark text-light p-3">$id</div>
EOD;

echo "HELLOOOOOOO";
