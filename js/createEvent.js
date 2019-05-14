$(function () {
  $('#datePicker').datetimepicker();// activate the datetime datePicker

  $('#createEvent').submit((e) => {
    if(typeof marker == "undefined")
    {
      e.preventDefault();
      displayError("Vous devez indiquer la position de l'évènement!");
      return false;
    }
  });

  /*********MAP INIT************/

  map = L.map('map').setView([48.852969, 2.349903], 11);// default is paris

  L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
		// Il est toujours bien de laisser le lien vers la source des données
		attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
		minZoom: 1,
		maxZoom: 20
	}).addTo(map);

  map.on('click', (e) => {
    $('#latitude').val(e.latlng.lat);
    $('#longitude').val(e.latlng.lng);

    if(typeof marker == "undefined")
    {
      marker = L.marker(e.latlng).addTo(map);
    }
    else
    {
      marker.setLatLng(e.latlng);
    }
  })
});

function displayError(msg)
{
  $('.alert').html(msg).show();
}
