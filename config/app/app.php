<?php


$config['log']['usuarios'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "log.txt";

$config['dias']= array(0=>'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');

$config['escuelas']['tipos']= array(0=>'Cualquiera','PREESCOLAR'=>'Preescolar','PRIMARIA'=>'Primaria','SECUNDARIA'=>'Secundaria');
$config['escuelas']['sectores']= array(0=>'Cualquiera','blico'=>'Público','PRIVADO'=>'Privado');
$config['escuelas']['turnos']= array(0=>'Cualquiera',1=>'MATUTINO',2=>'VESPERTINO',3=>'NOCTURNO',4=>'DISCONTINUO',5=>'CONTINUO');
$config['escuelas']['municipios']= array(0=>'Cualquiera');
$config['escuelas']['estatus']= array(0=>'Cualquiera',1=>'No atendida',2=>'Escuela en promoción',3=>'Venta confirmada',4=>'Prohibición');
$config['escuelas']['verificada']= array(0=>'Cualquiera',1=>'No',2=>'Si');

$config['estados']=array();
$config['estados'][0]='Cualquiera';
$config['estados'][1]='AGUASCALIENTES';
$config['estados'][2]='BAJA CALIFORNIA';
$config['estados'][3]='BAJA CALIFORNIA SUR';
$config['estados'][4]='CAMPECHE';
$config['estados'][5]='COAHUILA DE ZARAGOZA';
$config['estados'][6]='COLIMA';
$config['estados'][7]='CHIAPAS';
$config['estados'][8]='CHIHUAHUA';
$config['estados'][9]='CIUDAD DE MÉXICO';
$config['estados'][10]='DURANGO';
$config['estados'][11]='GUANAJUATO';
$config['estados'][12]='GUERRERO';
$config['estados'][13]='HIDALGO';
$config['estados'][14]='JALISCO';
$config['estados'][15]='MÉXICO';
$config['estados'][16]='MICHOACÁN DE OCAMPO';
$config['estados'][17]='MORELOS';
$config['estados'][18]='NAYARIT';
$config['estados'][19]='NUEVO LEÓN';
$config['estados'][20]='OAXACA';
$config['estados'][21]='PUEBLA';
$config['estados'][22]='QUERÉTARO';
$config['estados'][23]='QUINTANA ROO';
$config['estados'][24]='SAN LUIS POTOSÍ';
$config['estados'][25]='SINALOA';
$config['estados'][26]='SONORA';
$config['estados'][27]='TABASCO';
$config['estados'][28]='TAMAULIPAS';
$config['estados'][29]='TLAXCALA';
$config['estados'][30]='VERACRUZ DE IGNACIO DE LA LLAVE';
$config['estados'][31]='YUCATÁN';
$config['estados'][32]='ZACATECAS';


?>
