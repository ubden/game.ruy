<?php
$p1 = $_GET['p1'] ?? 'Oyuncu 1';
$p2 = $_GET['p2'] ?? 'Oyuncu 2';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Buz Hokeyi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<canvas id="gameCanvas" width="800" height="400"></canvas>
<div class="info-bar">
    <span id="scoreP1"><?php echo htmlspecialchars($p1); ?>: 0</span>
    <span id="scoreP2"><?php echo htmlspecialchars($p2); ?>: 0</span>
</div>
<script>
const player1Name = <?php echo json_encode($p1); ?>;
const player2Name = <?php echo json_encode($p2); ?>;
</script>
<script src="game.js"></script>
</body>
</html>
