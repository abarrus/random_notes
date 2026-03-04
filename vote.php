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
        <p>Vote for your favorite!</p>
        <div class="container-fluid">
            <div class="row g-3" id="submissions"></div>
        </div>
    </div>
    <script>
        function load() {
            fetch("helpers/display_submissions.php")
                .then(res => res.json())
                .then(data => {
                    const submissionsRow = document.getElementById("submissions");
                    const submissionsWithNames = data.submissions;
                    const goToVoting = data.goToVoting; // true or false
                    console.log("go to voting?",goToVoting);
                    submissionsWithNames.forEach(submissionWithName => {
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
                                    <input type="checkbox" id="${player}" name="vote" value="${player}">
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