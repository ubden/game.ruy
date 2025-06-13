<?php
session_start();
$scoreFile = __DIR__ . '/bowling_scores.json';
$scores = [];
if (file_exists($scoreFile)) {
    $json = file_get_contents($scoreFile);
    $scores = json_decode($json, true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Top Scores</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="game">
    <h1>Top 5 Scores</h1>
    <ol>
        <?php foreach ($scores as $s): ?>
            <li><?php echo htmlspecialchars($s['name'] . ' - ' . $s['score']); ?></li>
        <?php endforeach; ?>
    </ol>
    <a href="index.php"><button>Play Again</button></a>
</div>
</body>
</html>
