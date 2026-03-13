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
        name VARCHAR(255),
    )",

    "Moves" => "CREATE TABLE IF NOT EXISTS Moves (
        game_id CHAR(16),
        player_id CHAR(16),
        submission VARCHAR(1000),
        vote CHAR(16),
        round SMALLINT,
    )",

    "Games" => "CREATE TABLE IF NOT EXISTS Games (
        id CHAR(16),
        last_changed TIMESTAMP,
        name VARCHAR(255),
        status ENUM('open', 'closed') DEFAULT 'open',
        round SMALLINT DEFAULT 0
    )"
];

$conn = connect();

foreach ($tables as $name => $sql) {
    try {
        $conn->exec($sql);
        echo "Table $name is ready.\n";
    } catch (PDOException $e) {
        echo "Error creating $name: " . $e->getMessage() . "\n";
    }
}