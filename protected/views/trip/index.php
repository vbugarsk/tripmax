<?php
$this->breadcrumbs=array(
	'Trips',
);

$this->menu=array(
	array('label'=>'Create Trip', 'url'=>array('create')),
	array('label'=>'Manage Trip', 'url'=>array('admin')),
);
?>

<h1>Trips

<?php if(!empty($_GET['author'])): ?>
<h4>Trips created by <i><?php echo CHtml::encode($_GET['author']); ?></i></h4>
<?php endif; ?>
</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'template'=>"{items}\n{pager}",
)); ?>
