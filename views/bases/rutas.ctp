<?php

//new google.maps.LatLng(42.496403,-124.413128),
?>
 <fieldset>
      <legend>Filtros</legend>

      <?php




configure::load("app/app"); 
$escuelas=configure::read("escuelas"); 
$estados=configure::read("estados"); 

         echo $form->create('Base',array('action'=>'index','onsubmit'=>'return buscar_base()'));

         echo '<div class="row justify-content-center">';
      	
        echo '<div class="col-lg-4 col-md-4 ">';
           echo $form->input("estado",array('label'=>'Estado *','style'=>"width:100%",'type'=>'select','options'=>$estados));
        echo '<br></div>';

        echo '<div class="col-lg-4 col-md-4 ">';
              echo $form->input("municipio",array('label'=>'Municipio','multiple'=>'multiple','style'=>"width:100%",'type'=>'select','options'=>array('0'=>'Cualquiera')));
       echo '</div>';
        
        echo '<div class="col-lg-4 col-md-4 ">';
        echo $form->input("tipo",array('style'=>"width:100%",'type'=>'select','options'=>$escuelas['tipos']));
        echo '</div>';


        echo '<div class="col-lg-4 col-md-4 ">';
        echo $form->input("sector",array('style'=>"width:100%",'type'=>'select','options'=>$escuelas['sectores']));
        echo '<br></div>';
        
        echo '<div class="col-lg-4 col-md-4 ">';
        echo $form->input("turno",array('style'=>"width:100%",'type'=>'select','options'=>$escuelas['turnos']));
        echo '</div>';
        
        
        echo '<div class="col-lg-4 col-md-4 ">';
        echo $form->input("alumnos",array('style'=>"width:100%",'label'=>'# De Alumnos'));
        echo '</div>';

        echo '<div class="col-lg-6 col-md-6 ">';
        echo $form->input("distribuidor",array('style'=>"width:100%",'type'=>'select','options'=>$distribuidores));
        echo '</div>';

        echo '<div class="col-lg-4 col-md-4 ">';
        echo $form->input("cct",array('style'=>"width:100%",'label'=>'CCT'));
        echo '</div>';

       echo '</div>';
        
       // echo "<div style='position:absolute;width:0px;height:0px;overflow:hiddens;visibility:hiddens' id='cargador_municipios'></div>";

  

        echo "<br><center>";
         echo $form->submit('Buscar',array( 'class'=>"btn btn-primary py-3 px-4"));
 
 
        echo "</center>";

        echo "<div id='cargador_municipios'></div>";
        echo "<div style='margin-top:10px' id='cargador_rutas'></div>";
      
      ?>

      
   	
</fieldset>


<div class="row justify-content-center">
	<div class="col-lg-12 col-md-12 ">
    <div id='map' style='width:100%;height:500px;'></div>
  </div>
 </div>

    <!-- 
      The `defer` attribute causes the callback to execute after the full HTML
      document has been parsed. For non-blocking uses, avoiding race conditions,
      and consistent behavior across browsers, consider loading using Promises.
      See https://developers.google.com/maps/documentation/javascript/load-maps-js-api
      for more information.
      -->
      <script
      src="https://maps.googleapis.com/maps/api/js?key=keyY&callback=initMap&libraries=drawing"
      defer
    ></script>


<script>
  /**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 */
var directionsService;
var directionsRenderer;
var map 
 


function initMap() {
  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer();
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 23.6, lng: -102.5 },
    zoom: 5, styles:[
      {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "road",
        "elementType": "labels.icon",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "transit",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      }
    ]
  });

  directionsRenderer.setMap(map);
 
}


function establece_ruta(inicio,fin,way) {
  const waypts = [];
  for (let i = 0; i < way.length; i++) {
      waypts.push({
        location: way[i],
        stopover: true,
      });
  }

  directionsService
    .route({
      origin: inicio,
      destination: fin,
      waypoints: waypts,
      optimizeWaypoints: true,
      travelMode: google.maps.TravelMode.DRIVING,
    })
    .then((response) => {
      directionsRenderer.setDirections(response);

     

      // For each route, display summary information.
     
    })
    .catch((e) => window.alert("NO se encontraron rutas para estos puntos " + status));
}



function buscar_base(){

    variables={
      estado:$('#BaseEstado').val(),
      municipio:$('#BaseMunicipio').val(),
      tipo:$('#BaseTipo').val(),
      sector:$('#BaseSector').val(),
      turno:$('#BaseTurno').val(),
      distribuidor:$('#BaseDistribuidor').val(),
      alumnos:$('#BaseAlumnos').val(),
      territorios:$('#BaseTerritorios').is(':checked'),
      cct:$('#BaseCct').val(),
    }


    if(variables.estado<1){
      alert("Estado requerido")
      return false
    }

   /* if(variables.municipio<1){
      alert("Municipio requerido")
      return false
    }
*/
    $("#cargador_municipios").html("<center><h2>Buscando</h2></center>");
    $("#cargador_municipios").load("<?php echo $html->url(array('controller'=>'Bases','action'=>'filtros_ruta')) ?>", variables, 
		function(){

		 });

    return false;
  }

  function mete_municipio(l,mun){
		$("<option value='"+l+"'>"+mun+"</option>").appendTo("#BaseMunicipio");
	}

  function dame_municipios(el){
	$('#BaseMunicipio').empty();


$("#cargador_municipios").load("<?php echo $html->url(array('controller'=>'Bases','action'=>'municipio')) ?>", {estado:el}, 
		function(){

		 });



}

	$('#BaseEstado').change(function() {
		dame_municipios(this.value);
	});

window.initMap = initMap;
</script>