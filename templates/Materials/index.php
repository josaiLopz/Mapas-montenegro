<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Material> $materials
 */
?>
<style>
    /* ===== Materials Page ===== */
.materials-page{
  padding: 10px 6px;
}

.materials-header{
  display:flex;
  align-items:flex-end;
  justify-content:space-between;
  gap:16px;
  margin-bottom:14px;
}

.materials-title{
  margin:0;
  font-size: 2.0rem;
  color:#1f1b16;
}

.materials-subtitle{
  margin:6px 0 0;
  color:#6f6860;
  font-size: 1.25rem;
}

.materials-btn{
  background:#aa2334 !important;
  border-color:#aa2334 !important;
  color:#fff !important;
  border-radius: 12px;
  padding: 12px 14px;
  box-shadow: 0 10px 22px rgba(0,0,0,.12);
}
.materials-btn:hover{
  background:#8f1d2b !important;
  border-color:#8f1d2b !important;
}

.materials-card{
  background:#fff;
  border:1px solid #eee2d6;
  border-radius: 16px;
  box-shadow: 0 14px 30px rgba(0,0,0,.08);
  overflow:hidden;
}

/* Table */
.table-responsive{ overflow:auto; }
.materials-table{
  width:100%;
  border-collapse: separate;
  border-spacing: 0;
  margin:0;
}

.materials-table thead th{
  background: #faf6f1;
  color:#2b241d;
  font-weight: 700;
  font-size: 1.2rem;
  border-bottom: 1px solid #eee2d6;
  padding: 14px 12px;
  white-space: nowrap;
}

.materials-table tbody td{
  padding: 14px 12px;
  border-bottom: 1px solid #f0e7dd;
  vertical-align: middle;
  color:#1f1b16;
}

.materials-table tbody tr:hover{
  background: rgba(170,35,52,.05);
}

.materials-table th:first-child,
.materials-table td:first-child{
  padding-left: 16px;
}
.materials-table th:last-child,
.materials-table td:last-child{
  padding-right: 16px;
}

.materials-table .actions{
  white-space: nowrap;
}

/* Badges */
.badge{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 1.1rem;
  font-weight: 700;
  border: 1px solid transparent;
}
.badge-ok{
  background: rgba(18, 160, 70, .10);
  border-color: rgba(18, 160, 70, .22);
  color: #0f7a36;
}
.badge-off{
  background: rgba(120, 120, 120, .10);
  border-color: rgba(120, 120, 120, .22);
  color: #5a5651;
}

/* Action links */
.action-link{
  display:inline-block;
  padding: 8px 10px;
  border-radius: 10px;
  border: 1px solid #eadfcf;
  text-decoration:none;
  margin-right: 8px;
  color:#2f2922;
  background:#fff;
}
.action-link:hover{
  border-color:#aa2334;
  color:#aa2334;
  background: rgba(170,35,52,.06);
}
.action-link.danger:hover{
  border-color:#b42318;
  color:#b42318;
  background: rgba(180,35,24,.08);
}

/* Footer / paginator */
.materials-footer{
  padding: 14px 16px;
  background:#fff;
}

.pagination{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
  align-items:center;
  margin: 0 0 8px;
}

.pagination li a,
.pagination li span{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding: 8px 10px;
  border-radius: 10px;
  border:1px solid #eadfcf;
  background:#fff;
  color:#2f2922;
  text-decoration:none;
  line-height:1;
}

.pagination li.active span{
  background:#aa2334;
  border-color:#aa2334;
  color:#fff;
}

.pagination li a:hover{
  border-color:#aa2334;
  color:#aa2334;
  background: rgba(170,35,52,.06);
}

.materials-counter{
  margin:0;
  color:#6f6860;
  font-size: 1.2rem;
}

/* Mobile tweaks */
@media (max-width: 820px){
  .materials-header{
    align-items:stretch;
    flex-direction: column;
  }
  .materials-btn{
    width:100%;
    text-align:center;
  }
}
</style>
<div class="materials-page">
    <div class="materials-header">
        <div>
            <h3 class="materials-title"><?= __('Materiales') ?></h3>
            <p class="materials-subtitle">Administra tus materiales (crear, editar, activar/desactivar).</p>
        </div>
  
        <?= $this->Html->link(__('Nuevo Material'), ['action' => 'add'], ['class' => 'button materials-btn']) ?>
    </div>
  
    <div class="materials-card">
      <div class="table-responsive">
          <table class="materials-table">
              <thead>
                  <tr>
                      <th><?= $this->Paginator->sort('id') ?></th>
                      <th><?= $this->Paginator->sort('nombre', 'Nombre') ?></th>
                      <th><?= $this->Paginator->sort('nivel', 'Nivel') ?></th>
                      <th><?= $this->Paginator->sort('activo') ?></th>
                      <th class="actions"><?= __('Acciones') ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($materials as $material): ?>
                  <tr>
                      <td><?= $this->Number->format($material->id) ?></td>
                      <td><strong><?= h($material->nombre) ?></strong></td>
                      <td><?= h($material->nivel) ?></td>
                      <td>
                          <?php if ($material->activo): ?>
                              <span class="badge badge-ok"><?= __('Sí') ?></span>
                          <?php else: ?>
                              <span class="badge badge-off"><?= __('No') ?></span>
                          <?php endif; ?>
                      </td>
                      <td class="actions">
                          <?= $this->Html->link(__('Editar'), ['action' => 'edit', $material->id], ['class' => 'action-link']) ?>
                          <?= $this->Form->postLink(
                              __('Eliminar'),
                              ['action' => 'delete', $material->id],
                              ['confirm' => __('¿Estás seguro de que quieres eliminar # {0}?', $material->id), 'class' => 'action-link danger']
                          ) ?>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      </div>
  
      <div class="materials-footer">
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('primero')) ?>
                <?= $this->Paginator->prev('< ' . __('previo')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('siguiente') . ' >') ?>
                <?= $this->Paginator->last(__('último') . ' >>') ?>
            </ul>
            <p class="materials-counter">
                <?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registro(s) de un total de {{count}}')) ?>
            </p>
        </div>
      </div>
    </div>
  </div>