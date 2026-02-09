<h3>Importar Escuelas (CSV)</h3>

<style>
    #progress-wrapper {
        width: 100%;
        background-color: #f1f1f1;
        border-radius: 4px;
        margin-top: 15px;
        margin-bottom: 15px;
        display: none; /* Oculto por defecto */
    }
    #progress-bar {
        width: 0%;
        height: 30px;
        background-color: #9b4dca; /* Color primario de Milligram/CakePHP */
        text-align: center;
        line-height: 30px;
        color: white;
        border-radius: 4px;
        transition: width 0.2s;
    }
</style>

<?= $this->Form->create(null, ['type' => 'file', 'id' => 'form-import']) ?>
<?= $this->Form->control('csv_file', [
    'type' => 'file',
    'label' => 'Archivo CSV',
    'accept' => '.csv'
]) ?>

<!-- Barra de progreso -->
<div id="progress-wrapper">
    <div id="progress-bar">0%</div>
</div>

<?= $this->Form->button('Importar') ?>
<?= $this->Form->end() ?>

<script>
document.getElementById('form-import').addEventListener('submit', function(e) {
    e.preventDefault(); // Evitar el envío tradicional

    var form = this;
    var formData = new FormData(form);
    var xhr = new XMLHttpRequest();
    var progressBar = document.getElementById('progress-bar');
    var progressWrapper = document.getElementById('progress-wrapper');
    var submitBtn = form.querySelector('button[type="submit"]');

    // Mostrar la barra y deshabilitar botón
    progressWrapper.style.display = 'block';
    submitBtn.disabled = true;
    submitBtn.innerText = 'Subiendo...';

    // Evento de progreso
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            var percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressBar.innerText = percentComplete + '%';
            
            if (percentComplete === 100) {
                progressBar.innerText = 'Procesando datos...';
            }
        }
    });

    // Cuando termina la petición (éxito o error del servidor)
    xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
            // Reemplazamos el contenido de la página con la respuesta del servidor (la vista de resultados)
            document.open();
            document.write(xhr.responseText);
            document.close();
        } else {
            alert('Ocurrió un error al subir el archivo.');
            submitBtn.disabled = false;
            submitBtn.innerText = 'Importar';
            progressWrapper.style.display = 'none';
        }
    });

    xhr.addEventListener('error', function() {
        alert('Error de red al intentar subir el archivo.');
        submitBtn.disabled = false;
        submitBtn.innerText = 'Importar';
    });

    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(formData);
});
</script>
