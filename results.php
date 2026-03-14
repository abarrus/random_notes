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
    include "helpers/results.php";
    include "helpers/confirm_or_redirect.php";
    confirm_or_redirect("results");

    include "helpers/top.php";
    ?>
    <div class="main-content">
        <p>Results!</p>
        <?php
            echo_results();
        ?>
    </div>
</body>

</html>