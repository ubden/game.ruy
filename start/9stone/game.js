document.addEventListener('DOMContentLoaded',() => {
  const startScreen = document.getElementById('start-screen');
  const gameScreen = document.getElementById('game-screen');
  const board = document.getElementById('board');
  const turnInfo = document.getElementById('turn-info');
  const startBtn = document.getElementById('start-btn');
  const restartBtn = document.getElementById('restart-btn');
  const scoreboardList = document.getElementById('scoreboard');

  let player1 = ''; let player2 = ''; let current = 'X';
  let cells = [];

  function renderBoard() {
    board.innerHTML = '';
    cells = [];
    for(let i=0;i<9;i++) {
      const cell = document.createElement('div');
      cell.className = 'cell';
      cell.dataset.index = i;
      cell.addEventListener('click', onCellClick, { once:true });
      board.appendChild(cell);
      cells.push(cell);
    }
  }

  function onCellClick(e) {
    const cell = e.target;
    cell.textContent = current;
    if(checkWinner()) {
      turnInfo.textContent = `${current==='X'?player1:player2} wins!`;
      updateScore(current==='X'?player1:player2);
      endGame();
    } else if(cells.every(c => c.textContent)) {
      turnInfo.textContent = 'Draw!';
      endGame();
    } else {
      current = current==='X'?'O':'X';
      turnInfo.textContent = `Turn: ${current==='X'?player1:player2}`;
    }
  }

  function checkWinner() {
    const lines = [
      [0,1,2],[3,4,5],[6,7,8],
      [0,3,6],[1,4,7],[2,5,8],
      [0,4,8],[2,4,6]
    ];
    return lines.some(([a,b,c]) => {
      return cells[a].textContent &&
             cells[a].textContent === cells[b].textContent &&
             cells[a].textContent === cells[c].textContent;
    });
  }

  function endGame() {
    cells.forEach(c => c.removeEventListener('click', onCellClick));
    restartBtn.style.display = 'block';
  }

  function updateScore(winner) {
    fetch('scoreboard.php', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: `name=${encodeURIComponent(winner)}`
    })
    .then(r => r.json())
    .then(renderScores);
  }

  function renderScores(scores) {
    if(!Array.isArray(scores)) return;
    scoreboardList.innerHTML = '';
    scores.forEach(s => {
      const li = document.createElement('li');
      li.textContent = `${s.name}: ${s.score}`;
      scoreboardList.appendChild(li);
    });
  }

  function loadScores() {
    fetch('scoreboard.php')
      .then(r => r.json())
      .then(renderScores);
  }

  startBtn.addEventListener('click', () => {
    player1 = document.getElementById('player1').value || 'Player 1';
    player2 = document.getElementById('player2').value || 'Player 2';
    current = 'X';
    startScreen.classList.add('hidden');
    gameScreen.classList.remove('hidden');
    turnInfo.textContent = `Turn: ${player1}`;
    restartBtn.style.display = 'none';
    renderBoard();
    loadScores();
  });

  restartBtn.addEventListener('click', () => {
    current = 'X';
    turnInfo.textContent = `Turn: ${player1}`;
    renderBoard();
    restartBtn.style.display = 'none';
  });
});
