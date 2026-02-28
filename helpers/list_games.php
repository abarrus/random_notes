<?php
require_once 'db.php';

clean_old_games();
$games = get_games();

// send as JSON
header('Content-Type: application/json');
echo json_encode($games);