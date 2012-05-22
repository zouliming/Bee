<?php
abstract class BApplication {
    public $charset = "UTF-8";
    
    private $_basePath;
    private $_components=array();
    private $_componentConfig=array();
    abstract public function processRequest();
    public function __construct($config=null){
        Bee::setApplication($this);
        if(is_string($config)){
            $config = require($config);
        }
        if(isset($config['basePath'])){
            $this->setBasePath($config['basePath']);
        }
        $this->registerCoreComponents();
    }
    public function getBasePath()
	{
		return $this->_basePath;
	}
    public function setBasePath($path){
		if(($this->_basePath=realpath($path))===false || !is_dir($this->_basePath))
			throw new BException(Bee::t('程序的 "{path}" 这个路径不是一个有效的目录.',
				array('{path}'=>$path)));
	}
    public function run(){
		$this->processRequest();
	}
    public function setComponent($id,$component)
	{
		if($component===null){
			unset($this->_components[$id]);
        }else{
			$this->_components[$id]=$component;
			if(!$component->getIsInitialized())
				$component->init();
		}
	}
    public function setComponents($components,$merge=true)
	{
		foreach($components as $id=>$component){
			if($component instanceof IApplicationComponent){
				$this->setComponent($id,$component);
            }else if(isset($this->_componentConfig[$id]) && $merge){
				$this->_componentConfig[$id]=$component;
            }else{
				$this->_componentConfig[$id]=$component;
            }
		}
	}
    public function getComponent($id,$createIfNull=true)
	{
		if(isset($this->_components[$id])){
			return $this->_components[$id];
        }else if(isset($this->_componentConfig[$id]) && $createIfNull){
			$config=$this->_componentConfig[$id];
			if(!isset($config['enabled']) || $config['enabled']){
				unset($config['enabled']);
				$component=Bee::createComponent($config);
				$component->init();
				return $this->_components[$id]=$component;
			}
		}
	}
    public function getUrlManager()
	{
		return $this->getComponent('urlManager');
	}
    public function getRequest()
	{
		return $this->getComponent('request');
	}
    public function createUrl($route,$params = array(),$ampersand = "&"){
        return $this->getUrlManager()->createUrl($route,$params,$ampersand);
    }
    public function getDb(){
        return $this->getComponent('db');
    }
    protected function registerCoreComponents()
	{
		$components=array(
			'db'=>array(
				'class'=>'BDbConnection',
			),
			'urlManager'=>array(
				'class'=>'BUrlManager',
			),
			'request'=>array(
				'class'=>'BHttpRequest',
			),
		);
		$this->setComponents($components);
	}
    public function findLocalizedFile($srcFile)
	{
		return $srcFile;
	}
}