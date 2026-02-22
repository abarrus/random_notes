<?php
require_once 'db.php';

// deletes games where last change is 1+ days ago
function clean_old_games($conn)
{
    $stmt = "
    DELETE FROM Games
    WHERE last_changed < NOW() - INTERVAL 1 DAY
    ";
    $conn->exec($stmt);
}

function get_games($conn)
{
    $stmt = $conn->query("SELECT id, status, last_changed, name FROM Games ORDER BY last_changed DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


clean_old_games($conn);
$games = get_games($conn);

// send as JSON
header('Content-Type: application/json');
echo json_encode($games);
