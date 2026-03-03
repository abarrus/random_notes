<?php

require_once "db.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_SESSION["player_id"];
$name = get_nickname($id);

echo <<<EOD
<div class="bg-dark text-light p-3">$name</div>
EOD;

echo "HELLOOOOOOO";
