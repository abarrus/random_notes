<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/auto_refresh.js"></script>
</head>

<body onload="autoRefreshAlert()">
    <?php
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("submission_wait");

    include "helpers/top.php";
    ?>
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <h5 class="text-success border-bottom pb-2">Submitted</h5>
                    <div id="voted-list" class="list-group"></div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-warning border-bottom pb-2">Still Writing...</h5>
                    <div id="waiting-list" class="list-group"></div>
                </div>
            </div>
            <div class="row g-3" id="submissions"></div>
        </div>
        <div class="container-fluid">
            <div class="row g-3" id="submissions"></div>
        </div>
    </div>
    <script>
        function load() {
            fetch("helpers/display_submissions.php")
                .then(res => res.json())
                .then(data => {
                    const votedList = document.getElementById("voted-list");
                    const waitingList = document.getElementById("waiting-list");

                    const submissionsWithNames = data.submissions;
                    submissionsWithNames.forEach(submissionWithName => {
                        const player = submissionWithName.name;
                        const hasSubmitted = submissionWithName.submission != null;

                        if (hasSubmitted) {
                            // Item for those who have voted
                            votedList.innerHTML += `
                                <div class="list-group-item d-flex justify-content-between align-items-center list-group-item-success">
                                    <span class="fw-bold">${player}</span>
                                    <span class="badge bg-success rounded-pill">Ready</span>
                                </div>`;
                        } else {
                            // Item for those we are waiting for
                            waitingList.innerHTML += `
                                <div class="list-group-item d-flex justify-content-between align-items-center opacity-75">
                                    <span>${player}</span>
                                    <span class="badge bg-warning rounded-pill">Hurry Up</span>
                                </div>`;
                        }
                    })
                })
        }

        load();
    </script>
</body>

</html>