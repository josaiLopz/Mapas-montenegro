<?php 

echo "<div style='position:absolute;z-index:99;width:270px'>";
echo $html->image("logo.png",array('style'=>'width:100%'));
echo "</div>";
?>
  <div class="container-fluid p-0" style='height:80px'>
        <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-lg-5">

<?php
if(!empty($login)){
configure::load("app/configuracion"); 
$menu=configure::read("menu"); 

	if(empty($this->params['pass'][0]))
	$sel=2;
	else
		$sel=$this->params['pass'][0];

	if($sel=='home')
		$sel=2;


?>
  <!-- Navbar Start -->
	
  <!-- nombre d3e usuario loggueado -->

 <!-- hasta aqui -->

          <a href="index.html" class="navbar-brand ml-lg-3">
		  <?php /*   <h1 class="m-0 display-5 text-uppercase text-primary"><i class="fa fa-truck mr-2"></i>Faster</h1>	LOGOOO*/ ?>
            </a>
		
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav m-auto py-0">
					
					<?php
					$reg=array();
					$k=0;
					$fijo=-2;

					foreach($menu as $l=>$m){
						$x="/".$m[2]['controller']."/".$m[2]['action'];

						if($m[0]==$this->name){
							
							$k=$l;
						}
					
						if($x==$this->here){
							$fijo=$l;
					
						}
					}
				
					if($fijo>-1)
						$k=$fijo;
			
					foreach($menu as $l=>$m){
						$active="";
					
						if($k==$l){
							$active="active";
						}
					

						if(!empty($is_admin) || $m[0]=='Todos' || strpos($mis_permisos."|",$m[0])!==false){
								echo $html->link($m[1],$m[2],array('class'=>"nav-item nav-link ".$active,'escape'=>false));
					
						}	
			
					}
/*
					foreach($menu as $m){
						$active="";
						if($m['Seccion']['id']==$sel || $m['Seccion']['id']==$seccion['Seccion']['padre']){
							$reg=$m;
							$active="active";
						}
						
						if($m['Seccion']['tipo']=='pagina')
							echo $html->link($m['Seccion']['nombre'],$m['Seccion']['url'],array('class'=>"nav-item nav-link ".$active,'escape'=>false));
				
						else if($m['Seccion']['tipo']=='contenedor'){
							echo ' <div class="nav-item dropdown">
							<a href="#" class="nav-link dropdown-toggle '.$active.'" data-toggle="dropdown">'.$m['Seccion']['nombre'].'</a>
							<div class="dropdown-menu rounded-0 m-0">';
							foreach($m['Seccion'][$m['Seccion']['id']] as $mm){
								echo $html->link($mm['Seccion']['nombre'],array('controller'=>'Secciones','action'=>'contenido',$mm['Seccion']['id']),array('class'=>"dropdown-item",'escape'=>false));
				
							}
							echo '</div>
						</div>';
						}
						else
						echo $html->link($m['Seccion']['nombre'],array('controller'=>'Secciones','action'=>'contenido',$m['Seccion']['id']),array('class'=>"nav-item nav-link ".$active,'escape'=>false));
						
					}*/
					?>
					<?php /*
                    <a href="index.html" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="service.html" class="nav-item nav-link">Service</a>
                    <a href="price.html" class="nav-item nav-link">Price</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="blog.html" class="dropdown-item">Blog Grid</a>
                            <a href="single.html" class="dropdown-item">Blog Detail</a>
                        </div>
                    </div>
                    <a href="contact.html" class="nav-item nav-link active">Contact</a>
					*/ ?>
                </div>
              
            </div>
			<div class='text-right' style="margin-right:3%;">
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
			 <!-- Fechas -->
			<div class='text-right'>
    <?php

        $dias=array(1=>'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $mes=array(1=>'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        echo $dias[date("N")]." ".date('d'). " de ".$mes[date('n')]. " , ".date('Y');
    ?>
    </div>
	 <!-- Hasta aqui -->


    <!-- Navbar End -->



<?php /*

<div class='menu'>
<?php
	configure::load("app/configuracion"); 
	$menu=configure::read("menu"); 




	if(!empty($menu)){
		echo "<ul>";
		foreach($menu as $m){
			if(!empty($is_admin) || $m[0]=='Todos' || strpos($mis_permisos."|",$m[0])!==false){
				echo "<li>";
					echo $html->link($m[1],$m[2],array('escape'=>false));
				echo "</li>";
			}

		}
		echo "</ul>";

	}

?>

</div>
*/  }?>
        </nav>
    </div>


	   <!-- JavaScript Libraries -->
	
	   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/lib/easing/easing.min.js"></script>
    <script src="/js/lib/waypoints/waypoints.min.js"></script>
    <script src="/js/lib/counterup/counterup.min.js"></script>
    <script src="/js/lib/owlcarousel/owl.carousel.min.js"></script>
