<section class="school-import-page">
    <header class="school-import-header">
        <h2>Importar escuelas (CSV)</h2>
        <p>Sube un archivo CSV para crear registros en lote.</p>
    </header>

    <article class="school-import-card">
        <?= $this->Form->create(null, ['type' => 'file', 'id' => 'form-import']) ?>

        <?= $this->Form->control('csv_file', [
            'type' => 'file',
            'label' => 'Archivo CSV',
            'accept' => '.csv',
            'required' => true,
        ]) ?>

        <div id="progress-wrapper" aria-live="polite" aria-hidden="true">
            <div id="progress-bar">0%</div>
        </div>

        <div class="import-actions">
            <?= $this->Form->button('Importar', ['id' => 'import-submit', 'class' => 'button button-primary']) ?>
            <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </article>
</section>

<style>
.school-import-page {
    max-width: 760px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.school-import-header h2 {
    margin: 0;
    color: #2f251a;
}

.school-import-header p {
    margin: 4px 0 0;
    color: #6d655a;
}

.school-import-card {
    background: #fff;
    border: 1px solid #e9dfd2;
    border-radius: 12px;
    padding: 16px;
}

#progress-wrapper {
    width: 100%;
    background: #f2ebe1;
    border-radius: 999px;
    margin-top: 10px;
    margin-bottom: 12px;
    overflow: hidden;
    display: none;
}

#progress-bar {
    width: 0;
    min-height: 28px;
    background: linear-gradient(90deg, #8c1d2f, #b23448);
    text-align: center;
    line-height: 28px;
    color: #fff;
    font-weight: 600;
    transition: width 0.2s;
}

.import-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid transparent;
    padding: 8px 12px;
    text-decoration: none;
    font-weight: 600;
}

.button-primary {
    color: #fff;
    background: #8c1d2f;
    border-color: #8c1d2f;
}

.button-secondary {
    color: #4b3e31;
    background: #fff8ee;
    border-color: #dbc9b4;
}

@media (max-width: 680px) {
    .school-import-card {
        padding: 12px;
    }

    .import-actions .button {
        width: 100%;
    }
}
</style>

<script>
document.getElementById('form-import').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    const progressBar = document.getElementById('progress-bar');
    const progressWrapper = document.getElementById('progress-wrapper');
    const submitBtn = document.getElementById('import-submit');

    progressWrapper.style.display = 'block';
    progressWrapper.setAttribute('aria-hidden', 'false');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Subiendo...';

    xhr.upload.addEventListener('progress', function(ev) {
        if (!ev.lengthComputable) return;

        const percentComplete = Math.round((ev.loaded / ev.total) * 100);
        progressBar.style.width = percentComplete + '%';
        progressBar.innerText = percentComplete === 100 ? 'Procesando datos...' : percentComplete + '%';
    });

    xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
            document.open();
            document.write(xhr.responseText);
            document.close();
            return;
        }

        alert('Ocurrio un error al subir el archivo.');
        submitBtn.disabled = false;
        submitBtn.innerText = 'Importar';
        progressWrapper.style.display = 'none';
        progressWrapper.setAttribute('aria-hidden', 'true');
    });

    xhr.addEventListener('error', function() {
        alert('Error de red al intentar subir el archivo.');
        submitBtn.disabled = false;
        submitBtn.innerText = 'Importar';
        progressWrapper.style.display = 'none';
        progressWrapper.setAttribute('aria-hidden', 'true');
    });

    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(formData);
});
</script>
