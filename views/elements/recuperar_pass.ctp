<br><br><label class='sitios_color' onclick=hizo_click('div_recuperar')>Recuperar Password</label>
<div id='div_recuperar'>
<div class="contact-form bg-secondary" style="padding: 30px;">
<script>oculta('div_recuperar')</script>
<?php echo $form->create('Usuario', array('action' =>$ruta)); ?>
<?php echo $form->input('valor', array('type' => 'text','class'=>'form-control border-0 p-4','label'=>false, 'placeholder'=>'Nombre de Usuario / Correo Electronico')); ?>

<br>
<input type="submit" name="login" value="Enviar" class="btn btn-primary py-3 px-4" />
<?php echo $form->end();?>
</div>
</div>
