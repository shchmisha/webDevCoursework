// setup 
const startBtn = document.getElementById('start-button')
let canvas = document.getElementById("game-canvas") 

let scoreboard = document.getElementById("scoreboard") 
let ctx = canvas.getContext("2d") 


ctx.scale(BLOCK_SIDE_LENGTH, BLOCK_SIDE_LENGTH) 
// ctx.drawImage("./tetris-grid-bg.jpg",0,0);

let model = new GameModel(ctx)

let score = 0 
window.score = 0
let playing = true
let timerId;

model.renderGameState()

let newGameState = () => {
    fullSend() 
    if (model.fallingPiece === null) {
        const rand = Math.round(Math.random() * 6) + 1
        const newPiece = new Piece(SHAPES[rand], ctx) 
        model.fallingPiece = newPiece 
        model.moveDown()
    } else {
        model.moveDown()
    }
}

const fullSend = () => {
    const allFilled = (row) => {
        for (let x of row) {
            if (x === 0) {
                return false
            }
        }
        return true
    }

    for (let i = 0; i < model.grid.length; i++) {
        if (allFilled(model.grid[i])) {
            window.score += SCORE_WORTH 
            model.grid.splice(i, 1) 
            model.grid.unshift([0,0,0,0,0,0,0,0,0,0])
        }
    }

    scoreboard.innerHTML = "Score: " + String(window.score)
}

document.addEventListener("keydown", (e) => {
    if(timerId) {
        e.preventDefault() 
        switch(e.key) {
            case "w":
                model.rotate() 
                break 
            case "d":
                model.move(true) 
                break 
            case "s": 
                model.moveDown() 
                break 
            case "a":
                model.move(false) 
                break
            case "ArrowUp":
                model.rotate() 
                break
            case "ArrowRight":
                model.move(true)
                break
            case "ArrowLeft":
                model.move(false)
                break
            case "ArrowDown":
                model.moveDown()
        }
    }
})

startBtn.addEventListener('click', () => {
    if (timerId) {
      clearInterval(timerId)
      timerId = null
    } else {
      timerId = setInterval(() => newGameState(), GAME_CLOCK); 
    }
  })

$(document).ready(function () {
    createCookie("gfg", "GeeksforGeeks", "10");
});
   
// Function to create the cookie
// function createCookie(name, value, days) {
//     var expires;
      
//     if (days) {
//         var date = new Date();
//         date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
//         expires = "; expires=" + date.toGMTString();
//     }
//     else {
//         expires = "";
//     }
      
//     document.cookie = escape(name) + "=" + 
//         escape(value) + expires + "; path=/";
// }