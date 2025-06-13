<?php
$scores = [];
$scoreFile = __DIR__ . '/buzhokeyi_scores.json';
if (file_exists($scoreFile)) {
    $json = json_decode(file_get_contents($scoreFile), true);
    if (is_array($json)) {
        $scores = $json;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Buz Hokeyi - Giriş</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-bg">
<div class="login-container">
    <h1>Buz Hokeyi</h1>
    <form method="get" action="game.php" class="login-form">
        <input type="text" name="p1" placeholder="1. Oyuncu Adı" required>
        <input type="text" name="p2" placeholder="2. Oyuncu Adı" required>
        <button type="submit">Oyunu Başlat</button>
    </form>
    <h2>En Yüksek Skorlar</h2>
    <ol class="score-list">
        <?php foreach ($scores as $s): ?>
            <li><?php echo htmlspecialchars($s['name']) . ' - ' . intval($s['score']); ?></li>
        <?php endforeach; ?>
    </ol>
</div>
</body>
</html>
