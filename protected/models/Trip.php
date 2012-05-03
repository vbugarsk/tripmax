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
	
	public function trackPoints()
	{
		return $this->trackpointsCount;
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
}