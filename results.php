<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results - Random Notes!</title>
</head>

<body>
    <?php
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("results");

    include "helpers/top.php";
    include "helpers/get_prompt.php";
    ?>
    <div class="main-content">
        <h1 class="text-center m-3">Results!</h1>
        <div class="card p-3 my-4 mb-5">
            <p class="p-0 m-0"><?= prompt() ?></p>
        </div>
        <div id="results"></div>
    </div>
    <script>
        function load() {
            const resultsDiv = document.getElementById("results");

            fetch("helpers/get_results.php")
                .then(res => res.json())
                .then(data => {
                    let rank = 1;
                    data.forEach(result => {
                        resultsDiv.innerHTML += `
                            <div class="d-flex mb-3 align-items-center">
                                <div class="text-center me-4">
                                    <span class="text-muted text-uppercase d-block">rank</span>
                                    <p style="font-size: ${Math.max(20, 40 - rank*5)}px">${rank}</p>
                                </div>
                                <div class="card w-100 p-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="text-muted text-uppercase">${result.name}</h6>
                                        <span class="badge bg-primary rounded-pill py-2 px-3">${result.vote_count} votes</span>
                                    </div>
                                    <p class="mb-0" style="white-space:pre-wrap;">${result.submission}</p>
                                </div>
                            </div>
                        `;
                        rank++;
                    })
                })
        }

        load();
    </script>
</body>

</html>