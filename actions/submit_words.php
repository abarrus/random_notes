<?php
include "../helpers/db.php";
include "../helpers/consts.php";
include "../helpers/random_word.php";

/**
 * split by whitespace (not including the whitespace)
 * and also split all non-letter characters into their own item in the list
 * 
 * @param str The string to split into a list
 * @return list(string)
 */
function split_words($str)
{
    // the pattern:
    // \s+           Match one or more spaces
    // |             OR
    // (?=[^a-z])    lookahead: position followed by non-letter
    // |             OR
    // (?<=[^a-z])   Lookbehind: position preceded by non-letter
    // i             Case-insensitive
    // we are matching the character we DON'T need btw, the place to split, so that's why we match spaces even tho we discard them
    // and the lookaheads and lookbehinds mean that each non-letter characters will have their own spot in the list
    $pattern = "/\s+|(?=[^a-z])|(?<=[^a-z])/i";
    // preg stands for Perl REGular expression
    // the -1 means there's no limit to the number of elements the returned array can have
    // PREG_SPLIT_NO_EMPTY means empty strings will be removed from the returned array.
    $splitWordsList = preg_split($pattern, $str, -1, PREG_SPLIT_NO_EMPTY);

    return $splitWordsList;
}

/**
 * Make sure that this is an allowed submission.
 * And return which words the player used.
 * 
 * @param gameId    16-char game ID
 * @param playerId  16-char player ID
 * @param submission    The sentence the player is submitting
 * @return array{
 *      legal: boolean,
 *      remainingWords: list(string),
 *      wordsUsedCount: int,
 *      illegalWord: string (if the submission isn't allowed, what triggered it)
 * }
 */
function check_sentence($gameId, $playerId, $submission, $allowedPunctuation)
{
    $splitWordsList = split_words($submission);
    $remainingAllowedWords = get_words($gameId, $playerId);
    $wordsUsedCount = 0;

    $legal = true;
    $illegalWord = "";

    foreach ($splitWordsList as $word) {
        $i = array_search($word, $remainingAllowedWords);
        if ($i === false) {
            if (strlen($word) === 1 && str_contains($allowedPunctuation, $word)) {
                continue;
            }
            $legal = false;
            $illegalWord = $word;
            break;
        } else {
            array_splice($remainingAllowedWords, $i, 1);
            $wordsUsedCount++;
        }
    }

    return array("legal" => $legal, "remainingWords" => $remainingAllowedWords, "illegalWord" => $illegalWord, "wordsUsedCount" => $wordsUsedCount);
}

function exit_if_already_submitted($gameId, $playerId)
{
    $submissions = get_submissions($gameId);
    $alreadySubmitted = !empty(array_filter($submissions, function ($submission) use ($playerId) {
        return $submission["id"] === $playerId;
    }));
    if ($alreadySubmitted) {
        echo "NOT ALLOWED: This player has already submitted once.";
        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$gameId = $_SESSION["game_id"];
$playerId = $_SESSION["player_id"];

// check that the player hasn't already submitted
exit_if_already_submitted($gameId, $playerId);

$submission = $_POST["submission"];

$res = check_sentence($gameId, $playerId, $submission, $allowedPunctuation);
if (!$res["legal"]) {
    echo "NOT ALLOWED: At least one word / character that was not on your word list: " . $res["illegalWord"];
    exit;
}
clear_words($gameId, $playerId);
give_words($gameId, $playerId, $res["remainingWords"]);
give_random_words($playerId, $gameId, $res["wordsUsedCount"]);

submit_sentence($gameId, $playerId, $submission);

header("LOCATION: /submission_wait.php");
exit;
