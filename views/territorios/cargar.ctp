<?php

echo "<table border=1 width='100%' cellpadding='3px'>";
foreach($territorios as $ll=>$c){
  $lll=$ll;
  $lll++;

  $coords="[";
  echo "<tr>";
  if(empty($c['Territorio']['id_distribuidor']))
  $nom="Vacante";
  else
    $nom=$c['Usuario']['nombre']." ".$c['Usuario']['apellido_p']." ".$c['Usuario']['apellido_m'];
    echo "<td style='width:70px;font-size:10px;' align='center' ><a href=javascript:centra_territorio(".$c['Territorio']['id'].")>".$lll."</a></td>";
    echo "<td style='width:100px'><a href=javascript:centra_territorio(".$c['Territorio']['id'].") style='font-size:10px;'>".$nom."</a></td>";
    echo "<td style='cursor:pointer;width:30px;background:".$c['Territorio']['color']."' onclick='centra_territorio(".$c['Territorio']['id'].")'> </td>";

    echo "<input id='distribuidor_".$c['Territorio']['id']."' value='".$c['Territorio']['id_distribuidor']."' type='hidden'>";
    echo "<input id='color_".$c['Territorio']['id']."' value='".$c['Territorio']['color']."' type='hidden'>";
    echo "<input id='coords_".$c['Territorio']['id']."' value='".$c['Territorio']['coords']."' type='hidden'>";

    if(array_search("Territorios|edit",$c_permisos)!==false){
        echo "<td style='width:70px' align='center'>";
         if(array_search("Territorios|edit",$c_permisos)!==false)
          echo "<a href=javascript:editar_territorio(".$c['Territorio']['id'].")><img src='/img/iconos/modificar.png'></a>";
          if(array_search("Territorios|delete",$c_permisos)!==false)
          echo "<a href=javascript:eliminar_territorio(".$c['Territorio']['id'].")><img src='/img/iconos/eliminar.png'></a>";
        echo "</td>";
    }
  echo "</tr>";
  $x=explode(";",$c['Territorio']['coords']);
  foreach($x as $xx){
    $s=explode("/",$xx);
    if(!empty($s[0]))
    $coords.="{lat:".$s[0].",lng:".$s[1]."},";
  }
  $coords.="]";


if($ll==0){
  echo "<script>deleteAllShape()</script>";
}

  ?>
  <script>
  var num={label:'<?php echo $ll; ?>',title:'<?php echo $nom; ?>'}
  setTimeout('pon_territorio(<?php echo $c['Territorio']['id']; ?>,<?php echo $coords; ?>,"<?php echo $c['Territorio']['color']; ?>",false,{label:"<?php echo $lll; ?>",title:"<?php echo $nom; ?>"})',1000)</script>
  <?php
}

echo "<table>";

?>