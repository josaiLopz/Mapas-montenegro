<div class="migrar-section">
    <h3>Asignar Distribuidor por Estado</h3>
    
    <?php 
        // Mostrar mensajes flash
        if ($session->check('Message.flash')) {
            $message = $session->read('Message.flash.message');
            $class = $session->read('Message.flash.params.class'); // Asumiendo que guardas la clase en params
            // Si no guardas 'class', puedes definir una por defecto o basarla en el tipo de mensaje
            if (empty($class)) $class = 'info'; // Clase por defecto si no se especifica
            echo '<div class="alert alert-'.$class.'">'.$message.'</div>';
            $session->delete('Message.flash');
        }
        
        // Crear el formulario
        echo $form->create('Base', array('action' => 'asignar_distribuidor_estado', 'id' => 'FormAsignarDistribuidor')); 
    ?>
    
    <div class="row">
        <div class="col-md-4"> <!-- Changed to col-md-4 to make space for Municipio -->
            <label for="BaseEstadoOrigen">🏛️ Estado:</label> <?php // CakePHP 1.x genera ID como ModelNameFieldName ?>
            <?php echo $form->select('estado_origen', $estadosOptions, null, array( // Se usa null como tercer parámetro para que 'empty' funcione
                'empty' => '-- Selecciona un estado --', // Esto añadirá una opción vacía al principio
                'class' => 'form-control',
                'required' => true,
                'id' => 'BaseEstadoOrigen' 
            )); ?>
        </div>

        <div class="col-md-4"> <!-- NEW COLUMN FOR MUNICIPIO -->
            <label for="BaseMunicipio">🏙️ Municipio:</label>
            <?php echo $form->select('municipio', $municipiosOptions, null, array(
                'empty' => '-- Todos los Municipios --', // Optional filter
                'class' => 'form-control',
                'id' => 'BaseMunicipio',
                'disabled' => true // Start disabled until a state is selected
            )); ?>
        </div>
        
        <div class="col-md-4"> <!-- Changed to col-md-4 -->
            <label for="BaseIdUsuarioNuevo">👤 Distribuidor:</label>
            <?php echo $form->select('id_usuario_nuevo', $usuariosOptions, null, array(
                'empty' => '-- Selecciona un distribuidor --',
                'class' => 'form-control',
                'required' => true,
                'id' => 'BaseIdUsuarioNuevo'
            )); ?>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-4">
            <label for="BaseTipo">📚 Tipo:</label>
            <?php echo $form->select('tipo', $tiposOptions, null, array(
                'empty' => '-- Todos los Tipos --',
                'class' => 'form-control',
                'id' => 'BaseTipo'
            )); ?>
        </div>
        
        <div class="col-md-4">
            <label for="BaseTurno">🕒 Turno:</label>
            <?php echo $form->select('turno', $turnosOptions, null, array(
                'empty' => '-- Todos los Turnos --',
                'class' => 'form-control',
                'id' => 'BaseTurno'
            )); ?>
        </div>

        <div class="col-md-4">
            <label for="BaseSector">🏢 Sector:</label>
            <?php echo $form->select('sector', $sectoresOptions, null, array(
                'empty' => '-- Todos los Sectores --',
                'class' => 'form-control',
                'id' => 'BaseSector'
            )); ?>
        </div>
    </div>

    <!-- Contenedor para mostrar el conteo de registros y alumnado -->
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-6">
            <p>Registros que coinciden: <strong id="registros-count">0</strong></p>
        </div>
        <div class="col-md-6">
            <p>Alumnado total afectado: <strong id="alumnado-total">0</strong></p>
        </div>
    </div>
    <div id="contador-ajax-mensaje" style="margin-top: 5px;"></div>


    <br>
    
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Asignar Distribuidor</button>
        </div>
    </div>
    
    <?php echo $form->end(); ?>

    <!-- Contenedor para mensajes de respuesta AJAX del envío del formulario -->
    <div id="asignacionMensaje" style="margin-top: 15px;"></div>
</div>

<style type="text/css">
       .migrar-section {
        background: #f5f5f5;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .migrar-section h3 {
        margin-top: 0;
        color: #555;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        height: 38px; /* Ensure consistent height */
    }

    .btn {
        padding: 8px 16px;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }
    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-error { /* Ensure this class is defined if you use it for error messages */
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        margin-bottom: 15px; /* Add some margin between rows */
    }

    /* Updated col-md-6 to col-md-4 where needed */
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    .col-md-4 { 
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .col-md-6, .col-md-4 { 
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }
    }
</style>

<!-- Script para el conteo dinámico y envío AJAX del formulario -->
<script type="text/javascript">
if (typeof jQuery != 'undefined') {
    $(document).ready(function() {
        // --- INICIO: Conteo dinámico de registros y alumnado ---
        var $estadoSelect = $('#BaseEstadoOrigen'); 
        var $municipioSelect = $('#BaseMunicipio'); // NEW: Reference to municipality select
        var $tipoSelect = $('#BaseTipo');
        var $turnoSelect = $('#BaseTurno');
        var $sectorSelect = $('#BaseSector');
        var $countDisplay = $('#registros-count');
        var $alumnadoDisplay = $('#alumnado-total'); 
        var $contadorMensaje = $('#contador-ajax-mensaje');

        // NEW: Function to load municipalities based on selected state
        function cargarMunicipios() {
            var estadoId = $estadoSelect.val();
            $municipioSelect.empty().append($('<option>', {
                value: '',
                text: '-- Cargando Municipios --'
            }));
            $municipioSelect.prop('disabled', true); // Disable while loading

            if (!estadoId) {
                // If no state selected, reset municipality select
                $municipioSelect.empty().append($('<option>', {
                    value: '',
                    text: '-- Todos los Municipios --'
                }));
                 $municipioSelect.prop('disabled', true); // Keep disabled
                return;
            }

            $.ajax({
                url: '<?php echo $html->url(array("controller" => "bases", "action" => "municipios_por_estado_ajax")); ?>',
                type: 'GET',
                data: { estado_id: estadoId },
                dataType: 'json',
                success: function(response) {
                    $municipioSelect.empty(); // Clear current options
                    // Add the default "Todos" option
                    $municipioSelect.append($('<option>', {
                        value: '',
                        text: '-- Todos los Municipios --'
                    }));
                    // Add options from the response
                    $.each(response, function(value, text) {
                        $municipioSelect.append($('<option>', {
                            value: value,
                            text: text
                        }));
                    });
                    $municipioSelect.prop('disabled', false); // Enable municipality select
                    actualizarConteo(); // Update count after municipalities are loaded
                },
                error: function() {
                    $municipioSelect.empty().append($('<option>', {
                        value: '',
                        text: '-- Error al cargar Municipios --'
                    }));
                    $municipioSelect.prop('disabled', true); // Keep disabled on error
                    $contadorMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Error al cargar municipios.</div>');
                    actualizarConteo(); // Still try to update count with just state filter
                }
            });
        }


        function actualizarConteo() {
            var estado = $estadoSelect.val();
            var municipio = $municipioSelect.val(); // NEW: Get selected municipality
            var tipo = $tipoSelect.val();
            var turno = $turnoSelect.val();
            var sector = $sectorSelect.val();

            if (!estado) {
                $countDisplay.text('0');
                $alumnadoDisplay.text('0');
                $contadorMensaje.html('<div class="alert alert-warning" style="padding: 5px 10px; margin-bottom:0;">Seleccione un estado para ver los totales.</div>');
                // Disable municipality select if state is not selected
                $municipioSelect.empty().append($('<option>', {
                    value: '',
                    text: '-- Todos los Municipios --'
                })).prop('disabled', true);
                return;
            }

            $countDisplay.text('Calculando...');
            $alumnadoDisplay.text('Calculando...');
            $contadorMensaje.html('<div class="alert alert-info" style="padding: 5px 10px; margin-bottom:0;">Obteniendo totales...</div>');

            $.ajax({
                url: '<?php echo $html->url(array("controller" => "bases", "action" => "contar_registros_ajax")); ?>',
                type: 'GET',
                data: {
                    estado_origen: estado,
                    municipio: municipio, // NEW: Include municipality in data
                    tipo: tipo,
                    turno: turno,
                    sector: sector
                },
                dataType: 'json',
                success: function(response) {
                    if (response && typeof response.success !== 'undefined') {
                        if (response.success) {
                            $countDisplay.text(response.count);
                            $alumnadoDisplay.text(response.total_alumnado); 
                            $contadorMensaje.html('<div class="alert alert-success" style="padding: 5px 10px; margin-bottom:0;">Totales actualizados.</div>');
                        } else {
                            $countDisplay.text('0');
                            $alumnadoDisplay.text('0');
                            $contadorMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Error: ' + (response.message || 'No se pudo contar') + '</div>');
                        }
                    } else {
                         $countDisplay.text('0');
                         $alumnadoDisplay.text('0');
                         $contadorMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Respuesta inesperada del servidor.</div>');
                    }
                },
                error: function() {
                    $countDisplay.text('0');
                    $alumnadoDisplay.text('0');
                    $contadorMensaje.html('<div class="alert alert-danger" style="padding: 5px 10px; margin-bottom:0;">Error de conexión al obtener totales.</div>');
                }
            });
        }

        // Escuchar cambios en los filtros
        // Trigger cargarMunicipios when state changes
        $('#BaseEstadoOrigen').on('change', function() {
            cargarMunicipios(); // Load municipalities first
            // actualizarConteo is called inside cargarMunicipios success/error
        });

        // Listen for changes on other filters (including the new municipality select)
        $('#BaseMunicipio, #BaseTipo, #BaseTurno, #BaseSector').on('change', function() {
            actualizarConteo(); // Update count when other filters change
        });


        // Llamada inicial si el estado ya está seleccionado (por ejemplo, si se recarga la página con filtros)
        // Also load municipalities and update count on initial load if state is pre-selected
        if ($estadoSelect.val()) {
            cargarMunicipios(); 
        } else {
             // If no state is initially selected, ensure municipality is disabled
             $municipioSelect.prop('disabled', true);
        }
        // --- FIN: Conteo dinámico ---

        // --- INICIO: Envío AJAX del formulario de asignación ---
        $('#FormAsignarDistribuidor').submit(function(e) {
            e.preventDefault(); // Evitar envío tradicional del formulario
            // Serialize includes all form fields, including the new municipality select
            var formData = $(this).serialize(); 
            var $mensajeAsignacion = $('#asignacionMensaje');

            $mensajeAsignacion.html('<div class="alert alert-info">Procesando asignación...</div>');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    var alertClass = response.success ? 'alert-success' : 'alert-danger'; // o alert-error
                    $mensajeAsignacion.html(
                        '<div class="alert ' + alertClass + '">' + response.message + '</div>'
                    );
                    if (response.success) {
                        // Opcional: Resetear el formulario o solo los selects de filtro si es necesario
                        // $('#FormAsignarDistribuidor')[0].reset();
                        // actualizarConteo(); // Actualizar conteos después de asignar
                    }
                },
                error: function() {
                    $mensajeAsignacion.html(
                        '<div class="alert alert-danger">Error de comunicación al intentar asignar. Intente de nuevo.</div>'
                    );
                }
            });
        });
       
    });
} else {
    console.error("jQuery no está cargado. La funcionalidad AJAX no operará.");
    // Podrías mostrar un mensaje al usuario en la página si jQuery no carga
    var mainDiv = document.querySelector('.migrar-section'); // O un ID específico
    if (mainDiv) {
        var errorMsg = document.createElement('div');
        errorMsg.className = 'alert alert-danger';
        errorMsg.innerHTML = '<strong>Error Crítico:</strong> jQuery no está cargado. Algunas funcionalidades de esta página no operarán correctamente.';
        mainDiv.insertBefore(errorMsg, mainDiv.firstChild);
    }
}
</script>
