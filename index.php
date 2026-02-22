<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main-content">
        <form method="POST" action="helpers/create_game.php">
            <div class="form-group">
                <label for="nickname">Your Nickname:</label>
                <input type="text" class="form-control" name="nickname" aria-describedby="yourNickname" placeholder="Name here...">
            </div>
            <div class="card container-fluid overflow-hidden text-center">
                <div class="row">
                    <!-- Make Game half -->
                    <div class="col bg-light py-4">
                        <h2>Make Game</h2>
                        <hr>
                        <input type="text" class="form-control mb-2" name="game-name" aria-describedby="gameName" placeholder="Name here...">
                        <button type="submit" class="btn btn-primary">Make Game</button>
                    </div>
                    <!-- Join Game half -->
                    <div class="col bg-dark text-light py-4">
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
                <div class="row">
                    <button class="btn btn-dark rounded-0" type="submit">${name}</button>
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