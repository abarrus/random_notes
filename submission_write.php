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
    confirm_or_redirect("submission_write");

    include "helpers/top.php";
    ?>
    <div class="main-content">
        <h2>Prompt:</h2>
        <p id="prompt">Prompt goes here...</p>
        <h2>Your Submission:</h2>
        <div class="container-fluid">
            <form method="POST" action="actions/submit_words.php">
                <div class="row">
                    <textarea name="submission" id="myText" class="w-100" rows="1"></textarea>
                </div>
                <div class="row">
                    <p id="err"></p>
                </div>
                <!-- g-3 = gap between rows 1rem -->
                <div class="row g-3" id="words-container"></div>
                <hr>
                <button class="btn btn-success" onclick="submit()">Submit</button>
            </form>
            <hr>
            <div class="row mt-3">
                <p class="mb-0">Notes:</p>
                <ul>
                    <li>You don't have to use all words.</li>
                    <li>No duplicates (unless you actually have multiple of a word).</li>
                    <li> No special characters or numbers.</li>
                    <li>You can add whatever whitespace you want.</li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        words = [];
        unusedWords = [];

        function updateWordsHTML() {
            container = document.getElementById("words-container");
            container.innerHTML = "";
            unusedWords.forEach(word => {
                container.innerHTML += `
                    <div class="col-4">
                        <button class="btn btn-primary w-100" onclick="addWord('${word}')">
                            ${word}
                        </button>
                    </div>`;
            });
        }

        function load() {
            fetch("helpers/get_words.php")
                .then(res => res.json())
                .then(data => {
                    unusedWords = [...data];
                    words = data; // words is global var
                    updateWordsHTML();
                })
            fetch("helpers/get_prompt.php")
                .then(res => res.json())
                .then(prompt => {
                    const p = document.getElementById("prompt");
                    p.innerText = prompt;
                });
        }

        load();



        const ta = document.getElementById('myText');

        function addWord(word) {
            // add word to textarea, with extra space (only if necessary)
            lastLetter = s => s.charAt(s.length - 1);
            lastLetterIsWhitespace = /\s/.test(lastLetter(ta.value));
            if (ta.value === "" || lastLetterIsWhitespace) {
                ta.value += word;
            } else {
                ta.value += " " + word;
            }

            // remove word from unusedWords
            unusedWords = unusedWords.filter(w => w !== word);
            updateWordsHTML();
        }

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

            updateWordsHTML();
        });
    </script>
</body>

</html>