<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("vote_wait");

    include "helpers/top.php";
    ?>
    <div class="main-content">
        <p>Waiting for other players to vote... refreshing in <span id="seconds"></span> seconds...</p>
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

        function load() {
            fetch("helpers/display_votes.php")
                .then(res => res.json())
                .then(data => {
                    const submissionsRow = document.getElementById("submissions");
                    const submissionsWithNames = data.votes;
                    submissionsWithNames.forEach(submissionWithName => {
                        const player = submissionWithName.name;
                        const vote = submissionWithName.vote;

                        const voteP = vote == null ?
                            `<p style="color:red;">EMPTY</p>` :
                            `<p style='white-space:pre-wrap'>${vote}</p>`;

                        submissionsRow.innerHTML += `
                            <div class="col-6">
                                <div class="card p-2">
                                    <h3>${player}</h3>
                                    ${voteP}
                                </div>
                            </div>
                        `;
                    })
                })
        }

        load();
    </script>
</body>

</html>