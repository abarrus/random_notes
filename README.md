PDO - no mysqli

sql conventions

- table names are PascalCase
- row names are snake_case

php conventions:

- session variables are snake_case
- variables are camelCase
- function names are snake_case

My Database Tables

### Words

| COLUMN_NAME | TYPE         | NOTES |
| ----------- | ------------ | ----- |
| game_id     | char(16)     |       |
| player_id   | char(16)     |       |
| word        | varchar(255) |       |

### Players

probably getting rid of this table soon
| COLUMN_NAME | TYPE | NOTES |
| ----------- | ------------ | ----- |
| id | char(16) | |
| last_active | timestamp | |
| name | varchar(255) | |

### GamePlayers

| COLUMN_NAME | TYPE         | NOTES                                                           |
| ----------- | ------------ | --------------------------------------------------------------- |
| id          | char(16)     | can be multiple of the same, for same player in different games |
| game_id     | char(16)     |                                                                 |
| name        | varchar(255) |                                                                 |

### Moves

| COLUMN_NAME | TYPE          | NOTES                                                  |
| ----------- | ------------- | ------------------------------------------------------ |
| game_id     | char(16)      |
| player_id   | char(16)      |
| submission  | varchar(1000) | the sentence they made                                 |
| vote        | char(16)      | the player_id of who submitted their favorite sentence |
| round       | smallint      | round 0 is them joining the game                       |

### Games

| COLUMN_NAME  | TYPE          | NOTES                              |
| ------------ | ------------- | ---------------------------------- |
| id           | char(16)      |
| last_changed | timestamp     |
| name         | varchar(255)  |
| status       | enum(6)       | "open" or "closed"                 |
| round        | smallint      |                                    |
| prompt       | varchar(1000) |                                    |
| prompter     | char(16)      | id of the user to write the prompt |

word lists credit:

- nouns: https://gist.github.com/creikey/42d23d1eec6d764e8a1d9fe7e56915c6
- verbs: https://www.syllablecount.com/syllables/words/verbs.aspx
- adjectives: https://github.com/3mrgnc3/RouterKeySpaceWordlists/blob/master/top-500-ranked-english-adjectives.lst
- other: me. I just picked words
