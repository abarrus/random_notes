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
    include "helpers/display_game.php";
    display_game();
    ?>
    <br>
    <textarea id="myText"></textarea>
    <p id="err"></p>
    <div id="words-container"></div>
    <script>
        words = [];

        function load() {
            fetch("helpers/get_words.php")
                .then(res => res.json())
                .then(data => {
                    words = data;

                    container = document.getElementById("words-container");
                    container.innerHTML = "";
                    words.forEach(word => {
                        container.innerHTML += `<div class="word" draggable="true">${word}</div>`;
                    })
                })
        }

        load();


        const ta = document.getElementById('myText');
        const status = document.getElementById('status');

        function wordIsInList(wordToCheck, listToCheck) {
            return listToCheck.some(wordInWords => wordToCheck.toLowerCase() === wordInWords.toLowerCase());
        }

        ta.addEventListener('input', () => {
            ta.value = ta.value.replace(/[^a-zA-Z ]/g, "");
            text = ta.value.split(" ");

            valid = true;
            console.log("text:",text);
            wordsUsed = [];

            err = document.getElementById("err");

            text.forEach(word => {
                if (word === "") return;

                if (!wordIsInList(word, words)) {
                    valid = false;
                    err.textContent = "Invalid word: "+word;
                } else {
                    if (wordIsInList(word, wordsUsed)) {
                        // no duplicates
                        valid = false;
                        err.textContent = "Duplicate word not allowed: "+word;
                    }
                    wordsUsed.push(word);
                }
            })

            if (valid) {
                ta.style.borderColor = 'green';
                err.textContent = "";
            } else {
                ta.style.borderColor = 'red';
            }
        });
    </script>
</body>

</html>