<?php
$this->breadcrumbs=array(
	'Trips'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Trip', 'url'=>array('index')),
	array('label'=>'Create Trip', 'url'=>array('create')),
	array('label'=>'Update Trip', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Trip', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Trip', 'url'=>array('admin')),
);
?>

<h1>View Trip #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'userId',
		'title',
		'description',
		'private',
		'start',
		'finish',
		'trackpointCount',
		'distanceWithUnit',
		'created',
		'modified',
	),
)); ?>
