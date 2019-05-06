// On initialise la latitude et la longitude de Paris (centre de la carte)
var map = null;

// Fonction d'initialisation de la carte
function initMap()
{
	// Créer l'objet "map" et l'insèrer dans l'élément HTML qui a l'ID "map"
	map = L.map('map').setView([lat, lon], 11);
	// Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
	L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
		// Il est toujours bien de laisser le lien vers la source des données
		attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
		minZoom: 1,
		maxZoom: 20
	}).addTo(map);
	for (e in events) {
		(function(){
			var eCpy = e;
			L.marker([events[e].latitude, events[e].longitude]).addTo(map).on("click", () => {
				window.location.href = "eventDetails.php?eventId=" + events[eCpy].id;
			}).bindTooltip(events[e].name);
		})();
	}
}
window.onload = function(){
	// Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
	initMap();
};
