<?php
$this->breadcrumbs=array(
	'Trips'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Trip', 'url'=>array('index')),
	array('label'=>'Create Trip', 'url'=>array('create')),
	array('label'=>'View Trip', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Trip', 'url'=>array('admin')),
	array('label'=>'Upload Tracks', 'url'=>array('loadGPX', 'id'=>$model->id)),
	array('label'=>'Delete Tracks', 'url'=>array('deleteGPX', 'id'=>$model->id)),
);
?>

<h1>Update Trip <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>