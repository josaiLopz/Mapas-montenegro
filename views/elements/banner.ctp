<div class='container-fluid bg-dark text-white' style=''>

<div class='text-right'>
    <?php

        $dias=array(1=>'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $mes=array(1=>'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        echo $dias[date("N")]." ".date('d'). " de ".$mes[date('n')]. " , ".date('Y');
    ?>
    </div>


<div class='text-left'>
    <?php

$ses=$_SESSION["Alumno"]["estatus_pago"];
if($_SESSION["Usuario"]["rol"]==3){
if(strlen($ses)>1){
      echo "<a href='/Pagos'><span style='margin-top:-5px;margin-right:10px;float:left;width:20px;background:#f00;border-radius: 50%;'>&nbsp;</span></a>";
}
}
    
         echo "Usuario : " .$_SESSION['Usuario']['usernom']." ".$_SESSION['Usuario']['userfirst'];
        
    ?>
</div>

</div>