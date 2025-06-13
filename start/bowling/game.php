<?php
session_start();
if (!isset($_SESSION['player'])) {
    header('Location: index.php');
    exit;
}
$name = $_SESSION['player'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bowling Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="game">
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
    <canvas id="lane" width="600" height="200"></canvas>
    <div class="scoreboard" id="scoreboard">Score: 0</div>
</div>
<script>
let score = 0;
let frame = 1;
const canvas = document.getElementById('lane');
const ctx = canvas.getContext('2d');
function drawLane() {
    ctx.fillStyle = '#0b0c10';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#45a29e';
    ctx.fillRect(canvas.width/2 -5, 0, 10, canvas.height);
}
function rollBall() {
    const pins = Math.floor(Math.random()*11);
    score += pins;
    document.getElementById('scoreboard').innerText = 'Score: ' + score;
    frame++;
    if(frame>10){
        endGame();
    }
}
drawLane();
window.addEventListener('keydown', (e)=>{
    if(e.code==='Space'){
        rollBall();
    }
});
function endGame(){
    fetch('update_scores.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'score='+score
    }).then(()=>{
        alert('Game Over! Your score: '+score);
        window.location='scoreboard.php';
    });
}
</script>
</body>
</html>
