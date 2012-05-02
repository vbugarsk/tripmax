<?php
$this->breadcrumbs=array(
	'Trips'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Trip', 'url'=>array('index')),
	array('label'=>'Manage Trip', 'url'=>array('admin')),
);
?>

<h1>Create Trip</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>