const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

const width = canvas.width;
const height = canvas.height;

const paddleRadius = 30;
const puckRadius = 15;
const speed = 5;
let puckSpeedX = 4;
let puckSpeedY = 4;

const player1 = {x: paddleRadius + 20, y: height / 2, score: 0};
const player2 = {x: width - paddleRadius - 20, y: height / 2, score: 0};
const puck = {x: width / 2, y: height / 2};

const keys = {};

document.addEventListener('keydown', (e) => { keys[e.key] = true; });
document.addEventListener('keyup', (e) => { keys[e.key] = false; });

function movePlayers() {
    if (keys['w']) player1.y -= speed;
    if (keys['s']) player1.y += speed;
    if (keys['a']) player1.x -= speed;
    if (keys['d']) player1.x += speed;

    if (keys['ArrowUp']) player2.y -= speed;
    if (keys['ArrowDown']) player2.y += speed;
    if (keys['ArrowLeft']) player2.x -= speed;
    if (keys['ArrowRight']) player2.x += speed;

    // boundaries
    player1.x = Math.max(paddleRadius, Math.min(width/2 - paddleRadius, player1.x));
    player2.x = Math.max(width/2 + paddleRadius, Math.min(width - paddleRadius, player2.x));
    player1.y = Math.max(paddleRadius, Math.min(height - paddleRadius, player1.y));
    player2.y = Math.max(paddleRadius, Math.min(height - paddleRadius, player2.y));
}

function movePuck() {
    puck.x += puckSpeedX;
    puck.y += puckSpeedY;

    if (puck.y <= puckRadius || puck.y >= height - puckRadius) {
        puckSpeedY *= -1;
    }

    // goals
    if (puck.x <= puckRadius) {
        player2.score++;
        resetPuck();
    }
    if (puck.x >= width - puckRadius) {
        player1.score++;
        resetPuck();
    }

    // collision with paddles
    [player1, player2].forEach(p => {
        const dist = Math.hypot(p.x - puck.x, p.y - puck.y);
        if (dist < paddleRadius + puckRadius) {
            const angle = Math.atan2(puck.y - p.y, puck.x - p.x);
            puckSpeedX = 5 * Math.cos(angle);
            puckSpeedY = 5 * Math.sin(angle);
        }
    });
}

function resetPuck() {
    puck.x = width / 2;
    puck.y = height / 2;
    puckSpeedX = (Math.random() > 0.5 ? 4 : -4);
    puckSpeedY = (Math.random() > 0.5 ? 4 : -4);
    updateScoreDisplay();
    checkWin();
}

function updateScoreDisplay() {
    document.getElementById('scoreP1').textContent = `${player1Name}: ${player1.score}`;
    document.getElementById('scoreP2').textContent = `${player2Name}: ${player2.score}`;
}

function checkWin() {
    if (player1.score >= 5 || player2.score >= 5) {
        const winner = player1.score > player2.score ? player1Name : player2Name;
        const score = Math.max(player1.score, player2.score);
        fetch('update_score.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `name=${encodeURIComponent(winner)}&score=${score}`
        }).then(() => {
            alert(`${winner} kazandı! Skor: ${score}`);
            window.location.href = 'index.php';
        });
    }
}

function draw() {
    ctx.clearRect(0, 0, width, height);

    // middle line
    ctx.strokeStyle = '#555';
    ctx.setLineDash([10, 10]);
    ctx.beginPath();
    ctx.moveTo(width/2, 0);
    ctx.lineTo(width/2, height);
    ctx.stroke();
    ctx.setLineDash([]);

    // puck
    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.arc(puck.x, puck.y, puckRadius, 0, Math.PI * 2);
    ctx.fill();

    // paddles
    ctx.fillStyle = '#3a9bdc';
    ctx.beginPath();
    ctx.arc(player1.x, player1.y, paddleRadius, 0, Math.PI * 2);
    ctx.fill();

    ctx.fillStyle = '#e94e77';
    ctx.beginPath();
    ctx.arc(player2.x, player2.y, paddleRadius, 0, Math.PI * 2);
    ctx.fill();
}

function gameLoop() {
    movePlayers();
    movePuck();
    draw();
    requestAnimationFrame(gameLoop);
}

resetPuck();
updateScoreDisplay();
requestAnimationFrame(gameLoop);
