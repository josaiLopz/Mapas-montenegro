// *
// * Pesquisa de coordenadas do Google Maps
// * 2013 - www.marnoto.com
// *

// Váriáveis necessárias
var map;
var marker;
var poder_click=1;

function initialize() {

	try{
	if(!centro_1){

	}
	}catch(e){
		centro_1=24.131715;
		centro_2=-110.308228;
		el_zoom=12;
	}


   var mapOptions = {
      center: new google.maps.LatLng(centro_1,centro_2),
      zoom: el_zoom,
      mapTypeId: 'roadmap'
   };

   map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

   // Evento que detecta o click no mapa para criar o marcador
   google.maps.event.addListener(map, "click", function(event) {
if(poder_click==1){
      // O evento "click" retorna a posição do click no mapa,
      // através dos métodos latLng.lat() e latLng.lng().

      // Passamos as respectivas coordenadas para as variáveis lat e lng
      // para posterior referência.
      // Utilizamos o método toFixed(6) para limitar o número de casas decimais.
      // A API ignora os valores além da 6ª casa decimal
      var lat = event.latLng.lat().toFixed(6);
      var lng = event.latLng.lng().toFixed(6);

      // A criação do marcador é feita na função createMarker() e
      // passamos os valores das coordenadas do click através
      // dos parâmetros lat e lng.
      createMarker(lat, lng);

      // getCoords() actualiza os valores de Latitue e Longitude
      // das caixas de texto existentes no topo da página
      getCoords(lat, lng);
}
   });


}
google.maps.event.addDomListener(window, 'load', initialize);

// Função que cria o marcador
function createMarker(lat, lng,txt) {


   var mapOptions = {
      center: new google.maps.LatLng(lat,lng),
      zoom: 16,
      mapTypeId: 'roadmap'
   };

   map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
   
   // A intenção é criar um único marcador, por isso
   // verificamos se já existe um marcador no mapa.
   // Assim cada vez que é feito um click no mapa
   // o anterior marcador é removido e é criado outro novo.

   // Se a variável marker contém algum valor

if(poder_click==1){
   if (marker) {
      // remover esse marcador do mapa
      marker.setMap(null);
      // remover qualquer valor da variável marker
      marker = "";
   }
}


if(poder_click==1)
	drag=true;
else
	drag=false;

   // definir a variável marker com os novos valores
   marker = new google.maps.Marker({

      // Define a posição do marcador através dos valores lat e lng
      // que foram definidos através do click no mapa
      position: new google.maps.LatLng(lat, lng),

      // Esta opção permite que o marcador possa ser arrastado
      // para um posicionamento com maior precisão.
      draggable: drag,

//	 title:"Hello World!",
//	icon: "/img/iconos/modificar.png",


      map: map
   });


if(txt){
var contentString = txt;

  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });

 infowindow.open(map,marker);

}



   // Evento que detecta o arrastar do marcador para
   // redefinir as coordenadas lat e lng e
   // actualiza os valores das caixas de texto no topo da página

if(poder_click==1){

   google.maps.event.addListener(marker, 'dragend', function() {
      
      // Actualiza as coordenadas de posição do marcador no mapa
      marker.position = marker.getPosition();

      // Redefine as variáveis lat e lng para actualizar
      // os valores das caixas de texto no topo
      var lat = marker.position.lat().toFixed(6);
      var lng = marker.position.lng().toFixed(6);

      // Chamada da função que actualiza os valores das caixas de texto
      getCoords(lat, lng);

   });
}

}

// Função que actualiza as caixas de texto no topo da página
function getCoords(lat, lng) {

   // Referência ao elemento HTML (input) com o id 'lat'
   var coords_lat = document.getElementById('lat');

   // Actualiza o valor do input 'lat'
   coords_lat.value = lat;

   // Referência ao elemento HTML (input) com o id 'lng'
   var coords_lng = document.getElementById('lng');

   // Actualiza o valor do input 'lng'
   coords_lng.value = lng;
}
