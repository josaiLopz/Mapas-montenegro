<div class="rols index">
<h2><?php __('Rols');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('<center><h1>Pagina <strong>%page%</strong> de %pages%</h1></center><br> ', true)
));
?></p>
<table class='tabla_intranet'>
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('nombre');?></th>
	<th><?php echo $paginator->sort('Creado','created');?></th>
	<th><?php echo $paginator->sort('Modificado','modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($rols as $rol):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $rol['Rol']['id']; ?>
		</td>
		<td>
			<?php echo $rol['Rol']['nombre']; ?>
		</td>
	
		<td>
			<?php echo $rol['Rol']['created']; ?>
		</td>
		<td>
			<?php echo $rol['Rol']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $this->element('setAction',array('parametros'=>array('Ver','iconos/ver.png',array('action'=>'view', $rol['Rol']['id']),'Ver'))); ?>
			<?php echo $this->element('setAction',array('parametros'=>array('Modificar','iconos/modificar.png',array('action'=>'edit', $rol['Rol']['id']),'Modificar'))); ?>
			<?php echo $this->element('setAction',array('parametros'=>array('Eliminar','iconos/eliminar.png',array('action'=>'delete', $rol['Rol']['id']),'Eliminar','Eliminar el rol'))); ?>


<?php /* ?> 			<?php echo $html->link(__('View', true), array('action'=>'view', $rol['Rol']['id'])); ?>
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $rol['Rol']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $rol['Rol']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $rol['Rol']['id'])); ?>


<?php */ ?> 		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('Anterior', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('Siguiente', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->element('setAction',array('parametros'=>array('Nuevo Rol','', array('action'=>'add'),'Agregar'))); ?></li>
	</ul>
</div>
