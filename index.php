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

    <form method="POST" action="actions/create_game.php" class="d-flex flex-column min-vh-100">
        <div class="bg-dark text-light p-3 text-center p-5">
            <h1 class="display-3 italic-title fw-bold mb-5">Random Notes!</h1>
            <div class="col-md-4 mx-auto">
                <input type="text" class="form-control" name="nickname" aria-describedby="yourNickname" placeholder="Your Nickname...">
            </div>
        </div>
        <div class="row text-center flex-grow-1">
            <!-- Make Game half -->
            <div class="col-md-6 py-4 border-end d-flex flex-column align-items-center"> <!-- border-end puts line b/w the two halves -->
                <h2>Create</h2>
                <div class="col-8">
                    <input type="text" class="form-control mb-2" name="game-name" aria-describedby="gameName" placeholder="Name here...">
                    <button type="submit" class="btn btn-primary w-100">Make Game</button>
                </div>
            </div>
            <!-- Join Game half -->
            <div class="col-md-6 bg-light text-dark py-4 d-flex flex-column align-items-center">
                <h2>Join</h2>
                <div id="games" class="col-8">
                </div>
            </div>
        </div>
    </form>
    <script>
        function loadGames() {
            function addGameToList(id, name, container) {
                container.innerHTML += `
                <div class="row p-2">
                    <button
                        name="id" value="${id}"
                        class="btn btn-join mb-1 d-flex justify-content-between"
                        type="submit" formaction="actions/join_game_form.php"
                    ><span class="fw-bold">${name}</span><span class="text-muted">Join →</span></button>
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