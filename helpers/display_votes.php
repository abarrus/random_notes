<?php
require_once "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TODO: this same kinda function is in display_submissions, should we combine them somehow?
// or maybe combine the whole pages... they're not too different
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

$votes = get_votes($gameId);

$players = get_players($gameId);

foreach ($players as $player) {
    // use ($player) lets the function see $player, since that's outside its scope
    if (!containsPlayer($votes, $player["id"])) {
        array_push($votes, array("name" => $player["name"], "id" => $player["id"], "vote" => null));
    }
}

// send as JSON
header('Content-Type: application/json');
// TODO don't need vote in an outer array like that, it's cause we used to have goToVoting in the data
echo json_encode(array("votes" => $votes));

exit;
