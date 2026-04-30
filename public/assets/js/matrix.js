const canvas = document.getElementById('matrixCanvas');
const ctx = canvas.getContext('2d');

let width, height;
let columns = [];

const symbols = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

// ---------- НАСТРОЙКИ МАТРИЦЫ ----------
const fontSize = 20;
const columnSpacing = 50;
const minFadeSteps = 90;
const maxFadeSteps = 160;
const respawnChance = 0.006;
const maxSymbolsPerColumn = 16;
const symbolStartCount = 11;

// ПАЛИТРА ЦВЕТОВ (подсветка редактора кода)
const colors = [
    "rgba(86, 156, 214, ",   // синий
    "rgba(206, 145, 120, ",  // оранжевый
    "rgba(156, 220, 254, ",  // голубой
    "rgba(197, 134, 192, ",  // розовый
    "rgba(220, 220, 170, ",  // жёлтый
    "rgba(181, 206, 168, ",  // светло-зелёный
    "rgba(206, 145, 120, ",  // персиковый
    "rgba(152, 118, 170, "   // фиолетовый
];
// -------------------------------------

class MatrixSymbol {
    constructor(y, lifeLeft, char, colorIndex) {
        this.y = y;
        this.life = lifeLeft;
        this.char = char;
        this.colorIndex = colorIndex;
    }
}

class Column {
    constructor(x) {
        this.x = x;
        this.symbols = [];
        this.initRandomSymbols();
    }

    initRandomSymbols() {
        for (let i = 0; i < symbolStartCount; i++) {
            const y = Math.random() * height;
            const life = Math.floor(Math.random() * (maxFadeSteps - minFadeSteps) + minFadeSteps);
            const char = symbols[Math.floor(Math.random() * symbols.length)];
            const colorIdx = Math.floor(Math.random() * colors.length);
            this.symbols.push(new MatrixSymbol(y, life, char, colorIdx));
        }
        this.symbols.sort((a, b) => a.y - b.y);
    }

    update() {
        for (let s of this.symbols) {
            s.life--;
        }
        this.symbols = this.symbols.filter(s => s.life > 0);

        if (Math.random() < respawnChance && this.symbols.length < maxSymbolsPerColumn) {
            let newY = Math.random() * (height + fontSize);
            const tooClose = this.symbols.some(s => Math.abs(s.y - newY) < fontSize * 0.8);
            if (!tooClose) {
                const newLife = Math.floor(Math.random() * (maxFadeSteps - minFadeSteps) + minFadeSteps);
                const newChar = symbols[Math.floor(Math.random() * symbols.length)];
                const colorIdx = Math.floor(Math.random() * colors.length);
                this.symbols.push(new MatrixSymbol(newY, newLife, newChar, colorIdx));
                this.symbols.sort((a, b) => a.y - b.y);
            }
        }
    }

    draw() {
        for (let s of this.symbols) {
            let opacity = (s.life / maxFadeSteps) * 0.25;
            opacity = Math.min(0.25, Math.max(0.12, opacity));
            const baseColor = colors[s.colorIndex];
            ctx.fillStyle = `${baseColor} ${opacity})`;
            ctx.font = `${fontSize}px 'Courier New', monospace`;
            ctx.fillText(s.char, this.x, s.y);
        }
    }
}

function init() {
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = width;
    canvas.height = height;

    const columnCount = Math.ceil(width / columnSpacing) + 2;
    columns = [];
    for (let i = 0; i < columnCount; i++) {
        const x = i * columnSpacing;
        columns.push(new Column(x));
    }
}

function animate() {
    ctx.fillStyle = "#0a0f1a";
    ctx.fillRect(0, 0, width, height);

    for (let col of columns) {
        col.update();
        col.draw();
    }
    requestAnimationFrame(animate);
}

window.addEventListener('resize', () => {
    init();
});

init();
animate();