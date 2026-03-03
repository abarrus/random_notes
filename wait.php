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
    include "helpers/top.php";
    ?>
    <div class="main-content">
        <p>Waiting for other players... refreshing in <span id="seconds"></span> seconds...</p>
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
            console.log("load");
            fetch("helpers/display_submissions.php")
                .then(res => res.json())
                .then(data => {

                    console.log(data);
                    const submissionsRow = document.getElementById("submissions");
                    data.forEach(submissionWithName => {
                        console.log("for", submissionWithName);
                        const player = submissionWithName.name;
                        const submission = submissionWithName.submission;

                        const submissionP = submission == null ?
                            `<p style="color:red;">EMPTY</p>` :
                            `<p style='white-space:pre-wrap'>${submission}</p>`;

                        submissionsRow.innerHTML += `
                            <div class="col-6">
                                <div class="card p-2">
                                    <h3>${player}</h3>
                                    ${submissionP}
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