<?php

if(!empty($datos)){
foreach($datos['Escuela'] as $l=>$a){
  if($l=='id_distribuidor'){
    if(empty($a))
     $nom="Vacante";
    else
     $nom=$datos['Usuario']['nombre']." ".$datos['Usuario']['apellido_p']." ".$datos['Usuario']['apellido_m'];
   echo $l.":".$nom."@&@";
  }

else if($l=='matutino'){
  if(!empty($a))
      echo $l.":Matutino@&@";
  else
      echo $l.":@&@";
}
else if($l=='vespertino'){
  if(!empty($a))
       echo $l.":Vespertino@&@";
 else
       echo $l.":@&@";
}

 else
  echo $l.":".$a."@&@";

/*

  if(empty($a))
    echo '<script> $("#iw-turnos-row").slideUp(1)</script>';
  
  else
  echo '<script> $("#iw-turnos-row").slideDown(1)</script>';
*/
}
}
?>
