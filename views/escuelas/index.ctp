<script>
    var permiso_editar=0;
    var max_place=0;
  </script>
    <?php

  if(array_search("Escuelas|add",$c_permisos)!==false)
      echo "<script>permiso_editar=1</script>";


    ?>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- jsFiddle will insert css and js -->

    <link rel="stylesheet" type="text/css" href="/js/mapa/stilos.css" />
    <script  src="/js/mapa/index.js"></script>

	
    <link rel="stylesheet" href="/js/popup/sunrise.css"></link>
  <script src="/js/popup/sunrise.js"></script>

  <div id='datos_escuela' style='position:absolute;visibility:hidden'></div>



    <div class="hotel-search" style='width:100%;'>

      <div style='position:absolute;right:0px;width:50%;text-align:right'>

      <div style='float:right;margin-left:20px;'>
              <input id='filtro_escuela' placeholder='Escuela' style='width:230px;height:30px;margin-top:5px;' >
        </div>

        <div style='float:right'>
              <a href='javascript:search()' class='btn btn-primary py-2 px-4'>Buscar escuelas</a>
        </div>



    </div>
        <div id="findhotels">Buscador:</div>

      <div id="locationField">
        <input id="autocomplete" placeholder="Indica lugar" type="text" style='width:500px'/>
      </div>

      <div id="controls" style="display: none">
        <select id="country">
          <option value="mx">Mexico</option>
        </select>
      </div>
    </div>

    
  

    <div id="map" style='height:500px'></div>

    <div id="listing" style='margin-top:40px;'>
    <?php /*
<table id="resultsTable">
  <tbody id="results">
    <tr style="background-color: rgb(240, 240, 240);">
      <td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenA.png" class="placeIcon" classname="placeIcon"></td>
      <td>Motus Estudio de Baile</td>
    </tr>
    
    <tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenB.png" class="placeIcon" classname="placeIcon"></td><td>Centro Educativo Freire</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenC.png" class="placeIcon" classname="placeIcon"></td><td>Centro de Integración Infantil Andinos</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenD.png" class="placeIcon" classname="placeIcon"></td><td>Colegio Columbia</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenE.png" class="placeIcon" classname="placeIcon"></td><td>Jardín de Niños Sunrise</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenF.png" class="placeIcon" classname="placeIcon"></td><td>Colegio Liceo del Country</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenG.png" class="placeIcon" classname="placeIcon"></td><td>CENTRO EDUCATIVO PASOS CHIQUITOS</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenH.png" class="placeIcon" classname="placeIcon"></td><td>iDenizens</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenI.png" class="placeIcon" classname="placeIcon"></td><td>Instituto Americano</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenJ.png" class="placeIcon" classname="placeIcon"></td><td>Escuela Garibi sucursal Seattle</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenK.png" class="placeIcon" classname="placeIcon"></td><td>Instituto América Jardines del Country A.C.</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenL.png" class="placeIcon" classname="placeIcon"></td><td>CECATI 56</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenM.png" class="placeIcon" classname="placeIcon"></td><td>Harvest Language Center</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenN.png" class="placeIcon" classname="placeIcon"></td><td>Instituto Lumiere - Plantel Zapopan</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenO.png" class="placeIcon" classname="placeIcon"></td><td>Esc De Decoracion Veracruz Ac</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenP.png" class="placeIcon" classname="placeIcon"></td><td>Colegio Victoria</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenQ.png" class="placeIcon" classname="placeIcon"></td><td>English Corner</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenR.png" class="placeIcon" classname="placeIcon"></td><td>Honolulu Academic</td></tr><tr style="background-color: rgb(240, 240, 240);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenS.png" class="placeIcon" classname="placeIcon"></td><td>Instituto Tlaloc Educacion Bilingue</td></tr><tr style="background-color: rgb(255, 255, 255);"><td><img src="https://developers.google.com/maps/documentation/javascript/images/marker_greenT.png" class="placeIcon" classname="placeIcon"></td><td>JARDIN DE NIÑOS 208 Y 383</td></tr></tbody>
</table>
    */ ?>


      <table id="resultsTable">
        <tbody id="results"></tbody>
      </table>
    </div>

    <div style="display: none">
        <div id="info-content">
          <table>
            <tr id="iw-url-row" class="iw_table_row">
              <td id="iw-icon" class="iw_table_icon"></td>
              <td id="iw-url"></td>
            </tr>
            <tr id="iw-address-row" class="iw_table_row">
              <td class="iw_attribute_name">Direcci&oacute;n:</td>
              <td id="iw-address"></td>
            </tr>
            <tr id="iw-phone-row" class="iw_table_row">
              <td class="iw_attribute_name">Tel&eacute;fono:</td>
              <td id="iw-phone"></td>
            </tr>
            <tr id="iw-rating-row" class="iw_table_row">
              <td class="iw_attribute_name">Calificaci&oacute;n:</td>
              <td id="iw-rating"></td>
            </tr>
            <tr id="iw-website-row" class="iw_table_row">
              <td class="iw_attribute_name">Sitio web:</td>
              <td id="iw-website"></td>
            </tr>
            <tr id="iw-cct-row" class="iw_table_row">
              <td class="iw_attribute_name">CCT: </td>
              <td id="iw-cct"></td>
            </tr>

            <tr id="iw-alumnos-row" class="iw_table_row">
              <td class="iw_attribute_name">Alumnos: </td>
              <td id="iw-alumnos"></td>
            </tr>

            <tr id="iw-turnos-row" class="iw_table_row">
              <td class="iw_attribute_name">Turnos: </td>
              <td id="iw-turnos"><div id="iw-matutino"></div>  <div id="iw-vespertino"></div> </td>
            </tr>

            <tr id="iw-dis-row" class="iw_table_row">
              <td class="iw_attribute_name">Distribuidor: </td>
              <td id="iw-id_distribuidor"></td>
            </tr>

            <tr id="iw-editable-row" class="iw_table_row">
            
              <td id="iw-editable" colspan="2" style='text-align:center'></td>
            </tr>
          </table>
        </div>
      </div>

    <!-- 
      The `defer` attribute causes the callback to execute after the full HTML
      document has been parsed. For non-blocking uses, avoiding race conditions,
      and consistent behavior across browsers, consider loading using Promises
      with https://www.npmjs.com/package/@googlemaps/js-api-loader.
      -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=key&callback=initMap&libraries=places&v=weekly"
      defer
    ></script>
    <script>
        function editar_info(){
          tar='/Escuelas/edit/'
          tar+=max_place.place_id+'/'
          tar+=max_place.name+'/'
          tar+=max_place.geometry.location.lat()+'/'
          tar+=max_place.geometry.location.lng()+'/'
		      	sunrise({target: tar, ajax: true })
         }


         function cargar_datos(id_google){
            $( "#datos_escuela" ).load( "/Escuelas/cargar",{id_maps:id_google}, function(a) {
                  cad=a.split("@&@");
                  for(i=0;i<cad.length;i++){
                    s=cad[i].split(":");
                    $("#iw-"+s[0]).html(s[1])					
                  }
            });
		 }

		 function cierra_pop(datos){
   
        $( "#datos_escuela" ).load( "/Escuelas/guardar",datos, function(a) {
          cargar_datos(datos.id_maps)
        });
        $(".sunrise-visible").html('');
        $(".sunrise-visible").hide();
            
		 }

     

     $("#filtro_escuela").keyup(function(){
    if( $(this).val() != ""){
        $("#resultsTable tbody>tr").hide();
        $("#resultsTable td:contiene-palabra('" + $(this).val() + "')").parent("tr").show();
    }
    else{
        $("#resultsTable tbody>tr").show();
    }
});
 
$.extend($.expr[":"], 
{
    "contiene-palabra": function(elem, i, match, array) {
        return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});

</script>

