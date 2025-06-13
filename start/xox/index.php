<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tic Tac Toe</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<div class="start-screen" id="startScreen">
<h1>Tic Tac Toe</h1>
<form id="nameForm">
<input type="text" id="playerX" placeholder="Player X name" required>
<input type="text" id="playerO" placeholder="Player O name" required>
<button type="submit">Start Game</button>
</form>
</div>
<div class="game-screen hidden" id="gameScreen">
<div id="status"></div>
<div class="board">
<?php for($i=0;$i<9;$i++): ?>
<div class="cell" data-index="<?= $i ?>"></div>
<?php endfor; ?>
</div>
<button id="restart" class="hidden" onclick="restart()">Play Again</button>
<div class="scoreboard">
<h2>Top Players</h2>
<table>
<thead><tr><th>#</th><th>Name</th><th>Score</th></tr></thead>
<tbody></tbody>
</table>
</div>
</div>
</div>
<script src="script.js"></script>
</body>
</html>
