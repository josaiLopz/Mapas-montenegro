
<?php


echo  "<h2>".count($bases)." resultados</h2>";
echo $form->create('Base',array('action'=>'index','onsubmit'=>'return buscar_ruta()'));




echo '<div class="row justify-content-center" style="max-height:500px;overflow-x:hidden;">';
$mun=0;
	foreach($bases as $l=>$m){

		if($m['Base']['municipio']!=$mun){
			echo "</div>";
				echo "<br><br><div class='m-1' style='text-align:center;background:#ccc;font-size:2em;padding-left:5px;border: 1px solid #000;' >";
					echo wordwrap(utf8_encode($m['Base']['municipio_n']));
			echo '</div>';
		
		echo '<div class="row justify-content-center" style="max-height:500px;overflow-x:hidden;">';
			$mun=$m['Base']['municipio'];
		}
		
		echo "<div class='col-lg-4 col-md-4' >";
		echo "<div class='m-1' style='padding-left:5px;border: 1px solid #000;' >";
				echo $form->input("base_".$l,array('label'=>wordwrap(utf8_encode($m['Base']['nombre']))." (".$m['Base']['cct'].")<br>".$m['Base']['alumnos']." alumnos",'type'=>'checkbox','class'=>$m['Base']['lat'],'title'=>$m['Base']['lng'],'rel'=>$m['Base']['id']));		
		echo '</div>';
		echo '</div>';
	}
echo "</div>";

echo '<div class="row justify-content-center" style="margin-top:20px">';
echo "<div class='col-lg-4 col-md-4' >";
	echo $form->submit('Buscar ruta entre estas escuelas',array( 'class'=>"btn btn-primary py-3 px-4"));
echo "</div>";

if(array_search("Rutas|add",$c_permisos)!==false){
	echo "<div class='col-lg-4 col-md-4' >";
		echo "<input  type='button' class='btn btn-primary py-3 px-4' value='Guardar ruta' onclick='guardar_ruta()' >";
	echo "</div>";
}

echo "</div>";
echo "<div id='guardar_ruta'></div>";
?>

<script>

function guardar_ruta(){
	cad=new Array();
	escuelas=""
	voy=0;
	for(i=0;i< <?php echo count($bases); ?>; i++  ){
		if($("#BaseBase"+i).is(':checked')){
			cad[voy]=$("#BaseBase"+i).attr("class")+","+$("#BaseBase"+i).attr("title");
			escuela=$("#BaseBase"+i).attr("rel");
			escuelas+=escuela+",";
			voy++;
		}
	}

	if(voy<2){
		alert("Selecciona al menos 2 escuelas");
		return false;
	}

	way=""
	inicio=new Array()
	fin=new Array()
	voy1=voy
	voy1--
	xx=0
	for(i=0;i< voy; i++  ){
			if(i==0)
				inicio=cad[i]
			else if(i==voy1)
				fin=cad[i]
			else{	
				way+=cad[i]+";"
				xx++
			}
	}

	variables={
		inicio:inicio,
		fin:fin,
		way:way,
		ids:escuelas
	}

	$("#guardar_ruta").load("<?php echo $html->url(array('controller'=>'Bases','action'=>'guardar_ruta')) ?>", variables, 
		function(){
			$("#guardar_ruta").html("<center><h2>Ruta guardada</h2></center>")
		 });
}	
function buscar_ruta(){

	cad=new Array();
	voy=0;
	for(i=0;i< <?php echo count($bases); ?>; i++  ){
		if($("#BaseBase"+i).is(':checked')){
			cad[voy]=new google.maps.LatLng($("#BaseBase"+i).attr("class") ,$("#BaseBase"+i).attr("title") );
			voy++;
		}
	}

	if(voy<2){
		alert("Selecciona al menos 2 escuelas");
		return false;
	}

	way=new Array()
	inicio=new Array()
	fin=new Array()
	voy1=voy
	voy1--
	xx=0
	for(i=0;i< voy; i++  ){
			if(i==0)
				inicio=cad[i]
			else if(i==voy1)
				fin=cad[i]
			else{	
				way[xx]=cad[i]
				xx++
			}
	}

	establece_ruta(inicio,fin,way)

	
	return false;
}
</script>

