<div>
  <?php

    echo $form->create('Escuela',array('action'=>'index','onsubmit'=>'return enviar_datos_escuela()'));
    echo $form->input('id');
    echo $form->input('nombre',array('type'=>'hidden'));
    echo $form->input('lat',array('type'=>'hidden'));
    echo $form->input('lng',array('type'=>'hidden'));

    echo $form->input('cct',array('label'=>'CCT'));
		echo "<br/>";
    echo $form->input('alumnos',array('label'=>'#De Alumnos'));
		echo "<br/>";
      echo $form->input('id_distribuidor',array('label'=>'Distribuidor','type'=>'select','options'=>$distribuidores));
		echo "<br/>";
  
    echo "<strong>Turnos</strong>:";
    echo $form->input('matutino',array('label'=>'Matutino','type'=>'checkbox'));
	  echo $form->input('vespertino',array('label'=>'Vespertino','type'=>'checkbox'));
	

    echo "<center>";
    echo $form->submit('Guardar',array( 'class'=>"btn btn-primary py-3 px-4"));
    echo "</center>";
  ?>
</div>

<div style='visibility:hidden'>
<script>
function enviar_datos_escuela(){

  mat=$('#EscuelaMatutino').is(':checked')
  if(mat)
    mat=1
  else
    mat=0

  ves=$('#EscuelaVespertino').is(':checked')
  if(ves)
    ves=1
  else
    ves=0

  datos={
        id:'<?php echo $this->data['Escuela']['id']; ?>',
        id_maps:'<?php echo $id_google; ?>',
        cct:$('#EscuelaCct').val(),
        alumnos:$('#EscuelaAlumnos').val(),
        matutino:mat,
        vespertino:ves ,
        id_distribuidor:$('#EscuelaIdDistribuidor').val(),
        nombre:$('#EscuelaNombre').val(),
        lat:$('#EscuelaLat').val(),
        lng:$('#EscuelaLng').val()
      }
  cierra_pop(datos);
  return false;
}
</script>
</div>