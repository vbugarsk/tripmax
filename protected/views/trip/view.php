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

<div class="portlet-decoration">
	<div class="portlet-title"><?php echo strtoupper('TRIP '.$model->id.' : '.$model->title) ?></div>
</div>
<p class="quiet"> Here please find details about this trip. For editing press button on the right.</p>

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
