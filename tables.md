# My Database Tables

### Words

Which words players in games have

| COLUMN_NAME | TYPE         | NOTES |
| ----------- | ------------ | ----- |
| game_id     | char(16)     |       |
| player_id   | char(16)     |       |
| word        | varchar(255) |       |

### AllWords

ALL the words, the lists to pick random words from
| COLUMN_NAME | TYPE | NOTES |
| ----------- | ------------ | ----- |
| word | varchar(255) | |
| category | enum | 'nouns', 'verbs', 'adjectives', 'other' |

### Prompts

Pick a random prompt from here

| COLUMN_NAME | TYPE          | NOTES |
| ----------- | ------------- | ----- |
| prompt      | varchar(1000) |       |

### Players

this is a leftover table and I need to get rid of references to it in the code. i replaced it with GamePlayers

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

sql conventions

- table names are PascalCase
- row names are snake_case

php conventions:

- session variables are snake_case
- variables are camelCase
- function names are snake_case