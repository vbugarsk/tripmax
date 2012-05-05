<div class="view">

	<div class="span-5">
	 <?php
		Yii::import('ext.gmaps.*');
 
		$gMap = new EGMap();
		$gMap->width = 	'100%';
		$gMap->height = '150';
		
		$gMap->zoom = 10;
		$gMap->setCenter(45.4,14.5);
		
		$coords = array();
		
		foreach($data->trackpoints as $p)
		{
			$point = new EGMapCoord($p->latitude, $p->longitude);
			$coords[] = $point;
		}
		
		$polyline = new EGMapPolyline($coords);
		$gMap->addPolyline($polyline);
 
		$gMap->mapTypeId = EGMap::TYPE_TERRAIN;
		$gMap->renderMap();
	 ?>
	</div>

	<?php echo CHtml::tag('div',array('class'=>'right'), CHtml::link(CHtml::encode('Edit this trip'), array('view', 'id'=>$data->id))); ?>

	<?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id)) ; ?>
	<?php echo CHtml::encode(' by '); ?>
	<?php echo CHtml::link(CHtml::encode($data->user->username), '?author='.$data->user->id); ?>
	<br />
	<div style="color:#999; font-size:11px; margin-bottom:10px">Swiss and France Alps were passed. Andermatt and Briancon was center points.</div>

	<b><?php echo CHtml::encode($data->getAttributeLabel('private')); ?>:</b>
	<?php echo CHtml::encode($data->private); ?>
	<br />

	<b><?php echo CHtml::encode('Points'); ?>:</b>
	<?php echo CHtml::encode($data->trackpointCount); ?>
	<br />

	<b><?php echo CHtml::encode('Stages'); ?>:</b>
	<?php echo CHtml::encode($data->trackpointCount + 5); ?>
	<br /><br />

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