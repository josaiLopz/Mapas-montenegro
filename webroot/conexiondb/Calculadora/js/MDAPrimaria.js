
function calcularDescuentos() {
    // Obtener los valores de los campos de entrada
    var precioBase = parseFloat(document.getElementById("precioBase").value) || 0;
    var unidadesAnticipadas = parseFloat(document.getElementById("campo1").value) || 0;
    var unidadesPrecioRegular = parseFloat(document.getElementById("campo2").value) || 0;
    var unidadesPrecioEspecial = parseFloat(document.getElementById("campo3").value) || 0;
    var unidadesPrecioPromocion = parseFloat(document.getElementById("campo4").value) || 0;
    var unidadesRAP = parseFloat(document.getElementById("campo5").value) || 0;
    var unidadesLudosapiens = parseFloat(document.getElementById("campo6").value) || 0;
    var metaFirmada = parseFloat(document.getElementById("metaFirmada").value) || 0;
    var descuentoSelect = parseFloat(document.getElementById("descuentoSelect").value) || 0;
    var pagoPuntualChecked = document.getElementById("pagoPuntual").checked;
    var descuentoExtraChecked = document.getElementById("descuentoExtra").checked;
    var ventaAnoPasado = parseFloat(document.getElementById("ventaAnoPasado").value) || 0;
    var alumnado = parseFloat(document.getElementById("alumnado").value) || 0;
    var resultadoVenta = parseFloat(document.getElementById("resultadoVenta").textContent) || 1; // Asegúrate de que el valor sea al menos 1 para evitar división por 0
    var metaPlus = parseFloat(document.getElementById("metaPlus").value) || 0;
    var antiguedad = parseFloat(document.getElementById("antiguedad").value) || 0;
    var noDevolucion = document.getElementById("devolucion").checked;

    document.getElementById("unidadesPrecioRegular").textContent = unidadesPrecioRegular;

    // Calcular la suma total de unidades
    var suma = unidadesAnticipadas + unidadesPrecioRegular + unidadesPrecioEspecial + unidadesPrecioPromocion;
    var sumameta = unidadesAnticipadas + unidadesPrecioRegular + unidadesPrecioEspecial;
    document.getElementById("resultadoVenta").textContent = suma.toLocaleString();

    // Calcular descuento base en contrato
    // var descuentoBase = precioBase * descuentoSelect;
    // var porcentajeDescuentoBase = descuentoSelect * 100;

     var descuentoSelect = 0; 
     if (sumameta >= 15001 && sumameta <= 25000) {
         descuentoSelect = 0.33; 
     } else if (sumameta >= 25001 && sumameta <= 40000) {
         descuentoSelect = 0.37; 
     } else if (sumameta >= 40001 && sumameta <= 60000) {
         descuentoSelect = 0.40; 
     } else if (sumameta >= 60001) {
         descuentoSelect = 0.46; 
     } else {
         descuentoSelect = 0;
     }
     var descuentoBase = precioBase * descuentoSelect;
     var porcentajeDescuentoBase = descuentoSelect * 100;
     var resultadoBase = precioBase - descuentoBase; 
    // Calcular el resultado después del descuento base
    var resultadoBase = precioBase - descuentoBase;
    document.getElementById("descuentoSelect").textContent = porcentajeDescuentoBase.toFixed(2) + '%';

    // Calcular el descuento adicional por pago puntual
    var descuentoPagoPuntual = 0;
    var porcentajeDescuentoPagoPuntual = 0;

    if (pagoPuntualChecked) {
        descuentoPagoPuntual = precioBase * 0.01;
        porcentajeDescuentoPagoPuntual = 1;
    }

    // Calcular el resultado después del descuento adicional por pago puntual
    var resultadoPagoPuntual = resultadoBase - descuentoPagoPuntual;

    // Calcular descuento adicional por crecimiento de ventas
    var descuentoCrecimientoVentas = 0;
    var porcentajeDescuentoCrecimientoVentas = 0;
    var crecimientoVentas = ((suma / metaFirmada) - 1) * 100;

    document.getElementById("resultadoPorcentaje").textContent = crecimientoVentas.toFixed(2) + '%';

    if (metaFirmada >= 26 && metaFirmada <= 8000) {
        if (crecimientoVentas >= 0 && crecimientoVentas <= 10) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        } else if (crecimientoVentas >= 10.01) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        }
    } else if (metaFirmada >= 8001 && metaFirmada <= 15000) {
        if (crecimientoVentas >= 5 && crecimientoVentas <= 10) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        } else if (crecimientoVentas >= 10.01 && crecimientoVentas <= 15) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        } else if (crecimientoVentas >= 15.01) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        }
    } else if (metaFirmada >= 15001) {
        if (crecimientoVentas >= 5 && crecimientoVentas <= 10) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 1;
        } else if (crecimientoVentas >= 10.01 && crecimientoVentas <= 15) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 2;
        } else if (crecimientoVentas >= 15.01 && crecimientoVentas <= 20) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        } else if (crecimientoVentas >= 20.01) {
            descuentoCrecimientoVentas = precioBase * 0.03;
            porcentajeDescuentoCrecimientoVentas = 3;
        }
    }
    var resultadoCrecimientoVentas = resultadoPagoPuntual - descuentoCrecimientoVentas;
    var descuentoExtra = 0;
    var porcentajeDescuentoExtra = 0;
    if (descuentoExtraChecked) {
        descuentoExtra = precioBase * 0.02;
        porcentajeDescuentoExtra = 2;
    }
    var resultadoDescuentoExtra = resultadoCrecimientoVentas - descuentoExtra;

    var devolucion = 0;
    var porcentajeNoDevolucion = 0;

    if (noDevolucion && suma >= metaFirmada) {
        devolucion = precioBase * 0.01;
        porcentajeNoDevolucion = 1;
    }
    // var resultadoNoDevolucion = resultadoDescuentoExtra - devolucion;


    if (!isNaN(alumnado) && alumnado > 0) {
        // Calcular el porcentaje de Alumnado respecto a la Venta Total
        var camposResta = unidadesAnticipadas + unidadesPrecioRegular + unidadesPrecioEspecial + unidadesRAP + unidadesLudosapiens;
        var porcentajeAlumnado = (camposResta / alumnado) * 100;
        document.getElementById("resultadoCobertura").textContent = porcentajeAlumnado.toFixed(2) + '%';

        var porcentajeDescuento = 0;
        if (porcentajeAlumnado >= 0 && porcentajeAlumnado <= 9.99) {
            // No se aplica descuento
            porcentajeDescuento = 0;
        } else if (porcentajeAlumnado >= 10 && porcentajeAlumnado <= 11.99) {
            porcentajeDescuento = 0;
        } else if (porcentajeAlumnado >= 12 && porcentajeAlumnado <= 14.99) {
            porcentajeDescuento = 0;
        } else if (porcentajeAlumnado >= 15 && porcentajeAlumnado <= 20.99) {
            porcentajeDescuento = 0;
        } else if (porcentajeAlumnado >= 21 && porcentajeAlumnado <= 29.99) {
            porcentajeDescuento = 0;
        } else {
            porcentajeDescuento = 0;
        }
        var resta = (resultadoCrecimientoVentas * (porcentajeDescuento / 100));
        var resultadoConDescuento = resultadoDescuentoExtra - resta;
    }
    // Calcular descuentos según la antigüedad
    if (antiguedad >= 0 && antiguedad < 4.99) {
        descuentoAntiguedad = resultadoCrecimientoVentas * 0.000; // 0.5% de descuento
        porcentajeDescuentoAntiguedad = 0;
    } else if (antiguedad >= 5 && antiguedad < 9.99) {
        descuentoAntiguedad = resultadoCrecimientoVentas * 0.000; // 1.0% de descuento
        porcentajeDescuentoAntiguedad = 0.005;
    } else if (antiguedad >= 10 && antiguedad < 14.99) {
        descuentoAntiguedad = resultadoCrecimientoVentas * 0.00; // 1.5% de descuento
        porcentajeDescuentoAntiguedad = 1.00;
    } else if (antiguedad >= 15 && antiguedad <= 100) {
        descuentoAntiguedad = resultadoCrecimientoVentas * 0.00; // 1.5% de descuento
        porcentajeDescuentoAntiguedad = 1.5;
    }
    var resultadoDescuentoAntiguedad = resultadoConDescuento - descuentoAntiguedad;
    var descuentoIndividual = descuentoBase * unidadesPrecioRegular;

    //resta de no devolucio y antiguedad
    var resultadoNoDevolucion = resultadoDescuentoAntiguedad - devolucion;

    var tablaDescuentos = document.getElementById("tablaDescuentos");
    tablaDescuentos.innerHTML = ""; 

    tablaDescuentos.innerHTML += "<tr><td>DESCUENTO BASE EN CONTRATO</td><td>" + porcentajeDescuentoBase + "%</td><td>$" + descuentoBase.toFixed(3) + "</td><td>$" + resultadoBase.toFixed(2) + "</td><td></td></tr>";
    tablaDescuentos.innerHTML += "<tr><td>PAGO PUNTUAL</td><td>" + porcentajeDescuentoPagoPuntual + "%</td><td>$" + descuentoPagoPuntual.toFixed(3) + "</td><td>$" + resultadoPagoPuntual.toFixed(2) + "</td><td>$" + (descuentoPagoPuntual * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    
    tablaDescuentos.innerHTML += "<tr><td>CRECIMIENTO DE META</td><td>" + porcentajeDescuentoCrecimientoVentas + "%</td><td>$" + descuentoCrecimientoVentas.toFixed(3) + "</td><td>$" + resultadoCrecimientoVentas.toFixed(2) + "</td><td>$" + (descuentoCrecimientoVentas * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    
    tablaDescuentos.innerHTML += "<tr><td>PRONTO PAGO</td><td>" + porcentajeDescuentoExtra + "%</td><td>$" + descuentoExtra.toFixed(3) + "</td><td>$" + resultadoDescuentoExtra.toFixed(2) + "</td><td>$" + (descuentoExtra * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    //solo si es igual o mayor a meta firmada
    // if (sumameta >= metaFirmada) {
    //     tablaDescuentos.innerHTML += "<tr><td>COBERTURA</td><td>" + porcentajeDescuento + "%</td><td>$" + (resultadoCrecimientoVentas * (porcentajeDescuento / 100)).toFixed(3) + "</td><td>$" + resultadoConDescuento.toFixed(2) + "</td><td>$" + ((resultadoCrecimientoVentas * (porcentajeDescuento / 100)) * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    // }
    
    // tablaDescuentos.innerHTML += "<tr><td>DESCUENTO POR ANTIGUEDAD</td><td>" + porcentajeDescuentoAntiguedad + "%</td><td>$" + descuentoAntiguedad.toFixed(3) + "</td><td>$" + resultadoDescuentoAntiguedad.toFixed(2) + "</td><td>$" + (descuentoAntiguedad * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    if (sumameta >= metaFirmada) {
    tablaDescuentos.innerHTML += "<tr><td>NO DEVOLUCIÓN</td><td>" + porcentajeNoDevolucion + "%</td><td>$" + devolucion.toFixed(3) + "</td><td>$" + resultadoNoDevolucion.toFixed(2) + "</td><td>$" + (devolucion * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    }
    // if (sumameta >= metaPlus) {
    //     var descuentoMetaPlus = resultadoCrecimientoVentas * 0.00;
    //     var resultadoConDescuentoMetaPlus = resultadoDescuentoAntiguedad - descuentoMetaPlus;
    //     tablaDescuentos.innerHTML += "<tr><td>META PLUS</td><td>2%</td><td>" + descuentoMetaPlus.toFixed(2) + "</td><td>$" + resultadoConDescuentoMetaPlus.toFixed(2) + "</td><td>$" + (descuentoMetaPlus * unidadesPrecioRegular).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";
    // }
    tablaDescuentos.innerHTML += "<tr><td>DESCUENTO TOTAL</td><td>-</td><td>-</td><td>-</td><td>$" +
        ((descuentoPagoPuntual * unidadesPrecioRegular || 0) + 
            (descuentoCrecimientoVentas * unidadesPrecioRegular || 0) + 
            (descuentoExtra * unidadesPrecioRegular || 0) +
            // ((resultadoCrecimientoVentas * (porcentajeDescuento / 100)) * unidadesPrecioRegular || 0) +
            // (descuentoAntiguedad * unidadesPrecioRegular || 0) +
            // (descuentoMetaPlus * unidadesPrecioRegular || 0) +
            (devolucion * unidadesPrecioRegular || 0)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td></tr>";

}
document.getElementById("precioBase").setCustomValidity("mensaje de prueba");

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