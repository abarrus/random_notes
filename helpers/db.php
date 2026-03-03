<?php

function connect()
{
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "random_notes";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
  }
}

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
function is_in_game($gameId, $playerId)
{
  $words = get_words($gameId, $playerId);
  return count($words) > 0;
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

  // TODO: realistically the SQL should handle the second part
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
    ";
  $conn->exec($stmt);
}

function get_games()
{
  $conn = connect();

  $stmt = $conn->query("SELECT id, status, last_changed, name FROM Games ORDER BY last_changed DESC");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_players($gameId)
{
  $conn = connect();

  $sql = "SELECT DISTINCT Players.name FROM
    Players JOIN Words ON Words.player_id = Players.id
    WHERE Words.game_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameId]);

  return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

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
  $sql = "SELECT m.submission, m.round, p.name FROM Moves m JOIN Player p ON m.player_id = p.id JOIN Games g ON m.game_id = g.id WHERE m.game_id = ? AND m.round = g.round";
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
