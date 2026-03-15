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
    confirm_or_redirect("submission_write");

    include "helpers/top.php";

    include "helpers/get_prompt.php";
    ?>
    <div class="main-content">
        <h2>Prompt:</h2>
        <p><?= prompt() ?></p>
        <h2>Your Submission:</h2>
        <form method="POST" action="actions/submit_words.php" id="myForm">
            <textarea required name="submission" class="form-control" id="myText" class="w-100" rows="1" aria-describedby="submission" placeholder="Write submission here..."></textarea>
            <p id="err"></p>
            <div class="d-flex flex-wrap gap-3" id="words-container">
            </div>
            <hr>
            <button class="btn btn-success" type="submit">Submit</button>
        </form>
        <hr>
        <p class="mb-0">Notes:</p>
        <ul>
            <li>You don't have to use all words.</li>
            <li>No duplicates (unless you actually have multiple of a word).</li>
            <li> No special characters or numbers.</li>
            <li>You can add whatever whitespace you want.</li>
        </ul>
    </div>
    <script>
        // each word has a color and shape
        words = [];
        unusedWords = [];

        function updateWordsHTML() {
            container = document.getElementById("words-container");
            container.innerHTML = "";
            unusedWords.forEach(word => {
                const wordData = words.find(w => w.text == word)
                container.innerHTML += `
                    <button style="background-color: ${wordData.color}; border: 1px solid color-mix(in oklab, ${wordData.color}, black 25%)" class="btn text-dark" onclick="addWord('${word}')">
                        ${word}
                    </button>`
            });
        }

        // make words list with random colors and shapes
        // give it just a regular list of words
        function initializeWordsList(wordsToAdd) {
            const colors = ["#ccd5ae", "#e9edc9", "#fefae0", "#faedcd", "#d4a373"];
            // a subset of colors
            const shapes = ["round", "square"];

            wordsToAdd.forEach(word => {
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                const randomShape = shapes[Math.floor(Math.random() * shapes.length)];
                words.push({
                    text: word,
                    color: randomColor,
                    shape: randomShape
                });
            })
        }

        function load() {
            fetch("helpers/get_words.php")
                .then(res => res.json())
                .then(data => {
                    unusedWords = [...data];
                    initializeWordsList(data);
                    updateWordsHTML();
                })
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

            // it's valid so remove ONE of it from list of unusedWords
            // (if there are duplicates we don't want to remove them all)
            unusedWords.splice(unusedWords.indexOf(word), 1);
            updateWordsHTML();
        }

        function wordIsInWords(wordToCheck) {
            return words.some(obj => wordToCheck.toLowerCase() === obj.text.toLowerCase());
        }

        ta.addEventListener('input', () => {
            // \r is Windows newlines?
            ta.value = ta.value.replace(/[^a-zA-Z \n\r]/g, "");
            // split on one or more whitespace
            text = ta.value.split(/\s+/);

            valid = true;

            unusedWords = words.map(obj => obj.text);

            err = document.getElementById("err");

            duplicateWords = [];
            invalidWords = [];

            text.forEach(word => {
                if (word === "") return;

                if (!unusedWords.includes(word)) {
                    valid = false;
                    if (wordIsInWords(word)) {
                        duplicateWords.push(word);
                    } else {
                        invalidWords.push(word);
                    }
                } else {
                    // it's valid so remove ONE of it from list of unusedWords
                    // (if there are duplicates we don't want to remove them all)
                    unusedWords.splice(unusedWords.indexOf(word), 1);
                }
            })

            err.textContent = "";
            if (invalidWords.length > 0) {
                err.innerHTML = "Invalid word(s): " + invalidWords.join(", ") + "<br>";
            }
            if (duplicateWords.length > 0) {
                err.innerHTML += "Duplicate word(s): " + duplicateWords.join(", ") + "<br>";
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

        const form = document.getElementById("myForm");
        form.addEventListener('submit', e => {
            err = document.getElementById("err");
            if (err.textContent != "") {
                e.preventDefault();
                alert("Can't submit, there's invalid or duplicate words");
            }
        })
    </script>
</body>

</html>