
<script>
	clearMarkers();
	$("#div_territorios").html("");

	$('#map_escuelas').html("");
</script>
<?php

function dame_poly($cad){
	$puntos=array($cad[0],$cad[count($cad)-1]);


	array_pop($cad);
	$cad=array_reverse($cad);
	array_pop($cad);

	sort($cad);

$puntos[2]=$cad[0];
$puntos[3]=$cad[count($cad)-1];



	$res="[";
	$p=$puntos[0];
	$res.="{lat:".$p[0].",lng:".$p[1]."},";

	$p=$puntos[2];
	$res.="{lat:".$p[0].",lng:".$p[1]."},";

	$p=$puntos[1];
	$res.="{lat:".$p[0].",lng:".$p[1]."},";
	
	$p=$puntos[3];
	$res.="{lat:".$p[0].",lng:".$p[1]."}";
	$res=$res."]";

	return $res;

}

if(empty($mover))
	$mover=0;

function randomColor(){
	$str = "#";
	for($i = 0 ; $i < 6 ; $i++){
	$randNum = rand(0, 15);
	switch ($randNum) {
	case 10: $randNum = "A"; 
	break;
	case 11: $randNum = "B"; 
	break;
	case 12: $randNum = "C"; 
	break;
	case 13: $randNum = "D"; 
	break;
	case 14: $randNum = "E"; 
	break;
	case 15: $randNum = "F"; 
	break; 
	}
	$str .= $randNum;
	}
	return $str;
   }
   

   configure::load("app/app"); 
    $escuelas=configure::read("escuelas"); 

	$territorios=array();
	$puntos=array();
	$cont_ter=array();

	$limite=35000;
	$numero_alumnos=0;
	$nombres="";
	foreach($bases as $l=>$m){
		
		$numero_alumnos+=$m['Base']['alumnos'];

		if($l<10000){
			$ll=$l;
			$ll++;
		?>
		<script>$('#map_escuelas').append( "<div class='lista_nombres'><?php echo $ll; ?> <a href=javascript:click_lista('<?php echo $m['Base']['id']; ?>')><?php echo utf8_encode($m['Base']['nombre']) ?></a></div>" );</script>";
		<?php
		}
		 

		if(empty($territorios[$m['Base']['id_distribuidor']])){
		$territorios[$m['Base']['id_distribuidor']]="[";
		$con_ter[$m['Base']['id_distribuidor']]=0;
		}
		
		$con_ter[$m['Base']['id_distribuidor']]++;
		$territorios[$m['Base']['id_distribuidor']].="{lat:".$m['Base']['lat'].",lng:".$m['Base']['lng']."},";
		$puntos[$m['Base']['id_distribuidor']][]=array(0=>$m['Base']['lat'],1=>$m['Base']['lng']);

		if($l==$limite){
			echo '<script>
			$("#div_territorios").html("Numero de pines limitado a '.number_format($limite).'");
			</script>';
		}
	
		if($l<$limite){
		//if($this->params['form']['territorios']=='false' || empty($this->params['form']['territorios'])){
		?>
		<script>
			datos={
				id:<?php echo $m['Base']['id'] ?>,
				nombre:'<?php echo utf8_encode($m['Base']['nombre']) ?>',
				estado:'<?php echo utf8_encode($m['Base']['estado_n']) ?>',
				municipio:'<?php echo utf8_encode($m['Base']['municipio_n']) ?>',
				tipo:'<?php echo $m['Base']['tipo'] ?>',
				sector:'<?php echo utf8_encode($m['Base']['sector']) ?>',
				turno:'<?php echo $escuelas['turnos'][$m['Base']['turno']] ?>',
				cct:'<?php echo $m['Base']['cct'] ?>',
				alumnos:'<?php echo $m['Base']['alumnos'] ?>',
				distribuidor:'<?php echo $distribuidores[$m['Base']['id_distribuidor']] ?>',
				id_distribuidor:'<?php echo $m['Base']['id_distribuidor'] ?>',
				lat:<?php echo $m['Base']['lat'] ?>,
				lng:<?php echo $m['Base']['lng'] ?>,
				grupos:<?php echo $m['Base']['grupos'] ?>,
				nombre_contacto:'<?php echo $m['Base']['nombre_contacto'] ?>',
				telefono_contacto:'<?php echo $m['Base']['telefono_contacto'] ?>',
				correo_contacto:'<?php echo $m['Base']['correo_contacto'] ?>',
				notas:'<?php echo $m['Base']['notas'] ?>',
				estatus:<?php echo $m['Base']['estatus'] ?>,
				verificada:<?php echo $m['Base']['verificada'] ?>,
			}
		pon_marca(datos,<?php echo $mover; ?>);
		</script>
		<?php
		}
		//}
		
	}

?>
	<script>
	//$("#map_escuelas").html("<?php echo $nombres; ?>")
	$("#l_resultados").html("Se encontraron <?php echo number_format(count($bases)); ?> escuelas, y <?php echo number_format($numero_alumnos); ?> alumnos");
	</script>
<?php

	echo "<script>deleteAllShape()</script>";

	if($this->params['form']['territorios']=='true'){
		$ter="";
		$ll=0;
		$ter.="<br><br><h2>Territorios</h2><div class='row justify-content-center'>";
   

	foreach($territorios as $ll=>$m){
		if($ll>0){
		$color=randomColor();
	

		$ter.="<div class='col-lg-4 col-md-4' >";
		$ter.= "<div class='m-1' style='padding-left:5px;cursor:hand;cursor:pointer;border: 1px solid #000;' onclick='centra_territorio(".$ll.")'>";
		$ter.="<div style='right:20px;position:absolute;width:25px;height:24px;background:".$color."'>&nbsp;</div>";
	
		//$ter.=" <strong>".$ll."</strong>: (".$con_ter[$ll].")".$distribuidores[$ll];
		$ter.=" <strong>".$con_ter[$ll]."</strong>: ".$distribuidores[$ll];
		$ter.="</div>";
		$ter.="</div>";

		//$coords=dame_poly($puntos[$ll]);
		$coords=$territorios[$ll]."]";
		$nom="Vacante";
	
		?>
		<script>
		var num={label:'<?php echo $ll; ?>',title:'<?php echo $nom; ?>'}
		setTimeout('pon_territorio(<?php echo $ll; ?>,<?php echo $coords; ?>,"<?php echo $color; ?>",false,{label:"<?php echo $ll; ?>",title:"<?php echo $nom; ?>"})',1000)</script>
		<?php
		}
	}
	$ter.="</div><br>";

	?>
	<script>
		$("#div_territorios").html("<?php echo $ter ?>");
	</script>
	<?php

}

	

?>

