<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\School> $schools
 */
?>

<div class="schools index content">
    <?= $this->Html->link('Importar CSV', ['action' => 'import'], ['class' => 'button']) ?>
    <?= $this->Html->link(__('New School'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <?= $this->Html->link('📥 Descargar plantilla CSV', ['action' => 'downloadTemplate'], ['class' => 'button']) ?>

    <h3><?= __('Escuelas') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('nombre') ?></th>
                    <th><?= __('Estado') ?></th>
                    <th><?= __('Municipio') ?></th>
                    <th><?= __('Usuario asignado') ?></th>
                    <th><?= $this->Paginator->sort('tipo') ?></th>
                    <th><?= $this->Paginator->sort('sector') ?></th>
                    <th><?= $this->Paginator->sort('turno') ?></th>
                    <th><?= $this->Paginator->sort('num_alumnos') ?></th>
                    <th><?= $this->Paginator->sort('cct') ?></th>
                    <th><?= $this->Paginator->sort('lat') ?></th>
                    <th><?= $this->Paginator->sort('lng') ?></th>
                    <th><?= $this->Paginator->sort('grupos') ?></th>
                    <th><?= $this->Paginator->sort('nombre_contacto') ?></th>
                    <th><?= $this->Paginator->sort('telefono_contacto') ?></th>
                    <th><?= $this->Paginator->sort('correo_contacto') ?></th>
                    <th><?= $this->Paginator->sort('presupuesto') ?></th>
                    <th><?= $this->Paginator->sort('notas') ?></th>
                    <th><?= $this->Paginator->sort('estatus') ?></th>
                    <th><?= $this->Paginator->sort('verificada') ?></th>
                    <th><?= $this->Paginator->sort('editorial_actual') ?></th>
                    <th><?= $this->Paginator->sort('venta_montenegro') ?></th>
                    <th><?= $this->Paginator->sort('competencia') ?></th>
                    <th><?= $this->Paginator->sort('fecha_decision') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schools as $school): ?>
                <tr>
                    <td><?= $school->id ?></td>
                    <td><?= h($school->nombre) ?></td>
                    <td><?= h($school->estado->nombre ?? '—') ?></td>
                    <td><?= h($school->municipio->nombre ?? '—') ?></td>
                    <td>
                        <?= $school->has('user') ? h($school->user->name) : '—' ?><br>
                        <small><?= h($school->user->email ?? '') ?></small>
                    </td>
                    <td><?= h($school->tipo) ?></td>
                    <td><?= h($school->sector) ?></td>
                    <td><?= h($school->turno) ?></td>
                    <td><?= $school->num_alumnos ?? '—' ?></td>
                    <td><?= h($school->cct) ?></td>
                    <td><?= h($school->lat ?? '—') ?></td>
                    <td><?= h($school->lng ?? '—') ?></td>
                    <td><?= h($school->grupos ?? '—') ?></td>
                    <td><?= h($school->nombre_contacto ?? '—') ?></td>
                    <td><?= h($school->telefono_contacto ?? '—') ?></td>
                    <td><?= h($school->correo_contacto ?? '—') ?></td>
                    <td><?= h($school->presupuesto ?? '—') ?></td>
                    <td><?= h($school->notas ?? '—') ?></td>
                    <td><?= ucfirst(h($school->estatus)) ?></td>
                    <td><?= $school->verificada ? 'Sí' : 'No' ?></td>
                    <td><?= h($school->editorial_actual) ?></td>
                    <td><?= $school->venta_montenegro ? 'Sí' : 'No' ?></td>
                    <td><?= h($school->competencia) ?></td>
                    <td><?= h($school->fecha_decision ?? '—') ?></td>
                    <td><?= h($school->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $school->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $school->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $school->id], ['confirm' => __('Are you sure you want to delete # {0}?', $school->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
