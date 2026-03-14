<?php

function random_word()
{
    $categories = array("adjectives", "nouns", "verbs", "other");
    // each of the words lists has a 25% chance of being chosen
    $typeOfWord = $categories[array_rand($categories)];

    // load JSON file
    // __DIR__ means the directory THIS file is located in,
    // this is so that it's always the same path to words.json
    // even if random_word.php gets included in another file that's from another directory
    $wordsJson = file_get_contents(__DIR__ . "/../words/".$typeOfWord.".json");

    // convert JSON into PHP array
    $words = json_decode($wordsJson, true); // true = associative array

    // pick random word
    $randomWord = $words[array_rand($words)];

    return $randomWord;
}