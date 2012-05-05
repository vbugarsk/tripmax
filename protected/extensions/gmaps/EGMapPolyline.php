<?php
/**
 * 
 * EGPolyline
 * A GoogleMap Polyline
 * 
 * @author Peng Yue
 * 
 * @since 2011-05-04
 *	Added this class based on EGMapMarker
 * @TODO: look at event
 * @TODO: get static version of an overlay (polyline)
 * @TODO: modify $events to CTypeMap('EGMapEvent') (originates from EGMapMarker)
 * 
 * 
 * @copyright 
 * 
 * Copyright (c) 2010 Antonio Ramirez - Peng Yue
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
 * and associated documentation files (the "Software"), to deal in the Software without restriction, 
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial 
 * portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 * NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */
class EGMapPolyline extends EGMapBase
{
  
  	protected $options = array(
	  // coordinates of the polyline
	  'path' => null,
	  // stroke color of the edge of the polyline
	  'strokeColor' => '"#FF0000"',
	  //stroke opacity of the edge of the polyline
	  'strokeOpacity' => 0.8,
	  //stroke weight of the edge of the polyline
	  'strokeWeight' => 2,
  	);

  	protected $info_window 		= null;
  	protected $info_window_shared 	= false;
  	protected $events         	= null;
  	protected $custom_properties 	= array();

  	protected $polyline_object	= 'google.maps.Polyline';
  
  	/**
   	* @param string $js_name Javascript name of the polyline
   	* @param EGMapCoord[] array coordinates of the polyline
   	* @param string $js_name name of the polyline variable
   	* @param array $options of the polygon
   	* @param EGmapEvent[] array of GoogleMap Events linked to the polyline
   	* @author Peng Yue
   	*/
  	public function __construct($coords, $options = array(), $js_name='polyline', $events=array() )
  	{
    	if( $js_name !== 'polyline' )
	  		$this->setJsName($js_name);
    	
	// coords wont make any difference here
	// as it will be set afterwards by setCoords
    	$this->setOptions($options);
    	
    	$this->setCoords($coords);
    	
    	$this->events = new CTypedList('EGMapEvent');
    	
    	$this->setEvents( $events );
    	 
  	}
  	/**
  	 * 
  	 * Batch set events by an array of EGMapEvents
  	 * @param array $events
  	 */
  	public function setEvents( $events ){
  		if(!is_array($events))
  			throw new CException(Yii::t('EGMap', 'Parameter of "{class}.{method}" {e}.',
					array('{class}'=>get_class($this), '{method}'=>'setEvents','{e}'=>'must be of type array')));
  		if(null === $this->events) $this->events = new CTypedList('EGMapEvent');
  		
  		foreach($events as $e){
  			$this->events->add($e);
  		}
  	}
	
	/**
   	* Adds an event listener to the marker
   	*
   	* @param EGMapEvent $event
   	*/
  	public function addEvent(EGMapEvent $event)
  	{
    	$this->events->add($event);
  	}
  
  	/**
   	* @param array $options
   	* @author fabriceb
   	* @since 2011-05-04
   	* 	modified for EGMapPolyline by Matt Kay
   	*/
  	public function setOptions($options) {
	  
	      $this->options = array_merge($this->options, $options);
  	}
  	
	/**
	* gets the coordinates of the polyline
	* 
	* @param EGMapCoord[] $polyline_coords
	* @author Peng Yue
	*/
	public function getCoords(){
	  return $this->options['path'];
	}

  	/**
	* sets the coordinates of the polyline
	* 
	* @param EGMapCoord[] $polyline_coords
	* @author Peng Yue
	*/
  	public function setCoords($coords)
  	{
	  $tmp = array();
	  // go through array to check for valid EGMapCoord objects
	  foreach($coords as $coord){
	    if(is_a($coord, 'EGMapCoord')){
	      $tmp[] = $coord;
	    }
	  }
	  // throw Exception when number of coords is not sufficient for desribing a polyline
	  if(sizeof($tmp) < 2){
		  throw new CException(Yii::t('EGMapPolyline','EGMapPolyline argument #1 has to be an array of minimum two EGMapCoord objects.'));
	  }
	  $this->options['path'] = $tmp;
  	}  
  
  	/**
  	* @param string $map_js_name 
  	* @return string Javascript code to create the polyline
  	* @author Peng Yue
	* @since 2011-03-10
	*	Added this method based on the one in the class EGMapMarker, info window support missing (TODO)				  
  	*/
  	public function toJs($map_js_name = 'map')
  	{
    	$this->options['map'] = $map_js_name;
    
    	$return = '';

	$return .='var '. $this->getJsName().' = new '.$this->polyline_object.'('.EGMap::encode($this->options).');'.PHP_EOL;
    	foreach ($this->custom_properties as $attribute=>$value)
    	{
      		$return .= $this->getJsName().".".$attribute." = '".$value."';".PHP_EOL;
    	}
    	foreach ($this->events as $event)
    	{
      		$return .= $event->getEventJs($this->getJsName()).PHP_EOL;
    	}   
    
	$return .= $this->getJsName().'.setMap('.$map_js_name.');';

    	return $return;
  	}
  	
  	/**
   	* Adds an onlick listener that open a html window with some text 
   	*
   	* @param EGMapInfoWindow $info_window
   	* @param boolean $shared among other markers (unique info_window display)
   	* 
   	* @author Antonio Ramirez
   	* @since 2011-01-23 Added shared functionality for infoWindows
   	*/
  	public function addHtmlInfoWindow( EGMapInfoWindow $info_window, $shared = true )
  	{
    	$this->info_window = $info_window;
    	$this->info_window_shared = $shared;
  	}
  	/**
   	* 
   	* @return boolean if info window is shared or not
   	*/
  	public function htmlInfoWindowShared(){
  		return $this->info_window_shared;
  	}
  
  	/**
  	* @return EGMapInfoWindow
  	* @author Antonio Ramirez
  	*/
  	public function getHtmlInfoWindow()
  	{
    	return $this->info_window;
  	}
  
  	/**
   	* 
   	* Sets custom properties to the Marker
   	* @param array $custom_properties
   	*/
  	public function setCustomProperties($custom_properties)
  	{
  		if(!is_array($custom_properties))
  			throw new CException(Yii::t('EGMap','EGMapMarker custom properties must of type array to be set'));
    	$this->custom_properties=$custom_properties;
  	}
  	/**
   	* 
   	* @return array custom properties
   	*/
  	public function getCustomProperties()
  	{
    
    	return $this->custom_properties;
  	}
  	/**
   	* Sets a custom property to the generated javascript object
   	*
   	* @param string $name
   	* @param string $value
   	*/
  	public function setCustomProperty($name,$value)
  	{
    	$this->custom_properties[$name] = $value;
  	}
  
  	/**
  	*
  	* @param EGMapMarker[] $markers array of Markers
  	* @return EGMapCoord
  	* @author fabriceb
  	* @since 2011-05-04 modified by Peng Yue
  	**/
  	public static function getMassCenterCoord($markers)
  	{
    	$coords = array();
    	foreach($markers as $marker)
    	{
      		array_push($coords, $marker->position);
    	}
   
   		return EGMapCoord::getMassCenterCoord($coords);
  	}
  
  	/**
  	*
  	* @param EGMapMarker[] $markers array of MArkers
  	* @return EGMapCoord
  	* @author fabriceb
  	* @since 2011-05-04 modified by Peng Yue
  	**/
  	public static function getCenterCoord($polylines)
  	{
    	$bounds = EGMapBounds::getBoundsContainingPolygons($polylines);
  
    	return $bounds->getCenterCoord();
  	}
  
}
