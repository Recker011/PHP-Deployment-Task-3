<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conway's Game of Life</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        #gameCanvas {
            border: 1px solid #000;
            margin-top: 20px;
        }
        #controls {
            margin-top: 10px;
        }
        .btn {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Conway's Game of Life</h1>
    <canvas id="gameCanvas" width="500" height="500"></canvas>
    <div id="controls">
        <button id="start" class="btn">Start</button>
        <button id="pause" class="btn">Pause</button>
        <button id="reset" class="btn">Reset</button>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const resolution = 10;
        const COLS = canvas.width / resolution;
        const ROWS = canvas.height / resolution;
        let grid = buildGrid();
        let intervalId;
        let running = false;

        function buildGrid() {
            return new Array(COLS).fill(null)
                .map(() => new Array(ROWS).fill(null)
                .map(() => Math.floor(Math.random() * 2)));
        }

        function render(grid) {
            for (let col = 0; col < grid.length; col++) {
                for (let row = 0; row < grid[col].length; row++) {
                    const cell = grid[col][row];
                    ctx.beginPath();
                    ctx.rect(col * resolution, row * resolution, resolution, resolution);
                    ctx.fillStyle = cell ? 'black' : 'white';
                    ctx.fill();
                    ctx.stroke();
                }
            }
        }

        function nextGen(grid) {
            const nextGen = grid.map(arr => [...arr]);

            for (let col = 0; col < grid.length; col++) {
                for (let row = 0; row < grid[col].length; row++) {
                    const cell = grid[col][row];
                    let numNeighbors = 0;
                    for (let i = -1; i < 2; i++) {
                        for (let j = -1; j < 2; j++) {
                            if (i === 0 && j === 0) {
                                continue;
                            }
                            const x_cell = col + i;
                            const y_cell = row + j;

                            if (x_cell >= 0 && y_cell >= 0 && x_cell < COLS && y_cell < ROWS) {
                                const currentNeighbor = grid[x_cell][y_cell];
                                numNeighbors += currentNeighbor;
                            }
                        }
                    }

                    if (cell === 1 && numNeighbors < 2) {
                        nextGen[col][row] = 0;
                    } else if (cell === 1 && numNeighbors > 3) {
                        nextGen[col][row] = 0;
                    } else if (cell === 0 && numNeighbors === 3) {
                        nextGen[col][row] = 1;
                    }
                }
            }

            return nextGen;
        }

        function update() {
            grid = nextGen(grid);
            render(grid);
        }

        document.getElementById('start').addEventListener('click', () => {
            if (!running) {
                running = true;
                intervalId = setInterval(update, 1000);
            }
        });

        document.getElementById('pause').addEventListener('click', () => {
            if (running) {
                running = false;
                clearInterval(intervalId);
            }
        });

        document.getElementById('reset').addEventListener('click', () => {
            clearInterval(intervalId);
            running = false;
            grid = buildGrid();
            render(grid);
        });

        render(grid);
    </script>
</body>
</html>
