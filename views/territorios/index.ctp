<script>
    var permiso_editar=0;
    var map
    var all_overlays=new Array()
    var markets=new Array()
    var coordenadas=new Array()
    var editando=0
    var intervalo
  </script>

  <script src="/js/colores/jquery.minicolors.js"></script>
  <link rel="stylesheet" href="/js/colores/jquery.minicolors.css">

  <link rel="stylesheet" type="text/css" href="/js/mapa/territorio.css" />
    <script type="module" src="/js/mapa/territorio.js"></script>
    
    <?php
 
    if(array_search("Territorios|add",$c_permisos)!==false){
      echo "<div id='datos_editar'>";
      echo "<fieldset><legend>Datos del territorio</legend>";

      echo "<div style='float:right;font-size:3em;'> ";
      echo "<a href=javascript:click_cancelar()>X</a>";
      echo "</div>";

      echo '<div class="container"> <div class="row justify-content-center">';

        echo '<div class="col-lg-6 col-md-6 ">';
            echo $form->input('id_distribuidor',array('label'=>false,'type'=>'select','options'=>$distribuidores));
        echo "</div>";

	  echo '<div class="col-lg-6 col-md-6 ">';
          echo $form->input('color',array('label'=>false,'placeholder'=>'Color', 'class'=>'demo'));
  	echo "</div>";

 

echo '</div></div><br><center>';
echo "<a href='javascript:guardar_territorio()' class='btn btn-primary py-3 px-4'>Guardar</a>";
echo "</center>";
echo $form->input('coords',array('label'=>'','type'=>'hidden'));
echo $form->input('id',array('label'=>'','type'=>'hidden'));
echo "</fieldset>";
echo "</div>";

     echo "<center><a href='javascript:nuevo_territorio()' class='btn btn-primary py-3 px-4'>Nuevo territorio</a></center><br>";
    }

    ?>
  <div id='datos_escuela' style='position:absolutes;visibility:hiddens'></div>

<div class="container"> <div class="row justify-content-center">
	<div class="col-lg-9 col-md-6 ">
    <div id='map' style='width:100%;height:500px;'></div>
  </div>
  <div class="col-lg-3 col-md-6 ">
    <div id='territorios' style='height:500px;overflow:auto'></div>
  </div>
  </div>
  </div>
  
  
  <script>
    var drawingManager;
    </script>

 
  <script
  src="https://maps.googleapis.com/maps/api/js?key=keyY&callback=initMap&libraries=drawing"
     defer
    ></script>

  <script>
  oculta("datos_editar");

    function nuevo_territorio(){
      editando=0
      click_cancelar()

      $("#id_distribuidor").val(0)
       $("#color").val("#000000")
       $("#coords").val("")
       $("#id").val("")

       muestra("datos_editar");
       try{
        all_overlays[0].setMap(null);
       }catch(e){}

       mapa_modo_poligono();
    }

    function click_cancelar(){
      editando=0
      oculta('datos_editar');
      try{
        all_overlays[0].setMap(null);
      }catch(e){}

      try{
        id=$("#id").val()
        all_overlays[id].setMap(null);
        pon_territorio(id,coordenadas[id],$("#color").val(),false)
      }catch(e){}
      
      mapa_modo_hand();
    }

    function mapa_modo_poligono(){
        drawingManager.setOptions({
            drawingMode:google.maps.drawing.OverlayType.POLYGON
        
      });
    }

    function mapa_modo_hand(){
        drawingManager.setOptions({
            drawingMode:google.maps.drawing.OverlayType.HAND
        
      });
    }

    function editar_territorio(id){
      centra_territorio(id)
       click_cancelar()
       editando=id
       $("#id_distribuidor").val($("#distribuidor_"+id).val())
       $("#color").val($("#color_"+id).val())
       $("#coords").val($("#coords_"+id).val())
       $("#id").val(id)
       muestra("datos_editar");

       all_overlays[id].setMap(null);
       pon_territorio(id,coordenadas[id],$("#color").val(),true);  
    }

    function eliminar_territorio(id){
        c=confirm("Deseas elimianr el terriotior?")

        if(c){
          $( "#datos_escuela" ).load( "/Territorios/eliminar",{id:id}, function() {
              cargar_territorios()
            });
        }
    }
     

    function guardar_territorio(){
      if(editando>0)
      refrescar_bounds(editando)
 
      var error=0
      c=$("#color").val()
      if(c.length<4){
        alert("Debes seleccionar un color");
        error=1
      }

      c=$("#coords").val()
      if(c.length<4){
        alert("Debes marcar el territorio en el mapa");
        error=1
      }

      if( error==0){
      $( "#datos_escuela" ).load( "/Territorios/guardar",{id:$("#id").val(),id_distribuidor:$("#id_distribuidor").val(),color:$("#color").val(),coords:$("#coords").val()}, function() {
              cargar_territorios()
              oculta("datos_editar");
            });
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
                    let marker = new google.maps.Marker({
                        map: map,
                        title: num.title,
                        label:num.label,
                        position: bounds.getCenter()
                    });
                    markets[id]=marker
                  


      //editando=id
     // intervalo=setInterval("refrescar_bounds("+id+")",5000);
    }
                   

    }

    function refrescar_bounds(id){
  
          let bounds = new google.maps.LatLngBounds();
          let cad="";
          all_overlays[id].getPath().forEach((latLng) => {
                 cad+=latLng.lat()+"/"+latLng.lng()+";";
          });
          $("#coords").val(cad)
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

    function cargar_territorios(){
   
      $( "#territorios" ).load( "/Territorios/cargar", function() {
              
            });
    }
    

    $(document).ready( function() {
     
      cargar_territorios()

      $('.demo').each( function() {
        //
        // Dear reader, it's actually very easy to initialize MiniColors. For example:
        //
        //  $(selector).minicolors();
        //
        // The way I've done it below is just for the demo, so don't get confused
        // by it. Also, data- attributes aren't supported at this time...they're
        // only used for this demo.
        //
        $(this).minicolors({
          control: $(this).attr('data-control') || 'hue',
          defaultValue: $(this).attr('data-defaultValue') || '',
          format: $(this).attr('data-format') || 'hex',
          keywords: $(this).attr('data-keywords') || '',
          inline: $(this).attr('data-inline') === 'true',
          letterCase: $(this).attr('data-letterCase') || 'lowercase',
          opacity: $(this).attr('data-opacity'),
          position: $(this).attr('data-position') || 'bottom',
          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
          change: function(value, opacity) {
            if( !value ) return;
            if( opacity ) value += ', ' + opacity;
            if( typeof console === 'object' ) {
              console.log(value);
            }
          },
          theme: 'bootstrap'
        });

      });

    });

    function centra_territorio(id){
      var myLatLng=coordenadas[id]
      let bounds = new google.maps.LatLngBounds();
                    myLatLng.forEach((latLng) => {
                  //  console.log(latLng.lat()+" / "+latLng.lng())
                        bounds.extend(latLng);
                    });
                   
                    
      map.fitBounds(bounds);
    
    }
  </script>