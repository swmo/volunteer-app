// Initialize and add the map
let map;

async function initMap() {
  // The location of Uluru

  const position = { lat: 47.05630, lng: 7.62627 };
  // Request needed libraries.
  //@ts-ignore
  const { Map } = await google.maps.importLibrary("maps");
  //const { AdvancedMarkerView } = await google.maps.importLibrary("marker");

  // The map, centered at Uluru
  map = new Map(document.getElementById("map"), {
    zoom: 20,
    center: position,
    mapId: "DEMO_MAP_ID",
    mapTypeId: 'satellite',

  });

  // The marker, positioned at Uluru
  const marker = new AdvancedMarkerView({
    map: map,
    position: position,
    title: "Uluru",
  });
}

initMap();