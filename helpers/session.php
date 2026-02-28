<?php

function new_id($nickname, $conn)
{
    // make a random ID for this session
    $_SESSION['user_id'] = bin2hex(random_bytes(8)); // 16-char hex

    $sql = "INSERT INTO Users (id, name, last_active)
VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $nickname]);
}

function login($nickname, $conn)
{
    session_start();
    if (!isset($_SESSION['user_id'])) {
        new_id($nickname, $conn);
    } else {
        // check if user id still in table list
        // could have been deleted due to inactivity

        $sql = "SELECT * FROM Users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) === 0) {
            // user id not in table list, so just make a new one
            new_id($nickname, $conn);
        }
    }
    return $_SESSION['user_id'];
}