<?php

function random_word()
{
    // 1. load the JSON file
    $wordsJson = file_get_contents("words.json");

    // 2. convert JSON into PHP array
    $words = json_decode($wordsJson, true); // true = associative array

    // 3. pick a random word
    $randomWord = $words[array_rand($words)];

    return $randomWord;
}