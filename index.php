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
    <h1>Random Notes!</h1>
    <div class="main-content">
        <form method="POST" action="actions/create_game.php">
            <div class="form-group">
                <label for="nickname">Your Nickname:</label>
                <input type="text" class="form-control" name="nickname" aria-describedby="yourNickname" placeholder="Name here...">
            </div>
            <div class="card container-fluid overflow-hidden text-center">
                <div class="row">
                    <!-- Make Game half -->
                    <div class="col-12 col-md-6 bg-light py-4">
                        <h2>Make Game</h2>
                        <hr>
                        <input type="text" class="form-control mb-2" name="game-name" aria-describedby="gameName" placeholder="Name here...">
                        <button type="submit" class="btn btn-primary">Make Game</button>
                    </div>
                    <!-- Join Game half -->
                    <div class="col-12 col-md-6 bg-dark text-light py-4">
                        <h2>Join Game</h2>
                        <hr>
                        <div id="games">
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div>
        </form>
    </div>
    <script>
        function loadGames() {
            function addGameToList(id, name, container) {
                container.innerHTML += `
                <div class="row p-2">
                    <button
                        name="id" value="${id}"
                        class="btn btn-dark border-light mb-1"
                        type="submit" formaction="actions/join_game_form.php"
                    >${name}</button>
                </div>`;
            }

            fetch("helpers/list_games.php")
                .then(res => res.json())
                .then(data => {
                    const games = data;

                    const container = document.getElementById("games");
                    container.innerHTML = ''; // clear old stuff

                    games.forEach(game => {
                        if (game.status == "open") {
                            if (game.name === "") {
                                game.name = "Untitled";
                            }
                            addGameToList(game.id, game.name, container);
                        }
                    });
                });
        }
        loadGames();
    </script>
</body>

</html>