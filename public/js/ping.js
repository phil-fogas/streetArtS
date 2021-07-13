'use strict';
let canvas;
let ball;
let plateaux;
let ctx;
let bricks = [];
let hp;
let vie;
let tache;
let droite;
let gauche;
let start;
let anime;
let colors = ["yellow", "green", "red", "blue", "pink", "brown", "white"];
let images = [];


/* nombre aleatoire */
function getRandom(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/* dessine le mur de taches */
function murs() {

    for (let i = 0; i < bricks.length; i++) {
        ctx.beginPath();
        ctx.fillStyle = bricks[i].color;
        ctx.arc(bricks[i].x - bricks[i].rayon, bricks[i].y + bricks[i].rayon, bricks[i].rayon, 0, Math.PI * 2, true);
        ctx.fill();
        ctx.strokeStyle = bricks[i].color;
        ctx.shadowBlur = 20;
        ctx.shadowColor = bricks[i].color;
        ctx.stroke();
        ctx.closePath();
    }
}
/* dessine la ball */
function disBal() {
    ctx.beginPath();
    ctx.fillStyle = ball.color;
    ctx.arc(ball.x, ball.y, ball.rayon, 0, Math.PI * 2, true);
    ctx.fill();
    ctx.strokeStyle = "gold";
    ctx.stroke();
    ctx.closePath();
}

/* desine le plateaux */
function plateau() {
    ctx.beginPath();
    ctx.fillStyle = plateaux.color;
    ctx.fillRect(plateaux.x, plateaux.y, plateaux.width, plateaux.height);
    ctx.closePath();
}
/* gestion des colision */
function colision() {
    let casse = -1;
    for (let i = 0; i < bricks.length; i++) {
        if ((ball.x + ball.rayon >= bricks[i].x &&
                ball.x - ball.rayon <= bricks[i].x) &&
            (ball.y >= bricks[i].y - bricks[i].rayon &&
                ball.y <= bricks[i].y + bricks[i].rayon)) {
            casse = i;
            ball.direction.y *= -1;
            break;
        }
    }

    if (casse != -1) {
        bricks.splice(casse, 1);
        tache.textContent = bricks.length;
    }
}
/* le mecaniste de jeux */
function playGame() {
    ctx.clearRect(0, 0, canvas.width - 1, canvas.height - 1);
    if (images) {
        let image = new Image();
        image.src = "./img/" + images;
        ctx.drawImage(image, 50, 50, 1000, 1000, 50, 50, 500, 500);
    }
    if (ball.y >= plateaux.y - ball.rayon) {
        if (ball.x >= plateaux.x && ball.x < plateaux.x + plateaux.width) {
            ball.direction.y *= -1;

        }
        else {
            hp--;
            vie.textContent = hp;
            if (hp <= 0) {
                vie.textContent = "Perdu";
                ctx.fillStyle = 'white';
                ctx.fillRect(140, 140, 320, 300);
                ctx.fillStyle = 'firebrick';

                ctx.fillText("Perdu", 250, 200);
                ctx.fillText("il reste", 250, 250);
                ctx.fillText(bricks.length, 300, 300);
                ctx.fillText("taches", 250, 350);

                localStorage.perdu++;
                bricks = [];
                cancelAnimationFrame(anime);

                return;
            }
            else {
                ball.x = canvas.width / 2;
                ball.y = canvas.height / 2;
                //ctx.fillText("Gagné", 150, 150);
            }
        }
    }
    else if (ball.y < 0 + ball.rayon) {
        ball.direction.y *= -1;
    }
    if (ball.x < ball.rayon || ball.x > canvas.width - ball.rayon) {
        ball.direction.x *= -1;
    }

    ball.x += ball.speed.x * ball.direction.x;
    ball.y += ball.speed.y * ball.direction.y;

    murs();

    disBal();
    plateau();
    colision();

    if (hp > 0 && bricks.length == 0) {
        vie.textContent = "Gagnée";

        ctx.fillStyle = 'red';
        ctx.fillText('Bravo', 250, 250);
        ctx.fillText('vous avez', 250, 300);
        ctx.fillText('gagnée', 250, 350);
        ctx.fillText('reste ' + hp + ' vie', 200, 400);
        localStorage.gagne++;
        cancelAnimationFrame(anime);

        return;
    }
    if (hp > 0) {
        anime = requestAnimationFrame(playGame);
    }
}

/* gestion des touche <- -> */
document.addEventListener('keydown', function (event) {
    event.preventDefault();
    switch (event.key) {
    case 'ArrowRight':
        if (plateaux.x < canvas.width - plateaux.width) {
            plateaux.x += 10;
            plateau();
        }
        break;
    case 'ArrowLeft':
        if (plateaux.x >= 10) {
            plateaux.x -= 10;
            plateau();

        }
        break;

    }

});

function toucheDroite(event) {
    event.preventDefault();
    if (plateaux.x < canvas.width - plateaux.width) {
        plateaux.x += 20;
        plateau();
    }
}

function toucheGauche(event) {
    event.preventDefault();
    if (plateaux.x >= 20) {
        plateaux.x -= 20;
        plateau();

    }
}


function game() {
    let tperdu = Number(localStorage.perdu);
    let tgagne = Number(localStorage.gagne);
    if (!tperdu) { tperdu = 1 }
    if (!tgagne) { tgagne = 1 }
    let ajust = (tperdu - tgagne / (tperdu + tgagne));
    hp = Math.round(2 + ajust);

    ball = {
        x: canvas.width / 2,
        y: canvas.height / 2,
        color: "black",
        rayon: 10,
        direction: { x: 1, y: 1 },
        speed: { x: 5, y: 5 }

    };

    plateaux = {
        x: canvas.width / 2,
        y: canvas.height,
        width: 100 + ajust,
        height: 10,
        color: "brown",
        direction: { x: 1, y: 1 },
        speed: { x: 30, y: 30 }
    };

    plateaux.y -= plateaux.height + 10;
    plateaux.x -= plateaux.width / 2;
    bricks = [];

    for (let i = 0; i < getRandom(30, 50); i++) {
        let brick = {

            x: getRandom(1, canvas.width - 10),
            y: getRandom(1, canvas.height - 90),

            color: colors[getRandom(0, colors.length)],
            rayon: getRandom(15 + ajust, 60 - ajust),
        };
        bricks.push(brick);
        tache.textContent = bricks.length;
    }
    images = randomImages();
    parti.textContent = Number(localStorage.perdu) + Number(localStorage.gagne);
    perdu.textContent = Number(localStorage.perdu);
    gagne.textContent = Number(localStorage.gagne);
    vie.textContent = hp;
    requestAnimationFrame(playGame);
}

function randomImages() {
    let obj = new XMLHttpRequest();
    obj.overrideMimeType("application/json");
    obj.open('GET', './images.php', true);
    obj.onreadystatechange = function () {
        if (obj.readyState == 4 && obj.status == "200") {

            let json = JSON.parse(obj.responseText);
            return images = json[0]['photo'];
            //console.log(images);
        }
    }
    obj.send(null);
}

document.addEventListener('DOMContentLoaded', function () {

    if (!localStorage.perdu) {
        localStorage.setItem('perdu', 0);
        localStorage.setItem('gagne', 0);
    }
    canvas = document.querySelector('#rec');
    ctx = canvas.getContext('2d');

    ctx.font = "30px BakerStreetRough";
    ctx.fillStyle = 'silver';
    ctx.fillRect(50, 50, 500, 500);
    ctx.fillStyle = 'black';
    ctx.fillText("Effacer toutes les taches", 90, 100);
    ctx.fillText("pour découvrir", 100, 200);
    ctx.fillText("un street Art", 100, 300);
    ctx.fill();
    ctx.fillStyle = 'gold';
    ctx.arc(300, 450, 50, 0, Math.PI * 2, true);
    ctx.fill();
    ctx.strokeStyle = 'gold';
    ctx.shadowBlur = 150;
    ctx.shadowColor = 'black';
    ctx.stroke();
    ctx.closePath();

    tache = document.querySelector('#tache');
    let parti = document.querySelector('#parti');
    let perdu = document.querySelector('#perdu');
    let gagne = document.querySelector('#gagne');
    vie = document.querySelector('#vie');
    start = document.querySelector('#go');
    droite = document.querySelector('#droite');
    gauche = document.querySelector('#gauche');
    start.addEventListener('click', game);
    droite.addEventListener('touchstart', toucheDroite);
    gauche.addEventListener('touchstart', toucheGauche);
    droite.addEventListener('mousemove', toucheDroite);
    gauche.addEventListener('mousemove', toucheGauche);

});
