<?php
include "get_page.php";

function confirm_or_redirect($current_page) {
    $correct_page = get_page();
    if ($correct_page != $current_page) {
        header("LOCATION: /".$correct_page.".php");
        exit;
    }
}