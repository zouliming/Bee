<?php
class BWebApplication extends BApplication{
    public $defaultController = "site";
    
    private $_controller;
    private $_controllerPath;
    private $_viewPath;
    private $_layoutPath;
    
    public function processRequest() {
        $route=$this->getUrlManager()->parseUrl($this->getRequest());
        $this->runController($route);
    }
    public function runController($route){
        if(($ca=$this->createController($route))!==null){
			list($controller,$actionID)=$ca;
			$oldController=$this->_controller;
			$this->_controller=$controller;
			$controller->init();
			$controller->run($actionID);
			$this->_controller=$oldController;
		}else{
			throw new BHttpException(404,Bee::t('Unable to resolve the request "{route}".',
				array('{route}'=>$route===''?$this->defaultController:$route)));
        }
    }
    /**
     * 创建一个控制器
     * @param type $route 控制器/动作
     * @param type $owner 新的控制器属于的模块.默认是null,代表应用程序;
     * @return type 
     */
    public function createController($route,$owner=null){
        if($owner==null){
            $owner = $this;
        }
        if(($route=trim($route,'/'))===''){
			$route=$owner->defaultController;
        }
        $route .="/";
        $pos=strpos($route, '/');
        if($pos!==false){
            $id = substr($route,0, $pos);
            $route=(string)substr($route,$pos+1);
            $className=ucfirst($id).'Controller';
            $basePath = $this->getControllerPath();
            $classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';
            if(is_file($classFile)){
				if(!class_exists($className,false))
					require($classFile);
				if(class_exists($className,false) && is_subclass_of($className,'BController')){
					$id[0]=strtolower($id[0]);
					return array(
						new $className($id,$owner===$this?null:$owner),
						$this->parseActionParams($route),
					);
				}
                return null;
			}
        }
    }
    /**
	 * Parses a path info into an action ID and GET variables.
	 * @param string $pathInfo path info
	 * @return string action ID
	 * @since 1.0.3
	 */
	protected function parseActionParams($pathInfo)
	{
		if(($pos=strpos($pathInfo,'/'))!==false){
			$manager=$this->getUrlManager();
			$actionID=substr($pathInfo,0,$pos);
			return $manager->caseSensitive ? $actionID : strtolower($actionID);
		}else{
			return $pathInfo;
        }
	}
    public function getControllerPath()
	{
		if($this->_controllerPath!==null)
			return $this->_controllerPath;
		else
			return $this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers';
	}
    public function getViewPath()
	{
		if($this->_viewPath!==null)
			return $this->_viewPath;
		else
			return $this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views';
	}
    public function getLayoutPath()
	{
		if($this->_layoutPath!==null)
			return $this->_layoutPath;
		else
			return $this->_layoutPath=$this->getViewPath().DIRECTORY_SEPARATOR.'layouts';
	}
    /**
	 * @return CController the currently active controller
	 */
	public function getController()
	{
		return $this->_controller;
	}
}