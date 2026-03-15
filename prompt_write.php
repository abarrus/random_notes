<!DOCTYPE html>
<html>

<head>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- my styles -->
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="d-flex vh-100 flex-column">
    <?php
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("prompt_write");

    include "helpers/top.php";
    ?>
    <div style="background-color:#607196" class="main-content d-flex flex-grow-1 justify-content-center align-items-center">
        <form method="POST" action="actions/submit_prompt.php" class="w-100">
            <div class="card">
                <textarea required class="form-control border-0 shadow-none" rows="6" name="prompt" id="myText" aria-describedby="prompt" placeholder="Write prompt here..."></textarea>
                <div class="d-flex justify-content-between border-top p-2">
                    <button class="btn btn-light border-secondary rounded-pill px-4 text-muted" onclick="getRandomPrompt()" type="button"><i class="bi bi-shuffle me-2"></i>Random Prompt</button>
                    <button class="btn btn-success rounded-pill px-4" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        function getRandomPrompt() {
            fetch("helpers/random_prompt.php")
                .then(res => res.json())
                .then(prompt => {
                    const p = document.getElementById("myText");
                    p.value = prompt;
                })
        }
    </script>
</body>

</html>