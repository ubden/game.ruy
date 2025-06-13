<?php
// 9 Stone Game - simple tic-tac-toe variant with scoreboard
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>9 Stone Game</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="start-screen" class="screen">
  <h1>9 Stone</h1>
  <input id="player1" placeholder="Player 1 name" />
  <input id="player2" placeholder="Player 2 name" />
  <button id="start-btn">Start Game</button>
</div>
<div id="game-screen" class="screen hidden">
  <h2 id="turn-info"></h2>
  <div id="board" class="board"></div>
  <button id="restart-btn">Restart</button>
  <h3>Top Scores</h3>
  <ul id="scoreboard"></ul>
</div>
<script src="game.js"></script>
</body>
</html>
