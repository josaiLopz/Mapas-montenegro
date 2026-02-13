<?php
/** @var int $saved */
/** @var array<int, array<string, mixed>> $errors */

$errorCount = !empty($errors) ? count($errors) : 0;
?>

<section class="import-result-page">
    <header class="import-result-header">
        <h2>Resultado de importacion</h2>
        <p>Proceso completado. Revisa el resumen y corrige errores si aplica.</p>
    </header>

    <article class="import-result-summary">
        <div class="summary-item is-success">
            <span>Escuelas guardadas</span>
            <strong><?= (int)$saved ?></strong>
        </div>
        <div class="summary-item <?= $errorCount > 0 ? 'is-error' : 'is-neutral' ?>">
            <span>Registros con error</span>
            <strong><?= (int)$errorCount ?></strong>
        </div>
    </article>

    <?php if ($errorCount > 0): ?>
        <section class="import-errors-card">
            <h3>Errores al importar</h3>
            <div class="table-responsive">
                <table class="errors-table">
                    <thead>
                        <tr>
                            <th>Fila</th>
                            <th>Errores</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($errors as $error): ?>
                            <?php $rowData = isset($error['row']) ? (array)$error['row'] : []; ?>
                            <?php $fieldErrors = isset($error['errors']) ? (array)$error['errors'] : []; ?>
                            <tr>
                                <td>
                                    <code><?= h((string)json_encode($rowData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></code>
                                </td>
                                <td>
                                    <ul class="error-list">
                                        <?php foreach ($fieldErrors as $field => $messages): ?>
                                            <?php $messageList = is_array($messages) ? $messages : [(string)$messages]; ?>
                                            <li>
                                                <strong><?= h((string)$field) ?>:</strong>
                                                <?= h(implode(', ', array_map('strval', $messageList))) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <div class="import-result-actions">
        <?= $this->Html->link('Volver a escuelas', ['action' => 'index'], ['class' => 'button button-primary']) ?>
        <?= $this->Html->link('Importar otro archivo', ['action' => 'import'], ['class' => 'button button-secondary']) ?>
    </div>
</section>

<style>
.import-result-page {
    display: grid;
    gap: 14px;
}

.import-result-header h2 {
    margin: 0;
    color: #2f251a;
}

.import-result-header p {
    margin: 4px 0 0;
    color: #6d655a;
}

.import-result-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 10px;
}

.summary-item {
    border: 1px solid #e9dfd2;
    border-radius: 10px;
    padding: 12px;
    background: #fffaf3;
}

.summary-item span {
    display: block;
    color: #6f665c;
    font-size: 1.2rem;
}

.summary-item strong {
    display: block;
    margin-top: 6px;
    color: #30261b;
    font-size: 2rem;
}

.summary-item.is-success {
    border-color: #c8e7d7;
    background: #eefaf3;
}

.summary-item.is-error {
    border-color: #f0c8cd;
    background: #fff3f5;
}

.import-errors-card {
    background: #ffffff;
    border: 1px solid #e9dfd2;
    border-radius: 12px;
    padding: 14px;
}

.import-errors-card h3 {
    margin: 0 0 10px;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.errors-table {
    width: 100%;
    border-collapse: collapse;
}

.errors-table th,
.errors-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #efe5d8;
    text-align: left;
    vertical-align: top;
}

.errors-table code {
    white-space: pre-wrap;
    word-break: break-word;
}

.error-list {
    margin: 0;
    padding-left: 18px;
}

.import-result-actions {
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
</style>
