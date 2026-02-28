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
    <div class="container">
        <div class="row">
            <div class="col">
                <textarea id="myText" class="w-100" rows="1"></textarea>
            </div>
            <div class="row">
                <p id="err"></p>
            </div>
        </div>
        <div class="row">
            <div id="words-container"></div>
        </div>
    </div>
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
            // \r is Windows newlines?
            ta.value = ta.value.replace(/[^a-zA-Z \n\r]/g, "");
            // split on one or more whitespace
            text = ta.value.split(/\s+/);

            valid = true;
            unusedWords = [...words];

            err = document.getElementById("err");

            duplicateWords = [];
            invalidWords = [];

            text.forEach(word => {
                if (word === "") return;

                if (!wordIsInList(word, unusedWords)) {
                    valid = false;
                    if (wordIsInList(word, words)) {
                        duplicateWords.push(word);
                    } else {
                        invalidWords.push(word);
                    }
                } else {
                    // it's valid so remove it from list of unusedWords
                    unusedWords = unusedWords.filter(w => w !== word);
                }
            })

            err.textContent = "";
            if (invalidWords.length > 0) {
                err.textContent = "Invalid word(s): " + invalidWords.join(", ");
            }
            if (duplicateWords.length > 0) {
                err.innerHTML += "<br>Duplicate word(s): " + duplicateWords.join(", ");
            }

            if (valid) {
                ta.style.borderColor = 'green';
            } else {
                ta.style.borderColor = 'red';
            }

            // textarea rows increases when you add a newline

            // reset height so shrinking works too
            ta.style.height = 'auto';
            // set height to scrollHeight
            ta.style.height = ta.scrollHeight + 'px';
        });
    </script>
</body>

</html>