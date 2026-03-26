<div class="usuarios index">
<h2><?php __('Escuelas sin coordenadas');?></h2>


<p>
<?php

echo $paginator->counter(array(
'format' => __('<center><h3>%count% Escuelas - Pagina <strong>%page%</strong> de %pages%</h3></center><br> ', true)
));
?></p>


<table class='tabla_intranet'>
<tr>

	<th><?php echo $paginator->sort('estado_n');?></th>
	<th><?php echo $paginator->sort('municipio_n');?></th>
	<th><?php echo $paginator->sort('nombre');?></th>
	<th><?php echo $paginator->sort('cct');?></th>

</tr>
<?php




$i = 0;
foreach ($bases as $base):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>

		<td>
			<?php echo wordwrap(utf8_encode($base['Base']['estado_n'])); ?>
		</td>

		<td>
			<?php echo wordwrap(utf8_encode($base['Base']['municipio_n'])); ?>
		</td>

		<td>
			<?php echo wordwrap(utf8_encode($base['Base']['nombre'])); ?>
		</td>

		<td>
			<?php echo $base['Base']['cct']; ?>
		</td>



	
		
	
		
		<td class="actions">
		
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element("botones_paginador"); ?>

