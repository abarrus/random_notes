<?php
include "db_connect.php";

// Returns, as an array, all words that this player has in this game
function get_words($gameId, $playerId)
{
  $conn = connect();

  $sql = "SELECT word FROM Words WHERE game_id = ? AND player_id = ? ORDER BY word;";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId]);

  return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

// Returns true or false for if this player is in this game
// TODO check GamePlayers rather than Words
function is_in_game($gameId, $playerId)
{
  $words = get_words($gameId, $playerId);
  return count($words) > 0;
}

function add_to_game($playerId, $gameId, $nickname)
{
  $conn = connect();

  $sql = "INSERT INTO GamePlayers (id, game_id, name) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$playerId, $gameId, $nickname]);
}

function give_word($gameId, $playerId, $word)
{
  $conn = connect();

  $sql = "INSERT INTO Words (game_id, player_id, word) VALUES (?,?,?);";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId, $word]);
}

function user_exists($playerId)
{
  $conn = connect();

  $sql = "SELECT * FROM Players WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$playerId]);

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return (count($results) > 0);
}

function make_player($playerId, $nickname)
{
  $conn = connect();

  $sql = "INSERT INTO Players (id, name, last_active)
VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$playerId, $nickname]);
}

function make_game($gameName, $gameId)
{
  $conn = connect();

  $sql = "INSERT INTO Games (last_changed, status, name, id, round)
VALUES (NOW(), 'open', ?, ?, 0)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameName, $gameId]);
}

// deletes games where last change is 1+ days ago
function clean_old_games()
{
  $conn = connect();

  // TODO: realistically the SQL should handle the 2nd, 3rd, and 4th deletes
  // if i set it up properly with:
  // FOREIGN KEY (game_id)
  // REFERENCES Games(game_id)
  // ON DELETE CASCADE
  // but i don't feel like it rn
  $stmt = "
    DELETE FROM Games
    WHERE last_changed < NOW() - INTERVAL 1 DAY;

    DELETE FROM Words WHERE game_id NOT IN (
      SELECT game_id FROM Games
    );
    DELETE FROM GamePlayers WHERE game_id NOT IN (
      SELECT game_id FROM Games
    );
    DELETE FROM Moves WHERE game_id NOT IN (
      SELECT game_id FROM Games
    );
    ";
  $conn->exec($stmt);
}

function get_games()
{
  $conn = connect();

  $stmt = $conn->query("SELECT id, status, last_changed, name FROM Games ORDER BY last_changed DESC");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get names and IDs of players in a particular game
 * 
 * @param gameId 16-char game ID
 * @return list<array{
 *    name: string,
 *    id: string
 * }>
 */
function get_players($gameId)
{
  $conn = connect();

  $sql = "SELECT DISTINCT gp.name, gp.id FROM GamePlayers gp WHERE game_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// TODO this is weird now with the switch to GamePlayers
function set_nickname($playerId, $nickname)
{
  $conn = connect();

  $sql = "UPDATE Players SET name=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$nickname, $playerId]);
}

function submit_sentence($gameId, $playerId, $submission)
{
  $conn = connect();

  $sql = "INSERT INTO Moves (game_id, player_id, submission, round) VALUES (?, ?, ?, (SELECT round FROM Games WHERE id = ?))";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId, $submission, $gameId]);
}


function submit_vote($gameId, $playerId, $vote)
{
  $conn = connect();

  $sql = "INSERT INTO Moves (game_id, player_id, vote, round) VALUES (?, ?, ?, (SELECT round FROM Games WHERE id = ?))";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId, $vote, $gameId]);
}

function get_nickname($playerId)
{
  $conn = connect();

  $sql = "SELECT name FROM Players WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$playerId]);
  $name = $stmt->fetchColumn(); // gets first column of first row
  return $name ?: null;
}

function get_submissions($gameId)
{
  $conn = connect();

  // select submissions for that gameId only for the round the game is currently on
  $sql = "SELECT m.submission, m.round, p.name, p.id FROM Moves m JOIN Players p ON m.player_id = p.id JOIN Games g ON m.game_id = g.id WHERE m.game_id = ? AND m.round = g.round AND m.vote IS NULL";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_votes($gameId)
{
  $conn = connect();

  // select votes for that gameId only for the round the game is currently on
  $sql = "SELECT m.vote, m.round, p.name, p.id FROM Moves m JOIN Players p ON m.player_id = p.id JOIN Games g ON m.game_id = g.id WHERE m.game_id = ? AND m.round = g.round AND m.vote IS NOT NULL";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_game_name($gameId)
{
  $conn = connect();
  $sql = "SELECT name FROM Games WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);
  $name = $stmt->fetchColumn(); // gets first column of first row
  return $name;
}

/**
 * The prompt for the current round, and who is the prompter.
 * 
 * @param gameId 16-char game ID
 * @return array{
 *      prompt: string|null
 *      prompter: string (16-char player id)
 * }
 */
function get_prompt_info($gameId)
{
  $conn = connect();

  $sql = "SELECT prompt, prompter FROM Games WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Has this player given a submission for the current round?
 * 
 * @param gameId 16-char game ID
 * @param playerId 16-char player ID
 * @return boolean
 */
function has_submitted($gameId, $playerId)
{
  $conn = connect();

  $sql = "
    SELECT EXISTS(
      SELECT 1 FROM Moves m JOIN Games g ON m.game_id = g.id
      WHERE m.game_id = ? AND m.player_id = ? AND m.round = g.round
    )";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId]);

  return (bool)$stmt->fetchColumn();
}

/**
 * Have all players given a submission for the current round?
 * 
 * @param gameId 16-char game ID
 * @return boolean
 */
function all_submitted($gameId)
{
  $conn = connect();

  // technically DISTINCT isn't needed in this first query if GamePlayers already guarantees one row per player.
  // but leaving it in is harmless. so idc.
  $sql = "SELECT COUNT(DISTINCT id) FROM GamePlayers WHERE game_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);
  $playerCount = $stmt->fetchColumn();

  $sql = "SELECT COUNT(DISTINCT m.player_id) FROM Moves m JOIN Games g ON m.game_id = g.id WHERE m.game_id = ? AND m.round = g.round";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);
  $submittedPlayerCount = $stmt->fetchColumn();

  // converts them both to ints in case PHP returns "3" as a string instead of 3 as an int
  return (int)$playerCount == (int)$submittedPlayerCount;
}

/**
 * Has this player voted for the current round?
 * 
 * @param gameId 16-char game ID
 * @param playerId 16-char player ID
 * @return boolean
 */
function has_voted($gameId, $playerId)
{
  $conn = connect();

  $sql = "
    SELECT EXISTS(
      SELECT 1 FROM Moves m JOIN Games g ON m.game_id = g.id
      WHERE m.game_id = ? AND m.player_id = ? AND m.round = g.round AND vote IS NOT NULL
    )";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId]);

  return (bool)$stmt->fetchColumn();
}

/**
 * Have all players voted for the current round?
 * 
 * @param gameId 16-char game ID
 * @return boolean
 */
function all_voted($gameId)
{
  $conn = connect();

  // technically DISTINCT isn't needed in this first query if GamePlayers already guarantees one row per player.
  // but leaving it in is harmless. so idc.
  $sql = "SELECT COUNT(DISTINCT id) FROM GamePlayers WHERE game_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);
  $playerCount = $stmt->fetchColumn();

  $sql = "SELECT COUNT(DISTINCT m.player_id) FROM Moves m JOIN Games g ON m.game_id = g.id WHERE m.game_id = ? AND m.round = g.round AND vote IS NOT NULL";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);
  $submittedPlayerCount = $stmt->fetchColumn();

  // converts them both to ints in case PHP returns "3" as a string instead of 3 as an int
  return (int)$playerCount == (int)$submittedPlayerCount;
}

/**
 * 
 * @param gameId 16-char game ID
 * @return list<array{
 *    submission: string,
 *    vote_count: int,
 *    name: string (name of player who submitted it)
 * }>
 */
function get_results($gameId)
{
  $conn = connect();

  $sql = "SELECT gp.name, s.submission, COUNT(v.vote) AS vote_count
    FROM Moves s
    JOIN GamePlayers gp
      ON s.player_id = gp.id AND s.game_id = gp.game_id
    LEFT JOIN Moves v
      ON v.vote = s.player_id
      AND v.game_id = s.game_id
      AND v.round = s.round
    JOIN Games g
      ON s.game_id = g.id
    WHERE s.game_id = ? AND s.round = g.round AND s.submission IS NOT NULL
    GROUP BY s.player_id, s.submission, gp.name
    ORDER BY vote_count DESC";

  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_prompt($gameId) {
  $conn = connect();

  $sql = "SELECT prompt FROM Games WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetchColumn();
}

function set_prompter($gameId, $prompterId) {
    $conn = connect();

    $sql = "UPDATE Games SET prompter = ? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$prompterId, $gameId]);
}

function set_prompt($gameId, $prompt) {
  $conn = connect();

  $sql = "UPDATE Games SET prompt = ? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$prompt, $gameId]);
}

function get_name_from_id($gameId, $playerId) {
  $conn = connect();

  $sql = "SELECT name FROM GamePlayers WHERE game_id = ? AND id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId, $playerId]);

  return $stmt->fetchColumn();
}
