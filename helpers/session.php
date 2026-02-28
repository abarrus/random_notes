<?php

function new_id($nickname)
{
    // make a random ID for this session
    $_SESSION['user_id'] = bin2hex(random_bytes(8)); // 16-char hex

    make_player($_SESSION['user_id'], $nickname);
}

function login($nickname)
{
    session_start();
    if (!isset($_SESSION['user_id'])) {
        new_id($nickname);
    } else {
        // check if user id still in table list
        // could have been deleted due to inactivity

        if(!user_exists($_SESSION['user_id'])) {
            new_id($nickname);
        }
    }
    return $_SESSION['user_id'];
}