'use strict';
/* previsualiesr image avant upload et redementionne */
function previewFile(event) {
    let currentFile = document.getElementById('photo').files[0];
    let compress = document.getElementById("compressImg");
    let quality = 75;

    let reader = new FileReader();
    if (currentFile.type.indexOf('image') == 0) {
        reader.onload = function (event) {
            let image = new Image();
            image.src = event.target.result;

            image.onload = function () {
                let maxWidth = 1200,
                    maxHeight = 1200,
                    imageWidth = image.width,
                    imageHeight = image.height;

                if (imageWidth > imageHeight) {
                    if (imageWidth > maxWidth) {
                        imageHeight *= maxWidth / imageWidth;
                        imageWidth = maxWidth;
                    }
                }
                else {
                    if (imageHeight > maxHeight) {
                        imageWidth *= maxHeight / imageHeight;
                        imageHeight = maxHeight;
                    }
                }

                let canvas = document.createElement('canvas');
                canvas.width = imageWidth;
                canvas.height = imageHeight;
                image.width = imageWidth;
                image.height = imageHeight;
                let ctx = canvas.getContext("2d");
                ctx.drawImage(this, 0, 0, imageWidth, imageHeight);
                let source_img = new Image();
                source_img.src = canvas.toDataURL(currentFile.type, quality / 100);
                // console.log(source_img.src);
                /* Compression de l'image : */
                compress.src = source_img.src;

                document.getElementById("compressImg").style.display = "block";
                document.getElementById("image").value = source_img.src;
            }
        }
        reader.readAsDataURL(currentFile);
    }
};

// Envoi de la requête AJAX la recherche des street par adresse
/* recherche par rue*/
function onSearchRue(event) {
    // Empêche le rechargement de la page lié au formulaire
    event.preventDefault();

    const value = document.querySelector('#rue').value;
    // console.log(event);
    const streetSection = document.querySelector('#galerie');

    fetch(`${value}`).then(function (response) {
        // console.log(response);
        //streetSection.innerHTML = response;
        //return response.text();
    }).then(function (streets) {
        //console.log(streets);
       // streetSection.innerHTML = streets;
    });
}
/* recherche par categorie */
function onSearchCategorie(event) {

    event.preventDefault();

    const value = document.querySelector('#categorie').value;
    // console.log(value);
    const streetSection = document.querySelector('#galerie');
    fetch(`categorie=${value}`).then(function (response) {
        // console.log(response);
        //streetSection.innerHTML = response;
       // return response.text();
    }).then(function (streets) {
        //console.log(streets);
       // streetSection.innerHTML = streets;
    });
}

/* recherche par categorie */
function onSearchKm(event) {

    event.preventDefault();

    const value = document.querySelector('#km').value;
    const valueLat = document.querySelector('#latitude').value;
    const valueLon = document.querySelector('#longitude').value;
    // console.log(value);
    if (!valueLat) {
        valueLat = 45.764043;
        valueLon = 4.835659;
    }
    const streetSection = document.querySelector('#galerie');
    fetch(`km=${value}&latitude=${valueLat}&longitude=${valueLon}`).then(function (response) {
        // console.log(response);
        //streetSection.innerHTML = response;
        //return response.text();
    }).then(function (streets) {
        //console.log(streets);
      //  streetSection.innerHTML = streets;
    });
}

/* suugestion des nom de adresse */
function onSearch(event) {
    // Empêche le rechargement de la page lié au formulaire
    event.preventDefault();

    const value = document.querySelector('#adresse').value;
    //console.log(value);

    let promise = fetch(`https://api-adresse.data.gouv.fr/search/?q=${value}&type=housenumber&autocomplete=1&lat=45.75&lon=4.85`).then(function (response) {
            // console.log(response);
            if (response.ok) {
                return response.json();
            }
            else if (response.status !== 200) {
                console.log('Status Code: ' + response.status);
                // return;
            }
            else {
                console.log('Mauvaise réponse du réseau');
            }
        })
        .then(data => {
            const Section = document.querySelector('#address');

            Section.innerHTML = '';

            for (let r = 0; r < data.features.length; r++) {

                Section.innerHTML += (`<li><a href="#" id="selectRue" onclick="remplirInput('${data.features[r].properties.label}','${data.features[r].geometry.coordinates[0]}','${data.features[r].geometry.coordinates[1]}')">${data.features[r].properties.label} </a></li>`);
            } //
            Section.innerHTML += ' </ul>';

        })
        .catch(function (error) {
            console.log('Fetch Error 8-(', error);

        });
}
/* rempli les inputs sur le click de la sugestion */
function remplirInput(rue, lon, lat) {
    event.preventDefault();
    document.getElementById("adresse").value = rue;
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lon;
}

/* gestion erreur du gps */
function erreurPosition(error) {
document.getElementById("info").classList.remove("hidden");
    let info = "Erreur lors de la géolocalisation : ";
    switch (error.code) {
    case error.TIMEOUT:
        info += "Timeout !";

        break;
    case error.PERMISSION_DENIED:
        info += "Vous n’avez pas donné la permission";

        break;
    case error.POSITION_UNAVAILABLE:
        info += "La position n’a pu être déterminée";
        break;
    case error.UNKNOWN_ERROR:
        info += "Erreur inconnue";

        break;
    }
    document.getElementById("info").classList.add("danger");
    document.getElementById("info").innerHTML = info;
    //window.navigator.geolocation.clearwatch(watchID);
}


function localise(event) {
    event.preventDefault();

    if ("geolocation" in navigator) {
        let watchID = navigator.geolocation.getCurrentPosition(function (position) {
            document.getElementById("latitude").value = position.coords.latitude;
            document.getElementById("longitude").value = position.coords.longitude;

            let precis;
            if (position.coords.accuracy) {
                precis = "Précistion a " + position.coords.accuracy.toFixed(2) + " Mètres";
            }
            else {
                precis = "Précision non estimez, vous pouvez étre loin de ce lieu";
            }
             document.getElementById("info").classList.remove("hidden");
            document.getElementById("info").classList.toggle("info", true);
            document.getElementById("info").innerHTML = precis;
            fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=` + position.coords.longitude + `&lat=` + position.coords.latitude + `&type=housenumber`).then(function (response) {
                return response.json();
            }).then(data => {

                document.getElementById("adresse").value = data.features[0].properties.label;
            });

            function clearWatch() {
                if (watchID != null) {
                    navigator.geolocation.clearWatch(watchID);
                    watchID = null;
                }
            }

        }, erreurPosition, { enableHighAccuracy: true, maximumAge: 3000, timeout: 27000 });
    }
    else {
        document.getElementById("info").innerHTML = "Géocalisation non pris en charge";
    }

}

function dark() {

    if (localStorage.getItem('dark') == "nuit") {
        //localStorage.setItem('dark', 'nuit');

        document.querySelector('body').classList.add("dark");
        document.querySelector('main').classList.add("dark");

        document.querySelector('.lampadaire').classList.add("dark");
        document.querySelector('.lampe').classList.add("dark");
    }
    else {
        // localStorage.setItem('dark', 'jour');
        document.querySelector('body').classList.remove("dark");
        document.querySelector('main').classList.remove("dark");

        document.querySelector('.lampadaire').classList.remove("dark");
        document.querySelector('.lampe').classList.remove("dark");
    }


}

function darkClic() {
    if (localStorage.getItem('dark') == "jour") {
        localStorage.setItem('dark', 'nuit');

    }
    else {
        localStorage.setItem('dark', 'jour');

    }
    dark();
}

function verifi() {
    alert('champ obligatoire');
}


document.addEventListener('DOMContentLoaded', function () {


    if (document.getElementById("photo")) {

        document.getElementsByTagName('form')[0].addEventListener("submit", function (event) {

            if (!document.getElementById("photo").validity.valid) {
                document.querySelector('.alert2').classList.remove("hidden");
                document.querySelector('.alert2').innerHTML = "J'attends une photo, merci!";
                document.querySelector('.alert2').classList.add("danger");
                event.preventDefault();
            }

        }, false);

        document.getElementById("photo").onchange = function () {
            previewFile(event);
            /* message alert sur taille image */
            // if (this.files[0].size > (2 * 1048576)) {
            //     document.querySelector('.alert2').classList.add("danger");
            //     let taille = (this.files[0].size / 1048576).toFixed(2);
            //     document.querySelector('.alert2').innerHTML = "merci de transmetre un fichier de mois de 2 Mo, votre image fait " + taille + " Mo";
            //     this.value = "";
            // };
            /* message sur le type image */
            if (this.files[0]) {
                if (this.files[0].type != 'image/jpeg' && this.files[0].type != 'image/jpg' && this.files[0].type != 'image/png') {
                    document.querySelector('.alert2').classList.add("danger");
                    document.querySelector('.alert2').innerHTML = "format de fichier non autorisée";
                    this.value = "";
                };
            }

        };
    }


    document.querySelector('#dark').addEventListener('click', darkClic);

    if (!localStorage.getItem('dark')) {
        let d = new Date();
        let n = d.getHours();
        
        if (n > 19 || n < 8) {
            document.querySelector('body').classList.add("dark");
            document.querySelector('main').classList.add("dark");

            document.querySelector('.lampadaire').classList.add("dark");
            document.querySelector('.lampe').classList.add("dark");

        }
    }
    else { dark(); }

    if (document.querySelector('#localise')) {
        document.querySelector('#localise').addEventListener('click', localise);

    }
    if (document.querySelector('#submit')) {
        document.querySelector('#submit').addEventListener('submit', verifi);
    }
    if (document.querySelector('#rue')) {
        document.querySelector('#rue').addEventListener('keyup', onSearchRue);
    }
    if (document.querySelector('#adresse')) {
        document.querySelector('#adresse').addEventListener('keyup', onSearch);
    }
    if (document.querySelector('#categorie')) {
        document.querySelector('#categorie').addEventListener('change', onSearchCategorie);
    }
    if (document.querySelector('#km')) {
        document.querySelector('#km').addEventListener('change', onSearchKm);
    }
    if (document.querySelector('.alert')) {

        setTimeout(function () { document.querySelector('.alert').classList.add("hidden"); }, 13000);
    }
    if (document.querySelector('.alert2')) {

        setTimeout(function () { document.querySelector('.alert2').classList.add("hidden"); }, 13000);
    }
});
