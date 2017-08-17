<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace rsiripong\mapprovince;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\ArrayHelper;



class Mapprovince  extends ObjectAbstract
{
    
    
    /**
     * @var int the width in pixels or percent of the container holding the map.
     */
    public $width = '100%';
    /**
     * @var int the height in pixels or percent of the container holding the map.
     */
    public $height = 512;
    /**
     * @var array the HTML attributes for the layer that will render the map.
     */
    public $containerOptions = [];
    /**
     * @var array stores the overlays that are going to be rendered on the map.
     */
    private $_overlays = [];
    /**
     * @var array stores closure scope variables. Global to the js module written.
     */
    private $_closure_scope_variables = [];
    /**
     * @var array stores javascript code that is going to be rendered together with script initialization
     */
    private $_js = [];
    /**
     * @var PluginManager that manages the active plugins activated for the map.
     */
    private $_plugins;

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->options = ArrayHelper::merge(
            [
                'backgroundColor' => null,
                'center' => null,
                'disableDefaultUI' => null,
                'disableDoubleClickZoom' => null,
                'draggable' => null,
                'draggableCursor' => null,
                'draggingCursor' => null,
                'heading' => null,
                'keyboardShortcuts' => null,
                'mapMaker' => null,
                'mapTypeControl' => null,
                'mapTypeControlOptions' => null,
                'mapTypeId' => null,
                'maxZoom' => null,
                'minZoom' => null,
                'noClear' => null,
                'overviewMapControl' => null,
                'overviewMapControlOptions' => null,
                'panControl' => null,
                'panControlOptions' => null,
                'rotateControl' => null,
                'rotateControlOptions' => null,
                'scaleControl' => null,
                'scaleControlOptions' => null,
                'scrollwheel' => null,
                'streetView' => null,
                'streetViewControl' => null,
                'streetViewControlOptions' => null,
                'styles' => null,
                'tilt' => null,
                'zoom' => null,
                'zoomControl' => null,
                'zoomControlOptions' => null,
            ],
            $this->options
        );
        parent::__construct($config);
    }
    
    public function init()
    {
         parent::init();
    }
    
    // public function run()
   // {
    //     return "test";
    //}
    
     public function display()
    {
        $this->registerClientScript();

        return $this->renderContainer();
    }
    
    
      /**
     * @return string
     */
    public function renderContainer()
    {
        $this->containerOptions['id'] = ArrayHelper::getValue(
            $this->containerOptions,
            'id',
            $this->getName() . '-map-canvas'
        );

        return Html::tag('div', '', $this->containerOptions);
    }

    /**
     * @param int $position
     */
    public function registerClientScript($position = View::POS_END)
    {
        $view = Yii::$app->getView();
        MapAsset::register($view);
         MapAsset::register($view)->js[]= 'js/markermanager.js';
         MapAsset::register($view)->js[]= 'js/mapprovince.js';
        $view->registerJs($this->getJs(), $position);
    }
    
      public function getJs()
    {
           $name = $this->getName();
        $width = strpos($this->width, "%") ? $this->width : $this->width . 'px';
        $height = strpos($this->height, "%") ? $this->height : $this->height . 'px';
        $containerId = ArrayHelper::getValue($this->containerOptions, 'id', $name . '-map-canvas');
          $js = [];
          $js[] = "		
              /*
var iconData = {
  \"us\": { width: 24, height: 14 },
  \"ca\": { width: 24, height: 14 },
  \"flag-shadow\": { width: 40, height: 30 },
 \"house\": { width: 32, height: 32 },
  \"house-shadow\": { width: 59, height: 32 },
  \"headquarters\": { width: 32, height: 32 },
  \"headquarters-shadow\": { width: 59, height: 32 },
   \"people35\": { width: 32, height: 32 },
};
*/
  
            
            var map;
            var mgr;
            var icons = {};
            var allmarkers = [];
            //var infowindow = [];
            var infowindowtmp ;
            var ajaxurldata = '".  \yii\helpers\Url::to(["site/mapdata"])."';
            var ajaxurlinfo = '".  \yii\helpers\Url::to(["site/mapinfo"])."';
            var assetspath = '".\Yii::getAlias('@web')."/';
                        
";
          
          
          $js[] = "function initialize(){";
          $js[] = " var myLatLng = {lat: 13.7234186, lng: 100.4762319};			
              var mapOptions = {
                //zoom: 3,
                //center: new google.maps.LatLng(50.62504, -100.10742),
				 zoom: 6,
                center: myLatLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
              }";
          $js[] = "var container = document.getElementById('{$containerId}');";
              $js[] = "container.style.width = '{$width}';";
             $js[] = "container.style.height = '{$height}';";
           $js[] = "var {$this->getName()} = new google.maps.Map(container, mapOptions);";
           $js[] = "map = {$this->getName()} ;";
           $js[] = "          mgr = new MarkerManager(map);
              google.maps.event.addListener(mgr, 'loaded', function(){
			  /*
			  var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          //title: 'Hello World!'
		  draggable: true,
        });
        


		*/
                /*
                google.maps.event.addListener(map,'click', function(event) {  
    //alert('test');
    //addMarker(event.latLng);  
     //mgr.clearMarkers();
    marker = new google.maps.Marker({  
    position: event.latLng,  
    draggable: true,
    map: map  
  });  
  //markers.push(marker);
  });
  */
  
		
			  //var infoWindow = new google.maps.InfoWindow;
			  
              google.maps.event.addListener(map, 'zoom_changed', function() {
					rendermarkers(map);
                });
				google.maps.event.addListener(map, 'dragend', function() {
					rendermarkers(map);
                });
				rendermarkers(map);
				
              });  ";
           $js[] = "};";
        $js[] = "initialize();";
           return implode("\n", $js);
    }
}