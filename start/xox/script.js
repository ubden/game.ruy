const board=document.querySelector('.board');
const cells=Array.from(document.querySelectorAll('.cell'));
const statusEl=document.getElementById('status');
const startScreen=document.querySelector('.start-screen');
const gameScreen=document.querySelector('.game-screen');
const nameForm=document.getElementById('nameForm');
const playerXEl=document.getElementById('playerX');
const playerOEl=document.getElementById('playerO');
let playerX='',playerO='',current='X',moves=0,gameOver=false;

nameForm.addEventListener('submit',e=>{
    e.preventDefault();
    playerX=playerXEl.value.trim()||'Player X';
    playerO=playerOEl.value.trim()||'Player O';
    startScreen.classList.add('hidden');
    gameScreen.classList.remove('hidden');
    updateStatus();
});

cells.forEach(cell=>cell.addEventListener('click',()=>handleMove(cell)));

document.addEventListener('keydown',e=>{
    if(gameOver)return;
    const index=['1','2','3','4','5','6','7','8','9'].indexOf(e.key);
    if(index>=0)handleMove(cells[index]);
});

function handleMove(cell){
    if(gameOver||cell.textContent) return;
    cell.textContent=current;
    moves++;
    if(checkWin(current)){
        endGame(`${getCurrentPlayer()} wins!`);
        updateScore(getCurrentPlayer());
    } else if(moves===9){
        endGame('Draw!');
    } else {
        current=current==='X'?'O':'X';
        updateStatus();
    }
}

function getCurrentPlayer(){
    return current==='X'?playerX:playerO;
}

function updateStatus(){
    statusEl.textContent=`Turn: ${getCurrentPlayer()} (${current})`;
}

function endGame(message){
    statusEl.textContent=message;
    gameOver=true;
    document.getElementById('restart').classList.remove('hidden');
    loadScoreboard();
}

function checkWin(sym){
    const combos=[[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
    return combos.some(c=>c.every(i=>cells[i].textContent===sym));
}

function restart(){
    cells.forEach(c=>c.textContent='');
    current='X';
    moves=0;
    gameOver=false;
    document.getElementById('restart').classList.add('hidden');
    updateStatus();
}

function loadScoreboard(){
    fetch('scoreboard.json').then(r=>r.json()).then(data=>{
        const tbody=document.querySelector('.scoreboard tbody');
        tbody.innerHTML='';
        data.players.forEach((p,i)=>{
            const tr=document.createElement('tr');
            tr.innerHTML=`<td>${i+1}</td><td>${p.name}</td><td>${p.score}</td>`;
            tbody.appendChild(tr);
        });
    });
}

function updateScore(winner){
    fetch('update_score.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`name=${encodeURIComponent(winner)}`})
        .then(()=>loadScoreboard());
}
