<?php
// make_db.php
// this isn't normally run, it's just for if I move databases.

require_once "db.php";

$tables = [
    "Words" => "CREATE TABLE IF NOT EXISTS Words (
        game_id CHAR(16),
        player_id CHAR(16),
        word VARCHAR(255)
    )",

    // probably getting rid of this table soon
    "Players" => "CREATE TABLE IF NOT EXISTS Players (
        id CHAR(16),
        last_active TIMESTAMP,
        name VARCHAR(255)
    )",

    "GamePlayers" => "CREATE TABLE IF NOT EXISTS GamePlayers (
        id CHAR(16),
        game_id CHAR(16),
        name VARCHAR(255)
    )",

    "Moves" => "CREATE TABLE IF NOT EXISTS Moves (
        game_id CHAR(16),
        player_id CHAR(16),
        submission VARCHAR(1000),
        vote CHAR(16),
        round SMALLINT
    )",

    "Games" => "CREATE TABLE IF NOT EXISTS Games (
        id CHAR(16),
        last_changed TIMESTAMP,
        name VARCHAR(255),
        status ENUM('open', 'closed') DEFAULT 'open',
        round SMALLINT DEFAULT 0,
        prompt VARCHAR(1000),
        prompter CHAR(16)
    )",

    "AllWords" => "CREATE TABLE IF NOT EXISTS AllWords (
        word VARCHAR(255),
        category ENUM('nouns', 'verbs', 'adjectives', 'other')
    )",

    "Prompts" => "CREATE TABLE IF NOT EXISTS Prompts (
        prompt VARCHAR(1000)
    )"
];

$conn = connect();

// make and truncate tables
foreach ($tables as $name => $sql) {
    try {
        $conn->exec("DROP TABLE IF EXISTS " . $name);

        $conn->exec($sql);

        echo "Table $name is ready.<br>";
    } catch (PDOException $e) {
        echo "Error creating $name: " . $e->getMessage() . "<br>";
    }
}

// fill words table
$categories = array("adjectives", "nouns", "verbs", "other");
$params = [];
$totalWords = 0;
foreach ($categories as $cat) {
    $wordsJson = file_get_contents(__DIR__ . "/../words/" . $cat . ".json");
    $words = json_decode($wordsJson);

    foreach ($words as $word) {
        $params[] = $word;
        $params[] = $cat;
    }

    $totalWords += count($words);
}

$placeholders = str_repeat("(?, ?),", $totalWords - 1) . "(?, ?)";
$sql = "INSERT INTO AllWords (word, category) VALUES $placeholders";
$stmt = $conn->prepare($sql);
$stmt->execute($params);

echo "Done adding all words.<br>";

// fill prompts table
$promptsJson = file_get_contents(__DIR__ . "/../words/prompts.json");
$prompts = json_decode($promptsJson);
$placeholders = str_repeat("(?),", count($prompts) - 1) . "(?)";
$sql = "INSERT INTO Prompts (prompt) VALUES $placeholders";
$stmt = $conn->prepare($sql);
$stmt->execute($prompts);

echo "Done adding all prompts.<br>";
