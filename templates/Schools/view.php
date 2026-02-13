<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\School $school
 */
?>

<section class="school-view-page">
    <header class="school-view-header">
        <h2>Detalle de escuela</h2>
        <p>Consulta la informacion completa de la escuela y su estado comercial.</p>
    </header>

    <article class="school-view-card">
        <div class="school-view-actions">
            <?= $this->Html->link('Editar', ['action' => 'edit', $school->id], ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Volver', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
            <?= $this->Form->postLink(
                'Eliminar',
                ['action' => 'delete', $school->id],
                ['class' => 'button button-danger', 'confirm' => __('Are you sure you want to delete # {0}?', $school->id)]
            ) ?>
        </div>

        <section class="school-view-section">
            <h3>Datos generales</h3>
            <dl class="detail-grid">
                <div class="detail-item"><dt>ID</dt><dd><?= (int)$school->id ?></dd></div>
                <div class="detail-item"><dt>Nombre</dt><dd><?= h((string)$school->nombre) ?></dd></div>
                <div class="detail-item"><dt>CCT</dt><dd><?= h((string)($school->cct ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Usuario asignado</dt><dd><?= h((string)($school->user->name ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Email usuario</dt><dd><?= h((string)($school->user->email ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Estado</dt><dd><?= h((string)($school->estado->nombre ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Municipio</dt><dd><?= h((string)($school->municipio->nombre ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Tipo</dt><dd><?= h((string)($school->tipo ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Sector</dt><dd><?= h((string)($school->sector ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Turno</dt><dd><?= h((string)($school->turno ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Alumnos</dt><dd><?= h((string)($school->num_alumnos ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Grupos</dt><dd><?= h((string)($school->grupos ?? '-')) ?></dd></div>
            </dl>
        </section>

        <section class="school-view-section">
            <h3>Ubicacion y contacto</h3>
            <dl class="detail-grid">
                <div class="detail-item"><dt>Latitud</dt><dd><?= h((string)($school->lat ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Longitud</dt><dd><?= h((string)($school->lng ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Nombre de contacto</dt><dd><?= h((string)($school->nombre_contacto ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Telefono de contacto</dt><dd><?= h((string)($school->telefono_contacto ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Correo de contacto</dt><dd><?= h((string)($school->correo_contacto ?? '-')) ?></dd></div>
            </dl>
        </section>

        <section class="school-view-section">
            <h3>Seguimiento comercial</h3>
            <dl class="detail-grid">
                <div class="detail-item"><dt>Estatus</dt><dd><?= h((string)($school->estatus ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Verificada</dt><dd><span class="status-badge <?= $school->verificada ? 'is-active' : 'is-inactive' ?>"><?= $school->verificada ? 'Si' : 'No' ?></span></dd></div>
                <div class="detail-item"><dt>Editorial actual</dt><dd><?= h((string)($school->editorial_actual ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Venta Montenegro</dt><dd><span class="status-badge <?= $school->venta_montenegro ? 'is-active' : 'is-inactive' ?>"><?= $school->venta_montenegro ? 'Si' : 'No' ?></span></dd></div>
                <div class="detail-item"><dt>Competencia</dt><dd><?= h((string)($school->competencia ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Presupuesto</dt><dd><?= h((string)($school->presupuesto ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Fecha decision</dt><dd><?= h((string)($school->fecha_decision ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Creado</dt><dd><?= h((string)($school->created ?? '-')) ?></dd></div>
                <div class="detail-item"><dt>Modificado</dt><dd><?= h((string)($school->modified ?? '-')) ?></dd></div>
                <div class="detail-item detail-full"><dt>Notas</dt><dd><?= h((string)($school->notas ?? '-')) ?></dd></div>
            </dl>
        </section>
    </article>
</section>

<style>
.school-view-page {
    max-width: 1080px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.school-view-header h2 {
    margin: 0;
    color: #2f251a;
}

.school-view-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.school-view-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.school-view-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.school-view-section + .school-view-section {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #ece3d6;
}

.school-view-section h3 {
    margin: 0 0 10px;
    color: #30251a;
}

.detail-grid {
    margin: 0;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.detail-item {
    margin: 0;
    border: 1px solid #ece3d6;
    border-radius: 10px;
    padding: 10px 12px;
    background: #fffaf3;
}

.detail-item dt {
    margin: 0;
    font-size: 1.2rem;
    color: #786f63;
}

.detail-item dd {
    margin: 4px 0 0;
    color: #30261b;
    font-weight: 600;
    word-break: break-word;
}

.detail-full {
    grid-column: 1 / -1;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 3px 9px;
    font-size: 1.1rem;
    font-weight: 700;
}

.status-badge.is-active {
    color: #0f5132;
    background: #d1e7dd;
}

.status-badge.is-inactive {
    color: #842029;
    background: #f8d7da;
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
    line-height: 1.2;
}

.button-primary {
    color: #ffffff;
    background: #8c1d2f;
    border-color: #8c1d2f;
}

.button-primary:hover {
    color: #ffffff;
    background: #741727;
    border-color: #741727;
}

.button-secondary {
    color: #4b3e31;
    border-color: #dbc9b4;
    background: #fff8ee;
}

.button-secondary:hover {
    color: #4b3e31;
    background: #f9ebda;
}

.button-danger {
    color: #ffffff;
    border-color: #bb2d3b;
    background: #dc3545;
}

.button-danger:hover {
    color: #ffffff;
    background: #bb2d3b;
}

@media (max-width: 980px) {
    .detail-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 760px) {
    .school-view-card {
        padding: 12px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .detail-full {
        grid-column: auto;
    }

    .school-view-actions .button {
        width: 100%;
    }
}
</style>
