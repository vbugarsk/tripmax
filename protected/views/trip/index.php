<?php
$this->breadcrumbs=array(
	'Trips',
);

$this->menu=array(
	array('label'=>'Create Trip', 'url'=>array('create')),
	array('label'=>'Manage Trip', 'url'=>array('admin')),
);
?>

<h1>Trips</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
