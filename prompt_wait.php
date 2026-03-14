<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("prompt_wait");

    include "helpers/top.php";
    ?>
    <div class="main-content">
        <p>Waiting for <span style="font-weight:bold;">
            <?php
                require_once "helpers/db.php";
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                // TODO;: just make a get ptompter name function
                $gameId = $_SESSION["game_id"];
                $prompterId = get_prompt_info($gameId)["prompter"];
                $prompterName = get_name_from_id($gameId, $prompterId);
                echo $prompterName;
            ?>
        </span> to write the prompt... refreshing in <span id="seconds"></span> seconds...</p>
        <p>If you want it faster just reload the page yourself but be warned the server gets mad</p>
        <div class="container-fluid">
            <div class="row g-3" id="submissions"></div>
        </div>
    </div>
    <script>
        const span = document.getElementById("seconds");
        secondsLeft = 10;
        span.innerText = secondsLeft;
        setInterval(myTimer, 1000); // every second
        function myTimer() {
            secondsLeft--;
            span.innerText = secondsLeft;
            if (secondsLeft == 0) {
                window.location.reload();
            }
        }
    </script>
</body>

</html>