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

  $sql = "SELECT word FROM Words WHERE game_id = ? AND player_id = ?;";
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

  $sql = "SELECT * FROM Users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$playerId]);

  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return (count($results) > 0);
}

function make_player($playerId, $nickname)
{
  $conn = connect();

  $sql = "INSERT INTO Users (id, name, last_active)
VALUES (?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$playerId, $nickname]);
}

function make_game($gameName, $gameId)
{
  $conn = connect();

  $sql = "INSERT INTO Games (last_changed, status, name, id)
VALUES (NOW(), 'open', ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$gameName, $gameId]);
}

// deletes games where last change is 1+ days ago
function clean_old_games()
{
  $conn = connect();

  $stmt = "
    DELETE FROM Games
    WHERE last_changed < NOW() - INTERVAL 1 DAY
    ";
  $conn->exec($stmt);
}

function get_games()
{
  $conn = connect();

  $stmt = $conn->query("SELECT id, status, last_changed, name FROM Games ORDER BY last_changed DESC");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
