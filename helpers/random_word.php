<?php

function random_word()
{
    // load JSON file
    // __DIR__ means the directory THIS file is located in,
    // this is so that it's always the same path to words.json
    // even if random_word.php gets included in another file that's from another directory
    $wordsJson = file_get_contents(__DIR__ . "/../words.json");

    // convert JSON into PHP array
    $words = json_decode($wordsJson, true); // true = associative array

    // pick random word
    $randomWord = $words[array_rand($words)];

    return $randomWord;
}