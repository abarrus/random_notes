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
    <title>Submission Write - Random Notes!</title>

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
            <div class="d-flex gap-3 justify-content-between flex-column-reverse flex-sm-row mb-3">
                <div id="punctuation-container" class="d-flex flex-wrap gap-3 justify-content-center">
                </div>
                <div class="d-flex gap-3">
                    <button onclick="backspace()" class="btn btn-dark px-4 w-100 w-sm-auto" type="button">
                        <i class="bi bi-backspace-fill"></i>
                    </button>
                    <button onclick="newline()" class="btn btn-dark px-4 w-100 w-sm-auto" type="button">
                        <i class="bi bi-arrow-return-left"></i>
                    </button>
                </div>
            </div>
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
            <li>No numbers, but these characters are allowed: ,.!?:;"'()-</li>
            <li>You can add whatever whitespace you want.</li>
        </ul>
    </div>
    <script>
        /**
         * Clears the words in the words UI,
         * then adds in all the words from the global words array
         * 
         * Used both for initial setup and after things are changed
         * (like a word being written)
         */
        function updateWordsHTML() {
            container = document.getElementById("words-container");
            container.innerHTML = "";
            unusedWords.forEach(word => {
                const wordData = words.find(w => w.text == word)
                container.innerHTML += `
                    <button type="button" style="background-color: ${wordData.color}; border: 1px solid color-mix(in oklab, ${wordData.color}, black 25%)" class="btn text-dark" onclick="addWord('${word}')">
                        ${word}
                    </button>`
            });
        }

        /**
         * Add all the words the player has to the words list and to the UI.
         */
        function load() {
            /**
             * Adds the given words to the global array words, with random colors for each word.
             * 
             * @param wordsToAdd list(string)
             */
            function initializeWordsList(wordsToAdd) {
                const colors = ["#ccd5ae", "#e9edc9", "#fefae0", "#faedcd", "#d4a373"];

                wordsToAdd.forEach(word => {
                    const randomColor = colors[Math.floor(Math.random() * colors.length)];
                    words.push({
                        text: word,
                        color: randomColor,
                    });
                })
            }

            fetch("helpers/get_words.php")
                .then(res => res.json())
                .then(data => {
                    unusedWords = [...data];
                    initializeWordsList(data);
                    updateWordsHTML();
                });
        }

        /**
         * Add the punctuation buttons to the UI.
         * 
         * Must be called after global var allowedPunctuation has been initialized.
         * Only to be called once during setup.
         */
        function initializePunctuationButtons() {
            const div = document.getElementById("punctuation-container");
            for (const char of allowedPunctuation) {
                const btn = document.createElement("button");
                btn.className = "btn btn-dark px-4";
                btn.textContent = char;
                btn.type = "button";
                btn.onclick = () => addPunctuation(char);
                div.append(btn);
            };
        }

        /**
         * Add word to textarea, with extra space (only if necessary)
         * The word buttons use this.
         */
        function addWord(word) {
            // subset of the allowed punctuation, that doesn't need to automatically have a space after it
            const noSpacePunctuation = "\"'(-";

            const lastLetter = ta.value.charAt(ta.value.length - 1);
            const lastLetterIsWhitespace = /\s/.test(lastLetter);
            const lastLetterIsNoSpacePunctuation = noSpacePunctuation.includes(lastLetter);
            if (ta.value === "" || lastLetterIsWhitespace || lastLetterIsNoSpacePunctuation) {
                ta.value += word;
            } else {
                ta.value += " " + word;
            }

            // it's valid so remove ONE of it from list of unusedWords
            // (if there are duplicates we don't want to remove them all)
            unusedWords.splice(unusedWords.indexOf(word), 1);
            updateWordsHTML();
            sizeTextArea();
        }

        /**
         * Adds a piece of punctuation to the text area.
         * The punctuation buttons use this.
         */
        function addPunctuation(char) {
            ta.value += char;
            sizeTextArea();
        }

        /**
         * Add a newline to the textarea
         * 
         * Used for the newline button.
         */
        function newline() {
            ta.value += "\n";
            sizeTextArea();
        }

        /**
         * Delete last word (or non-letter character) in textarea.
         * If the word had whitespace after it,
         * this deletes all the whitespace and the word.
         * 
         * Used for backspace button and
         * when a player clicks the backspace key on their keyboard.
         * 
         * @param stopIfInvalidWord boolean. 
         * @return boolean. if it actually deleted a word.
         *      the only reason if would return false is it stopIfInvalidWord is true
         *          AND the word to delete is not a valid word
         */
        function backspace(stopIfInvalidWord) {
            // each word and following whitespace is its own item
            // each non-letter non-space character and the following whitespace is its own item
            const pattern = /(?<=\s+)(?=[^\s])|(?=[^a-z\s])|(?<=[^a-z\s])(?=[^\s])/gi;
            // "hello there!!    person" becomes:
            // ["hello ", "there", "!", "!    ", "person"]
            const words = ta.value.split(pattern);

            if (stopIfInvalidWord) {
                const strip = s => s.replace(/\s+/g, "");
                const lastWord = strip(words[words.length - 1]);
                if (!wordIsInWords(lastWord) && !allowedPunctuation.includes(lastWord)) {
                    return false;
                }
            }

            words.pop();
            ta.value = words.join("");

            // does more than necessary but will add back the word you just deleted
            checkWords();

            return true;
        }

        /**
         * This function is helpful because words is an array of objects.
         * 
         * Used for checkWords() and backspace()
         * 
         * @param wordToCheck string
         * @return boolean
         */
        function wordIsInWords(wordToCheck) {
            return words.some(obj => wordToCheck.toLowerCase() === obj.text.toLowerCase());
        }

        /**
         * Show error text
         * if the textarea value has invalid or duplicate words.
         */
        function checkWords() {

            // delete forbidden punctuation from the actual textarea
            let re = new RegExp(`[^a-zA-Z \\s${allowedPunctuation}]`, "g");
            ta.value = ta.value.replace(re, "");
            // we will ignore the allowed punctuation when judging what words are in the list
            re = new RegExp(`[${allowedPunctuation}]`, "g");
            const textToJudge = ta.value.replace(re, " ");
            // split on one or more whitespace
            text = textToJudge.split(/\s+/);

            valid = true;

            unusedWords = words.map(obj => obj.text);

            err = document.getElementById("err");

            duplicateWords = [];
            invalidWords = [];

            text.forEach(word => {
                word = word.toLowerCase();
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
            sizeTextArea();

            updateWordsHTML();
        }

        /**
         * Give the textarea the right amount of rows for the text in it.
         */
        function sizeTextArea() {
            // reset height so shrinking works too
            ta.style.height = 'auto';
            // set height to scrollHeight
            ta.style.height = ta.scrollHeight + 'px';
        }

        /**
         * If there are invalid or duplicate words in the textarea
         * (if the error text has text in it)
         * don't allow the user to submit,
         * and pop up a warning.
         */
        function preventSubmitIfInvalid(event) {
            err = document.getElementById("err");
            if (err.textContent != "") {
                event.preventDefault();
                alert("Can't submit, there's invalid or duplicate words");
            }
        }

        // words will look like this:
        // list(
        //      object(
        //          text: string,
        //          color: string
        //      )
        // )
        words = [];

        // just a regular list of strings
        unusedWords = [];

        load();

        const form = document.getElementById("myForm");
        form.addEventListener('submit', preventSubmitIfInvalid);

        const ta = document.getElementById('myText');
        ta.addEventListener('input', checkWords);

        ta.addEventListener('keydown', (e) => {
            if (e.key === "Backspace") {
                hasSelectedText = ta.selectionStart !== ta.selectionEnd;
                if (!hasSelectedText) {
                    if (backspace(stopIfInvalidWord = true)) {
                        event.preventDefault();
                    }
                }
            }
        });

        // hyphen should stay at the end so it doesn't accidentally create a range in regex
        const allowedPunctuation = ",.!?:;\"'()-";
        initializePunctuationButtons();
    </script>
</body>

</html>