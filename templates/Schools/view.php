<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\School $school
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit School'), ['action' => 'edit', $school->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete School'), ['action' => 'delete', $school->id], ['confirm' => __('Are you sure you want to delete # {0}?', $school->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Schools'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New School'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="schools view content">
            <h3><?= h($school->nombre) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nombre') ?></th>
                    <td><?= h($school->nombre) ?></td>
                </tr>
                <tr>
                    <th><?= __('Estado') ?></th>
                    <td><?= h($school->estado) ?></td>
                </tr>
                <tr>
                    <th><?= __('Municipio') ?></th>
                    <td><?= h($school->municipio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo') ?></th>
                    <td><?= h($school->tipo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sector') ?></th>
                    <td><?= h($school->sector) ?></td>
                </tr>
                <tr>
                    <th><?= __('Turno') ?></th>
                    <td><?= h($school->turno) ?></td>
                </tr>
                <tr>
                    <th><?= __('Editorial Actual') ?></th>
                    <td><?= h($school->editorial_actual) ?></td>
                </tr>
                <tr>
                    <th><?= __('Competencia') ?></th>
                    <td><?= h($school->competencia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($school->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Distribuidor Id') ?></th>
                    <td><?= $school->user_id === null ? '' : $this->Number->format($school->user_id) ?></td>
                </tr>
                <th>CCT</th>
                    <td><?= h($school->cct) ?></td>
                <tr>
                    <th><?= __('Num Alumnos') ?></th>
                    <td><?= $school->num_alumnos === null ? '' : $this->Number->format($school->num_alumnos) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fecha Decision') ?></th>
                    <td><?= h($school->fecha_decision) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($school->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($school->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Estatus') ?></th>
                    <td><?= $school->estatus ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Verificada') ?></th>
                    <td><?= $school->verificada ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Venta Montenegro') ?></th>
                    <td><?= $school->venta_montenegro ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>