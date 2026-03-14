<?php
require_once "db.php";

/* could return:
    - stage 1:
        - prompt_write
        - prompt_wait
    - stage 2:
        - submission_write
        - submission_wait
    - stage 3:
        - vote
        - vote_wait
    - stage 4:
        - results
*/
function get_page() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["game_id"]) || !isset($_SESSION["player_id"])) {
        header("LOCATION: /");
        exit;
    }

    $gameId = $_SESSION["game_id"];
    $playerId = $_SESSION["player_id"];

    // is there a prompt?
    $prompt_info = get_prompt_info($gameId);
    if ($prompt_info["prompt"] == null) {
        // if no prompt: are you the prompter?
        if ($prompt_info["prompter"] == $playerId) {
            return "prompt_write";
        } else {
            return "prompt_wait";
        }
    }
    // has your submission been submitted yet?
    if (!has_submitted($gameId, $playerId)) {
        return "submission_write";
    }
    // have all submissions been submitted?
    if (!all_submitted($gameId)) {
        return "submission_wait";
    }
    // have you voted?
    if (!has_voted($gameId, $playerId)) {
        return "vote";
    }
    // has everyone voted?
    if (!all_voted($gameId)) {
        return "vote_wait";
    }
    return "results";
}