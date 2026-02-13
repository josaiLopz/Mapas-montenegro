<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\School $school
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var array<int|string, string> $estados
 * @var array<int|string, string> $municipios
 */

$statusOptions = [
    'noAtendida' => 'No atendida',
    'escuelaPromocion' => 'Escuela en promocion',
    'ventaConfirmada' => 'Venta confirmada',
    'prohibicion' => 'Prohibicion',
    'ventaMarcas' => 'Venta otras marcas',
];
?>

<section class="school-form-page">
    <header class="school-form-header">
        <h2>Agregar escuela</h2>
        <p>Registra una nueva escuela con datos generales, contacto y seguimiento.</p>
    </header>

    <article class="school-form-card">
        <?= $this->Form->create($school, ['class' => 'school-form']) ?>

        <div class="school-form-grid">
            <?= $this->Form->control('nombre', ['label' => 'Nombre']) ?>
            <?= $this->Form->control('cct', ['label' => 'CCT', 'placeholder' => 'Ej. 14DJN0001X']) ?>

            <?= $this->Form->control('estado_id', [
                'label' => 'Estado',
                'options' => $estados,
                'empty' => '-- Seleccione estado --',
            ]) ?>
            <?= $this->Form->control('municipio_id', [
                'label' => 'Municipio',
                'options' => $municipios,
                'empty' => '-- Seleccione municipio --',
            ]) ?>

            <?= $this->Form->control('user_id', [
                'label' => 'Usuario asignado',
                'options' => $users,
                'empty' => '-- Seleccione usuario --',
            ]) ?>
            <?= $this->Form->control('estatus', [
                'type' => 'select',
                'options' => $statusOptions,
                'empty' => '-- Seleccione estatus --',
            ]) ?>

            <?= $this->Form->control('tipo') ?>
            <?= $this->Form->control('sector') ?>
            <?= $this->Form->control('turno') ?>
            <?= $this->Form->control('num_alumnos', ['label' => 'Numero de alumnos']) ?>

            <?= $this->Form->control('lat', ['label' => 'Latitud']) ?>
            <?= $this->Form->control('lng', ['label' => 'Longitud']) ?>
            <?= $this->Form->control('grupos') ?>

            <?= $this->Form->control('nombre_contacto') ?>
            <?= $this->Form->control('telefono_contacto') ?>
            <?= $this->Form->control('correo_contacto') ?>

            <?= $this->Form->control('editorial_actual') ?>
            <?= $this->Form->control('competencia') ?>
            <?= $this->Form->control('presupuesto') ?>
            <?= $this->Form->control('fecha_decision', ['empty' => true]) ?>

            <?= $this->Form->control('verificada', [
                'type' => 'select',
                'options' => [1 => 'Si', 0 => 'No'],
            ]) ?>
            <?= $this->Form->control('venta_montenegro', [
                'type' => 'select',
                'options' => [1 => 'Si', 0 => 'No'],
            ]) ?>

            <div class="field-full">
                <?= $this->Form->control('notas', ['type' => 'textarea']) ?>
            </div>
        </div>

        <div class="school-form-actions">
            <?= $this->Form->button('Guardar escuela', ['class' => 'button button-primary']) ?>
            <?= $this->Html->link('Cancelar', ['action' => 'index'], ['class' => 'button button-secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </article>
</section>

<style>
.school-form-page {
    max-width: 1080px;
    margin: 0 auto;
    display: grid;
    gap: 14px;
}

.school-form-header h2 {
    margin: 0;
    color: #2f251a;
}

.school-form-header p {
    margin: 4px 0 0;
    color: #6c6458;
}

.school-form-card {
    background: #ffffff;
    border: 1px solid #e9e0d3;
    border-radius: 12px;
    padding: 16px;
}

.school-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.field-full {
    grid-column: 1 / -1;
}

.school-form-grid .input,
.school-form-grid .select,
.school-form-grid .textarea,
.school-form-grid .email,
.school-form-grid .number {
    margin-bottom: 0;
}

.school-form-actions {
    margin-top: 14px;
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

@media (max-width: 760px) {
    .school-form-card {
        padding: 12px;
    }

    .school-form-grid {
        grid-template-columns: 1fr;
    }

    .field-full {
        grid-column: auto;
    }

    .school-form-actions .button {
        width: 100%;
    }
}
</style>
