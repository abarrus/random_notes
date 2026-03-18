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
    confirm_or_redirect("vote");

    include "helpers/top.php";
    include "helpers/get_prompt.php";
    ?>
    <div class="main-content">
        <h2>Prompt:</h2>
        <div class="card p-3 my-4">
            <p class="p-0 m-0"><?= prompt() ?></p>
        </div>
        <p>Vote for your favorite!</p>
        <form action="/actions/submit_vote.php" method="POST">
            <div id="submissions"></div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script>
        function load() {
            fetch("helpers/display_submissions.php")
                .then(res => res.json())
                .then(data => {
                    const submissionsRow = document.getElementById("submissions");
                    const submissionsWithNames = data.submissions;
                    submissionsWithNames.forEach(submissionWithName => {
                        const player = submissionWithName.name;
                        const submission = submissionWithName.submission;
                        const id = submissionWithName.id;

                        submissionsRow.innerHTML += `
                            <label type="radio" for="${id}" class="card p-2 d-flex flex-row align-items-center mb-3">
                                <input class="me-3" type="radio" id="${id}" name="vote" value="${id}" required>
                                <p class="mb-0" style="white-space:pre-wrap">${submission}</p>
                            </label>
                        `;
                    })
                })
        }
        load();
    </script>
</body>

</html>