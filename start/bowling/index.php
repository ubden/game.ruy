<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['player'] ?? '');
    if ($name !== '') {
        $_SESSION['player'] = $name;
        header('Location: game.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bowling Game - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login">
    <h1>Bowling Game</h1>
    <form method="post">
        <input type="text" name="player" placeholder="Enter your name" required>
        <br>
        <button type="submit">Start Game</button>
    </form>
</div>
</body>
</html>
