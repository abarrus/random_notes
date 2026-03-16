<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Does $submissions have a submission from $playerName?
 * 
 * @param $submissions  List of associative arrays.
 *                      The arrays should have "name" and "submission"
 * @param $playerName   String. The name
 */
function containsPlayer($submissions, $playerId)
{
    return !empty(array_filter($submissions, function ($submission) use ($playerId) {
        return $submission["id"] == $playerId;
    }));
}

$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

$submissions = get_submissions($gameId);

$players = get_players($gameId);

foreach ($players as $player) {
    // use ($player) lets the function see $player, since that's outside its scope
    if (!containsPlayer($submissions, $player["id"])) {
        array_push($submissions, array("name" => $player["name"], "id" => $player["id"], "submission" => null));
    }
}

// send as JSON
header('Content-Type: application/json');
echo json_encode(array("submissions" => $submissions));

exit;
