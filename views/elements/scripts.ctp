<script>
function hizo_click(a){

 var x=$("#"+a);
  if (x.is(":hidden")) {
        x.slideDown(1000);
      }
 else {
        x.slideUp(1000);
      }
}



function oculta(a){
var x=$("#"+a);
 x.slideUp(1);

}


function muestra(a){
var x=$("#"+a);
x.slideDown(1);

}

function desaparece(a){
var x=$("#"+a);
x.fadeOut(1000);

}



function aparece(a){
var x=$("#"+a);
x.fadeIn(1000);

}


function muestra(a){
var x=$("#"+a);
x.slideDown(1);

}

function muestra_tiempo(a){
var x=$("#"+a);
x.slideDown(1000);

}


function oculta_tiempo(a){
var x=$("#"+a);
x.slideUp(1000);

}


function oculta_rapido(a){
var x=$("#"+a);
 x.slideUp(1);

}

</script>




