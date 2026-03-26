<div class="Page navigation">
<ul class="pagination pagination-lg justify-content-center mb-0">
	<?php echo $paginator->prev("&laquo;", array('class'=>'page-link','escape'=>false), null, array('class'=>'page-link disabled'));?>
  	<?php echo $paginator->numbers(array('class'=>'page-link',"separator" => ""));?>
	<?php echo $paginator->next("&raquo;", array('class'=>'page-link','escape'=>false), null, array('class'=>'page-link disabled'));?>
</ul>
</div>