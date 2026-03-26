

function initMap() {
   map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 23.6, lng: -102.5 },
    zoom: 5
  });
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.HAND,
    drawingControl: false
  
  });
  



  
   google.maps.event.addListener(drawingManager, 'polygoncomplete', (polygon) => {
   //polygon.setMap(null);
   all_overlays[0]=polygon;
                    let bounds = new google.maps.LatLngBounds();
                    let cad="";
                    polygon.getPath().forEach((latLng) => {
                      cad+=latLng.lat()+"/"+latLng.lng()+";";
                   
                    });
                    mapa_modo_hand();
                   $("#coords").val(cad);
                });



  drawingManager.setMap(map);
}



window.initMap = initMap;
