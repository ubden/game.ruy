<?php
$scoreFile = __DIR__ . '/flapybird_scores.json';
$scores = [];
if (file_exists($scoreFile)) {
    $data = json_decode(file_get_contents($scoreFile), true);
    if ($data && isset($data['top_scores'])) {
        $scores = $data['top_scores'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FlappyBird</title>
<style>
body { margin:0; font-family: Arial, sans-serif; background:#70c5ce; overflow:hidden; }
#gameCanvas { display:block; margin:0 auto; background:#4ec0ca; box-shadow:0 0 10px rgba(0,0,0,0.5); }
#startScreen, #gameOverScreen { position:absolute; top:0; left:0; right:0; bottom:0; display:flex; justify-content:center; align-items:center; flex-direction:column; background:rgba(0,0,0,0.5); color:#fff; font-size:20px; }
#gameOverScreen { display:none; }
button { padding:10px 20px; font-size:16px; cursor:pointer; border:none; border-radius:5px; background:#ffcc00; }
#scoreboard { position:absolute; top:10px; right:10px; background:rgba(255,255,255,0.8); padding:10px; border-radius:5px; }
</style>
</head>
<body>
<canvas id="gameCanvas" width="320" height="480"></canvas>
<div id="startScreen"><h2>Flappy Bird</h2><input id="playerName" placeholder="Name"/><button onclick="startGame()">Start</button></div>
<div id="gameOverScreen"><h2>Game Over</h2><p id="finalScore"></p><button onclick="restartGame()">Restart</button></div>
<div id="scoreboard">
<h3>Top Scores</h3>
<ol id="topList">
<?php foreach ($scores as $s): ?>
<li><?= htmlspecialchars($s['name']) ?> - <?= $s['score'] ?></li>
<?php endforeach; ?>
</ol>
</div>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
let frames = 0;
const DEGREE = Math.PI/180;
const sprite = new Image();
sprite.src = 'https://i.imgur.com/Q9RjG7F.png';
let state = { current: 0, get READY() { return 0; }, get GAME() { return 1; }, get OVER() { return 2; } };
const startScreen = document.getElementById('startScreen');
const gameOverScreen = document.getElementById('gameOverScreen');
const finalScoreEl = document.getElementById('finalScore');
let playerName = '';

const bird = {
    animation: [ {sX: 276, sY: 112}, {sX: 276, sY: 139}, {sX: 276, sY: 164}, {sX: 276, sY: 139} ],
    x: 50,
    y: 150,
    w: 34,
    h: 26,
    radius: 12,
    frame:0,
    speed:0,
    gravity:0.25,
    jump:4.6,
    rotation:0,
    draw(){
        let bird = this.animation[this.frame];
        ctx.save();
        ctx.translate(this.x,this.y);
        ctx.rotate(this.rotation);
        ctx.drawImage(sprite,bird.sX,bird.sY,this.w,this.h,- this.w/2,- this.h/2,this.w,this.h);
        ctx.restore();
    },
    flap(){ this.speed = -this.jump; },
    update(){
        this.period = state.current === state.READY ? 10 : 5;
        this.frame += frames%this.period === 0 ? 1 : 0;
        this.frame = this.frame%this.animation.length;
        if(state.current === state.READY){ this.y = 150; this.rotation = 0; }
        else{
            this.speed += this.gravity;
            this.y += this.speed;
            if(this.y + this.h/2 >= canvas.height - 112){ this.y = canvas.height - 112 - this.h/2; if(state.current===state.GAME){ state.current = state.OVER; } }
            if(this.speed >= this.jump){ this.rotation = 90*DEGREE; this.frame=1; } else { this.rotation = -25*DEGREE; }
        }
    },
    reset(){ this.speed=0; }
};
const pipes = {
    position:[],
    top:{sX:553, sY:0},
    bottom:{sX:502, sY:0},
    w:53,
    h:400,
    gap:85,
    maxYPos:-150,
    dx:2,
    draw(){
        for(let i=0;i<this.position.length;i++){
            let p=this.position[i];
            let topY=p.y;
            let bottomY=p.y+this.h+this.gap;
            ctx.drawImage(sprite,this.top.sX,this.top.sY,this.w,this.h,p.x,topY,this.w,this.h);
            ctx.drawImage(sprite,this.bottom.sX,this.bottom.sY,this.w,this.h,p.x,bottomY,this.w,this.h);
        }
    },
    update(){
        if(state.current !== state.GAME) return;
        if(frames%100===0){ this.position.push({x:canvas.width, y:this.maxYPos*(Math.random()+1)}); }
        for(let i=0;i<this.position.length;i++){
            let p=this.position[i];
            p.x -= this.dx;
            if(p.x + this.w <=0){ this.position.shift(); score.value++; score.best = Math.max(score.value, score.best); if(score.value%10===0) level++; this.dx+=0.1; }
            let bottomPipeYPos = p.y + this.h + this.gap;
            if(bird.x+bird.radius>p.x && bird.x-bird.radius<p.x+this.w && (bird.y-bird.radius<p.y + this.h || bird.y+bird.radius> bottomPipeYPos)){ state.current = state.OVER; }
        }
    },
    reset(){ this.position=[]; this.dx=2; }
};
const score = { value:0, best:0, draw(){ ctx.fillStyle="#fff"; ctx.strokeStyle="#000"; ctx.lineWidth=2; ctx.font="35px Arial"; ctx.fillText(this.value, canvas.width/2,50); ctx.strokeText(this.value, canvas.width/2,50); }, reset(){ this.value=0; } };
let level = 1;
function draw(){ ctx.fillStyle="#70c5ce"; ctx.fillRect(0,0,canvas.width,canvas.height); pipes.draw(); bird.draw(); ctx.fillStyle="#fff"; ctx.font="20px Arial"; ctx.fillText('Level: '+level,10,25); score.draw(); }
function update(){ bird.update(); pipes.update(); }
function loop(){ update(); draw(); frames++; requestAnimationFrame(loop); }
canvas.addEventListener('click', function(){ if(state.current === state.READY){ state.current = state.GAME; } else if(state.current === state.GAME){ bird.flap(); } else { } });
document.addEventListener('keydown', function(e){ if(e.code==='Space'){ e.preventDefault(); if(state.current === state.READY){ state.current = state.GAME; } else if(state.current === state.GAME){ bird.flap(); } else if(state.current === state.OVER){ restartGame(); } } });
function startGame(){ playerName = document.getElementById('playerName').value.trim(); if(playerName===''){ alert('Enter your name'); return; } state.current = state.READY; startScreen.style.display='none'; loop(); }
function restartGame(){ pipes.reset(); bird.reset(); score.reset(); level=1; gameOverScreen.style.display='none'; state.current=state.READY; startScreen.style.display='flex'; }
function gameOver(){ finalScoreEl.textContent='Score: '+score.value; gameOverScreen.style.display='flex'; fetch('scoreboard.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`name=${encodeURIComponent(playerName)}&score=${score.value}`}).then(r=>r.json()).then(d=>{ if(d.scores){ const list=document.getElementById('topList'); list.innerHTML=''; d.scores.forEach(s=>{ const li=document.createElement('li'); li.textContent=s.name+' - '+s.score; list.appendChild(li); }); }}); }
(function checkGameOver(){ if(state.current===state.OVER){ gameOver(); } requestAnimationFrame(checkGameOver); })();
</script>
</body>
</html>
