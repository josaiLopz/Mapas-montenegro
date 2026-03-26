<?php ?>



<script language='javascript' type='text/javascript'>







function texto(a,id){
document.getElementById(id).innerHTML=a;

}

function oculta(a){

 var x=$("#"+a);
 x.slideUp(1);
}


function cambio(a){

 var x=$("#"+a);
  if (x.is(":hidden")) {
        x.slideDown(1000);
      }
 else {
        x.slideUp(1000);
      }

}


</script>

<?php
	echo "<h1>Log de ".$nom_archivo."</h1>";
	
	echo $html->link('Regresar',array('action'=>'index'),array('class'=>'sitios_color'));
		echo "<center>";
		if(!empty($usu)){
			echo "<table class='tabla_intranet'>";
			echo "<th colspan=5>Buscar Descargas por Usuario</th>";
			$i=0;

			echo "<tr>";
			foreach($usu as $us){
				if($i%5==0){

				if($i%2==0)
					$clss='LcellContentPar';
				else
					$clss='LcellContentInPar';

					echo "</tr><tr class='".$clss."'>";

				}
				echo "<td>".$html->link($us['Usuario']['usern'],array('action'=>'logs','nombre'=>$us['Usuario']['usern']))."</td>";
			$i++;
			}
			echo "</tr>";
			echo "</table>";
		}

	//////////////////////

	echo "<br/><br/>";
	echo $form->create(array('controller'=>'usuarios','action'=>'logs'));
	echo "<table><tr><td>";
	echo $form->input('buscar',array('type'=>'text','label'=>'Buscar Texto '));
	echo "</td><td>";
	echo $form->submit('Buscar',array('class'=>'boton'));
	echo "</td></tr></table>";
	echo $form->end();
/////////////////////////////
		echo "</center>";


		Configure::load('app/app');
		$dirs=Configure::read('log');

		if(!empty($dirs[$nom_archivo])){
			$vlineas = @file($dirs[$nom_archivo]);
	
		}
		else
			$vlineas = @file($dirs['usuarios']);
    

		$num=count($vlineas);
		$fechas=array();
		$actual="";
		$ban=0;

		if(!empty($buscar)){
			echo "<br/><br/>";
			echo "<h1>Movimientos de '<strong>".$buscar."</strong>'</h1>";
			for($i=$num-1;$i>=0;$i--){
				if(stripos($vlineas[$i],$buscar)>-1){
				if($i%2==0)
					$clss='LcellContentPar';
				else
					$clss='LcellContentInPar';

					echo "<div class='".$clss."'>";
					echo $vlineas[$i]."<br/>";
					echo "</div>";

				}
			}


		}

		if(!empty($usuario)){
			echo "<br/><br/>";
			echo "<h1>Movimientos del Usuario <strong>".$usuario."</strong></h1>";
			for($i=$num-1;$i>=0;$i--){
				if(stripos($vlineas[$i],"el usuario ".$usuario))
					echo $vlineas[$i]."<br/>";
			}


		}

		$ban=0;
		echo "<br/><br/><h1><label onclick=cambio('div_ultimos') class='cursor_mano' id='label_ultimo'></label></h1>";
		echo "<div id='div_ultimos'>";
		for($i=$num-1;$i>=0;$i--){
			$cad=explode(" ",$vlineas[$i]);
			if($ban==0 || $ban==$cad[0]){
				if($i%2==0)
					$clss='LcellContentPar';
				else
					$clss='LcellContentInPar';

				echo "<div class='".$clss."'>";
				echo $vlineas[$i]."<br/>";
				echo "</div>";
				$ban=$cad[0];
			}
			else
				$i=-1;
		}
		echo "<script language='javascript' type='text/javascript'>texto('Ultimos Movimientos: ".$ban."','label_ultimo');</script>";
		echo "</div>";

		echo "<br/><br/><h1><label onclick=cambio('div_fechas') class='cursor_mano'>Movimientos Ordenados Por Fecha</label></h1>";
		echo "<div id='div_fechas'>";
		echo "<script language='javascript' type='text/javascript'>oculta('div_fechas')</script>";
		echo "<div>";




		$ban=0;
		for($i=$num-1;$i>=0;$i--){
			$cad=explode(" ",$vlineas[$i]);

			if($actual!=$cad[0]){
				echo "</div>";
				echo "<br/><h2><label onclick=cambio('".$cad[0]."') class='cursor_mano'>".$cad[0]."</label></h2>";
				echo "<div id='".$cad[0]."'>";
				echo "<script language='javascript' type='text/javascript'>oculta('".$cad[0]."')</script>";
			
				if($ban==0){
					if(!isset($fechas[$cad[0]]))
						$fechas[$cad[0]]=array();
					$ban=$cad[0];
				}
				
			}	
			
			if(isset($fechas[$cad[0]]))
				$fechas[$cad[0]][]=$vlineas[$i];

				if($i%2==0)
					$clss='LcellContentPar';
				else
					$clss='LcellContentInPar';

				echo "<div class='".$clss."'>";
				echo $vlineas[$i]."<br/>";
				echo "</div>";
			$actual=$cad[0];
		}
		echo "</div>";
		echo "</div>";

?>
