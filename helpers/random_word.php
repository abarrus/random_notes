<?php

function random_word()
{
    require_once "db.php";
    $conn = connect();

    $categories = array("adjectives", "nouns", "verbs", "verbs", "other", "other", "other");
    // each of the words lists has a 25% chance of being chosen
    $cat = $categories[array_rand($categories)];
    
    $sql = "SELECT word FROM AllWords WHERE category = ? ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cat]);

    return $stmt->fetchColumn();
}

function give_random_words($playerId, $gameId, $numWords)
{
    require_once "db.php";

    $words = [];
    for ($i = 0; $i < $numWords; $i++) {
        $newWord = random_word();
        array_push($words, $newWord);
    }

    give_words($gameId, $playerId, $words);
}