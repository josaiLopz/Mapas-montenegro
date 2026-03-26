<div class="migrar-section">
    <h3>Transferir Territorio entre Usuarios</h3>
    
    <?php echo $form->create('Base', array('action' => 'transferir_usuarios', 'id' => 'FormTransferirUsuarios')); ?>
    
    <div class="row">
        <div class="col-md-6">
            <label for="BaseUsuarioOrigen">👤 Usuario Origen:</label>
            <?php echo $form->select('usuario_origen', $usuariosOptions, null, array(
                'empty' => '-- Selecciona Usuario Origen --',
                'class' => 'form-control',
                'required' => true,
                'id' => 'BaseUsuarioOrigen'
            )); ?>
        </div>
        
        <div class="col-md-6">
            <label for="BaseUsuarioDestino">👤 Usuario Destino:</label>
            <?php echo $form->select('usuario_destino', $usuariosOptions, null, array(
                'empty' => '-- Selecciona Usuario Destino --',
                'class' => 'form-control',
                'required' => true,
                'id' => 'BaseUsuarioDestino'
            )); ?>
        </div>
    </div>
    <br>

    <h4>Filtros Opcionales de Territorio:</h4>
    <div class="row">
        <!-- NUEVO: Selector de Estado -->
        <div class="col-md-4">
            <label for="BaseEstadoFiltro">🌍 Estado:</label>
            <?php echo $form->select('estado_filtro', $estadosOptions, null, array( // $estadosOptions viene del controller
                'empty' => '-- Todos los Estados --', // O el placeholder que definiste en el controller
                'class' => 'form-control',
                'id' => 'BaseEstadoFiltro'
            )); ?>
        </div>

        <!-- NUEVO: Selector de Municipio -->
        <div class="col-md-4">
            <label for="BaseMunicipioFiltro">📍 Municipio:</label>
            <?php echo $form->select('municipio_filtro', $municipiosOptions, null, array( // $municipiosOptions viene del controller
                'empty' => '-- Todos los Municipios --', // O el placeholder que definiste
                'class' => 'form-control',
                'id' => 'BaseMunicipioFiltro'
            )); ?>
        </div>
        
        <div class="col-md-4">
            <label for="BaseTipoFiltro">📚 Tipo:</label>
            <?php echo $form->select('tipo_filtro', $tiposOptions, null, array(
                'empty' => '-- Todos los Tipos --',
                'class' => 'form-control',
                'id' => 'BaseTipoFiltro'
            )); ?>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;"> 
        <div class="col-md-4">
            <label for="BaseTurnoFiltro">🕒 Turno:</label>
            <?php echo $form->select('turno_filtro', $turnosOptions, null, array(
                'empty' => '-- Todos los Turnos --',
                'class' => 'form-control',
                'id' => 'BaseTurnoFiltro'
            )); ?>
        </div>

        <div class="col-md-4">
            <label for="BaseSectorFiltro">🏢 Sector:</label>
            <?php echo $form->select('sector_filtro', $sectoresOptions, null, array(
                'empty' => '-- Todos los Sectores --',
                'class' => 'form-control',
                'id' => 'BaseSectorFiltro'
            )); ?>
        </div>
    </div>
    
    <!-- Contenedor para mostrar el conteo de registros y alumnado -->
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-6">
            <p>Registros a transferir: <strong id="registros-transferir-count">0</strong></p>
        </div>
        <div class="col-md-6">
            <p>Alumnado total afectado: <strong id="alumnado-transferir-total">0</strong></p>
        </div>
    </div>
    <div id="contador-transferencia-ajax-mensaje" style="margin-top: 5px;"></div>

    <br>
    
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Transferir Datos</button>
        </div>
    </div>
    
    <?php echo $form->end(); ?>
    
    <!-- Contenedor para mensajes de respuesta AJAX del envío del formulario -->
    <div id="transferenciaMensaje" style="margin-top: 15px;"></div>

    <?php 
        if ($session->check('Message.flash')) {
            $message = $session->read('Message.flash.message');
            $class = $session->read('Message.flash.params.class');
            if (empty($class)) $class = 'info';
            echo '<div class="alert alert-'.$class.'">'.$message.'</div>';
            $session->delete('Message.flash');
        }
    ?>
</div>

 <style type="text/css">
    .alert { padding: 12px 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
    .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
    .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
    .alert-info { color: #31708f; background-color: #d9edf7; border-color: #bce8f1; }
    .alert-warning { color: #8a6d3b; background-color: #fcf8e3; border-color: #faebcc; }
</style> 


<script type="text/javascript">
if (typeof jQuery != 'undefined') {
    $(document).ready(function() {
        var baseUrl = '<?php echo $html->url('/'); ?>'; // Base URL para AJAX

        // --- Selectores para los nuevos filtros ---
        var $usuarioOrigenSelect = $('#BaseUsuarioOrigen'); 
        var $estadoFiltroSelect = $('#BaseEstadoFiltro');     // NUEVO
        var $municipioFiltroSelect = $('#BaseMunicipioFiltro'); // NUEVO
        var $tipoFiltroSelect = $('#BaseTipoFiltro');
        var $turnoFiltroSelect = $('#BaseTurnoFiltro');
        var $sectorFiltroSelect = $('#BaseSectorFiltro');
        
        var $countTransferirDisplay = $('#registros-transferir-count');
        var $alumnadoTransferirDisplay = $('#alumnado-transferir-total');
        var $contadorTransferenciaMensaje = $('#contador-transferencia-ajax-mensaje');

        // --- Función para poblar selects ---
        function populateSelect($selectElement, data, defaultOptionText, defaultOptionValue) {
            $selectElement.empty();
            if (defaultOptionValue === undefined) defaultOptionValue = ''; // Valor por defecto para la opción "Todos" o "Seleccione"
            if (defaultOptionText) {
                 $selectElement.append($('<option>').text(defaultOptionText).val(defaultOptionValue));
            }
            if (data) {
                $.each(data, function(value, text) {
                    // Evitar duplicar la opción por defecto si ya viene en 'data' con la misma clave vacía
                    if (value !== defaultOptionValue || !defaultOptionText) {
                        $selectElement.append($('<option>').text(text).val(value));
                    }
                });
            }
            $selectElement.prop('disabled', false);
        }
        
        // --- Cargar Estados cuando cambia Usuario Origen ---
        $usuarioOrigenSelect.on('change', function() {
            var usuarioId = $(this).val();
            $estadoFiltroSelect.empty().append($('<option>').text('-- Cargando Estados --').val('')).prop('disabled', true);
            $municipioFiltroSelect.empty().append($('<option>').text('-- Selecciona Estado Primero --').val('')).prop('disabled', true);
            
            actualizarConteoTransferencia(); // Actualizar conteo

            if (usuarioId) {
                $.ajax({
                    url: baseUrl + 'bases/estados_por_usuario_ajax',
                    data: { usuario_origen_id: usuarioId },
                    dataType: 'json',
                    success: function(response) {
                        populateSelect($estadoFiltroSelect, response, '-- Todos los Estados --', '');
                    },
                    error: function() {
                        populateSelect($estadoFiltroSelect, null, '-- Error al cargar Estados --', '');
                    }
                });
            } else {
                 populateSelect($estadoFiltroSelect, null, '-- Selecciona Usuario Origen Primero --', '');
            }
        });

        // --- Cargar Municipios cuando cambia Estado ---
        $estadoFiltroSelect.on('change', function() {
            var estadoId = $(this).val();
            $municipioFiltroSelect.empty().append($('<option>').text('-- Cargando Municipios --').val('')).prop('disabled', true);

            actualizarConteoTransferencia(); // Actualizar conteo

            if (estadoId) {
                $.ajax({
                    url: baseUrl + 'bases/municipios_por_estado_ajax1',
                    data: { estado_id: estadoId },
                    dataType: 'json',
                    success: function(response) {
                        populateSelect($municipioFiltroSelect, response, '-- Todos los Municipios --', '');
                    },
                    error: function() {
                        populateSelect($municipioFiltroSelect, null, '-- Error al cargar Municipios --', '');
                    }
                });
            } else {
                populateSelect($municipioFiltroSelect, null, '-- Selecciona Estado Primero --', '');
            }
        });

        // --- Función para actualizar conteo ---
        function actualizarConteoTransferencia() {
            var usuarioOrigen = $usuarioOrigenSelect.val();
            // Obtener valores de TODOS los filtros
            var estadoFiltro = $estadoFiltroSelect.val();       // NUEVO
            var municipioFiltro = $municipioFiltroSelect.val(); // NUEVO
            var tipoFiltro = $tipoFiltroSelect.val();
            var turnoFiltro = $turnoFiltroSelect.val();
            var sectorFiltro = $sectorFiltroSelect.val();

            if (!usuarioOrigen) {
                $countTransferirDisplay.text('0');
                $alumnadoTransferirDisplay.text('0');
                $contadorTransferenciaMensaje.html('<div class="alert alert-warning" style="padding: 5px 10px; margin-bottom:0;">Seleccione un Usuario Origen para ver los totales.</div>');
                return;
            }

            $countTransferirDisplay.text('Calculando...');
            $alumnadoTransferirDisplay.text('Calculando...');
            $contadorTransferenciaMensaje.html('<div class="alert alert-info" style="padding: 5px 10px; margin-bottom:0;">Obteniendo totales...</div>');

            $.ajax({
                url: '<?php echo $html->url(array("controller" => "bases", "action" => "contar_registros_transferencia_ajax")); ?>',
                type: 'GET',
                data: {
                    usuario_origen: usuarioOrigen,
                    estado: estadoFiltro,         // NUEVO
                    municipio: municipioFiltro,   // NUEVO
                    tipo: tipoFiltro,
                    turno: turnoFiltro,
                    sector: sectorFiltro
                },
                dataType: 'json',
                success: function(response) {
                    if (response && typeof response.success !== 'undefined') {
                        if (response.success) {
                            $countTransferirDisplay.text(response.count);
                            $alumnadoTransferirDisplay.text(response.total_alumnado);
                            $contadorTransferenciaMensaje.html('<div class="alert alert-success" style="padding: 5px 10px; margin-bottom:0;">Totales actualizados.</div>');
                        } else {
                            $countTransferirDisplay.text('0');
                            $alumnadoTransferirDisplay.text('0');
                            $contadorTransferenciaMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Error: ' + (response.message || 'No se pudo contar') + '</div>');
                        }
                    } else {
                         $countTransferirDisplay.text('0');
                         $alumnadoTransferirDisplay.text('0');
                         $contadorTransferenciaMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Respuesta inesperada del servidor.</div>');
                    }
                },
                error: function() {
                    $countTransferirDisplay.text('0');
                    $alumnadoTransferirDisplay.text('0');
                    $contadorTransferenciaMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Error de conexión al obtener totales.</div>');
                }
            });
        }

        // Escuchar cambios en TODOS los filtros relevantes para el conteo
        // $usuarioOrigenSelect ya tiene su propio 'change' que llama a actualizarConteoTransferencia
        // $estadoFiltroSelect ya tiene su propio 'change' que llama a actualizarConteoTransferencia
        $('#BaseMunicipioFiltro, #BaseTipoFiltro, #BaseTurnoFiltro, #BaseSectorFiltro').on('change', function() {
            actualizarConteoTransferencia();
        });

        // Llamada inicial si el usuario origen ya está seleccionado
        if ($usuarioOrigenSelect.val()) {
            // Disparamos el change para que cargue estados y luego el conteo se actualice
            $usuarioOrigenSelect.trigger('change'); 
        } else {
            // Si no hay usuario origen, al menos mostramos el mensaje inicial
            actualizarConteoTransferencia();
        }


        // --- Envío AJAX del formulario de transferencia ---
        $('#FormTransferirUsuarios').submit(function(e) {
            e.preventDefault(); 
            var formData = $(this).serialize(); // Esto incluirá los nuevos selects si tienen name
            var $mensajeTransferencia = $('#transferenciaMensaje'); 

            $mensajeTransferencia.html('<div class="alert alert-info">Procesando transferencia...</div>');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    var alertClass = response.success ? 'alert-success' : 'alert-danger';
                    $mensajeTransferencia.html(
                        '<div class="alert ' + alertClass + '">' + response.message + '</div>'
                    );
                    if (response.success) {
                        // Opcional: Resetear el formulario o campos específicos
                        // $('#FormTransferirUsuarios')[0].reset();
                        // $usuarioOrigenSelect.val('').trigger('change'); // Esto resetea y actualiza en cascada
                        actualizarConteoTransferencia(); // Actualizar conteos después de transferir
                    }
                },
                error: function() {
                    $mensajeTransferencia.html(
                        '<div class="alert alert-danger">Error de comunicación al intentar transferir. Intente de nuevo.</div>'
                    );
                }
            });
        });
    });
} else {
    console.error("jQuery no está cargado. La funcionalidad AJAX no operará.");
    var mainDiv = document.querySelector('.migrar-section'); 
    if (mainDiv) {
        var errorMsg = document.createElement('div');
        errorMsg.className = 'alert alert-danger';
        errorMsg.innerHTML = '<strong>Error Crítico:</strong> jQuery no está cargado. Algunas funcionalidades de esta página no operarán correctamente.';
        if (mainDiv.firstChild) {
            mainDiv.insertBefore(errorMsg, mainDiv.firstChild);
        } else {
            mainDiv.appendChild(errorMsg);
        }
    }
}
</script>
