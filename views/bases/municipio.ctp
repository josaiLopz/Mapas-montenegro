<?php
	if(empty($this->params['form']['iden']))
		$iden="";
	else	
		$iden=$this->params['form']['iden'];
	foreach($municipios as $l=>$m){
		echo "<script>";
		echo "mete_municipio".$iden."(".$l.",'".$m."')";
		echo "</script>";
		
	}

?>

