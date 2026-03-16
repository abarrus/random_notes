<?php
// make_db.php
// this isn't normally run, it's just for if I move databases.

require_once "db.php";

$tables = [
    "Words",

    // probably getting rid of this table soon
    "Players",

    "GamePlayers",

    "Moves",

    "Games",

    "AllWords",

    "Prompts"
];

$conn = connect();

// make and truncate tables
foreach ($tables as $name) {
    try {
        $stmt = $conn->prepare("SELECT * FROM ?");
        $stmt->execute([$name]);

        $res = $stmt->fetchAll();
        echo("<h2>".$name."<h2>");
        print_r($res);

        echo("<br><br>");
    } catch (PDOException $e) {
        echo "Error printing $name: " . $e->getMessage() . "<br>";
    }
}