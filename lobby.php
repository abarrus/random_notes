<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby - Random Notes!</title>
    <script src="js/auto_refresh.js"></script>
</head>

<body class="d-flex vh-100 flex-column" onload="autoRefreshAlert(true)">
    <?php
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("lobby");

    include "helpers/top.php";
    ?>
    <div style="background-color:#607196" class="main-content d-flex flex-grow-1 justify-content-center align-items-center">
        <div class="card w-75">
            <div class="border-bottom p-4 mb-0">
                <h1 class="fw-bold">Players</h1>
            </div>
            <div id="players" class="p-4 d-flex flex-wrap gap-2 justify-content-center"></div>
            <div class="border-top d-flex flex-column flex-sm-row justify-content-center p-4 justify-content-sm-between align-items-center">
                <div id="auto-refresh-alert"></div>
                <button onclick="refreshList()" class="btn btn-outline-secondary btn-sm rounded-pill px-4">Refresh List</button>
            </div>
        </div>
    </div>
    <script>
        function refreshList() {
            fetch("helpers/display_players.php")
                .then(res => res.json())
                .then(newPlayers => {
                    const playersRow = document.getElementById("players");
                    const newChildren = [];
                    newPlayers.forEach(player => {
                        const div = document.createElement("div");
                        div.className = ("p-2 px-4 border rounded-pill shadow-sm");
                        div.textContent = player.name;
                        newChildren.push(div);
                    });
                    playersRow.replaceChildren(...newChildren);
                })
        }
        refreshList();
    </script>
</body>

</html>