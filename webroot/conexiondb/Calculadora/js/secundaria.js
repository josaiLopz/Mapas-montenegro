function calcularDescuentos() {
    var precioBase = parseFloat(document.getElementById("precioBase").value) || 0;
    var unidadesPrecioRegular = parseFloat(document.getElementById("campo2").value) || 0;
    var meta = parseFloat(document.getElementById("metaFirmada").value) || 0;
    var pagoPuntualChecked = document.getElementById("pagoPuntual").checked;
    // var meta = parseFloat(document.getElementById("meta").value) || 0;
    var prontoPagoChecked = document.getElementById("prontoPago").checked;
    var noDevolucionChecked = document.getElementById("noDevolucion").checked;
    var unidadesAnticipadas = parseFloat(document.getElementById("campo1").value) || 0;

    document.getElementById("unidadesPrecioRegular").textContent = unidadesPrecioRegular.toLocaleString();
    document.getElementById("sumaUnidades").textContent = (unidadesPrecioRegular + unidadesAnticipadas).toLocaleString();
    // Calcular descuento base
    var porcentajeDescuentoBase = 41;
    var descuentoBase = precioBase * (porcentajeDescuentoBase / 100);
    var resultadoBase = precioBase - descuentoBase;
    document.getElementById("descuentoBase").textContent = '$' + descuentoBase.toFixed(3);
    document.getElementById("resultadoBase").textContent = '$' + resultadoBase.toFixed(2);
    document.getElementById("multi0").textContent = '';

    // // Calcular el porcentaje de crecimiento respecto a la meta
    // var crecimiento = ((unidadesPrecioRegular - meta) / meta) * 100;

    // // Calcular el porcentaje de descuento de acuerdo al crecimiento
    // var porcentajeDescuentoMeta = 0;
    // if (crecimiento >= 5 && crecimiento <= 10) {
    //     porcentajeDescuentoMeta = 2;
    // } else if (crecimiento > 10 && crecimiento <= 15) {
    //     porcentajeDescuentoMeta = 4;
    // } else if (crecimiento > 15) {
    //     porcentajeDescuentoMeta = 6;
    // }

    // var resultadoCrecimientoMeta =(unidadesPrecioRegular * porcentajeDescuentoMeta / 100);

    // Calcular descuento pago puntual
    var porcentajeDescuentoPagoPuntual = pagoPuntualChecked ? 2 : 0;
    var descuentoPagoPuntual = precioBase * (porcentajeDescuentoPagoPuntual / 100);
    var resultadoPagoPuntual = resultadoBase - descuentoPagoPuntual;
    var multiplicarPagoPuntual = descuentoPagoPuntual * unidadesPrecioRegular;
    document.getElementById("porcentajePagoPuntual").textContent = porcentajeDescuentoPagoPuntual + "%";
    document.getElementById("descuentoPagoPuntual").textContent = '$' + descuentoPagoPuntual.toFixed(3);
    document.getElementById("resultadoPagoPuntual").textContent = '$' + resultadoPagoPuntual.toFixed(2);
    document.getElementById("multi1").textContent = '$' + multiplicarPagoPuntual.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // var resultadodescuentoMeta = (resultadoPagoPuntual - (resultadoBase * porcentajeDescuentoMeta / 100))

    // Calcular descuento pronto pago
    var porcentajeDescuentoProntoPago = prontoPagoChecked ? 3 : 0;
    var descuentoProntoPago = precioBase * (porcentajeDescuentoProntoPago / 100);
    var resultadoProntoPago = resultadoPagoPuntual - descuentoProntoPago;
    var multiplicarProntoPago = descuentoProntoPago * unidadesPrecioRegular;
    document.getElementById("porcentajeProntoPago").textContent = porcentajeDescuentoProntoPago + "%";
    document.getElementById("descuentoProntoPago").textContent = '$' + descuentoProntoPago.toFixed(3);
    document.getElementById("resultadoProntoPago").textContent = '$' + resultadoProntoPago.toFixed(2);
    document.getElementById("multi3").textContent = '$' + multiplicarProntoPago.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    //Calcular descuento no devolución
    var porcentajeDescuentoNoDevolucion = noDevolucionChecked ? 2 : 0;
    var descuentoNoDevolucion = precioBase * (porcentajeDescuentoNoDevolucion / 100);
    var resultadoNoDevolucion = resultadoProntoPago - descuentoNoDevolucion;
    var multiplicarNoDevolucion = descuentoNoDevolucion * unidadesPrecioRegular;


        document.getElementById("porcentajeNoDevolucion").textContent = porcentajeDescuentoNoDevolucion + "%";
        document.getElementById("descuentoNoDevolucion").textContent = '$' + descuentoNoDevolucion.toFixed(3);
        document.getElementById("resultadoNoDevolucion").textContent = '$' + resultadoNoDevolucion.toFixed(2);
        document.getElementById("multi5").textContent = '$' + multiplicarNoDevolucion.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
   
    // Actualizar el porcentaje de crecimiento de la meta en la tabla
    // document.getElementById("porcentajeMeta").textContent = porcentajeDescuentoMeta.toFixed(2) + "%";
    // document.getElementById("descuentoMeta").textContent = '$' + (unidadesPrecioRegular * porcentajeDescuentoMeta / 100).toFixed(2);
    // document.getElementById("resultadoMeta").textContent = '$' + resultadodescuentoMeta.toFixed(2);
    // document.getElementById("multi2").textContent = '$' + ((resultadoBase - (resultadoBase * porcentajeDescuentoMeta / 100)) * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Calcular el total de descuentos
    var suma = descuentoPagoPuntual * unidadesPrecioRegular + descuentoProntoPago * unidadesPrecioRegular + multiplicarNoDevolucion;
    document.getElementById("total").textContent = '$' + suma.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById("meta_porcentaje").textContent = crecimiento.toFixed(2) + "%";
}
// Variable para almacenar el temporizador de inactividad
let inactivityTimer;

// Función para manejar la visibilidad del navbar
function handleNavbarVisibility() {
const navbar = document.querySelector('.navbar');
if (inactivityTimer) clearTimeout(inactivityTimer);
navbar.classList.remove('hidden');
inactivityTimer = setTimeout(() => navbar.classList.add('hidden'), 1000);
}

// Configurar eventos para reiniciar el temporizador en interacción del usuario
window.onload = handleNavbarVisibility;
['mousemove', 'keydown', 'scroll', 'click'].forEach(event =>
document.addEventListener(event, handleNavbarVisibility)
);