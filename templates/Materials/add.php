<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Material $material
 * @var array $niveles
 */
?>
<style>
    /* ===== Materials Form ===== */
.materials-form-page{
  padding: 12px 6px;
  display:flex;
  justify-content:center;
}

.materials-form-card{
  width: min(780px, 100%);
  background:#fff;
  border:1px solidrgb(216, 216, 216);
  border-radius:16px;
  box-shadow: 0 14px 30px rgba(0,0,0,.08);
  overflow:hidden;
}

/* Head */
.materials-form-head{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:14px;
  padding: 18px 18px 14px;
  background:rgb(224, 223, 221);
  border-bottom: 1px solidrgb(3, 2, 0);
}

.materials-form-title{
  margin:0;
  color:#1f1b16;
  font-size: 2.0rem;
}
.materials-form-subtitle{
  margin:6px 0 0;
  color:#6f6860;
  font-size: 1.25rem;
}

.materials-form-actions{
  display:flex;
  gap:10px;
}

/* Form body */
.materials-form{
  padding: 18px;
}

.materials-form-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

.materials-field-full{
  grid-column: 1 / -1;
}

/* Inputs: Cake genera input/select dentro de .input/.select según versión */
.materials-form .input,
.materials-form .select,
.materials-form .checkbox{
  margin:0;
}

.materials-form label{
  font-weight: 700;
  color:#2b241d;
  margin-bottom: 6px;
}

.materials-form input[type="text"],
.materials-form input[type="email"],
.materials-form input[type="number"],
.materials-form select,
.materials-form textarea{
  width:100%;
  border-radius: 12px;
  border: 1px solidrgb(0, 0, 0);
  padding: 12px 12px;
  font-size: 1.4rem;
  background:#fff;
  color:#1f1b16;
  outline:none;
  transition: border-color .12s ease, box-shadow .12s ease;
}

.materials-form input:focus,
.materials-form select:focus,
.materials-form textarea:focus{
  border-color:#aa2334;
  box-shadow: 0 0 0 4px rgba(170,35,52,.12);
}

/* Switch estilo (checkbox) */
.materials-switch{
  display:flex;
  align-items:flex-start;
  gap:12px;
  padding: 12px;
  border:1px dashedrgb(53, 53, 53);
  border-radius: 12px;
  background: rgba(170,35,52,.03);
}

.materials-switch input[type="checkbox"]{
  width: 18px;
  height: 18px;
  margin-top: 4px;
  accent-color: #aa2334;
}

.materials-switch-text strong{
  display:block;
  color:#1f1b16;
  margin-bottom: 2px;
}
.materials-switch-text span{
  display:block;
  color:#6f6860;
  font-size: 1.2rem;
  line-height: 1.3;
}

/* Footer buttons */
.materials-form-footer{
  display:flex;
  gap:10px;
  justify-content:flex-end;
  margin-top: 16px;
  padding-top: 14px;
  border-top: 1px solidrgb(112, 112, 112);
}

.materials-save{
  background:#aa2334 !important;
  border-color:#aa2334 !important;
  color:#fff !important;
  border-radius: 12px;
  padding: 12px 14px;
}
.materials-save:hover{
  background:#8f1d2b !important;
  border-color:#8f1d2b !important;
}

.materials-cancel{
  border-radius: 12px;
}
.materials-outline-red{
    border-radius: 12px;
    border-color: rgba(10, 9, 9, 0.55) !important;
    color:rgb(0, 0, 0) !important;
  }
  
  .materials-outline-red:hover{
    background: rgba(170,35,52,.10) !important;
    border-color: rgba(170,35,52,.85) !important;
    color: #aa2334 !important;
  }
/* Responsive */
@media (max-width: 820px){
  .materials-form-head{
    flex-direction:column;
    align-items:stretch;
  }
  .materials-form-actions{
    justify-content:flex-end;
  }
  .materials-form-grid{
    grid-template-columns: 1fr;
  }
  .materials-form-footer{
    flex-direction:column;
  }
  .materials-form-footer .button{
    width:100%;
  }
}
</style>
<div class="materials-form-page">
  <div class="materials-form-card">

    <div class="materials-form-head">
      <div>
        <h3 class="materials-form-title"><?= __('Agregar Material') ?></h3>
        <p class="materials-form-subtitle">Completa los datos para registrar un nuevo material.</p>
      </div>

      <div class="materials-form-actions">
        <?= $this->Html->link(__('Volver'), ['action' => 'index'], ['class' => 'button button-outline materials-outline-red']) ?>
    </div>
    </div>

    <?= $this->Form->create($material, ['class' => 'materials-form']) ?>

      <div class="materials-form-grid">
        <div class="materials-field">
          <?= $this->Form->control('nombre', [
              'label' => 'Nombre del Material',
              'placeholder' => 'Ej. Guía de Matemáticas 1',
              'class' => 'materials-input'
          ]) ?>
        </div>

        <div class="materials-field">
          <?= $this->Form->control('nivel', [
              'options' => $niveles,
              'empty' => 'Seleccione un nivel',
              'label' => 'Nivel',
              'class' => 'materials-input'
          ]) ?>
        </div>

        <div class="materials-field materials-field-full">
          <div class="materials-switch">
            <?= $this->Form->control('activo', [
                'type' => 'checkbox',
                'label' => false
            ]) ?>
            <div class="materials-switch-text">
              <strong>Activo</strong>
              <span>Si está desactivado, no se mostrará para selección.</span>
            </div>
          </div>
        </div>
      </div>

      <div class="materials-form-footer">
        <?= $this->Form->button(__('Guardar Material'), ['class' => 'button materials-save']) ?>
        <?= $this->Html->link(__('Cancelar'), ['action' => 'index'], ['class' => 'button button-outline materials-outline-red']) ?>      
    </div>

    <?= $this->Form->end() ?>

  </div>
</div>