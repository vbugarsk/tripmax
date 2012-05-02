<?php

/**
 * This is the model class for table "tbl_trackpoint".
 *
 * The followings are the available columns in table 'tbl_trackpoint':
 * @property string $id
 * @property string $tripId
 * @property double $latitude
 * @property double $longitude
 * @property double $elevation
 * @property string $time
 *
 * The followings are the available model relations:
 * @property Trip $trip
 */
class Trackpoint extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Trackpoint the static model class
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
		return 'tbl_trackpoint';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tripId, latitude, longitude, time', 'required'),
			array('latitude, longitude, elevation', 'numerical'),
			array('tripId', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tripId, latitude, longitude, elevation, time', 'safe', 'on'=>'search'),
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
			'trip' => array(self::BELONGS_TO, 'Trip', 'tripId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tripId' => 'Trip',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'elevation' => 'Elevation',
			'time' => 'Time',
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
		$criteria->compare('tripId',$this->tripId,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('elevation',$this->elevation);
		$criteria->compare('time',$this->time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}