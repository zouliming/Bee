<?php
class BController extends BBaseController{
    /**
	 * @var string the name of the default action. Defaults to 'index'.
	 */
	public $defaultAction='index';
    
    public $layout;
    
    private $_id;
    private $_action;
    
    public function __construct($id) {
        $this->_id = $id;
    }
    public function init(){
        
    }
    public function run($actionID)
	{
		if(($action=$this->createAction($actionID))!==null){
            $this->runAction($action);
		}else{
			$this->missingAction($actionID);
        }
	}
    /**
	 * Runs the action after passing through all filters.
	 * This method is invoked by {@link runActionWithFilters} after all possible filters have been executed
	 * and the action starts to run.
	 * @param CAction $action action to run
	 */
	public function runAction($action)
	{
		$priorAction=$this->_action;
		$this->_action=$action;
		$action->run();
		$this->_action=$priorAction;
	}
    public function missingAction($actionID)
	{
		throw new BHttpException(404,Bee::t('系统找不到"{action}"这个请求.',
			array('{action}'=>$actionID==''?$this->defaultAction:$actionID)));
	}
    public function createAction($actionID)
	{
		if($actionID===''){
			$actionID=$this->defaultAction;
        }
        if(method_exists($this,'action'.$actionID)){
			return new BInlineAction($this,$actionID);
        }else{
            return null;
        }
	}
    /**
	 * Creates the action instance based on the action map.
	 * This method will check to see if the action ID appears in the given
	 * action map. If so, the corresponding configuration will be used to
	 * create the action instance.
	 * @param string $actionID 去掉了前缀的action ID
	 * @param string $requestActionID 原本请求的action ID
	 * @param array $config the action configuration that should be applied on top of the configuration specified in the map
	 * @return CAction the action instance, null if the action does not exist.
	 * @since 1.0.1
	 */
	protected function createActionFromMap($actionMap,$actionID,$requestActionID,$config=array())
	{
		if(($pos=strpos($actionID,'.'))===false){
			$baseConfig=is_array($actionMap[$actionID]) ? $actionMap[$actionID] : array('class'=>$actionMap[$actionID]);
			return Bee::createComponent(empty($config)?$baseConfig:array_merge($baseConfig,$config),$this,$requestActionID);
		}else if($pos===false){
			return null;
        }

		// the action is defined in a provider
		$prefix=substr($actionID,0,$pos+1);
		if(!isset($actionMap[$prefix]))
			return null;
		$actionID=(string)substr($actionID,$pos+1);

		$provider=$actionMap[$prefix];
		if(is_string($provider)){
			$providerType=$provider;
        }else if(is_array($provider) && isset($provider['class'])){
			$providerType=$provider['class'];
			if(isset($provider[$actionID])){
				if(is_string($provider[$actionID])){
					$config=array_merge(array('class'=>$provider[$actionID]),$config);
                }else{
					$config=array_merge($provider[$actionID],$config);
                }
			}
		}else{
			throw new CException(Bee::t('配置对象必须是一个包含 "class" 元素的数组'));
        }

		$class=Bee::import($providerType,true);
		$map=call_user_func(array($class,'actions'));

		return $this->createActionFromMap($map,$actionID,$requestActionID,$config);
	}
    public function getLayoutFile($layoutName){
        if($layoutName===false){
            return false;
        }else{
            return $this->resolveViewFile($layoutName,Bee::app()->getLayoutPath(),Bee::app()->getViewPath());
        }
    }
    public function render($view,$data=null,$return=false)
	{
        $output=$this->renderPartial($view,$data,true);
        if(($layoutFile=$this->getLayoutFile($this->layout))!==false){
            $output=$this->renderFile($layoutFile,array('content'=>$output),true);
        }
        $output=$this->processOutput($output);

        if($return)
            return $output;
        else
            echo $output;
	}
    public function renderPartial($view,$data=null,$return=false,$processOutput=false)
	{
		if(($viewFile=$this->getViewFile($view))!==false)
		{
			$output=$this->renderFile($viewFile,$data,true);
			if($processOutput)
				$output=$this->processOutput($output);
			if($return)
				return $output;
			else
				echo $output;
		}else{
			throw new BException(Bee::t('{controller} cannot find the requested view "{view}".',
				array('{controller}'=>get_class($this), '{view}'=>$view)));
        }
	}
    public function processOutput($output)
	{
		return $output;
	}
    public function getViewFile($viewName)
	{
		$viewPath=Bee::app()->getViewPath();
		return $this->resolveViewFile($viewName,$this->getViewPath(),$viewPath,$viewPath);
	}
    /**
     * 根据视图名字找到视图文件
     * @param type $viewName 视图名字
     * @param type $viewPath 用来查找视图的目录
     * @param type $basePath 在应用程序里用来查找绝对的视图名称的目录
     * @return boolean 
     */
    public function resolveViewFile($viewName,$viewPath,$basePath)
	{
		if(empty($viewName))
			return false;

		$extension='.php';
		if($viewName[0]==='/'){
			if(strncmp($viewName,'//',2)===0){//比较$viewName的前两位是否是//
				$viewFile=$basePath.$viewName;
            }else{
				$viewFile=$viewName;
            }
		}elseif(strpos($viewName,'.')){
			$viewFile=Bee::getPathOfAlias($viewName);
        }else{
			$viewFile=$viewPath.DIRECTORY_SEPARATOR.$viewName;
        }

		if(is_file($viewFile.$extension)){
			return Bee::app()->findLocalizedFile($viewFile.$extension);
        }else if($extension!=='.php' && is_file($viewFile.'.php')){
			return Bee::app()->findLocalizedFile($viewFile.'.php');
        }else{
			return false;
        }
	}
    public function getId()
	{
		return $this->_id;
	}

	/**
	 * Returns the directory containing view files for this controller.
	 * The default implementation returns 'protected/views/ControllerID'.
	 * Child classes may override this method to use customized view path.
	 * If the controller belongs to a module (since version 1.0.3), the default view path
	 * is the {@link CWebModule::getViewPath module view path} appended with the controller ID.
	 * @return string the directory containing the view files for this controller. Defaults to 'protected/views/ControllerID'.
	 */
	public function getViewPath()
	{
		return Bee::app()->getViewPath().DIRECTORY_SEPARATOR.$this->getId();
	}
    public function redirect($url,$terminate=true,$statusCode=302)
	{
		if(is_array($url)){
			$route=isset($url[0]) ? $url[0] : '';
			$url=$this->createUrl($route,array_splice($url,1));
		}
        Bee::app()->getRequest()->redirect($url,$terminate,$statusCode);
	}
}