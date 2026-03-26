// Función para calcular descuentos y actualizar la tabla
function calcularDescuentos() {
    var precioBase = parseFloat(document.getElementById("precioBase").value) || 0;
    var unidadesAnticipadas = parseFloat(document.getElementById("campo1").value) || 0;
    var unidadesPrecioRegular = parseFloat(document.getElementById("campo2").value) || 0;
    var pagoPuntualChecked = document.getElementById("pagoPuntual").checked;
    var prontoPagoChecked = document.getElementById("prontoPago").checked;
    var meta = parseFloat(document.getElementById("metaFirmada").value) || 0;
    var UnidadesEdicion2023 = parseFloat(document.getElementById("campo4").value) || 0;
    // var meta = parseFloat(document.getElementById("meta").value) || 0;
    // var metaPlus = parseFloat(document.getElementById("metaPlus").value) || 0;
    var noDevolucion = document.getElementById("devolucion").checked;

    document.getElementById("unidadesPrecioRegular1").textContent = unidadesPrecioRegular;

    // Calcular descuentos
    var porcentajeDescuentoBase = 36;
    var descuentoBase = precioBase * (porcentajeDescuentoBase / 100);
    var resultadoBase = precioBase - descuentoBase;

    var porcentajeDescuentoPagoPuntual = pagoPuntualChecked ? 2 : 0;
    var descuentoPagoPuntual = precioBase * (porcentajeDescuentoPagoPuntual / 100);
    var resultadoPagoPuntual = resultadoBase - descuentoPagoPuntual;

    //DESCUENTO META
    // var sumaUnidades = unidadesAnticipadas + unidadesPrecioRegular;
    // var porcentajeDescuentoMeta = sumaUnidades >= meta ? 6 : 0;
    // var descuentoMeta = resultadoPagoPuntual * (porcentajeDescuentoMeta / 100);
    // var resultadoMeta = resultadoPagoPuntual - descuentoMeta;

    //PRONTO PAGO
    var porcentajeDescuentoProntoPago = prontoPagoChecked ? 3 : 0;
    var descuentoProntoPago = resultadoPagoPuntual * (porcentajeDescuentoProntoPago / 100);
    // var resultadoProntoPago = resultadoMeta - descuentoProntoPago;
    var resultadoProntoPago = resultadoPagoPuntual - descuentoProntoPago;


    // var porcentajeDescuentoMetaPlus = sumaUnidades >= metaPlus ? 2 : 0;
    // var descuentoMetaPlus = resultadoPagoPuntual * (porcentajeDescuentoMetaPlus / 100);
    // var resultadoMetaPlus = resultadoProntoPago - descuentoMetaPlus;

    var suma = unidadesAnticipadas + unidadesPrecioRegular + UnidadesEdicion2023;
    document.getElementById("total1").textContent = suma.toLocaleString();

    
    var porcentajeNoDevolucion = noDevolucion  && suma >= meta ? 2 : 0;
    var descuentoNoDevolucion = resultadoPagoPuntual * (porcentajeNoDevolucion / 100);
    var resultadoNoDevolucion = resultadoProntoPago - descuentoNoDevolucion;

    var totalDescuento =
        (descuentoPagoPuntual * unidadesPrecioRegular) +
        // (descuentoMeta * unidadesPrecioRegular) +
        descuentoProntoPago * unidadesPrecioRegular +
        descuentoNoDevolucion * unidadesPrecioRegular;

    
    // Actualizar tabla
    document.getElementById("descuentoBase").textContent = '$' + descuentoBase.toFixed(3);
    document.getElementById("resultadoBase").textContent = '$' + resultadoBase.toFixed(2);
    document.getElementById("multi0").textContent = '';

    document.getElementById("porcentajePagoPuntual").textContent = porcentajeDescuentoPagoPuntual + "%";
    document.getElementById("descuentoPagoPuntual").textContent = '$' + descuentoPagoPuntual.toFixed(3);
    document.getElementById("resultadoPagoPuntual").textContent = '$' + resultadoPagoPuntual.toFixed(2);
    document.getElementById("multi1").textContent = '$' + (descuentoPagoPuntual * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // document.getElementById("porcentajeMeta").textContent = porcentajeDescuentoMeta + "%";
    // document.getElementById("descuentoMeta").textContent = '$' + descuentoMeta.toFixed(2);
    // document.getElementById("resultadoMeta").textContent = '$' + resultadoMeta.toFixed(2);
    // document.getElementById("multi2").textContent = '$' + (descuentoMeta * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    document.getElementById("porcentajeProntoPago").textContent = porcentajeDescuentoProntoPago + "%";
    document.getElementById("descuentoProntoPago").textContent = '$' + descuentoProntoPago.toFixed(3);
    document.getElementById("resultadoProntoPago").textContent = '$' + resultadoProntoPago.toFixed(2);
    document.getElementById("multi3").textContent = '$' + (descuentoProntoPago * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // document.getElementById("porcentajeMetaPlus").textContent = porcentajeDescuentoMetaPlus + "%";
    // document.getElementById("descuentoMetaPlus").textContent = '$' + descuentoMetaPlus.toFixed(2);
    // document.getElementById("resultadoMetaPlus").textContent = '$' + resultadoMetaPlus.toFixed(2);
    // document.getElementById("multi5").textContent = '$' + (descuentoMetaPlus * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (suma >= meta) {
        document.getElementById("contenido").style.display = 'table-row';  
        document.getElementById("porcentajeNoDevolucion").textContent = porcentajeNoDevolucion + "%";
        document.getElementById("descuentoNoDevolucion").textContent = '$' + descuentoNoDevolucion.toFixed(3);
        document.getElementById("resultadoNoDevolucion").textContent = '$' + resultadoNoDevolucion.toFixed(2);
        document.getElementById("multi6").textContent = '$' + (descuentoNoDevolucion * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }else{
        document.getElementById("contenido").style.display = 'none';
    }

    document.getElementById("total").textContent = '$' + totalDescuento.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })

    // Actualizar resultado de la venta total
    document.getElementById("resultadoVent").textContent = '$' + unidadesAnticipadas + unidadesPrecioRegular;
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