PDO - no mysqli

sql convenctions
- table names are capitalized
- row names are not capitalized, and they are like_this with underscores

php conventions:
- session variables are snake_case
- variables are camelCase
- function names are snake_case

My Database Tables

### Words
| COLUMN_NAME | TYPE | NOTES |
|--------|-----|--|
| game_id | char(16)  ||
| player_id    | char(16)  ||
| word | varchar(255) ||

### Users
| COLUMN_NAME | TYPE | NOTES
|--|--|--|
| id          | char(16)      ||
| last_active | timestamp ||
| name        | varchar(255)   ||

### Moves
| COLUMN_NAME | TYPE     | NOTES |
|-|-|-|
| game_id     | char(16)      |
| player_id   | char(16)      |
| submission  | varchar(1000) | the sentence they made
| vote        | char(16)      | the player_id of who submitted their favorite sentence

### Games
| COLUMN_NAME  | TYPE    |NOTES|
|-|-|-|
| id           | char(16)     |
| last_changed | timestamp         |
| name         | varchar(255) |
| status       | enum(6)      | "open" or "closed" |