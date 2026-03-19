<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="d-flex vh-100 flex-column">
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
            <div class="border-top d-flex justify-content-between p-4 align-items-center">
                <p class="mb-0 text-muted small">Waiting!</p>
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

        setInterval(refreshList, 5000); // every 5 seconds
    </script>
</body>

</html>