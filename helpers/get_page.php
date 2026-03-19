<?php
require_once "db.php";

/* could return:
    - ROUND 0:
        - gather
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

    // redirects to home page if you go to a game page without being "logged in"
    if (!isset($_SESSION["game_id"]) || !isset($_SESSION["player_id"])) {
        header("LOCATION: /");
        exit;
    }

    $gameId = $_SESSION["game_id"];
    $playerId = $_SESSION["player_id"];

    $prompt_info = get_prompt_info($gameId);

    // is it round 0? (round 0 means lobby)
    if (get_round($gameId) == 0) {
        // if round 0: are you the prompter? (prompter is in charge of clicking start game)
        if ($prompt_info["prompter"] == $playerId) {
            return "lobby_prompter";
        } else {
            return "lobby";
        }
    }
    
    // is there a prompt?
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