# Word lists credit:

I majorly edited the lists though.

- nouns: https://gist.github.com/creikey/42d23d1eec6d764e8a1d9fe7e56915c6
- verbs: https://www.syllablecount.com/syllables/words/verbs.aspx
- adjectives: https://github.com/3mrgnc3/RouterKeySpaceWordlists/blob/master/top-500-ranked-english-adjectives.lst
- other: me. I just picked words



# How to run this on your computer:
\* skip installation steps if you already have it installed
## Get PHP*
- Install Homebrew*
  - `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"`
  - (apple doesnt support PHP anymore so you need homebrew to download it - unless you want to download each package/dependency yourself but that’s a pain)
- Install PHP with Homebrew
  - (takes 5-10 min?)
  - `brew install php`

https://dev.to/hikarimaeda/guide-for-installing-php-on-mac-a1g

## Make the database
- Get MySQL*
  - Non-Mac:
    - probably download it on the MySQL website
  - Mac:
    - `brew install mysql`
    - to get it set up: `brew services start mysql`
- `mysql -u root`
- `CREATE DATABASE IF NOT EXISTS random_notes;`
- You don't need to create the tables in the database because I already have a script for that. More info in future steps.

## Edit code
The current code connects to an online database using secret env variables. You don't have these variables, so you are going to change this to connect to the database you just made!
- Go to random_notes/helpers/db_connect.php
- Replace
    ```
    $servername = getenv('servername');
    $username = getenv('username');
    $password = getenv('password');
    $dbname = getenv('dbname');
    ```
  with
    ```
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "random_notes";
    ```
- You don't want to commit these changes, so do this:
  - Go to terminal and navigate to random_notes main folder
  - Run `git update-index --assume-unchanged helpers/db_connect.php`
  - Add this comment at the top of db_connect.php so you don't forget or get confused later!
    ```php
    /*

    all changes to this file will not be tracked in git!
    https://stackoverflow.com/a/23673910

    If you wanna start tracking changes again run the following command:
    git update-index --no-assume-unchanged helpers/db_connect.php

    */
    ```

## Launch website
- go to website folder, /random_notes
- `php -S localhost:8000`
- Leave this server running while you do the following steps, and while playing the game.

## Create tables in database
- In your browser, go to http://localhost:8000/helpers/make_db.php. It will run a script to create all the tables in the database. You can also go here anytime you want to clear the data in the database, or if you've updated the words lists.

## Play the game!
- You can play at `http://localhost:8000`.