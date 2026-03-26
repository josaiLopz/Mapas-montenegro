
<script>
    var permiso_editar=0;
 </script>
<style>
  .lista_nombres{
    border-bottom: 1px solid #aaa;
    margin:5px;
  }
  .migrar-section{
    background: #f5f5f5;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    border:1px solid #ddd;
  }
  .migrar-section h3{
    margin-top: 0;
    color:#555;
  }
  .migrar-section {
    background: #f5f5f5;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.migrar-section h3 {
    margin-top: 0;
    color: #555;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert-error {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}

</style>
</styl>
<link rel="stylesheet" type="text/css" href="/js/mapa/stilos.css" />
<hr>
<div class="containerz"> 
<?php
$mostrar_transferencia = false;
if(array_search("Bases|transferir_usuarios", $c_permisos) !== false) {
    $mostrar_transferencia = true;
}

if(isset($_SESSION['Usuario']['rol']) && $_SESSION['Usuario']['rol'] == 1) {
    $mostrar_transferencia = true;
}
if ($mostrar_transferencia): ?>

        <div class="col-lg-12 col-md-12">
            <!-- <a href="<?php echo $html->url(array('controller'=>'Bases','action'=>'transferir_usuarios')); ?>" 
            
               class="btn btn-primary">
                Administrar Transferencia de Territorios
            </a>
            <a href="<?php echo $html->url(array('controller'=>'Bases','action'=>'asignar_distribuidor_estado')); ?>" 
               class="btn btn-primary">
                Asignar estado
            </a> -->
            <a href="#"  data-toggle="modal" data-target="#modalAjax" data-url="<?php echo $html->url(array('controller'=>'Bases','action'=>'transferir_usuarios')); ?>"><img src="/img/botones/transferir-datos.png" style="max-width:30px;" alt=""></a>
            <a href="#"  data-toggle="modal" data-target="#modalAjax" data-url="<?php echo $html->url(array('controller'=>'Bases','action'=>'asignar_distribuidor_estado')); ?>"><img src="/img/botones/asignar.png" style="max-width:30px;margin-left:15px;" alt=""></a>
         <!-- Nuevo botón de Ver Historial -->
<a href="#" data-toggle="modal" data-target="#modalHistorial" onclick="cargarHistorial()"><img src="/img/botones/cheque.png" style="max-width:30px;margin-left:15px;" alt=""></a>
   
<?php endif; ?>

<!-- Modal genérico para cargar contenido -->
<div class="modal fade" id="modalAjax" tabindex="-1" role="dialog" aria-labelledby="modalAjaxLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAjaxLabel">Migración</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <!-- Aquí se cargará el contenido -->
        <div id="modalAjaxContent">Cargando...</div>
      </div>
    </div>
  </div>
</div>
<!-- Modal para Historial -->
<div class="modal fade" id="modalHistorial" tabindex="-1" role="dialog" aria-labelledby="modalHistorialLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalHistorialLabel">Historial de Cambios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modalHistorialContent">
          <div class="text-center">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Cargando...</span>
            </div>
            <p>Cargando historial...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<link rel="stylesheet" href="/js/popup/sunrise.css"></link>
<script src="/js/popup/sunrise.js"></script>


  <fieldset>
      

      <?php

$id_editar=0;
$ses=$_SESSION['Usuario']['rol'];
if($ses==4){
    $id_editar=$_SESSION['Usuario']['userid'];
}


configure::load("app/app"); 
$escuelas=configure::read("escuelas"); 
$estados=configure::read("estados"); 


echo "<div style='text-align:right; margin-bottom:10px;'>";
echo "<a href='javascript:toggleBuscador()'>";// Quitar para poder ver completo el div del buscador agregado recien
echo "<img id='toggle_icon' src='/img/iconos/desins.png' alt='Mostrar/Ocultar'>";// Quitar para poder ver completo el div del buscador agregado recien
echo "</a>";// Quitar para poder ver completo el div del buscador agregado recien
echo "</div>";// Quitar para poder ver completo el div del buscador agregado recien

// Contenedor del buscador
echo '<div id="buscador-container" style="display:block;">';// Quitar para poder ver completo el div del buscador agregado recien

         echo $form->create('Base',array('action'=>'index','onsubmit'=>'return buscar_base()'));

         echo '<div class="row justify-content-center">';
      	
        echo '<div class="col-lg-2 col-md-2 ">';
           echo $form->input("estado",array('style'=>"width:100%",'type'=>'select','options'=>$estados));
        echo '</div>';

        echo '<div class="col-lg-2 col-md-2 ">';
              echo $form->input("municipio",array('style'=>"width:100%",'type'=>'select','options'=>array('0'=>'Cualquiera')));
       echo '</div>';
        
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("tipo",array('style'=>"width:100%",'type'=>'select','options'=>$escuelas['tipos']));
        echo '</div>';


        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("sector",array('style'=>"width:100%",'type'=>'select','options'=>$escuelas['sectores']));
        echo '<br></div>';
        
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("turno",array('style'=>"width:100%",'type'=>'select','options'=>$escuelas['turnos']));
        echo '</div>';
        
        
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("alumnos",array('style'=>"width:100%",'label'=>'# De Alumnos','placeholder'=>'0, 500, -1000, +2000'));
        echo '</div>';

        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("distribuidor",array('style'=>"width:100%",'type'=>'select','options'=>$distribuidores));
        echo '</div>';

        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("nombre",array('style'=>"width:100%",'label'=>'Nombre de escuela'));
        echo '</div>';

        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("cct",array('style'=>"width:100%",'label'=>'CCT'));
        echo '</div>';

        
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("estatus",array('style'=>"width:100%",'label'=>'Estatus','type'=>'select','options'=>$escuelas['estatus']));
        echo '</div>';

        
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("verificada",array('style'=>"width:100%",'label'=>'Verificada','type'=>'select','options'=>$escuelas['verificada']));
        echo '</div>';
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("campo_editorial", ['style' => "width:100%", 'label' => 'Campo editorial', 'type'  => 'text', 'placeholder' => 'Ej: Primaria / Secundaria / Etc.']);
        echo '</div>';
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("venta_montenegro", ['style'   => "width:100%",'label'   => 'Venta Montenegro','type'    => 'select','options' => ['' => 'Cualquiera', '1' => 'Sí', '0' => 'No']]);
        echo '</div>';
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("competencia", ['style' => "width:100%",'label' => 'Competencia','type'  => 'text','placeholder' => 'Marca / Editor']);
        echo '</div>';
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("presupuesto", ['style' => "width:100%", 'label' => 'Presupuesto', 'type'  => 'text',  'placeholder' => '0, 50000, -100000, +200000']);
        echo '</div>';
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("fecha_decision", ['style' => "width:100%",'label' => 'Fecha decisión','type'  => 'date']);
        echo '</div>';
/*
        echo '<div class="col-lg-2 col-md-2 ">';
        echo $form->input("territorios",array('label'=>'Territorios','type'=>'checkbox'));
        echo '</div>';
  */      
        echo '</div>';
        
       // echo "<div style='position:absolute;width:0px;height:0px;overflow:hiddens;visibility:hiddens' id='cargador_municipios'></div>";

       echo "<div style='position:absolute;visibility:hidden;' id='cargador_municipios'></div>";

       echo '<br><div class="row justify-content-center" >';

       echo '<div class="col-lg-2 col-md-2 ">';
       echo $form->button('Limpiar', array(
        'type'=>'reset',
        'class' => 'btn btn-primary py-3 px-4',
        'style'=>'width:100%',
        'div' => false
    ));
       echo '</div>';

         echo '<div class="col-lg-2 col-md-2 ">';
             echo $form->submit('Buscar',array('style'=>'width:100%', 'class'=>"btn btn-primary py-3 px-4"));
         echo '</div>';

         if(array_search("Bases|add",$c_permisos)!==false){
          echo '<div class="col-lg-3 col-md-3 ">';
          echo "<script>permiso_editar=1</script>";
          echo "<input  style='width:100%' type='button' class='btn btn-primary py-3 px-4' value='Nueva Escuela' onclick='nueva_escuela()' >";
          echo '</div>';
        }
        echo '</div>'; // Quitar para poder ver completo el div del buscador agregado recien

        
        echo '</div>';
        echo "<center>";
        echo "<br><h2 id='l_resultados'></h2>";
        echo "</center>";

        echo "<div id='div_territorios'></div>";

        echo '</div>'; // Quitar para poder ver completo el div del buscador agregado recien
      ?>

      
   	
</fieldset>
<div class="row justify-content-center">
  <div class="col-lg-12 col-md-12 ">
    <div style='position:absolute;right:20px;'><a href='javascript:click_map_escuelas()'><img id='boton_ins' src='/img/iconos/desins.png'></a></div>
    <div id='map' style='width:70%;height:500px;float:left'></div>
    <div id='map_escuelas' style='margin-top:40px;width:27%;height:500px;float:right;border: 2px solid #aaa;overflow:auto'></div>
  </div>
 </div>
</div> 
  
<!-- Aqui va la API Key -->
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-b48ZJFA5XwcUihadKuJR-INlKZOcGzU&callback=initMap"
  async
  defer
></script>

<script>
  $('#modalAjax').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget);
    var url = button.data('url');
    var modal = $(this);
    
    // Cargar el formulario
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            modal.find('.modal-body').html(response);
            
            // Configurar el envío del formulario
            $('form', modal).on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Mostrar mensaje de éxito
                            modal.find('.modal-body').html(
                                '<div class="alert alert-success">' + response.message + '</div>' +
                                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>'
                            );
                        } else {
                            // Mostrar mensaje de error y mantener el formulario
                            var formContent = $('form', modal).html();
                            modal.find('.modal-body').html(
                                '<div class="alert alert-error">' + response.message + '</div>' +
                                formContent
                            );
                            // Volver a configurar el evento submit
                            $('form', modal).on('submit', arguments.callee);
                        }
                    },
                    error: function() {
                        modal.find('.modal-body').html(
                            '<div class="alert alert-error">Error al procesar la solicitud</div>'
                        );
                    }
                });
            });
        },
        error: function() {
            modal.find('.modal-body').html('<p>Error al cargar el contenido.</p>');
        }
    });
});

</script>
   
<script>
  var map
  let markers = [];
  let infowindow;
  var markets=new Array()
  var all_overlays=new Array()
  var coordenadas=new Array()
  var pop_sun=""
  var id_editar=<?php echo $id_editar; ?>

  function click_map_escuelas(){

    var x=$("#map_escuelas");
  if (x.is(":hidden")) {
        $("#boton_ins").attr("src",'/img/iconos/desins.png');
        $("#map").animate({width: '78%'},1000,function() {  x.slideDown(1000); })
      }
 else {
      $("#boton_ins").attr("src",'/img/iconos/ins.png');
        // x.slideUp(1000,function() {  $("#map").animate({width: '100%'},1000)});
        x.slideUp(1000,function() {  $("#map").animate({width: '113%',  height: '80vh', marginLeft: '-105px'},1000)});
      }

  }
  function pon_territorio(id,gem,color,edit,num){    

    coordenadas[id]=gem;
    

    const bermudaTriangle = new google.maps.Polygon({
      editable:edit,
     
        paths: gem,
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 3,
        fillColor: color,
        fillOpacity: 0.35,
  });

  bermudaTriangle.setMap(map);
  all_overlays[id]=bermudaTriangle;


  if(!edit){
    var myLatLng=gem
    let bounds = new google.maps.LatLngBounds();
                  myLatLng.forEach((latLng) => {
                      bounds.extend(latLng);
                  });
                 /* let marker = new google.maps.Marker({
                      map: map,
                      title: num.title,
                      label:num.label,
                      position: bounds.getCenter()
                  });
                  markets[id]=marker*/

  }
                 

  }

  function centra_territorio(id){
      var myLatLng=coordenadas[id]
      let bounds = new google.maps.LatLngBounds();
                    myLatLng.forEach((latLng) => {
                  //  console.log(latLng.lat()+" / "+latLng.lng())
                        bounds.extend(latLng);
                    });
                   
                    
      map.fitBounds(bounds);
    
    }


  function deleteAllShape() {
      for (var i=0; i < all_overlays.length; i++)
      {
        try{
          all_overlays[i].setMap(null);
          markets[i].setMap(null);
        }catch(e){}
      }
      all_overlays = [];
      markets= [] ;
}



  function buscar_base(){
    $("#l_resultados").html("<center><h2>Buscando</h2></center>");

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
      nombre:$('#BaseNombre').val(),
      estatus:$('#BaseEstatus').val(),
      verificada:$('#BaseVerificada').val(),

      campo_editorial:   $('#BaseCampoEditorial').val(),
      venta_montenegro:  $('#BaseVentaMontenegro').val(),
      competencia:       $('#BaseCompetencia').val(),
      presupuesto:       $('#BasePresupuesto').val(),
      fecha_decision:    $('#BaseFechaDecision').val(),
    }


    $("#cargador_municipios").load("<?php echo $html->url(array('controller'=>'Bases','action'=>'filtros')) ?>", variables, 
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
  



  function clearMarkers() {

    infoWindow = new google.maps.InfoWindow({
    content: " ",
  });

  for (let i = 0; i < markers.length; i++) {
    if (markers[i]) {
      markers[i].setMap(null);
    }
  }

  markers = [];
}



  function pon_marca(datos,mover){
    if(mover)
      mover=true;
    else
      mover=false;

color_pin="f00"
    if(datos.estatus==2)
      color_pin="ff0"
    if(datos.estatus==3)
      color_pin="0f0"
    if(datos.estatus==4)
      color_pin="333"
    
  


      const svgMarker = {
    path: "M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z",
    fillColor: color_pin,
    fillOpacity: 1,
    strokeWeight: 0,
    rotation: 0,
    scale: 1.5,
    anchor: new google.maps.Point(0, 20),
  };


/*
   icon: {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        strokeColor: "red",
        scale: 3
    },

     icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|'+color_pin,
*/


    markers[datos.id] = new google.maps.Marker({
          position:   { lat: datos.lat, lng: datos.lng },
          map,
          //icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|'+color_pin,
         icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
          draggable:mover,
          animation: google.maps.Animation.DROP
        });

/*
        google.maps.event.addListener(markers[datos.id], "click", () => {
  infowindow = new google.maps.InfoWindow();
   infowindow.setContent(`<div class="ui header">Parliment Hill</div>`);
   infowindow.open(map, markers[datos.id]);
});
*/

       
if(mover){
  xx=datos.id
  markers[datos.id].setIcon({
          url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
          scaledSize: new google.maps.Size(60, 60)
          });
  cad="<div style='width:200px;padding:10px;'>";
          cad+="<span class='titulo_etiqueta'>Nombre:</span> "+datos.nombre;
          cad+="<br><span class='titulo_etiqueta'>Estado:</span> "+datos.estado;
          cad+="<br><span class='titulo_etiqueta'>Municipio:</span> "+datos.municipio;
          

          if(permiso_editar==1){
            cad+="<br><br><center><a href=javascript:guardar_pin(xx) >Guardar nuevas coordenadas</a></center>";
            cad+="<br><center><a href=javascript:cancelar_pin() >Cancelar nuevas coordenadas</a></center>";
    
          }


          cad+="</div>";
          infoWindow.setContent(cad)
          
          infoWindow.open({
      anchor: markers[datos.id],
      map,
    });
}

 
        

        markers[datos.id].addListener("click", () => {
          xx=datos.id
          markers[datos.id].setIcon({
          url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
          scaledSize: new google.maps.Size(60, 60)
          });
          // markers[datos.id].setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
          cad="<div style='width:200px;padding:10px;'>";


          if(datos.verificada==2)
		        cad+='<div style="position:absolute;left:5px;top:5px" ><img src="/img/iconos/calificaciones.png" ></div>';


          cad+="<span class='titulo_etiqueta'>Nombre:</span> "+datos.nombre;
          cad+="<br><span class='titulo_etiqueta'>Estado:</span> "+datos.estado;
          cad+="<br><span class='titulo_etiqueta'>Municipio:</span> "+datos.municipio;

          cad+="<div id='info_1' >";
          cad+="<br><span class='titulo_etiqueta'>Tipo:</span> "+datos.tipo;
          cad+="<br><span class='titulo_etiqueta'>Sector:</span> "+datos.sector;
          cad+="<br><span class='titulo_etiqueta'>Turno:</span> "+datos.turno;
          cad+="<br><span class='titulo_etiqueta'>CCT:</span> "+datos.cct;
          cad+="<br><span class='titulo_etiqueta'># De Alumnos:</span> "+datos.alumnos;
          cad+="<br><span class='titulo_etiqueta'>Distribuidor:</span> "+datos.distribuidor;
          cad+="</div>";


          cad+="<div id='info_2' >";
          cad+="<br><span class='titulo_etiqueta'># De Grupos:</span> "+datos.grupos;
          cad+="<br><span class='titulo_etiqueta'>Nombre contacto:</span> "+datos.nombre_contacto;
          cad+="<br><span class='titulo_etiqueta'>Tel&eacute;fono contacto:</span> "+datos.telefono_contacto;
          cad+="<br><span class='titulo_etiqueta'>Correo contacto:</span> "+datos.correo_contacto;
          cad+="<br><span class='titulo_etiqueta'>Notas:</span> "+datos.notas;
          cad+="</div>";

          cad+="<div style='font-size:2em;'><center>";
             cad+="<a id='minfo_1' href=javascript:muestra_minfo(2)><img src='/img/iconos/next.png' ></a>";
             cad+="<a id='minfo_2' href=javascript:muestra_minfo(1)><img src='/img/iconos/prev.png' ></a>";
          cad+="</center></div>";

          setTimeout("muestra_minfo(1)",100);

          if(permiso_editar==1){
           if(id_editar==0 || id_editar==datos.id_distribuidor){

            if(!mover){
                cad+="<center><a href=javascript:editar_info(xx) >Editar información</a></center>";
                cad+="<br><center><a href=javascript:mover_pin(xx) >Mover Pin</a></center>";
            
              }
              else{
              cad+="<br><br><center><a href=javascript:guardar_pin(xx) >Guardar nuevas coordenadas</a></center>";
              cad+="<br><center><a href=javascript:cancelar_pin() >Cancelar nuevas coordenadas</a></center>";
    
            }
          }}


          cad+="</div>";
          infoWindow.setContent(cad)
          
          infoWindow.open({
      anchor: markers[datos.id],
      map,
    });
  });
 }

 function muestra_minfo(n){

  if(n==1){
    $("#info_2").slideUp(1)
    $("#minfo_2").slideUp(1)

    $("#info_1").slideDown(1)
    $("#minfo_1").slideDown(1)
  }
  else{
    $("#info_1").slideUp(1)
    $("#minfo_1").slideUp(1)

    $("#info_2").slideDown(1)
    $("#minfo_2").slideDown(1)

  }
 }
    // google.maps.event.addListener(markers[datos.id], "click", showInfoWindow);
  
  function guardar_pin(id){
    pos=markers[id].getPosition();
      

    lat=pos.lat();
    lng=pos.lng();

    $( "#cargador_municipios" ).load( "/Bases/guardar_pin",{id:id,lat:lat,lng:lng}, function() {
      buscar_base()
          });

  }

    function cancelar_pin(){
      buscar_base()
    }

  function editar_info(id){
          tar='/Bases/edit/'+id
         	pop_sun=sunrise({target: tar, ajax: true })
         }

   function cierra_pop(datos){
   
   $( "#cargador_municipios" ).load( "/Bases/guardar",datos, function(a) {
          try{
            buscar_base()
          }catch(e){}
  });
   pop_sun.sunrise('closePopup');
  // $(".sunrise-visible").html('');
  // $(".sunrise-visible").hide();
       
}

function click_lista(i){
  google.maps.event.trigger(markers[i], "click");
}

function mover_pin(id){
  $( "#cargador_municipios" ).load( "/Bases/mover_pin",{id:id}, function() {
           
  });
  
}
 
function nueva_escuela(){
         tar='/Bases/add/'
         	pop_sun=sunrise({target: tar, ajax: true })
  
}

function initMap() {
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
 

}

window.initMap = initMap;
function carga_pop_inicio(){

          tar='/Bases/edit/10/1'
         	pop_sun=sunrise({target: tar, ajax: true })
           setTimeout("cierra_pop_inicio()",2000)
           $(".sunrise-frame").hide(1)
           $(".sunrise-outer").hide(1)
         
}

function cierra_pop_inicio(){
 pop_sun.sunrise('closePopup');
}
setTimeout("carga_pop_inicio()",2000)
function toggleBuscador() {
    var buscador = document.getElementById("buscador-container");
    var icono = document.getElementById("toggle_icon");

    if (buscador.style.display === "none") {
        buscador.style.display = "block";
        icono.src = "/img/iconos/desins.png"; // Imagen cuando el buscador está visible
    } else {
        buscador.style.display = "none";
        icono.src = "/img/iconos/ins.png"; 
    }
}
function cargarHistorial() {
    // Mostrar spinner mientras carga
    $('#modalHistorialContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <p>Cargando historial...</p>
        </div>
    `);
    
    // Cargar el contenido del archivo PHP
    $.ajax({
        url: '/aprobaciones/mostrar_historial.php', // Ajusta esta ruta según la ubicación de tu archivo
        type: 'GET',
        success: function(response) {
            $('#modalHistorialContent').html(response);
        },
        error: function() {
            $('#modalHistorialContent').html(`
                <div class="alert alert-danger">
                    Error al cargar el historial. Por favor, intente nuevamente.
                </div>
            `);
        }
    });
}

</script>