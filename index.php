<?php
$gameDir = __DIR__ . '/start';
$games = [];
if (is_dir($gameDir)) {
    foreach (scandir($gameDir) as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        $path = "$gameDir/$entry";
        if (is_dir($path)) {
            $games[] = [
                'name' => ucfirst($entry),
                'url'  => "start/$entry/",
            ];
        } elseif (
            is_file($path) &&
            strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'php' &&
            $entry !== 'index.php'
        ) {
            $games[] = [
                'name' => ucfirst(pathinfo($entry, PATHINFO_FILENAME)),
                'url'  => "start/$entry",
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gameruy Library</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
body{background:#1e1e2f;color:#e4e4e4;font-family:'Inter',Arial,sans-serif;margin:0;padding:0;}
.library{max-width:960px;margin:0 auto;padding:40px 20px;}
h1{text-align:center;margin-bottom:30px;color:#fff;}
.games{display:flex;flex-wrap:wrap;gap:20px;justify-content:center;}
.game-card{background:#292b39;border-radius:12px;width:220px;padding:20px;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,0.3);transition:transform 0.2s;}
.game-card:hover{transform:translateY(-5px);}
.game-card svg{width:64px;height:64px;fill:#ff9f43;}
.game-title{margin:15px 0;font-size:1.2em;font-weight:600;color:#fff;}
.play-btn{display:inline-block;padding:10px 20px;background:#ff9f43;color:#1e1e2f;border-radius:25px;text-decoration:none;font-weight:600;}
.play-btn:hover{background:#ffc163;}
.no-games{text-align:center;color:#ccc;}
</style>
</head>
<body>
<div class="library">
<h1>Gameruy Game Library</h1>
<div class="games">
<?php if ($games): foreach ($games as $game): ?>
<div class="game-card">
<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
<path d="M48 24h-3.172l-5.586-5.586A2 2 0 0 0 37.828 18H26.172a2 2 0 0 0-1.414.586L19.172 24H16a12 12 0 0 0 0 24h3a3 3 0 0 0 3-3v-1a3 3 0 0 1 3-3h20a3 3 0 0 1 3 3v1a3 3 0 0 0 3 3h3a12 12 0 0 0 0-24zM24 36h-4v4h-4v-4H12v-4h4v-4h4v4h4v4zm24 4a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
</svg>
<div class="game-title"><?= htmlspecialchars($game['name']) ?></div>
<a class="play-btn" href="<?= htmlspecialchars($game['url']) ?>">Play</a>
</div>
<?php endforeach; else: ?>
<p class="no-games">No games found in the start directory.</p>
<?php endif; ?>
</div>
</div>
</body>
</html>
