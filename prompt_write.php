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
    confirm_or_redirect("prompt_write");

    include "helpers/top.php";
    ?>
    <div class="main-content">
        <h2>Prompt:</h2>
        <form method="POST" action="actions/submit_prompt.php">
            <textarea  class="form-control" name="prompt" id="myText" class="w-100" aria-describedby="prompt" placeholder="Write prompt here..."></textarea>
            <button class="btn btn-success" onclick="submit()">Submit</button>
        </form>
    </div>
    </div>
    <script>
        function getRandomPrompt() {
            // TODO
        }
    </script>
</body>

</html>