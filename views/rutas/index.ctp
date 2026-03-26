<div class="usuarios index">
  <h2><?php __('Mis rutas'); ?></h2>

  <p>
    <?php
    echo $paginator->counter(array(
      'format' => __('<center><h3>%count% Rutas - Página <strong>%page%</strong> de %pages%</h3></center><br>', true)
    ));
    ?>
  </p>

  <?php
  function obtener_url_googlemaps($rr)
  {
    $cad = "";
    $cad .= $rr['inicio'] . "/";
    $cad .= $rr['fin'] . "/";
    $x = explode(";", $rr['way']);

    foreach ($x as $xx) {
      if (!empty($xx))
        $cad .= $xx . "/";
    }

    return 'https://www.google.com.mx/maps/dir/' . $cad;
  }

  echo '<div class="row justify-content-center">';

  configure::load("app/app");
  $escuelas = configure::read("escuelas");

  foreach ($rutas as $r) {
    $url_google_maps = obtener_url_googlemaps($r['Ruta']);
    echo "<div class='col-lg-12 col-md-12' style='padding-top:20px;'>";
    echo "<center><strong>" . $r['Ruta']['created'] . "</strong></center>";
    echo "<a href='javascript:pon_ruta(\"" . $r['Ruta']['inicio'] . "\", \"" . $r['Ruta']['fin'] . "\", \"" . $r['Ruta']['way'] . "\")'>Mostrar mapa</a>";
    echo "<div style='float:right'>";
    echo "<a style='margin-right:20px;' target='_blank' href='" . $url_google_maps . "'>Exportar</a>";
    echo $this->element('setAction', array('parametros' => array('Eliminar', 'iconos/eliminar.png', array('action' => 'delete', $r['Ruta']['id']), 'Eliminar', '¿Eliminar la ruta?')));
    echo "</div>";
    echo "<table border='1' cellspacing='5' cellpadding='5' style='width:100%'>";
    echo "<tr>";
    echo "<th style='text-align:center'>Nombre</th>";
    echo "<th style='text-align:center'>Estado</th>";
    echo "<th style='text-align:center'>Municipio</th>";
    echo "<th style='text-align:center'>Tipo</th>";
    echo "<th style='text-align:center'>Sector</th>";
    echo "<th style='text-align:center'>Turno</th>";
    echo "<th style='text-align:center'># de alumnos</th>";
    echo "<th style='text-align:center'>CCT</th>";
    echo "</tr>";
    foreach ($rutas_ids[$r['Ruta']['id']] as $escuela) {
      echo "<tr>";
      echo "<td>" . wordwrap(utf8_encode($escuela['Base']['nombre'])) . "</td>";
      echo "<td>" . wordwrap(utf8_encode($escuela['Base']['estado_n'])) . "</td>";
      echo "<td>" . wordwrap(utf8_encode($escuela['Base']['municipio_n'])) . "</td>";
      echo "<td>" . wordwrap(utf8_encode($escuela['Base']['tipo'])) . "</td>";
      echo "<td>" . wordwrap(utf8_encode($escuela['Base']['sector'])) . "</td>";
      echo "<td>" . wordwrap(utf8_encode($escuelas['turnos'][$escuela['Base']['turno']])) . "</td>";
      echo "<td style='text-align:center'>" . wordwrap(utf8_encode($escuela['Base']['alumnos'])) . "</td>";
      echo "<td>" . wordwrap(utf8_encode($escuela['Base']['cct'])) . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    echo '</div>';
  }
  echo "</div>";
  ?>

  <br>

  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12">
      <div id="map" style="width:100%;height:500px;"></div>
    </div>
  </div>

  <br>
  <?php echo $this->element("botones_paginador"); ?>

</div>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap&libraries=drawing" async></script>

<script>
  var directionsService;
  var directionsRenderer;
  var map;

  function initMap() {
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    map = new google.maps.Map(document.getElementById("map"), {
      center: {
        lat: 23.6,
        lng: -102.5
      },
      zoom: 5,
      styles: [{
          "featureType": "administrative",
          "elementType": "geometry",
          "stylers": [{
            "visibility": "off"
          }]
        },
        {
          "featureType": "poi",
          "stylers": [{
            "visibility": "off"
          }]
        },
        {
          "featureType": "road",
          "elementType": "labels.icon",
          "stylers": [{
            "visibility": "off"
          }]
        },
        {
          "featureType": "transit",
          "stylers": [{
            "visibility": "off"
          }]
        }
      ]
    });

    directionsRenderer.setMap(map);
  }

  function dame_latlng(punto) {
    var inic = punto.split(",");
    return new google.maps.LatLng(parseFloat(inic[0]), parseFloat(inic[1]));
  }

  function pon_ruta(inicio, fin, way) {
    var i = dame_latlng(inicio);
    var f = dame_latlng(fin);
    var ws = way.split(";");
    var ways = [];

    for (var j = 0; j < ws.length - 1; j++) {
      ways.push(dame_latlng(ws[j]));
    }

    establece_ruta(i, f, ways);
  }

  function establece_ruta(inicio, fin, way) {
    const waypts = way.map(location => ({
      location: location,
      stopover: true
    }));

    directionsService.route({
        origin: inicio,
        destination: fin,
        waypoints: waypts,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING,
      })
      .then((response) => {
        directionsRenderer.setDirections(response);
      })
      .catch((e) => window.alert("No se encontraron rutas para estos puntos: " + e));
  }

  window.initMap = initMap;
</script>
