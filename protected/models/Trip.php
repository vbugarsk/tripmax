<?php

/**
 * This is the model class for table "tbl_trip".
 *
 * The followings are the available columns in table 'tbl_trip':
 * @property string $id
 * @property string $userId
 * @property string $title
 * @property string $description
 * @property integer $private
 * @property string $start
 * @property string $finish
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Trackpoint[] $trackpoints
 * @property User $user
 */
class Trip extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Trip the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_trip';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, start, finish', 'required'),
			array('private', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, userId, title, description, private, start, finish, created, modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'trackpoints' => array(self::HAS_MANY, 'Trackpoint', 'tripId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
			'trackpointCount' => array(self::STAT, 'Trackpoint', 'tripId'),
		);
	}
	
	public function getUrl()
	{
		return Yii::app()->createUrl('trip/view', array(
			'id'=>$this->id,
			'title'=>$this->title,
		));
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->created = $this->modified = new CDbExpression('NOW()');
				$this->userId=Yii::app()->user->id;
			}
			else
				$this->modified = new CDbExpression('NOW()');
			return true;
		}
		else
			return false;
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'userId' => 'User',
			'title' => 'Title',
			'description' => 'Description',
			'private' => 'Private',
			'start' => 'Start',
			'finish' => 'Finish',
			'created' => 'Created',
			'modified' => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('userId',$this->userId,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('private',$this->private);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('finish',$this->finish,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Load points from GPX file
	 */
	public function loadGPX($fileName)
	{
		$doc = new DOMDocument();
		$doc->load($fileName);

		/* TRACK POINTS */

		$trks = $doc->getElementsByTagName('trk');
		foreach($trks as $trk)
		{
			$name = $trk->getElementsByTagName('name')->item(0)->nodeValue;

			$trkpts = $trk->getElementsByTagName('trkpt');
			foreach($trkpts as $trkpt)
			{
				$lat  = $trkpt->getAttribute('lat');
				$lon  = $trkpt->getAttribute('lon');
				$ele  = $trkpt->getElementsByTagName('ele')->item(0)->nodeValue;
				$time = $trkpt->getElementsByTagName('time')->item(0)->nodeValue;

				$point = new Trackpoint();
				
				$point->tripId = $this->id;
				$point->latitude = $lat;
				$point->longitude = $lon;
				$point->elevation = $ele;
				$point->time = $time;
				
				$point->save();
			}
		}
	}

	private $earthRadius  = 3960.00;
	
	public function getDistance()
	{
		$startDatetime = "2009-11-25 03:00:00";
		$endDatetime   = "2009-11-29 13:00:00";

		$totalDist = 0.0;
		
		for($i=1; $i<sizeof($this->trackpoints); $i++)
		{
			$p1 = $this->trackpoints[$i-1];
			$p2 = $this->trackpoints[$i];
			$haverDist = $this->distanceHaversine($p1->latitude, $p1->longitude, $p2->latitude, $p2->longitude);
			$totalDist = $totalDist + $haverDist;
		}	
		return $totalDist;
	}

	function distanceHaversine($lat1, $lon1, $lat2, $lon2)
	{
		$delta_lat = $lat2 - $lat1 ;
		$delta_lon = $lon2 - $lon1 ;
		$alpha     = $delta_lat/2 ;
		$beta      = $delta_lon/2 ;
 
		$a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
		$c        = asin(min(1, sqrt($a)));
		$distance = 2*$this->earthRadius * $c;
		$distance = round($distance, 4);

		return $distance;
	}
}
