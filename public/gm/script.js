let map;

// here we store the position of the created marker 
// when the user clicks on the map
let marker;



/**
 * It creates a new map object, and adds a click listener to it
 */
async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");

  map = new Map(document.getElementById("map"), {
    center: { lat: 24.713673926899162, lng: 46.74328697578708 },
    zoom: 13,
  });

  map.addListener('click', function (e) {
    addMarker(e.latLng);
  });
}

/**
 * It creates a marker if it doesn't exist, and if it does, it updates its 
 * position
 * 
 * @param markerPosition - The position of the marker.
 */
function addMarker(markerPosition) {
  if (!marker) {
    marker = new google.maps.Marker({
      map: map,
      position: markerPosition,
      draggable: true
    });
  } else {
    marker.setPosition(markerPosition);
  }

  // Getting the latitude and longitude inputs and setting their values to the
  // marker's latitude and longitude.
  const latitudeInput = document.querySelector('input[name="latitude"]');
  const longitudeInput = document.querySelector('input[name="longitude"]');

  latitudeInput.setAttribute('value', markerPosition.lat())
  longitudeInput.setAttribute('value', markerPosition.lng())
}

initMap();