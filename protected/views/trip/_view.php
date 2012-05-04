<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userId')); ?>:</b>
	<?php echo CHtml::encode($data->userId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<div class="content">
	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php
		$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
		echo $data->description;
		$this->endWidget();
	?>
	</div>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('private')); ?>:</b>
	<?php echo CHtml::encode($data->private); ?>
	<br />

	<b><?php echo CHtml::encode('Points'); ?>:</b>
	<?php echo CHtml::encode($data->trackpointCount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('distanceWithUnit')); ?>:</b>
	<?php echo CHtml::encode($data->distanceWithUnit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start')); ?>:</b>
	<?php echo CHtml::encode($data->start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('finish')); ?>:</b>
	<?php echo CHtml::encode($data->finish); ?>
	<br />

</div>