<!--
La page HTML qui va nous permettre de travailler
-->
<!DOCTYPE html>
<html>
	<head>
        <!-- Importation de la librairie Leaflet -->
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"/>
        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>

        <!-- Notre code pour gerer la carte -->
    		<script type="text/javascript">
    			// On initialise la latitude et la longitude de Paris (centre de la carte)
    			var lat = 48.852969;
    			var lon = 2.349903;
    			var macarte = null;


    			// Fonction d'initialisation de la carte
    			function initMap() {
    				        // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
                    macarte = L.map('map').setView([lat, lon], 6);

                    // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
                    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                        // Il est toujours bien de laisser le lien vers la source des données
                        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                        minZoom: 1,
                        maxZoom: 20
                    }).addTo(macarte);
                }
    			window.onload = function(){
    				// Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
    				initMap();

						// Quand on appuie sur entrer dans la barre de recherche
						document.getElementById('barre-adresse').addEventListener('keydown', function(key) {
							if(key.keyCode == 13) {
								fetch('https://nominatim.openstreetmap.org/search?q=' + document.getElementById('barre-adresse').value + '&format=json&polygon=1&addressdetails=1')
                .then(function(reponse) {
                  return reponse.json();
                })
                .then(function(resultat) {
                      if(resultat.length > 0){
												L.marker([resultat[0].lat, resultat[0].lon]).addTo(macarte);
												console.log(resultat);
											}
											else{
												alert('Erreur survenue : pas de résultat');
											}
                })
                .catch(function(erreur) {
                      console.error(erreur);
              	});
								console.log('L`\'adresse recherchee est : ' + document.getElementById('barre-adresse').value);
							}
						});
    			};
    		</script>

        <!-- Un peu de CSS pour que la carte remplisse la page -->
        <style type="text/css">
        body,html {
              margin:0px!important;
              padding:0px!important;
        }

    	   #map {
    				height:100vh;
    		}
    		</style>

        <!-- Titre de la page -->
        <title>Carte</title>
	</head>
	<body>
		<div id="map"><!-- Element necessaire pour la carte --></div>
    <div style="height:40px!important; width:50vw!important; position:absolute; top:10px; padding:0px; left:25vw;z-index:1000!important;">
        <input id="barre-adresse" style="width:100%!important; border:none!important; display:inline!important; height:100%!important;border-radius:5px!important;height:100%!important; padding-left:10px!important; font-size:16px!important; outline:none!important;" placeholder="Entrez une adresse puis appuyez sur [Entrer]"></input>
    </div>
	</body>
</html>
