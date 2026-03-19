<?php
require_once "db.php";

/**
 * Finds the value's index and splices the array. Returns new array.
 * 
 * @param arr list<array{
 *    name: string,
 *    id: 16-char string
 * }>
 * @param id The id of the player to remove
 * @return list The array without the value in it.
 */
function remove_player($players, $id) {
    $i = array_search($id, array_column($players, "id"));
    if ($i !== false) {
        array_splice($players, $i, 1);
    }
    return $players;
}

/**
 * Selects a random user from the game and sets them to be prompter
 * But, if there's more than 1 player it won't let the current prompter be selected.
 * (so that it's not the same person twice in a row)
 * 
 * @param gameId 16-char game ID
 */
function random_prompter($gameId) {
    $players = get_players($gameId);
    if (count($players) > 1) {
        $currentPrompter = get_prompt_info($gameId)["prompter"];
        $players = remove_player($players, $currentPrompter);
    }
    $prompterId = $players[array_rand($players)]["id"];

    set_prompter($gameId, $prompterId);
}